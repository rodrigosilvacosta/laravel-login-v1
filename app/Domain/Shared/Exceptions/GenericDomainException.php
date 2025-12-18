<?php

namespace App\Domain\Shared\Exceptions;

use DomainException;

abstract class GenericDomainException extends DomainException
{
    public function __construct(private AppDomainExceptionCodeEnum $exceptionCodeEnum)
    {
        parent::__construct($this->exceptionCodeEnum->getMessage(), $this->exceptionCodeEnum->value);
    }
}
