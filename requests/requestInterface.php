<?php

/* 
 * All requests must have a send function. This has to be the world's smallest interface.
 */

interface RequestInterface
{    
    public function send();
}