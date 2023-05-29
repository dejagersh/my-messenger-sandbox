<?php

namespace App;

use Exception;

class RetryException extends Exception
{
    public function __construct(int $delay)
    {
        parent::__construct("Retry after {$delay} seconds");
    }
}
