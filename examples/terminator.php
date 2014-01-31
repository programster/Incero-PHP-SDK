<?php

/* 
 * A simple script to terminate all of your servers!
 * You could easily 
 */

require_once(dirname(__FILE__) . '/../autoload.php');

$request = new GetServersRequest();
$servers = $request->send();

if (count($servers) > 0)
{
    foreach ($servers as $server)
    {
        # Here we are just printing out the properties, but you could easily put an if statement
        # here to only terminate specific servers. E.g. all those whose name match a regex.
        print
            "Terminating server: " . PHP_EOL .
            "Name: "        . $server->getName() . PHP_EOL .
            "Model ID: "    . $server->getModelId() . PHP_EOL .
            "OS ID: "       . $server->getOsId() . PHP_EOL .
            "IP Address: "  . $server->getIpAddress() . PHP_EOL .
            "Status: "      . $server->getServerStatus() . PHP_EOL .
            "Start Time: "  . $server->getStartTime() . PHP_EOL .
            "End Time: "    . $server->getEndTime() . PHP_EOL .
            "Order Time: "  . $server->getOrderTime() . PHP_EOL .
            "Password: "    . $server->getRootPassword() . PHP_EOL;
            
        /* @var $server InceroServer */
        $server->terminate(); # cancel would also work
    }
}
else
{
    print "You don't have any servers to terminate." . PHP_EOL;
}


print "done!" . PHP_EOL;

