<?php

declare(strict_types=1);

namespace tests\Unit\DB;

use Matheus\TestePleno\DB\PDOSingleton;
use PDO;
use PHPUnit\Framework\TestCase;

class PDOSingletonTest extends TestCase
{
  public function testCheckConnectionPDOSingletonTypeObjectReturn(): void 
  {
    $this->assertEquals(expected: "object", actual: gettype(PDOSingleton::getConnection())); 
  }

  public function testCheckConnectionPDOSingletonTypePDOInstanceReturn(): void
  {
    $this->assertTrue(condition: PDOSingleton::getConnection() instanceof PDO); 
  }
}

