<?php

declare(strict_types=1);

namespace Requester\Exception;

use Exception;

class NoResponseException extends Exception
{
    public function __construct()
    {
        parent::__construct('no response');
    }
}
