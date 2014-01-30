<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Server implements JsonSerializable
{
    private $m_serverId;
    
    # extra info that may be returned
    private $m_name;
    private $m_model;
    private $m_imageId;
    private $m_ipAddress;
    private $m_serverStatus;
    private $m_startTime;
    private $m_endTime;
    private $m_orderTime;
    private $m_rootPassword;
    
    
    /**
     * Create a server from an ID. Once you have done this, you can perform all manner of 
     * shenanigans.
     * @param int $serverId - the ID of the server (duh)
     * @return Server
     */
    public function construct($serverId)
    {
        $this->m_serverId = $serverId;
    }
    
    
    /**
     * Build one of these objects from a stdObject returned from an API request
     * @param StdObject $stdObject
     * @return Server
     */
    public function buildFromStdObject($stdObject)
    {
        $server = new Server($stdObject->id);
        $server->updateFromResponse($stdObject);
        return $server;
    }
    
    
    /**
     * Cancel/terminate a server.
     * @param void
     * @return void
     */
    public function cancel()
    {
        self::sendRequest('cancel');
    }
    
    
    /**
     * Alias for cancel(). Both of these terminate/cancel a server.
     * @param void
     * @return void 
     */
    public function terminate()
    {
        self::sendRequest('cancel');
    }
    
    
    /**
     * Rename the server.
     * @param String $newName - the new name for the server
     */
    public function rename($newName)
    {
        $parameters = array('name' => $newName);
        self::sendRequest('rename', $parameters);        
    }
    
    
    /**
     * "Have you tried turning it off and on again?"
     * @param void
     * @return void
     */
    public function reboot()
    {
        self::sendRequest('reboot');
    }
    
    
    /**
     * Converts this object into a form that can be json encoded. The object is deliberately 
     * kept the same as the format that the incero stdObjects come in.
     * @param void
     * @return Array
     */
    public function jsonSerialize()
    {
        return array(
            'id'            => $this->m_id,
            'name'          => $this->m_name,
            'model_id'      => $this->m_model,
            'image_id'      => $this->m_imageId,
            'ip_address'    => $this->m_ipAddress,
            'server_status' => $this->m_serverStatus,
            'start_time'    => $this->m_startTime,
            'end_time'      => $this->m_endTime,
            'order_time'    => $this->m_orderTime,
            'root_pw'       => $this->m_rootPassword
        );
    }
    
    
    /**
     * 
     * @global type $globals
     * @param type $action
     * @param type $parameters
     * @throws Exception
     */
    private static function sendRequest($action, $parameters=array())
    {        
        $extension = 'server/' . $this->m_serverId . '/' . $action . '/';
        
        $response = SiteSpecific::sendInceroApiRequst($extension, $parameters);
        
        if ($response->status == "OK")
        {
            $this->updateFromResponse($response);
        }
        else
        {
            throw new Exception($response->message);
        }
    }
    
    
    /**
     * Builds this object from a stdObject returned from an API request.
     * @param StdObject $response
     * @return void
     */
    private function updateFromResponse($response)
    {
        $this->m_id             = $response->id;
        $this->m_name           = $response->name;
        $this->m_model          = $response->model_id;
        $this->m_imageId        = $response->image_id;
        $this->m_ipAddress      = $response->ip_address;
        $this->m_status         = $response->server_status;
        $this->m_startTime      = $response->start_time;
        $this->m_endTime        = $response->end_time;
        $this->m_orderTime      = $response->order_time;
        $this->m_rootPassword   = $response->root_pw;
    }
}