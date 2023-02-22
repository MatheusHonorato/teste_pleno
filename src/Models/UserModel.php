<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Models;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
    
    public function getDate(): string
    {
        return $this->date;
    }

    public function getCityId(): string
    {
        return $this->city;
    }
}