<?php

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

/**
 * Testing the command factory.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class CommandFactoryTest extends TestCase
{
    /**
     * The subject under test.
     *
     * @var Toxic_CommandFactory
     */
    protected $subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->subject = new Toxic_CommandFactory();
    }

    /**
     * Tests makeTabCommand
     *
     * @return void
     */
    public function testMakeTabCommand()
    {
        $this->assertInstanceOf(
            'Toxic_TabCommand', $this->subject->makeTabCommand(array())
        );
    }

    /**
     * Tests makeInfoCommand
     *
     * @return void
     */
    public function testMakeInfoCommand()
    {
        $this->assertInstanceOf(
            'Toxic_InfoCommand', $this->subject->makeInfoCommand()
        );
    }
}

?>
