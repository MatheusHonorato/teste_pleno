<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Models;

class UserModel
{
    public const TABLE = 'users';

    public function __construct(
        private ?int $id = null,
        private ?string $name = null,
        private ?string $email = null,
        private ?string $phone = null,
        private ?string $date = null,
        private ?string $city = null,
        private ?array $company_ids = [],
    ) {}

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'email' => $this->email,
          'phone' => $this->phone,
          'date' => $this->date,
          'city' => $this->city,
        ];
    }

    public function __get($atrib): mixed
    {
        return $this->$atrib;
    }
}