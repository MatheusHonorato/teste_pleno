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
        $user = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->findById(id: $id);

        if(count($user) == 0)
            return [];

        $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $user['id']]);

        $user['companies'] = [];

        if(isset($user_company['id']) && isset($user_company['company_id']))
            $user['companies'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['id' => $user_company['company_id']]);
        else
            foreach ($user_company as $value)
                if(isset($value['company_id']))
                    $user['companies'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['id' => $value['company_id']]);
           
        return $user;
    }

    public static function finByParam(array $terms): array
    {
        $user_company = null;

        if(isset($terms['company'])) {
            $company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['name' => $terms['company']]);

            if(count($company) == 0)
                return [];

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
            
            $users = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $user_id]);
            
            if(isset($users['id']))
                $users = [0 => $users];

            foreach ($users as $key => $user) {
                $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $user['id']]);
    
                $users[$key]['companies'] = [];

                if(isset($user_company['id']))
                    $user_company = [0 => $user_company];
    
                foreach ($user_company as $value)
                    if(isset($value['company_id']))
                        $users[$key]['companies'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['id' => $value['company_id']]);
            }
    
            return $users;
        }

        $users = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: $terms);

        if(isset($users['id']))
            $users = [0 => $users];

        foreach ($users as $key => $user) {
            if(isset($user['id']))  {
                $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $user['id']]);

                $users[$key]['companies'] = [];
    
                if(isset($user_company['id']))
                    $user_company = [0 => $user_company];

                foreach ($user_company as $value)
                    if(isset($value['company_id']))
                        $users[$key]['companies'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['id' => $value['company_id']]);
                    
            }
           
        }

        return $users;
    }

    public static function getAll(): array
    {
        $users = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->fetch();

        foreach ($users as $key => $user) {
            $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $user['id']]);

            $users[$key]['companies'] = [];

            if(isset($user_company['id']))
                $user_company = [0 => $user_company];

            foreach ($user_company as $value)
                if(isset($value['company_id']))
                    $users[$key]['companies'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['id' => $value['company_id']]);
        }

        return $users;
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