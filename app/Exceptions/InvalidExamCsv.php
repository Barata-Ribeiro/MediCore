<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidExamCsv extends RuntimeException
{
    public function __construct(public string $errorCode, public ?int $rowNumber = null)
    {
        parent::__construct($errorCode);
    }
}
