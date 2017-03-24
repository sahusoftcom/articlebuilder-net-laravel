PayPal.ExpressCheckout.Digital.Goods Laravel Version: 1.0
==========================

Service Provider of PayPal.ExpressCheckout.Digital.Goods API for Laravel PHP Framework [ [Packagist] ]

[Packagist]: <https://packagist.org/packages/sahusoftcom/paypal-expresscheckout-digital-goods>

## Installation

Type the following command in your project directory

`composer require sahusoftcom/paypal-expresscheckout-digital-goods`

OR

Add the following line to the `require` section of `composer.json`:

```json
{
    "require": {
        "sahusoftcom/paypal-expresscheckout-digital-goods": "dev-master"
    }
}
```

## Setup

In `/config/app.php`, add the following to `providers`:
  
```php
SahusoftCom\Paypal\PayPalServiceProvider::class
```

## How to use

1. You should use the `PayPal` class to access its function
2. Pass `apiContext` parameter in `PayPal` Class

For example:

```php
<?php
namespace App;
 
use SahusoftCom\PayPal\PayPal;

class PayPal
{	
	// Example to use ArticleBuilder Service
	public function firstMethod()
	{
		$apiContext = (object)[];
		
        $apiContext->APIUSERNAME = "varunsahu-facilitator_api1.yahoo.co.in";
        $apiContext->APIPASSWORD = "RCGW9N8HMU7Y3M28";
        $apiContext->APISIGNATURE = "AFcWxV21C7fd0v3bYYYRCpSSRl31ArRWgR3MHk7Bc3HwAOny8r7IGOUh";
        $apiContext->ENDPOINT = "https://api-3t.sandbox.paypal.com/nvp";
        $apiContext->VERSION = "65.1";
        $apiContext->REDIRECTURL = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
        
        $object = new \SahusoftCom\PayPal\PayPal($apiContext);
        
        $data = [];
        $data['RETURNURL'] = "http://paypal.local.geekydev.com/getDone";
        $data['CANCELURL'] = "http://paypal.local.geekydev.com/getCancel";
    
        $data['CURRENCY'] = "USD";
        $data['TOTAL_AMOUNT'] = "100";
        $data['AMOUNT'] = "100";
        $data['TAX_AMOUNT'] = "0";
        $data['DESCRIPTION'] = "Movies";
        $data['PAYMENT_ACTION'] = "SALE";
        $data['NOSHIPPING'] = "1```";
    
        $data['ITEM_LIST'] = [];
        $data['ITEM_LIST'][0] = [
        	'NAME'			=> 'First Name',
    		'NUMBER'		=> 123,
    		'QUANTITY'		=> 1,
    		'TAX_AMOUNT'	=> 0,
    		'AMOUNT'		=> 100,
    		'DESCRIPTION'	=> 'First Name Description'
        ];
    
        $status = $object->handle($data);
	}

```

## PayPal class Functions

1.	`handle`

	`funtion`: handle($data)

	`require`:
	```
	    $data = [];
	    $data['RETURNURL'] = "route(getDone)";
	    $data['CANCELURL'] = "route(getCancel)";

	    $data['CURRENCY'] = "USD";
	    $data['TOTAL_AMOUNT'] = "100";
	    $data['AMOUNT'] = "100";
	    $data['TAX_AMOUNT'] = "0";
	    $data['DESCRIPTION'] = "Movies";
	    $data['PAYMENT_ACTION'] = "SALE";
	    $data['NOSHIPPING'] = "1```";

	    $data['ITEM_LIST'] = [];
	    $data['ITEM_LIST'][0] = [
	    	'NAME'			=> 'First Name',
			'NUMBER'		=> 123,
			'QUANTITY'		=> 1,
			'TAX_AMOUNT'	=> 0,
			'AMOUNT'		=> 100,
			'DESCRIPTION'	=> 'First Name Description'
	    ];

	Returns:
		response with status and message.
	```

## PayPalHttpPost class Functions

1.	`handle`

	`funtion`: handle($myEndpoint, $myApiStr)

	`require`:
	```
	$myEndpoint = "https://api-3t.sandbox.paypal.com/nvp";
	$myApiStr	NVP String

	Returns:
		makes a curl request and returns response from server.
	```

