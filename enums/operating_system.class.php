<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OperatingSystem
{
    private $m_id;
    private $m_name;
    
    private static $s_map = array(
        "centos-6"              => 1,
        "debian-7.3"            => 2,
        "ubuntu-12.04"          => 3,
        "ubuntu-13.04"          => 4,
        "ubuntu-13.10"          => 5,
        "ubuntu-12.04 Desktop"  => 6
    );
    
    private function __construct($name)
    {
        $this->m_id   = self::$s_map[$name];
        $this->m_name = $name;
    }
    
    public static function buildUbuntu12_04()
    {
        return new OperatingSystem("ubuntu-12.04");
    }
    
    
    public static function buildCentos6()
    {
        return new OperatingSystem("centos-6");
    }
    
    
    /**
     * Builds a model based on the specified ID.
     * @param int $id
     * @return OperatingSystem
     * @throws Exception if you provided an incorrect ID.
     */
    public static function buildFromId($id)
    {
        $operatingSystem = null;
        
        $reverseLookup = array_flip(self::$s_map);
        
        if (isset($reverseLookup[$id]))
        {
            $osName = $reverseLookup[$id];
            $operatingSystem = new OperatingSystem($osName);
        }
        else
        {
            throw new Exception('Invalid operating system ID provided [' . $id . ']');
        }
        
        return $operatingSystem;
    }
}