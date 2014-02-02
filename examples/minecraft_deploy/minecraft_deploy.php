<?php

# Advanced script to automatically deploy a server and get the minecraft
# server program set up and running on it.


require_once(dirname(__FILE__) . '/../../autoload.php');


# Deploy the server
print "Deploying server..." . PHP_EOL;
$model = InceroModel::buildSolidState120();
$os = InceroOperatingSystem::buildUbuntu12_04();
$names = array('Minecraft');

$request = new DeploymentRequest($model, $os, $numInstances=1, $names);

# if the 128 becomes available try to deploy the 2TB which is the same price
$request->addModel(InceroModel::buildHardDrive2Tb());

$servers = $request->send();

if (count($servers) > 0)
{
    /* @var $server InceroServer */
    $server = $servers[0];
    
    # Wait until the server has been deployed
    print "Server has been set to deploy, now waiting until it is ready...";
    sleep(3);
    $server->updateDetails();
    
    while ($server->getServerStatus() != "online")
    {
        print "still not ready..." . PHP_EOL;
        sleep(3);
        $server->updateDetails();
    }
    
    # Install minecraft to the server
    print "Server is online. Now transferring install script..." . PHP_EOL;    
    $authenticator = new PasswordAuthenticator('root', $server->getRootPassword());
    $ssh2 = new Ssh2($server->getIpAddress(), $authenticator);

    $ssh2->send(dirname(__FILE__) . '/deploy.sh', '/root/deploy.sh');

    # You still need the & on the end to not "truly" block (block just means wait for output);
    print "Installing Minecraft...";
    
    try
    {
        $ssh2->exec("/bin/bash /root/deploy.sh", $block=true);
    } 
    catch (Exception $ex) 
    {
        # Unfortunately the script casuses a debconf error message which does not matter as it 
        # continues to work anyways.
    }
    
    print "Your minecraft server should be available at " . $server->getIpAddress() . PHP_EOL;
}
else
{
    print "Sorry, could not deploy one of the base servers." . PHP_EOL;
}

print "done!" . PHP_EOL;
