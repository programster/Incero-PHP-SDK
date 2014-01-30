<?php

/* 
 * Fetch information about all your deployed servers!
 */

class GetServersRequest implements Request
{
    public function __construct() {}
    
    /**
     * Sends the request to fetch all the servers.
     * @param bool $indexed - override to true if you want the returned list to be indexed by server
     *                        ID.
     * @return Array<Server>
     */
    public function send($indexed=false)
    {
        $servers = array();
        
        $response = SiteSpecific::sendInceroApiRequst('server/');
        
        foreach ($response as $serverStdObject)
        {
            if ($indexed)
            {
                $server = Server::buildFromStdObject($serverStdObject);
                $servers[$server->getId()] = $server;
            }
            else
            {
                $servers[] = Server::buildFromStdObject($serverStdObject);
            }
        }
        
        return $servers;
    }
    
    
}

