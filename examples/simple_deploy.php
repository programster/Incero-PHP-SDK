<?php

/* 
 * Deploy the smallest server! Dont forget to terminate it afterwards!
 */

require_once(dirname(__FILE__) . '/../autoload.php');

/* 
 * Terminate all servers!
 */

$model = InceroModel::buildSolidState120();
$os = InceroOperatingSystem::buildUbuntu12_04();

$request = new DeploymentRequest($model, $os);
$servers = $request->send();



if (count($servers) > 0)
{
    $msg = "The following server was deployed:" . PHP_EOL;
    
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

