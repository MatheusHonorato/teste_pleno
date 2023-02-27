<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Controllers;

use Matheus\TestePleno\Models\UserModel;
use Matheus\TestePleno\Services\UserService;
use Matheus\TestePleno\Util\Validator;

class UserController
{
    public function get(?string $id = null): array
    {
        if(!is_numeric($id) && $id != null)
            return [];

        if($id)
            return UserService::findById(id: (int) $id);

        $terms = (array) $_GET;

        if(count($terms) > 0)
            return UserService::finByParam(terms: $terms);

        return UserService::getAll();
    }

    public function post(): array
    {
        $request_user = (array) json_decode(file_get_contents('php://input', true));

        $user = null;

        try {
            $user =  new UserModel(...$request_user);
        } catch (\Throwable) {
            return ["error input types"];
        }

        $validator = new Validator();
        $validator->validateRequired($user->name, 'name');

        $validator->validateRequired($user->email, 'e-mail');
        $validator->validateEmail($user->email, 'e-mail');
        $validator->validateUniqueFind('user', $user->email, 'email');

        $validator->validateDate($user->date, 'date');

        $validator->validateString($user->city, 'city');

        $validator->validateRequired($user->company_ids, 'company_ids');
        foreach ($user->company_ids as $value)
            $validator->validateUniqueFindNot('company', $value, 'id');

        $errors = $validator->getErrors();
        
        if(count($errors) > 0)
            return $errors;

        return UserService::save(user: $user, company_ids: $user->company_ids);
    }

    public function put(?string $id = null): array
    {
        $request_user = (array) json_decode(file_get_contents('php://input', true));
        $request_user['id'] = (int) $id;

        try {
            $user =  new UserModel(...$request_user);
        } catch (\Throwable) {
            return ["error input types"];
        }

        $validator = new Validator();
        $validator->validateRequired($user->name, 'name');

        $validator->validateRequired($user->email, 'e-mail');
        $validator->validateEmail($user->email, 'e-mail');

        $validator->validateDate($user->date, 'date');

        $validator->validateString($user->city, 'city');

        $validator->validateRequired($user->company_ids, 'company_ids');
        foreach ($user->company_ids as $value)
            $validator->validateUniqueFindNot('company', $value, 'id');

        $errors = $validator->getErrors();
        
        if(count($errors) > 0)
            return $errors;
        
        return UserService::update(user: $user, company_ids: $user->company_ids);
    }

    public function delete(string $id = null): bool
    {    
        return UserService::destroy(user_id: (int) $id);
    }
}