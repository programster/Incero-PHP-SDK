<?php

/* 
 * Fetch information about the models from the API.
 */

require_once(dirname(__FILE__) . '/../autoload.php');

$request = new ListModelsRequest();
$response = $request->send();

$output = "The available models are (raw): " . PHP_EOL .
          print_r($response, true) . PHP_EOL;

$output .= "Models available in SDK object form: " . PHP_EOL;

foreach ($response as $modelStdObject)
{
    $model = InceroModel::buildFromStdObject($modelStdObject);
    $output .= print_r($model, true);
}

print $output . PHP_EOL;
