<?php

/* 
 * Terminate all servers!
 */

require_once(dirname(__FILE__) . '/../autoload.php');

$request = new GetServersRequest();
$servers = $request->send();

if (count($servers) > 0)
{
    foreach ($servers as $server)
    {
        /* @var $server Server */
        $server->terminate(); # cancel would also work
    }
}
else
{
    print "You don't have any servers to terminate." . PHP_EOL;
}


print "done!" . PHP_EOL;

