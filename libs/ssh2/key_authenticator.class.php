<?php

/*
 * SSH Key based authenticator for the SSH2 extension. 
 */

class KeyAuthenticator implements AuthenticatorInterface
{
    private $m_username;
    private $m_pubKeyPath;
    private $m_privKeyPath;
    private $m_privKeyPass = "";

    /* Creates an Authenticator from key file details.
     * @param String $username - the username of who is 'logging in'
     * @param String $pubKeyPath - the path to the public key of the server
     * @param String $privKeyPath - the path to the private key belonging to the server.
     * @param String $privKeyPass - (optional) the password used to decrypt the private key file 
     *                            (they should be encrypted but not forced.)
     * @return Authenticator $authenticator - the newly created instance of this object.
     */
    public function __construct($username, 
                                $pubKeyPath, 
                                $privKeyPath, 
                                $privKeyPass="")
    {
        $this->m_username = $username;
        $this->m_pubKeyPath = $pubKeyPath;
        $this->m_privKeyPath = $privKeyPath;
        $this->m_privKeyPass = $privKeyPass;
    }
   
    
    
    /**
     * This is the point of this class. Authenticates this object.
     * @param type $connection - the resource from an ssh2 extension.
     * @return bool $result - true if successfully authenticated, false otherwise.
     */
    public function authenticate($connection)
    {
        $result = false;
        
        $result = ssh2_auth_pubkey_file($connection, 
                                        $this->m_username, 
                                        $this->m_pubKeyFile, 
                                        $this->m_privateKeyFile, 
                                        $this->m_privateKeyPass);

        return $result;
    }
}
