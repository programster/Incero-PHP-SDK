<?php

/* 
 * List all the possible types of server you can deploy.
 */

class ListModelsRequest implements RequestInterface
{
    public function __construct(){}
    
    public function send()
    {
        $response = SiteSpecific::sendInceroApiRequst('server/listmodel/');
        return $response;
    }
}

