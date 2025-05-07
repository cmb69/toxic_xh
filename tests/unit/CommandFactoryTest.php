<?php

namespace Toxic;

use PHPUnit\Framework\TestCase;

class CommandFactoryTest extends TestCase
{
    /** @var CommandFactory  */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new CommandFactory();
    }

    public function testMakeTabCommand(): void
    {
        $this->assertInstanceOf(TabCommand::class, $this->subject->makeTabCommand(array()));
    }

    public function testMakeInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, $this->subject->makeInfoCommand());
    }
}
