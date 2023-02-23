<?php

declare(strict_types=1);

namespace tests\Unit\Util;

use PHPUnit\Framework\TestCase;

use Matheus\TestePleno\Util\ClearString;

class ClearStringTest extends TestCase {

  public function testClearBarStringCheck() {
    $expected = "";
    $atual = "/\\";
    $this->assertEquals($expected, ClearString::execute($atual)); 
  }
}

