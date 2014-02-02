<?php

/*
 * Password based authenticator for the SSH2 extension. 
 */

class PasswordAuthenticator implements AuthenticatorInterface
{
    private $m_username;
    private $m_password;
   

    /* Creates an authenticator from a username and password. Whilst this is the simplest method, it
     * is also probably the least secure (passwords in your codebase?)
     * @param String $username - the user who can connect to the other end
     * @param String $password - the password for the provided user.
     */
    public function __construct($username, $password) 
    {
        $this->m_username = $username;
        $this->m_password = $password;
    }
    
    
    /**
     * This is the point of this class. Authenticates this object.
     * @param resource $connection - the resource from an ssh2 extension.
     * @return bool $result - true if successfully authenticated, false otherwise.
     */
    public function authenticate($connection)
    {
        $result = ssh2_auth_password($connection, $this->m_username, $this->m_password);
        return $result;
    }
}
