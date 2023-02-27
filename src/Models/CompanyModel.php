<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Models;

class CompanyModel
{
    public const TABLE = 'companies';

    public function __construct(
        private ?int $id = null,
        private ?string $name = null,
        private ?string $cnpj = null,
        private ?string $address = null,
        private ?array $user_ids = [],
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

    public function __get($atrib): mixed
    {
        return $this->$atrib;
    }
}