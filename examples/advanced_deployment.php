<?php

/* 
 * A more advanced deployment.
 * Dont forget to terminate them with the terminator.
 */

require_once(dirname(__FILE__) . '/../autoload.php');

/* 
 * Terminate all servers!
 */

$model = InceroModel::buildSolidState128();
$os = InceroOperatingSystem::buildUbuntu12_04();
$names = array('Stuart', 'Gordon', 'James');

$request = new DeploymentRequest($model, $os, $numInstances=3, $names);

# if the 128 becomes available try to deploy the 2TB which is the same price
$request->addModel(InceroModel::buildHardDrive2Tb());

# if that becomes unavailable try to deploy the 480
$request->addModel(InceroModel::buildSolidState480());


$servers = $request->send();

if (count($servers) > 0)
{
    $msg = "The following servers were deployed:" . PHP_EOL;
    
    foreach ($servers as $server)
    {
        /* @var $server InceroServer */
        $msg .= print_r($server, true);
    }
    
    print $msg . PHP_EOL;
}
else
{
    print "Sorry, no servers were deployed. Try again later." . PHP_EOL;
}


print "done!" . PHP_EOL;

