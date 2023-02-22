<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Models;

class CompanyModel
{
    public const TABLE = 'companies';

    public function __construct(
        private int|null $id,
        private string $name,
        private string $cnpj,
        private string $address,
    ) {}

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'cnpj' => $this->cnpj,
          'address' => $this->address
        ];
    }

    public function __get($atrib): int|string
    {
        return $this->$atrib;
    }
}