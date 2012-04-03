<?php

// Tested on PHP 5.2, 5.3

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('Stripe needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Stripe needs the JSON PHP extension.');
}


abstract class Stripe
{
  public static $apiKey;
  public static $apiBase = 'https://api.stripe.com/v1';
  public static $verifySslCerts = true;
  const VERSION = '1.6.2';

  public static function getApiKey()
  {
    return self::$apiKey;
  }

  public static function setApiKey($apiKey)
  {
    self::$apiKey = $apiKey;
  }

  public static function getVerifySslCerts() {
    return self::$verifySslCerts;
  }

  public static function setVerifySslCerts($verify) {
    self::$verifySslCerts = $verify;
  }
}


// Utilities
require(dirname(__FILE__) . '/Util.php');
require(dirname(__FILE__) . '/Util/Set.php');

// Errors
require(dirname(__FILE__) . '/Error.php');
require(dirname(__FILE__) . '/ApiError.php');
require(dirname(__FILE__) . '/ApiConnectionError.php');
require(dirname(__FILE__) . '/AuthenticationError.php');
require(dirname(__FILE__) . '/CardError.php');
require(dirname(__FILE__) . '/InvalidRequestError.php');

// Plumbing
require(dirname(__FILE__) . '/Object.php');
require(dirname(__FILE__) . '/ApiRequestor.php');
require(dirname(__FILE__) . '/ApiResource.php');

// Stripe API Resources
require(dirname(__FILE__) . '/Charge.php');
require(dirname(__FILE__) . '/Customer.php');
require(dirname(__FILE__) . '/Invoice.php');
require(dirname(__FILE__) . '/InvoiceItem.php');
require(dirname(__FILE__) . '/Plan.php');
require(dirname(__FILE__) . '/Token.php');
require(dirname(__FILE__) . '/Coupon.php');
require(dirname(__FILE__) . '/Event.php');