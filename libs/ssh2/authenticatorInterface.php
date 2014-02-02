<?php

interface AuthenticatorInterface
{
    public function authenticate($connection);
}