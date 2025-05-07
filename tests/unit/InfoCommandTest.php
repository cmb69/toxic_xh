<?php

namespace Toxic;

use PHPUnit\Framework\TestCase;

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

/**
 * Testing the info command.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class InfoCommandTest extends TestCase
{
    /**
     * The subject under test.
     *
     * @var InfoCommand
     */
    protected $subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The localization of the plugins.
     */
    public function setUp(): void
    {
        global $pth, $plugin_tx;

        if (!defined('TOXIC_VERSION')) {
            define('TOXIC_VERSION', '1.0');
        } else {
            uopz_redefine('TOXIC_VERSION', '1.0');
        }
        $pth = [
            "folder" => ["plugins" => ""],
        ];
        $plugin_tx = array(
            'toxic' => array(
                'caption_info' => 'Info'
            )
        );
        $plugin_tx = XH_includeVar("./languages/en.php", "plugin_tx");
        $this->subject = new InfoCommand();
    }

    /**
     * Tests that a heading is rendered.
     *
     * @return void
     */
    public function testRendersHeading()
    {
        $this->assertStringContainsString("<h1>Toxic &ndash; Info</h1>", $this->subject->render());
    }

    /**
     * Tests that the version information is rendered.
     *
     * @return void
     */
    public function testRendersVersion()
    {
        $this->assertStringContainsString("<p>Version: 1.0</p>", $this->subject->render());
    }

    /**
     * Tests that the copyright information is rendered.
     *
     * @return void
     */
    public function testRendersCopyright()
    {
        $this->assertStringContainsString("<p>Copyright", $this->subject->render());
    }

    /**
     * Tests that the license is rendered.
     *
     * @return void
     */
    public function testRendersLicense()
    {
        $this->assertStringContainsString(
            "<p class=\"toxic_license\">This program is free software:",
            $this->subject->render()
        );
    }

    /**
     * Asserts that $matcher is rendered.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     */
    protected function assertRenders($matcher)
    {
        @$this->assertTag($matcher, $this->subject->render());
    }
}

?>
