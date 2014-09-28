<?php

/*

When giving gifts, consumers usually keep in mind two variables - cost and 
quantity. In order to facilitate better gift-giving on the Zappos website, the 
Software Engineering team would like to test a simple application that allows 
a user to submit two inputs: N (desired # of products) and X (desired dollar 
amount). The application should take both inputs and leverage the Zappos API (
http://developer.zappos.com/docs/api-documentation) to create a list of Zappos 
products whose combined values match as closely as possible to X dollars. For 
example, if a user entered 3 (# of products) and $150, the application would 
print combinations of 3 product items whose total value is closest to $150.

Please post your source code and library packages on GitHub so that we can run 
your application! Application performance and efficiency will be the most 
important criteria as we select winners.

*/

// Define Zappos API Key in Separate File to Keep Private
include('key.php');
if (!defined('ZAPPOS_API_KEY')) {
	define('ZAPPOS_API_KEY', false);
}


class ZapposProductCombo {

	// Keeps Track of Results Page
	private static $page;

	// List of All Items Retreived from API
	private static $items;

	public static function getProductCombos() {

		self::$page = 0;
		self::$items = [];

		self::getItems();
		self::getItems();

		return self::$items;

	}

	public static function getItems() {

		self::$page++;

		// TODO Check Total Result Count

		$zapposApiUrl = 'http://api.zappos.com/Search?key='.ZAPPOS_API_KEY;

		$params = array(
			'limit' => 100
		);
		$paramString = '&'.http_build_query($params);

		// Get the Data
		$ch = curl_init($zapposApiUrl.$paramString);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		// TODO Check Errors

		$items = json_decode(stripslashes($response));

		var_dump(self::$items);
		self::$items = array_merge(self::$items, $items['results']);

	}

}

print_r(ZapposProductCombo::getProductCombos());

?>