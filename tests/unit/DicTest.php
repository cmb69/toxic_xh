<?php

namespace Toxic;

use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;
use XH\Publisher;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $c, $xh_publisher, $pd_router, $plugin_cf;
        $c = [];
        $xh_publisher = $this->createStub(Publisher::class);
        $pd_router = $this->createStub(PageDataRouter::class);
        $plugin_cf = ["toxic" => []];
    }

    public function testMakesLiCommand(): void
    {
        $this->assertInstanceOf(LiCommand::class, Dic::liCommand([], 1));
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
