<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Services;

use Matheus\TestePleno\DB\PDOSingleton;
use Matheus\TestePleno\DB\QueryBuilder;
use Matheus\TestePleno\Models\CompanyModel;

class CompanyService
{
    public static function findById(int $id): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->findById(id: $id);
    }

    public static function finByParam(array $terms): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: $terms);
    }

    public static function getAll(): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->fetch();
    }

    public static function save(CompanyModel $company): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->create($company->toArray());
    }

    public static function update(CompanyModel $company): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->update($company->toArray());
    }

    public static function destroy(int $company_id): bool
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->delete("id = :id", ['id' => (string) $company_id]);
    }
}