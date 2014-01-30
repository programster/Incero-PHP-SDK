<?php

/* 
 * List all the possible types of server you can deploy.
 */

class ListModelsRequest implements Request
{
    public function construct();
    
    public function send()
    {
        SiteSpecific::sendInceroApiRequst('server/listmodel/');
        return $response;
    }
}

