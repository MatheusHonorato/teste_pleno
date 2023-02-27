<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Services;

use Matheus\TestePleno\DB\PDOSingleton;
use Matheus\TestePleno\DB\QueryBuilder;
use Matheus\TestePleno\Models\CompanyModel;
use Matheus\TestePleno\Models\UserCompanyModel;
use Matheus\TestePleno\Models\UserModel;

class CompanyService
{
    public static function findById(int $id): array
    {
        $company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->findById(id: $id);

        if(count($company) == 0)
            return [];

        $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $company['id']]);

        $company['users'] = [];

        if(isset($user_company['id']) && isset($user_company['user_id'])) {
            $company['users'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $user_company['user_id']]);
            return $company;
        }
        
        foreach ($user_company as $value) {
            if(isset($value['user_id']))
                $company['users'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $value['user_id']]);
        }
        
        return $company;

    }

    public static function finByParam(array $terms): array
    {
        $user_company = null;

        if(isset($terms['user'])) {
            $user = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['name' => $terms['user']]);

            if(count($user) == 0)
                return [];

            if(count(array_column($user, 'id')) > 0)
                foreach ($user as $value) {
                    $user_company_loop = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $value['id']]);

                    foreach ($user_company_loop as $value)
                        $user_company[] = $value;
                }
            else
                $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['user_id' => $user['id']]);

            if(count($user_company) == 0)
                return [];

            (count(array_column($user_company, 'company_id')) > 0) ? $company_id = array_column($user_company, 'company_id') : $company_id = $user_company['company_id'];
            
            $companies = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: ['id' => $company_id]);
            
            if(isset($companies['id']))
                $companies = [0 => $companies];

            foreach ($companies as $key => $company) {
                $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $company['id']]);
    
                $companies[$key]['users'] = [];
    
                if(isset($user_company['id']))
                    $user_company = [0 => $user_company];

                foreach ($user_company as $value)
                    if(isset($value['user_id']))
                        $companies[$key]['users'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $value['user_id']]);
            }
    
            return $companies;
        }

        $companies = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: $terms);

        if(isset($companies['id']))
            $companies = [0 => $companies];

        foreach ($companies as $key => $company) {
            if(isset($company['id']))  {
                $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $company['id']]);

                $companies[$key]['users'] = [];

                if(isset($user_company['id']))
                    $user_company = [0 => $user_company];
    
                foreach ($user_company as $value)
                    if(isset($value['user_id']))
                        $companies[$key]['users'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $value['user_id']]);
            }
           
        }

        return $companies;
    }

    public static function getAll(): array
    {
        $companies = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->fetch();
        
        foreach ($companies as $key => $company) {
            $user_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->find(terms: ['company_id' => $company['id']]);

            $companies[$key]['users'] = [];

            if(isset($user_company['id']))
                $user_company = [0 => $user_company];

            foreach ($user_company as $value)
                if(isset($value['user_id']))
                    $companies[$key]['users'][] = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: ['id' => $value['user_id']]);
        }

        return $companies;
    
    }

    public static function save(CompanyModel $company, array $user_ids): array
    {
        PDOSingleton::getConnection()->beginTransaction();

        $new_company = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->create($company->toArray());

        foreach ($user_ids as $user_id)
            (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->create(['user_id' => $user_id, 'company_id' => $new_company['id']]);

        PDOSingleton::getConnection()->commit();

        return self::findById($new_company['id']);
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
        PDOSingleton::getConnection()->beginTransaction();

        (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserCompanyModel::TABLE))->delete("company_id = :company_id", ['company_id' => $company_id]);

        (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->delete("id = :id", ['id' => (string) $company_id]);
        
        PDOSingleton::getConnection()->commit();

        return true;
    }
}