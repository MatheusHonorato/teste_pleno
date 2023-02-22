<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Controllers;

use Matheus\TestePleno\Models\UserModel;
use Matheus\TestePleno\Services\UserService;

class UserController
{
    public function get(?string $id = null): array
    {

        if($id)
            return UserService::findById(id: (int) $id);

        $terms = (array) $_GET;

        $offset = isset($terms['offset']) ? $terms['offset'] : 0;
        $limit = isset($terms['limit']) ? $terms['limit'] : 10;

        //if(count($terms) > 0)
        return UserService::finByParam(terms: $terms, offset: (int) $offset, limit: (int) $limit);

        //return UserService::getAll(offset: (int) $offset, limit: (int) $limit);
    }

    public function post()
    {
        $data = (array) json_decode(file_get_contents('php://input', true));
        $data['id'] = null;

        return UserService::save(user: (new UserModel(...$data)));
    }

    public function put(): array
    {
        $data = (array) json_decode(file_get_contents('php://input', true));
        
        return UserService::update(user: (new UserModel(...$data)));
    }

    public function delete(string $id = null): bool
    {    
        return UserService::destroy(user_id: (int) $id);
    }
}