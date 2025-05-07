<?php

namespace Toxic;

use PHPUnit\Framework\TestCase;

class DicTest extends TestCase
{
    public function testMakeTabCommand(): void
    {
        $this->assertInstanceOf(TabCommand::class, Dic::makeTabCommand(array()));
    }

    public function testMakeInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, Dic::makeInfoCommand());
    }
}
