<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Models;

class UserCompanyModel
{
    public const TABLE = 'users_companies';

    public function __construct(
        private int|null $id,
        private int $user_id,
        private int $company_id,
    ) {}

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'user_id' => $this->user_id,
          'company_id' => $this->company_id
        ];
    }

    public function __get($atrib): int|string
    {
        return $this->$atrib;
    }
}