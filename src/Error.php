<?php

namespace Dsjsdz\Signatory;

use Exception;

class Error extends Exception
{
    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
        parent::__construct();
    }

    public function __toString()
    {
        return $this->message;
    }
}