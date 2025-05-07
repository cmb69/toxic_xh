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
    /** @var InfoCommand */
    private $subject;

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

    public function testRendersHeading(): void
    {
        $this->assertStringContainsString("<h1>Toxic &ndash; Info</h1>", $this->subject->render());
    }

    public function testRendersVersion(): void
    {
        $this->assertStringContainsString("<p>Version: 1.0</p>", $this->subject->render());
    }

    public function testRendersCopyright(): void
    {
        $this->assertStringContainsString("<p>Copyright", $this->subject->render());
    }

    public function testRendersLicense(): void
    {
        $this->assertStringContainsString(
            "<p class=\"toxic_license\">This program is free software:",
            $this->subject->render()
        );
    }
}
