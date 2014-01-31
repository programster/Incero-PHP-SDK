<?php

/* 
 * Fetch all your deployed servers!
 */

class GetServersRequest implements RequestInterface
{
    public function __construct() {}
    
    /**
     * Sends the request to fetch all the servers.
     * @param bool $indexed - override to true if you want the returned list to be indexed by server
     *                        ID.
     * @return Array<InceroServer> - list of InceroServer objects that you own
     */
    public function send($indexed=false)
    {
        $servers = array();
        
        $response = SiteSpecific::sendInceroApiRequst('server/');
        
        foreach ($response as $serverStdObject)
        {
            if ($indexed)
            {
                $server = InceroServer::buildFromStdObject($serverStdObject);
                $servers[$server->getId()] = $server;
            }
            else
            {
                $servers[] = InceroServer::buildFromStdObject($serverStdObject);
            }
        }
        
        return $servers;
    }
    
    
}

