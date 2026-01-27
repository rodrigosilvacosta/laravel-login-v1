<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;
use App\Application\Mappers\MapperInterface;

class MappedDataOutputDto extends OutputDto
{
    private function __construct(protected readonly array $data) {}

    public static function createMappedData(MapperInterface $mapper): self
    {
        return new self($mapper->getMappedData());
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
