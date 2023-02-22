<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Services;

use Matheus\TestePleno\DB\PDOSingleton;
use Matheus\TestePleno\DB\QueryBuilder;
use Matheus\TestePleno\Models\CompanyModel;
use Matheus\TestePleno\Models\UserCompanyModel;

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

    public static function save(CompanyModel $company, array $user_ids): array
    {
        PDOSingleton::getConnection()->beginTransaction();

        $new_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->create($company->toArray());

        foreach ($user_ids as $user_id)
            (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->create(['user_id' => $user_id, 'company_id' => $new_company['id']]);

        PDOSingleton::getConnection()->commit();

        return [];
    }

    public static function update(CompanyModel $company, array $user_ids): array
    {
        PDOSingleton::getConnection()->beginTransaction();

        (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->update($company->toArray());

        (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->delete("company_id = :company_id", ['company_id' => (string) $company->id]);

        foreach ($user_ids as $user_id)
            (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->create(['user_id' => $user_id, 'company_id' => $company->id]);

        PDOSingleton::getConnection()->commit();

        return [];
    }

    public static function destroy(int $company_id): bool
    {
        $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $company_id]);

        if(count($user_company) == 0)
            return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->delete("id = :id", ['id' => (string) $company_id]);
        
        return false;
    }
}