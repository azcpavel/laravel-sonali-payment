# Sonali Payment For For Laravel

A simple package for handling Sonali Bank Payment in Laravel.

- [Installation](#installation)
    - [Composer](#composer)
    - [Service Provider](#service-provider)
    - [Config File](#config-file)
- [Opening an Issue](#opening-an-issue)
- [License](#license)

---

## Installation

This package is very easy to set up. There are only couple of steps.

### Composer

Pull this package in through Composer
```
composer require exceptio/laravel-sonali-payment
```

### Service Provider
* Laravel 5.5 and up
Uses package auto discovery feature, no need to edit the `config/app.php` file.

* Laravel 5.4 and below
Add the package to your application service providers in `config/app.php` file.

```php
'providers' => [

    ...

    /**
     * Third Party Service Providers...
     */
    Exceptio\SonaliPayment\SonaliPaymentServiceProvider::class,

],
```

### Config File

Publish the package config file to your application. Run these commands inside your terminal.

    php artisan vendor:publish --provider="Exceptio\SonaliPayment\SonaliPaymentServiceProvider" --tag="config"

You can change token and other settings in config or [.env]. Have a look at config file for more information.


### And that's it!

---

## Usage

### Checkout And Validation Process

```php
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Exceptio\SonaliPayment\Http\Controllers\SonaliPaymentController;
use Exceptio\SonaliPayment\Http\Dtos\CreateRequestDto;
use Exceptio\SonaliPayment\Http\Dtos\CreditInformationDto;

class DemoController
{
    public function doCheckout(Request $request)
    {
        $sonaliPayment = new SonaliPaymentController();
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
                    route('route_name.test_response')
                );

        } catch (\Exception $e) {
            if (env('APP_DEBUG',true)) {
                echo 'Error: ' . $e->getMessage();
            }else{
                Log::info('Error: ' . $e->getMessage());
            }
        }

        return $sonaliPayment->checkout($createRequestDto);
    }

    public function test_response(Request $request){
        if($request->input('Mode') == 'success'){
            $sonaliPayment = new SonaliPaymentController();
            $data = $sonaliPayment->validate_response($request);
            dd($data);
        }else{
            return "Failed Payment";
        }
    }
```

## Opening an Issue
Before opening an issue there are a couple of considerations:
* A **star** on this project shows support and is way to say thank you to all the contributors. If you open an issue without a star, *your issue may be closed without consideration.* Thank you for understanding and the support.
* **Read the instructions** and make sure all steps were *followed correctly*.
* **Check** that the issue is not *specific to your development environment* setup.
* **Provide** *duplication steps*.
* **Attempt to look into the issue**, and if you *have a solution, make a pull request*.
* **Show that you have made an attempt** to *look into the issue*.
* **Check** to see if the issue you are *reporting is a duplicate* of a previous reported issue.
* **Following these instructions show me that you have tried.**
* If you have a questions send me an email to zahid@exceptionsolutions.com
* Please be considerate that this is an open source project that I provide to the community for FREE when opening an issue. 

## License
<p xmlns:cc="http://creativecommons.org/ns#" xmlns:dct="http://purl.org/dc/terms/"><a property="dct:title" rel="cc:attributionURL" href="https://github.com/exception-soluitions/laravel-sonali-payment">Sonali Payment For For Laravel</a> by <a rel="cc:attributionURL dct:creator" property="cc:attributionName" href="https://github.com/exception-soluitions">Exception Solutions</a> is marked with <a href="http://creativecommons.org/publicdomain/zero/1.0?ref=chooser-v1" target="_blank" rel="license noopener noreferrer" style="display:inline-block;">CC0 1.0 Universal
<img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1"><img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/zero.svg?ref=chooser-v1"></a></p>
