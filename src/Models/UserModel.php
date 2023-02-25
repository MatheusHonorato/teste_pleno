<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Models;

use Matheus\TestePleno\Util\Validator;

class UserModel
{
    public const TABLE = 'users';

    public function __construct(
        private int|null $id,
        private string $name,
        private string $email,
        private string $phone,
        private string $date,
        private string $city,
    ) {}

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'email' => $this->email,
          'phone' => $this->phone,
          'date' => $this->date,
          'city' => $this->city
        ];
    }

    public function __get($atrib): int|string
    {
        return $this->$atrib;
    }
}