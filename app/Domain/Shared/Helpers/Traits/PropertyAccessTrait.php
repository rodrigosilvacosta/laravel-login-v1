<?php

namespace App\Domain\Shared\Helpers\Traits;

trait PropertyAccessTrait
{
    public function __get($name)
    {
        return $this->{$name};
    }

    public function __isset($name)
    {
        return isset($this->{$name});
    }
}
