<?php

/* 
 * Send a request to deploy servers!
 */

class DeploymentRequest implements RequestInterface
{
    private $m_names;            # array of names to give servers.
    private $m_models = array(); # list of models in preferred order of deployment
    private $m_os;               # The operating system to deploy.
    private $m_numInstances;     # number of servers to try and deploy
    
    /**
     * Deploy a server or many servers. This will try to fulfill your request, but if trying to
     * deploy many, you may want to use addModel to add acceptable alternative models in order of 
     * preference.
     * 
     * @param InceroModel $model - the model you wish to deploy. See the Model in enums.
     * @param InceroOperatingSystem $os - the operating system you wish to deploy.
     * @param int $numInstances - optionally set to more than 1 in order to attempt to deploy 
     *                            multiple servers.
     * 
     * @param Array<String> $names - names to give to your server. If number of names is less than
     *                               the number of instances, then the rest will be left blank.
     */
    public function __construct(InceroModel $model, 
                                InceroOperatingSystem $os, 
                                $numInstances=1, 
                                $names=array())
    {
        if (is_string($names))
        {
            # Somebody forgot to read the documentation....
            $names = array($names);
        }
        
        $this->m_names = $names;
        $this->m_models[] = $model;
        $this->m_os = $os;
        $this->m_numInstances = $numInstances;
    }
    
    
    /**
     * Add models that are acceptable as alternatives, make sure to add them in order of preference!
     * @param Model $model - an acceptable alternative model if primary model is out of stock.
     * @return void.
     */
    public function addModel(InceroModel $model)
    {
        $this->m_models[] = $model;
    }
    
    
    /**
     * Send the request and try to fulfill the order.
     * @param void
     * @return Array<InceroServer> - list of the servers that managed to deploy.
     */
    public function send()
    {
        $deployedServers = array();
        
        $modelIndex = 0;
        $nameIndex = 0;
        
        $extension = 'server/new/';
        
        while (count($deployedServers) != $this->m_numInstances)
        {
            # This is a nasty hack to get around the fact that exceptions can happen anywhere!
            # Really need to implement multiple exception types.
            $deployedOk = false;
            
            try
            {
                if (!isset($this->m_models[$modelIndex]))
                {
                    # Ran out of possible models so stop trying to deploy.
                    break;
                }
                
                $model = $this->m_models[$modelIndex];
                $nameIndex = count($deployedServers); # keep thinking +1, but no.
                
                $params = array(
                    'model' => $model->getId(),
                    'os'    => $this->m_os->getId()
                );
                
                if (isset($this->m_names[$nameIndex]))
                {
                    $params['name'] = $this->m_names[$nameIndex];
                }
            
                $response = SiteSpecific::sendInceroApiRequst($extension, $params);
                $deployedOk = true;
                
            } 
            catch (Exception $ex) 
            {
                # Couldn't deploy the model we desired so try again with a different model.
                $modelIndex++; 
            }
            
            # Leave this outside the loop just in case it throws an error.
            if ($deployedOk)
            {
                $deployedServers[] = InceroServer::buildFromStdObject($response);
            }
        }
        
        return $deployedServers;
    }

}