<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Controllers;

use Matheus\TestePleno\Models\CompanyModel;
use Matheus\TestePleno\Services\CompanyService;

class CompanyController
{
    public function get(?string $id = null): array
    {

        if($id)
            return CompanyService::findById(id: (int) $id);

        $terms = (array) $_GET;

        if(count($terms) > 0)
            return CompanyService::finByParam(terms: $terms);

        return CompanyService::getAll();
    }

    public function post()
    {
        $company = (array) json_decode(file_get_contents('php://input', true));

        $company['id'] = null;
        $user_ids = $company['user_ids'];
        unset($company['user_ids']);

        return CompanyService::save(company: (new CompanyModel(...$company)), user_ids: $user_ids);
    }

    public function put(): array
    {
        $data = (array) json_decode(file_get_contents('php://input', true));
        
        return CompanyService::update(company: (new CompanyModel(...$data)));
    }

    public function delete(string $id = null): bool
    {    
        return CompanyService::destroy(company_id: (int) $id);
    }
}