<?php

declare(strict_types=1);

namespace tests\Unit\DB;

use Matheus\TestePleno\Controllers\CompanyController;
use PHPUnit\Framework\TestCase;

class CompanyControllerTest extends TestCase
{
  public function testGetMethodParamterIdNullReturnTypeArray(): void 
  {
    $company_controller = new CompanyController();

    $this->assertEquals(expected: "array", actual: gettype($company_controller->get())); 
  }

}

