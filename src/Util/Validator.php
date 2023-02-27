<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Util;

use Matheus\TestePleno\DB\PDOSingleton;
use Matheus\TestePleno\DB\QueryBuilder;
use Matheus\TestePleno\Models\CompanyModel;
use Matheus\TestePleno\Models\UserModel;

class Validator
{
    public function __construct(
        private array $errors = [],
    ) {}

    public function validateRequired($value, string $fieldName): void
    {
        if(empty($value))
            $this->addError("{$fieldName} is required");
    }

    public function validateEmail($email, $fieldName): void
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $this->addError("{$fieldName} is not a valid email address");
    }

    public function validateUniqueFind($type, $value, $fieldName): void
    {
        if($this->find(type: $type, value: $value, fieldName: $fieldName))
            $this->addError("{$fieldName} unique is required");
    }

    public function validateUniqueFindNot($type, $value, $fieldName): void
    {
        if(!$this->find(type: $type, value: $value, fieldName: $fieldName))
            $this->addError("{$fieldName} is not exists");
    }

    public function validateDate($date, $fieldName): void
    {
        if($date != null && strtotime($date) === false)
            $this->addError("{$fieldName} is not a valid date");
    }

    public function validateInt($value, $fieldName): void
    {
        if (!filter_var($value, FILTER_VALIDATE_INT) !== false)
            $this->addError("{$fieldName} is not ineger type");

    }

    public function validateString($value, $fieldName): void
    {
        if (!is_string($value))
            $this->addError("{$fieldName} is not string type");

    }

    private function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function find($type, $value, $fieldName): bool 
    {
        $count = 0;

        switch ($type) {
            case 'user':
                $result = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: UserModel::TABLE))->find(terms: [$fieldName => $value]);
                $count = count($result);
                break;
            case 'company':
                $result = (new QueryBuilder(connection: PDOSingleton::getConnection(), table: CompanyModel::TABLE))->find(terms: [$fieldName => $value]);
                $count = count($result);
                break;
            }

        if($count>0)
            return true;
        return false;
    }
}