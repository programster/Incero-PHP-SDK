<?php

/* 
 * At the moment this is sort of a dumping ground of library functions that didnt belong anywhere
 * else.
 */

class SiteSpecific
{    
    /**
     * Send an API Request to the Incero API.
     * @param String $extension - the url extension. E.g. /server or /server/9/restart
     * @param Array $params - name value pairs to send with the request
     * 
     * @return StdObject
     */
    public static function sendInceroApiRequst($extension, $params=array())
    {
        global $globals;
        
        $baseUrl = 'https://api.incero.com/';
        
        $url = $baseUrl . $extension;
        
        $parameters['key'] = $globals['INCERO_API_KEY'];
        
        $response = self::sendApiRequest($url, $parameters);
        
        if (strcmp($response->status, "OK") !== 0)
        {
            throw new Exception($response->message);
        }
        
        return $response;
    }
    
    
    /**
     * Sends an api request through the use of CURL
     * 
     * @param url - the url where the api is located. e.g. technostu.com/api
     * @param parameters - associative array of name value pairs for sending to the api server.
     * 
     * @return ret - array formed from decoding json message retrieved from xml api
     */
    public static function sendApiRequest($url, $parameters)
    {      
        $query_string = http_build_query($parameters, '', '&');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $jsondata = curl_exec($ch);
        
        if (curl_error($ch))
        {
            throw new Exception("Connection Error: " . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        
        curl_close($ch);
        $ret = json_decode($jsondata); # Decode JSON String
        
        if ($ret == null)
        {
            throw new Exception('Recieved a non json response from API: ' . $jsondata);
        }
        
        return $ret;
    }
}