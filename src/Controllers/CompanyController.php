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
        $data = (array) json_decode(file_get_contents('php://input', true));
        $data['id'] = null;

        return CompanyService::save(user: (new CompanyModel(...$data)));
    }

    public function put(): array
    {
        $data = (array) json_decode(file_get_contents('php://input', true));
        
        return CompanyService::update(user: (new CompanyModel(...$data)));
    }

    public function delete(string $id = null): bool
    {    
        return CompanyService::destroy(user_id: (int) $id);
    }
}