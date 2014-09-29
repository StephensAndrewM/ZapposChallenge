<?php

/* 
 * Andrew Stephens
 * andrew@andrewmediaprod.com
 * 
 * Submission for Zappos Internship Coding Challenge
 */


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
	private static $page = 0;
	// List of All Items Retreived from API
	private static $items = [];

	// Used to Determine How Many Times to Run the Algorithm
	// More Times = Higher Chance of Success, but Longer Run Time
	const ATTEMPTS = 5;

	public static function getProductCombo($numProducts, $dollarAmount) {

		// Data Storage for Finding Best Combo
		$bestCombo = [];
		$bestComboDiff = 999999;

		// Run the Algorithm a Set Number of Times
		for ($i = 0; $i < self::ATTEMPTS; $i++) {

			// Fetch Items and Add to Cumulative Listing
			self::getItems();

			// Find Combinations that Satisfy the Given Conditions
			$combos = self::getCombos($numProducts, $dollarAmount);

			// And Then Find the Best One
			foreach ($combos as $combo) {
				$comboVal = 0;
				foreach ($combo as $items) {
					$comboVal += self::moneyToFloat($items->price);
				}
				if (abs($dollarAmount - $comboVal) < $bestComboDiff) { 
					$bestCombo = $combo;
				}
			}

		}

		return $bestCombo;

	}

	// Convert a String with a Dollar Sign to a Floating Point Number
	private static function moneyToFloat($moneyStr) {
		return floatval(str_replace("$","",$moneyStr));
	}

	// Combo Finder
	// Finds the average target price, then finds products that have that price.
	// Loops several times, increasing the amount of variance allowed between the item's
	//   price and the average price.
	private static function getCombos($numProducts, $dollarAmount) {

		$dollarsPerProduct = $dollarAmount/$numProducts;
		$deviance = 0;

		$potentialCombos = [];

		// Run Multiple Times to Increased Likelihood of Success
		for ($k = 0; $k < self::ATTEMPTS; $k++) {

			$itemsInRange = [];

			// Check if Item's Price is Close Enough
			foreach(self::$items as $item) {
				if (abs(self::moneyToFloat($item->price) - $dollarsPerProduct) < $deviance) {
					$itemsInRange[] = $item;
				}
			}

			// Group Items Together
			while(sizeof($itemsInRange) > ($numProducts-1)) {

				$newCombo = [];
				for($i = 0; $i < $numProducts; $i++) {
					$item = array_pop($itemsInRange);
					$newCombo[] = $item;
				}
				$potentialCombos[] = $newCombo;

			}

			// Allow More Variation on Next Cycle
			$deviance += 2;

		}

		return $potentialCombos;

	}

	// Item Getter
	// Calls the Zappos API and fetches item data.
	// Appends to a global array of items.
	private static function getItems() {

		self::$page++;

		$zapposApiUrl = 'http://api.zappos.com/Search?key='.ZAPPOS_API_KEY;

		$params = array(
			'limit' => 100,
			'page' => self::$page
		);
		$paramString = '&'.http_build_query($params);

		// Get the Data
		$ch = curl_init($zapposApiUrl.$paramString);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		if(curl_errno($ch)) {
			echo 'Curl error: ' . curl_error($ch);	
			return;
		}
		curl_close($ch);

		$items = json_decode(stripslashes($response));
		
		if ($items == null) { return; }

		self::$items = array_merge(self::$items, $items->results);

	}

}

// Get Params
$numProducts = (isSet($_GET['numProducts'])) ? intval($_GET['numProducts']) : 5;
$dollarAmount = (isSet($_GET['dollarAmount'])) ? intval($_GET['dollarAmount']) : 100;

print_r(ZapposProductCombo::getProductCombo($numProducts, $dollarAmount));

?>