<?php

/* 
 * Fetch information about the available operating systems from the API.
 * This is particualarly useful for checking that the incero_operating_system enum is up-to-date.
 */

require_once(dirname(__FILE__) . '/../autoload.php');

$request = new ListOsRequest();
$response = $request->send();

print "The available operating systems are (raw): " . PHP_EOL .
      print_r($response, true) . PHP_EOL;

