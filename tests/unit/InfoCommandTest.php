<?php

/**
 * Testing the info command.
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
 * Testing the info command.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class InfoCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Toxic_InfoCommand
     */
    private $_subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The localization of the plugins.
     */
    public function setUp()
    {
        global $plugin_tx;

        if (!defined('TOXIC_VERSION')) {
            define('TOXIC_VERSION', '1.0');
        } else {
            runkit_constant_redefine('TOXIC_VERSION', '1.0');
        }
        $plugin_tx = array(
            'toxic' => array(
                'caption_info' => 'Info'
            )
        );
        $this->_subject = new Toxic_InfoCommand();
    }

    /**
     * Tests that a heading is rendered.
     *
     * @return void
     */
    public function testRendersHeading()
    {
        $this->_assertRenders(
            array(
                'tag' => 'h1',
                'content' => "Toxic \xE2\x80\x93 Info"
            )
        );
    }

    /**
     * Tests that the version information is rendered.
     *
     * @return void
     */
    public function testRendersVersion()
    {
        $this->_assertRenders(
            array(
                'tag' => 'p',
                'content' => 'Version: 1.0'
            )
        );
    }

    /**
     * Tests that the copyright information is rendered.
     *
     * @return void
     */
    public function testRendersCopyright()
    {
        $this->_assertRenders(
            array(
                'tag' => 'p',
                'content' => 'Copyright'
            )
        );
    }

    /**
     * Tests that the license is rendered.
     *
     * @return void
     */
    public function testRendersLicense()
    {
        $this->_assertRenders(
            array(
                'tag' => 'p',
                'attributes' => array('class' => 'toxic_license'),
                'content' => 'This program is free software:'
            )
        );
    }

    /**
     * Asserts that $matcher is rendered.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     */
    private function _assertRenders($matcher)
    {
        $this->assertTag($matcher, $this->_subject->render());
    }
}

?>
