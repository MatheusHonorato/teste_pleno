<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Services;

use Matheus\TestePleno\DB\PDOSingleton;
use Matheus\TestePleno\DB\QueryBuilder;
use Matheus\TestePleno\Models\CompanyModel;
use Matheus\TestePleno\Models\UserCompanyModel;
use Matheus\TestePleno\Models\UserModel;

class UserService
{
    public static function findById(int $id): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->findById(id: $id);
    }

    public static function finByParam(array $terms): array
    {
        if(isset($terms['company'])) {
            $company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['name' => $terms['company']]);

            $user_company = null;

            if(count(array_column($company, 'id')) > 0)
                foreach ($company as $value) {
                    $user_company_loop = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $value['id']]);

                    foreach ($user_company_loop as $value)
                        $user_company[] = $value;
                }
            else
                $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $company['id']]);

            if(count($user_company) == 0)
                return [];

            (count(array_column($user_company, 'user_id')) > 0) ? $user_id = array_column($user_company, 'user_id') : $user_id = $user_company['user_id'];

            return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $user_id]);
        }

        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: $terms);
    }

    public static function getAll(): array
    {
        return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->fetch();
    }

    public static function save(UserModel $user, array $company_ids): array
    {
        PDOSingleton::getConnection()->beginTransaction();

        $new_user = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->create($user->toArray());

        foreach ($company_ids as $company_id)
            (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->create(['user_id' => $new_user['id'], 'company_id' => $company_id]);

        PDOSingleton::getConnection()->commit();

        return [];
    }

    public static function update(UserModel $user, array $company_ids): array
    {
        PDOSingleton::getConnection()->beginTransaction();

        (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->update($user->toArray());

        (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->delete("user_id = :user_id", ['user_id' => (string) $user->id]);

        foreach ($company_ids as $company_id)
            (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->create(['user_id' => $user->id, 'company_id' => $company_id]);

        PDOSingleton::getConnection()->commit();

        return [];
    }

    public static function destroy(int $user_id): bool
    {
        $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $user_id]);

        if(count($user_company) == 0)
            return (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->delete("id = :id", ['id' => (string) $user_id]);
        
        return false;
    }
}