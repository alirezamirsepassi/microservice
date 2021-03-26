<?php

namespace Requester\Exception;

use Exception;

class NoResponseException extends Exception
{
    public function __construct()
    {
        parent::__construct("no response");
    }
}
