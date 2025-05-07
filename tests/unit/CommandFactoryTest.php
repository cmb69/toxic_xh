<?php

namespace Toxic;

use PHPUnit\Framework\TestCase;

/**
 * Testing the command factory.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Toxic
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

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
