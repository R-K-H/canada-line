<?php

// Assuming you installed from Composer:
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$baseURL = 'https://bookings.blueskybooking.net/';
$function = 'Booking.aspx?';
$date = '07%2F08%2F2016';
$query = 'Company_ID=4&Return=0&Departure_Location_ID=18&Departure_Date='.$date.'&Arrival_Location_ID=3&Return_Date=&Weight_ID%5B9%5D=0&Weight_ID%5B10%5D=1&Weight_ID%5B7%5D=0&Weight_ID%5B8%5D=0&Action=Booking&Sorting=0';

$minPrice = 10000;

$url = $baseURL . $function . $query;

$dom = new Dom;
$dom->load($url); // '<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>'

$a = $dom->find('.ui-tablerow-global-schedules');
foreach($a as $flight) {
	$fare = $flight->find('.ui-link-global-schedules-dialog-fare');
	$fare = $fare->find('span');
	$fare = $fare->innerHtml;
	$fare = str_replace('$', '', $fare); // /&.*?;/
	foreach ($fare as $fare) {
		if($fare < $minPrice) {
			$minPrice = $fare;
		} else {
			echo 'price broken';
		}
	}
	$fare = floatval($fare);
	if ($fare <= $minPrice) {
		$minPrice = $fare;
		$flightNumber = $flight->find('.ui-tablecolumn-global-schedules-schedule');
		$flightNumber = preg_replace('|/Twin Otter|', '', $flightNumber);
		$flightNumber = preg_replace('|Flight #|', '', $flightNumber);
		$time = $flight->find('.ui-tablecolumn-global-schedules-departure-time');
		echo $time;
		echo $fare;
		echo '<br>';
	} else {
		echo 'NOT LOWEST<br>';
	}
	
}
exit;