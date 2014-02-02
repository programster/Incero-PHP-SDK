<?php

/*
 * Class to wrap around the SSH2 functionality.
 */

# Implement guard to ensure we have the necessary packages.
if (!function_exists("ssh2_connect"))
{
    throw new Exception("SSH2 Support must be installed to use the SSH2 class.");
}



class Ssh2
{
    private $m_connection;
    private $m_fingerprint; # The fingerprint fetched from ssh connection.
    private $m_authenticator; #
    
    public function __construct($host, AuthenticatorInterface $authenticator, $port=22)
    {
        $this->m_host = $host;
        $this->m_port = $port;
        $this->m_authenticator = $authenticator;
        
        $this->connect();
        $this->authenticate();
    }
    

    /**
     * Connects to the remote server. Note that this does not perform any authentication.
     * @throws Exception if failed to connect
     */
    private function connect()
    {        
        $this->m_connection = @ssh2_connect($this->m_host, $this->m_port);

        if ($this->m_connection === FALSE)
        {
            $errMsg = "Failed to connect to " . $this->m_host . " on port " . $this->m_port;
            throw new Exception($errMsg);
        }
        
        $this->m_fingerprint = ssh2_fingerprint($this->m_connection);
    }
    
    
    /**
     * 
     * @param void
     * @return boolean
     * @throws SSH2FailedToAuthenticate
     */
    public final function authenticate()
    {        
        if (!$this->m_authenticator->authenticate($this->m_connection))
        {
            $errMsg = "Failed to authenticate on " . $this->m_host;
        }
    }
    

    /**
     * Executes a command on the remote server and waits until it is finished (blocks). This will
     * then return all the output from the server side. If you don't want to wait, then set the
     * optional blocking parameter to false.
     * @param mixed $cmd - a string command or an array list of string commands to execute.
     * @return $response - the response from the host. Note that this will always be an empty string
     *                     if blocking is overridden to false.
     */
    public function exec($cmd, $block=true)
    {
        $this->m_error = "";
        $response = "";
        
        $stream      = @ssh2_exec($this->m_connection, $cmd);
        $errorStream = @ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        
        if ($block)
        {
            stream_set_blocking($errorStream, true);
            stream_set_blocking($stream, true);

            # Grab Response 
            $response .= stream_get_contents($stream);
            $this->m_error .= stream_get_contents($errorStream);
            
            if ($this->m_error != '')
            {
                throw new Exception($this->m_error);
            }
        }
        else
        {
            stream_set_blocking($errorStream, false);
            stream_set_blocking($stream, false);
        }

        return $response;
    }


    /**
     * Send a file or directory to the rmote server.
     * @param $localFile - the local file or directory you wish to send
     * @param String $remoteFile - where to stick the file on the remote server
     * @param int $createMode - optionally set the creation mode but you MUST have 4 digits.
     */
    public function send($localFile, $remoteFile, $createMode=0644)
    {
        if (is_dir($localFile))
        {
            $sftp = ssh2_sftp($this->m_connection);
            ssh2_sftp_mkdir($sftp, $remoteFile);
            $innerFiles = array_diff(scandir($localFile), array('.', '..'));

            foreach ($innerFiles as $innerFile)
            {
                $this->send($localFile . '/' . $innerFile, $remoteFile . '/' . $innerFile);
            }
        }
        else
        {
            ssh2_scp_send($this->m_connection, $localFile, $remoteFile, $createMode);
        }
    }

    
    /**
     * Disconnects from the connection
     */
    public function disconnect()
    {
        @ssh2_exec($this->m_connection, 'exit');
    }
}

