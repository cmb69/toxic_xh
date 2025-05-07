<?php

namespace Toxic;

use PHPUnit\Framework\TestCase;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $plugin_cf;
        $plugin_cf = ["toxic" => []];
    }

    public function testMakeTabCommand(): void
    {
        $this->assertInstanceOf(TabCommand::class, Dic::makeTabCommand(array()));
    }

    public function testMakeInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, Dic::makeInfoCommand());
    }
}
