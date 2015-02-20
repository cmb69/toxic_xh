<?php

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

require_once './classes/Presentation.php';

/**
 * Testing the command factory.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class CommandFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Toxic_CommandFactory
     */
    private $_subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_subject = new Toxic_CommandFactory();
    }

    /**
     * Tests makeTabCommand
     *
     * @return void
     */
    public function testMakeTabCommand()
    {
        $this->assertInstanceOf(
            'Toxic_TabCommand', $this->_subject->makeTabCommand(array())
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
            'Toxic_InfoCommand', $this->_subject->makeInfoCommand()
        );
    }
}

?>
