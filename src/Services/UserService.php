<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Services;

use Matheus\TestePleno\DB\PDOSingleton;
use Matheus\TestePleno\DB\QueryBuilder;
use Matheus\TestePleno\Models\UserModel;

class UserService
{
    public static function findById(int $id): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->findById(id: $id);
    }

    public static function finByParam(array $terms): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: $terms);
    }

    public static function getAll(): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->fetch();
    }

    public static function save(UserModel $user): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->create($user->toArray());
    }

    public static function update(UserModel $user): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->update($user->toArray());
    }

    public static function destroy(int $user_id): bool
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->delete("id = :id", ['id' => (string) $user_id]);
    }
}