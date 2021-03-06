<?php

/* 
 * Enum for the available models. Please use one of the build methods to create one of these objects
 *
 * Hardcoding these prevents having to perform lookups with list_models, but has the disadvantage
 * of not being dynamic and instantly reflecting any changes that Incero may make to its offering.
 */

class InceroModel
{
    private $m_id;
    private $m_name;
    
    private $m_isSsd;
    private $m_ram;
    private $m_cpu;
    private $m_capacity;
    private $m_price;
    
    
    private function __construct() {}
    
    
    public static function buildSolidState120()
    {
        $model = new InceroModel();
        
        $model->m_id = 1;
        $model->m_name = "E3, 32GB RAM, 120GB SSD ($0.004/min)";
        $model->m_cpu = "E3";
        $model->m_ram = 32;
        $model->m_capacity = 120;
        $model->m_isSsd = true;
        $model->m_price = 0.004;
        
        return $model;
    }
    
    
    public static function buildHardDrive2Tb()
    {
        $model = new InceroModel();
        
        $model->m_id = 2;
        $model->m_name = "E3, 32GB RAM, 2TB HDD ($0.004/min)";
        $model->m_cpu = "E3";
        $model->m_ram = 32;
        $model->m_capacity = 2000; # Anyone debating this should look up diff between TiB and TB
        $model->m_isSsd = false;
        $model->m_price = 0.004;
        
        return $model;
    }
    
    
    public static function buildSolidState480()
    {
        $model = new InceroModel();
        
        $model->m_id = 3;
        $model->m_cpu = "E3, 32GB RAM, 480GB SSD ($0.005/min)";
        $model->m_ram = 32;
        $model->m_capacity = 480;
        $model->m_isSsd = true;
        $model->m_price = 0.005;
        
        return $model;
    }
    
    
    public static function buildSolidState960()
    {
        $model = new InceroModel();
        
        $model->m_id = 4;
        $model->m_name = "Dual E5, 384GB RAM, 960GB SSD ($0.03/min)";
        $model->m_cpu = "Duel E5";
        $model->m_ram = 384; # This is not a typo!
        $model->m_capacity = 960;
        $model->m_isSsd = true;
        $model->m_price = 0.03;
        
        return $model;
    }
    
    
    /**
     * Builds a model based on the specified ID.
     * @param int $id
     * @return InceroModel
     * @throws Exception if you provided an incorrect ID.
     */
    public static function buildFromId($id)
    {
        $model = null;
        
        switch ($id)
        {
            case 1: $model = self::buildSolidState120(); break;
            case 2: $model = self::buildHardDrive2Tb(); break;
            case 3: $model = self::buildSolidState480(); break;
            case 4: $model = self::buildSolidState960(); break;
            
            default:
            {
                throw new Exception('Unrecognized model id [' . $id . ']');
            }
        }
        
        return $model;
    }


    /**
     * Builds a model based on the std object returned from a get_models request.
     * @param StdObject $stdObject returned from json decoding a response.
     * @return InceroModel
     * @throws Exception if you provided an incorrect ID.
     */
    public static function buildFromStdObject($stdObject)
    {
        $model = self::buildFromId($stdObject->model_id);
        
        # Override the model with details we recieved, in case there is an update/change.
        $model->m_name = $stdObject->model;

        # model price string looks like "($0.03/min)" and I hate RegExps 
        # http://regex.info/blog/2006-09-15/247
        $priceParts = explode('$', $stdObject->model);
        $priceParts = explode('/min', $priceParts[1]);
        $model->m_price = $priceParts[0];
        
        return $model;
    }
    
    
    public function getId()             { return $this->m_id; }
    public function getName()           { return $this->m_name; }
    public function getRam()            { return $this->m_ram; }
    public function getCpu()            { return $this->m_cpu; }
    public function getCapacity()       { return $this->m_capacity; }
    public function isSsd()             { return $this->m_isSsd; }
    public function getPricePerMin()    { return $this->m_price; }
    
    
    # I prefer straight forward simplicity over performance. Your computer is fast enough.
    public function getPricePerHour()   { return $this->getPricePerMin() * 60; }
    public function getPricePerDay()    { return $this->getPricePerHour() * 24; }
    public function getPricePerWeek()   { return $this->getPricePerDay() * 7; }
    public function getPricePerYear()   { return $this->getPricePerWeek() * 52; }
    public function getPricePerMonth()  { return $this->getPricePerYear() / 12.0; }
}