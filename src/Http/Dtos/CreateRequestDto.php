<?php
namespace Exceptio\SonaliPayment\Http\Dtos;

use \InvalidArgumentException;
use Exceptio\SonaliPayment\Http\Dtos\CreditInformationDto;

class CreateRequestDto {
    private string $invoiceNo;
    private string $invoiceDate;
    private float $requestTotalAmount;
    private string $customerName;
    private string $customerContactNo;
    private string $allowDuplicateInvoiceNoDate;
    private array $creditInformations;
    private string $successUrl;
    private string $failUrl;
    private string $email;  // Optional field

    public function __construct(
        string $invoiceNo,
        string $invoiceDate,
        float $requestTotalAmount,
        string $customerName,
        string $customerContactNo,
        string $email = "",
        string $allowDuplicateInvoiceNoDate,
        array $creditInformations,
        string $responseUrl
    ){
        // Validate and set properties
        $this->setInvoiceNo($invoiceNo);
        $this->setInvoiceDate($invoiceDate);
        $this->setRequestTotalAmount($requestTotalAmount);
        $this->setCustomerName($customerName);
        $this->setCustomerContactNo($customerContactNo);
        $this->setEmail($email);
        $this->setAllowDuplicateInvoiceNoDate($allowDuplicateInvoiceNoDate);
        $this->setCreditInformations($creditInformations);
        $this->setResponseUrl($responseUrl);
    }

    public function getInvoiceNo(): string {
        return $this->invoiceNo;
    }

    public function getInvoiceDate(): string {
        return $this->invoiceDate;
    }

    public function getRequestTotalAmount(): float {
        return $this->requestTotalAmount;
    }

    public function getCustomerName(): string {
        return $this->customerName;
    }

    public function getCustomerContactNo(): string {
        return $this->customerContactNo;
    }

    public function getAllowDuplicateInvoiceNoDate(): string {
        return $this->allowDuplicateInvoiceNoDate;
    }

    public function getCreditInformations(): array {
        return $this->creditInformations;
    }

    public function getResponseUrl(): string {
        return $this->responseUrl;
    }

    public function getEmail(): string {
        return $this->email;
    }

    // Setters with validation methods
    private function setInvoiceNo(string $invoiceNo): void {
        // Check length limit
        if (strlen($invoiceNo) > 50) {
            throw new InvalidArgumentException('InvoiceNo must be less than or equal to 50 characters.');
        }
        $this->invoiceNo = $invoiceNo;
    }

    private function setInvoiceDate(string $invoiceDate): void {
        // Validate date format
        $date = \DateTime::createFromFormat('Y-m-d', $invoiceDate);
        if (!$date || $date->format('Y-m-d') !== $invoiceDate) {
            throw new InvalidArgumentException('Invalid InvoiceDate format. Use YYYY-MM-DD.');
        }
        $this->invoiceDate = $invoiceDate;
    }

    private function setRequestTotalAmount(float $requestTotalAmount): void {
        // No specific validation needed for float (20,2) in PHP
        $this->requestTotalAmount = $requestTotalAmount;
    }

    private function setCustomerName(string $customerName): void {
        // Check length limit
        if (strlen($customerName) > 120) {
            throw new InvalidArgumentException('CustomerName must be less than or equal to 120 characters.');
        }
        $this->customerName = $customerName;
    }

    private function setCustomerContactNo(string $customerContactNo): void {
        // Validate format based on Bangladeshi or international numbers
        // For simplicity, assuming it's a general validation
        $filteredString = preg_replace("/[^0-9]/", "", $customerContactNo);
        if (strlen($filteredString) < 11 || strlen($filteredString) > 15) {
            throw new InvalidArgumentException('CustomerContactNo must be greater than or equal to 13 less than or equal to 15 characters.');
        }
        $this->customerContactNo = $filteredString;
    }

    private function setAllowDuplicateInvoiceNoDate(string $allowDuplicateInvoiceNoDate): void {
        // Validate if it's "Y" or "N"
        if ($allowDuplicateInvoiceNoDate !== 'Y' && $allowDuplicateInvoiceNoDate !== 'N') {
            throw new InvalidArgumentException('AllowDuplicateInvoiceNoDate must be "Y" or "N".');
        }
        $this->allowDuplicateInvoiceNoDate = $allowDuplicateInvoiceNoDate;
    }

    private function setCreditInformations(array $creditInformations): void {
        // Validate each item in the array as CreditInformation objects
        $totalAmount = 0;
        foreach ($creditInformations as $creditInfo) {
            if (!$creditInfo instanceof CreditInformationDto) {
                throw new InvalidArgumentException('All entries in CreditInformationDto must be instances of CreditInformationDto class.');
            }
            $totalAmount += $creditInfo->getCrAmount();
            $this->creditInformations[] = [
            	"SerialNo" => $creditInfo->getSerialNo(),
				"CrAccountOrChallanNo" => $creditInfo->getCrAccountOrChallanNo(),
				"CrAmount" => $creditInfo->getCrAmount(),
				"TranMode" => $creditInfo->getTranMode(),
				"Onbehalf" => $creditInfo->getOnBehalf()
            ];
        }

        if($totalAmount != $this->requestTotalAmount){
            throw new InvalidArgumentException('The RequestTotalAmount amount missmatch.');
        }           

    }

    private function setSerialNo(int $serialNo): void {
        // Validate if needed, assuming integer increments
        $this->serialNo = $serialNo;
    }

    private function setResponseUrl(string $responseUrl): void {
        if(filter_var($responseUrl, FILTER_VALIDATE_URL) === false){
        	throw new InvalidArgumentException('SuccessUrl must be an URL');
        }
        $this->responseUrl = $responseUrl;
    }

    private function setFailUrl(string $failUrl): void {
        if(filter_var($failUrl, FILTER_VALIDATE_URL) === false){
        	throw new InvalidArgumentException('FailUrl must be an URL');
        }
        $this->failUrl = $failUrl;
    }

    private function setEmail(?string $email): void {
        // Validate email format if provided
        if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid Email format.');
        }
        $this->email = $email;
    }
}

?>