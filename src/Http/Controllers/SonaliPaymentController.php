<?php

namespace Exceptio\SonaliPayment\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

use Exceptio\SonaliPayment\Http\Dtos\CreateRequestDto;
use Exceptio\SonaliPayment\Http\Dtos\CreditInformationDto;


class SonaliPaymentController extends Controller
{
	private $test_mode;
	private $base_end_point;
	private $access_token;
	private $route_name_prefix;
	public function __construct(){
		$this->test_mode = config('sonali-payment-config.test-mode');
		$this->route_name_prefix = config('sonali-payment-config.route-name-prefix');
		if($this->test_mode){
			$this->base_end_point = config('sonali-payment-config.test-mode-url');
			$this->access_token = config('sonali-payment-config.test-mode-token');
		}else{
			$this->base_end_point = config('sonali-payment-config.pord-mode-url');
			$this->access_token = config('sonali-payment-config.pord-mode-token');
		}
	}

	public function hello(Request $request){
		return 'Sonali Payment Package for Laravel.';
	}

	public function test(Request $request){
		try {
		    // Instantiate your CreateRequestDto with sample data
			$createRequestDto = new CreateRequestDto(
			    "INV123456",
			    "2024-09-02",
			    1500.50,
			    "John",
			    "01711448444",
			    "a@b.com",
			    "Y",
			    [
			        new CreditInformationDto(
			            1,
			           500.50,
			            "TRN",
			            "0002601020864",
			            "John's Company"
			        ),
			        new CreditInformationDto(
			        	2,
			           1000.00,
			            "TRN",
			            "0002601020865",
			            "John's Company"
			        )
			    ],
			    route($this->route_name_prefix.'.test_response')
			);

		} catch (InvalidArgumentException $e) {
			if (env('APP_DEBUG',true)) {
		    	echo 'Error: ' . $e->getMessage();
		    }else{
		    	Log::info('Error: ' . $e->getMessage());
		    }
		}

		$data = $this->checkout($createRequestDto);
		if(isset($data->Status) && $data->Status == 200){
			return redirect()->to($data->RedirectToGateway);
		}
	}

	public function test_response(Request $request){
		if($request->input('Mode') == 'success'){
			$data = $this->validate_response($request);
			Log::info('Response Data: ' . json_encode($data));
			dd($data);
		}else{
			return "Failed Payment";
		}
	}

	public function test_ipn(Request $request){
		$data = $this->validate_ipn($request);
		Log::info('IPN Data: ' . json_encode($data));
		dd($data);
	}

	public function checkout(CreateRequestDto $createRequestDto){
		// Guzzle HTTP client
		$client = new Client();

		// Prepare headers
		$headers = [
		    'Authorization' => "Basic ".$this->access_token,
		    'Content-Type' => 'application/json',
		];

		// Prepare JSON payload
		$jsonPayload = json_encode([
		    "InvoiceNo" => $createRequestDto->getInvoiceNo(),
		    "InvoiceDate" => $createRequestDto->getInvoiceDate(),
		    "RequestTotalAmount" => $createRequestDto->getRequestTotalAmount(),
		    "CustomerName" => $createRequestDto->getCustomerName(),
		    "CustomerContactNo" => $createRequestDto->getCustomerContactNo(),
		    "Email" => $createRequestDto->getEmail(),
		    "ResponseUrl" => $createRequestDto->getResponseUrl(),
		    "AllowDuplicateInvoiceNoDate" => $createRequestDto->getAllowDuplicateInvoiceNoDate(),
		    "CreditInformations" => $createRequestDto->getCreditInformations(),
		]);

		// Send POST request
		try {
		    $response = $client->post($this->base_end_point.'/api/v3/spgservice/CreatePaymentRequest', [
		        'headers' => $headers,
		        'body' => $jsonPayload,
		    ]);

		    // Handle response
		    $statusCode = $response->getStatusCode();
		    $responseData = $response->getBody()->getContents();

		    if($statusCode == 200){
		    	return (object) json_decode($responseData);
		    }else{
		    	echo "Status Code: $statusCode\n";
		    	echo "Response Data:\n$responseData\n";
		    }
		} catch (RequestException $e) {
		    // Handle request exception
		    if ($e->hasResponse() && env('APP_DEBUG',true)) {
		        $statusCode = $e->getResponse()->getStatusCode();
		        $responseBody = $e->getResponse()->getBody()->getContents();
		        echo "Error: $statusCode - $responseBody\n";
		    } else {
		        Log::info("Error: " . $e->getMessage() . "\n");
		    }
		}
	}

	public function validate_response(Request $request){
		if(!$request->Token){
			return [
				'Status' => 401
			];
		}
		// Guzzle HTTP client
		$client = new Client();

		// Prepare headers
		$headers = [
		    'Authorization' => "Basic ".$this->access_token,
		    'Content-Type' => 'application/json',
		];

		// Prepare JSON payload
		$jsonPayload = json_encode([
		    "Token" => $request->Token
		]);

		// Send POST request
		try {
		    $response = $client->post($this->base_end_point.'/api/v3/spgservice/TransactionVerificationWithToken', [
		        'headers' => $headers,
		        'body' => $jsonPayload,
		    ]);

		    // Handle response
		    $statusCode = $response->getStatusCode();
		    $responseData = $response->getBody()->getContents();

		    return json_decode($responseData);
		} catch (RequestException $e) {
		    // Handle request exception
		    if ($e->hasResponse() && env('APP_DEBUG',true)) {
		        $statusCode = $e->getResponse()->getStatusCode();
		        $responseBody = $e->getResponse()->getBody()->getContents();
		        echo "Error: $statusCode - $responseBody\n";
		    } else {
		        Log::info("Error: " . $e->getMessage() . "\n");
		    }
		}
	}

	public function validate_ipn(Request $request){
		if(!$request->Token){
			return [
				'Status' => 401
			];
		}
		// Guzzle HTTP client
		$client = new Client();

		// Prepare headers
		$headers = [
		    'Authorization' => "Basic ".$this->access_token,
		    'Content-Type' => 'application/json',
		];

		// Prepare JSON payload
		$jsonPayload = json_encode($request->all());

		// Send POST request
		try {
		    $response = $client->post($this->base_end_point.'/api/v3/spgservice/IPNCheck', [
		        'headers' => $headers,
		        'body' => $jsonPayload,
		    ]);

		    // Handle response
		    $statusCode = $response->getStatusCode();
		    $responseData = $response->getBody()->getContents();

		    return json_decode($responseData);
		} catch (RequestException $e) {
		    // Handle request exception
		    if ($e->hasResponse() && env('APP_DEBUG',true)) {
		        $statusCode = $e->getResponse()->getStatusCode();
		        $responseBody = $e->getResponse()->getBody()->getContents();
		        echo "Error: $statusCode - $responseBody\n";
		    } else {
		        Log::info("Error: " . $e->getMessage() . "\n");
		    }
		}
	}
}
