<?php

/* 
 * Fetch info about all the possible operating systems available. Please use the OperatingSystem 
 * factory instead of relying on this.
 */

class ListOsRequest implements Request
{
    public function __construct() {}
    
    public function send()
    {
        $response = SiteSpecific::sendInceroApiRequst('server/listos');
        return $response;
    }
}

