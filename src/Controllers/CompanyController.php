<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Controllers;

use Matheus\TestePleno\Models\CompanyModel;
use Matheus\TestePleno\Services\CompanyService;
use Matheus\TestePleno\Util\Validator;

class CompanyController
{
    public function get(?string $id = null): array
    {
        if(!is_numeric($id) && $id != null)
            return [];

        if($id)
            return CompanyService::findById(id: (int) $id);

        $terms = (array) $_GET;

        if(count($terms) > 0)
            return CompanyService::finByParam(terms: $terms);

        return CompanyService::getAll();
    }

    public function post()
    {
        $request_company = (array) json_decode(file_get_contents('php://input', true));

        $company = null;

        try {
            $company =  new CompanyModel(...$request_company);
        } catch (\Throwable) {
            return ["error input types"];
        }

        $validator = new Validator();
        $validator->validateRequired($company->name, 'name');
        $validator->validateRequired($company->cnpj, 'cnpj');
        $validator->validateUniqueFind('company', $company->cnpj, 'cnpj');
        $validator->validateRequired($company->address, 'address');

        $validator->validateRequired($company->user_ids, 'user_ids');
        foreach ($company->user_ids as $value)
            $validator->validateUniqueFindNot('user', $value, 'id');

        $errors = $validator->getErrors();
        
        if(count($errors) > 0)
            return $errors;

        return CompanyService::save(company: $company, user_ids: $company->user_ids);
    }

    public function put(?string $id = null): array
    {
        $request_company = (array) json_decode(file_get_contents('php://input', true));
        $request_company['id'] = (int) $id;

        $company = null;

        try {
            $company =  new CompanyModel(...$request_company);
        } catch (\Throwable) {
            return ["error input types"];
        }
        
        $validator = new Validator();
        $validator->validateRequired($company->name, 'name');

        $validator->validateRequired($company->cnpj, 'cnpj');

        $validator->validateRequired($company->address, 'address');

        $validator->validateRequired($company->user_ids, 'user_ids');
        foreach ($company->user_ids as $value)
            $validator->validateUniqueFindNot('user', $value, 'id');

        $errors = $validator->getErrors();
        
        if(count($errors) > 0)
            return $errors;

        return CompanyService::update(company: $company, user_ids: $company->user_ids);
    }

    public function delete(string $id = null): bool
    {    
        return CompanyService::destroy(company_id: (int) $id);
    }
}