<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Util;

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

    private function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}