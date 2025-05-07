<?php

namespace Toxic;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

/**
 * Testing the tab command.
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
 * Testing the tab command.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class TabCommandTest extends TestCase
{
    /**
     * The subject under test.
     *
     * @var TabCommand
     */
    protected $subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global string The script name.
     * @global string The selected URL.
     * @global array  The localization of the plugins.
     */
    public function setUp(): void
    {
        global $sn, $su, $plugin_cf, $plugin_tx;

        $sn = '/xh/';
        $su = 'Welcome';
        // $plugin_tx = array(
        //     'toxic' => array(
        //         'label_class' => 'Class',
        //         'label_save' => 'Save'
        //     )
        // );
        $plugin_cf = XH_includeVar("./config/config.php", "plugin_cf");
        $plugin_tx = XH_includeVar("./languages/en.php", "plugin_tx");
        $pageData = array('toxic_class' => 'test', "toxic_category" => "");
        $this->subject = new TabCommand($pageData);
    }

    /**
     * Tests that a form element is rendered.
     *
     * @return void
     */
    public function testRendersForm()
    {
        Approvals::verifyHtml($this->subject->render());
    }

    /**
     * Tests that the class input is rendered.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testRendersClassInput()
    {
        global $plugin_cf;

        $plugin_cf['toxic']['classes_available'] = '';
        Approvals::verifyHtml($this->subject->render());
    }

    /**
     * Tests that the class select element is rendered.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testRendersClassSelect()
    {
        global $plugin_cf;

        $plugin_cf['toxic']['classes_available'] = 'one,two,three,test';
        Approvals::verifyHtml($this->subject->render());
    }

    /**
     * Tests that a submit button is rendered.
     *
     * @return void
     */
    public function testRendersSubmitButton()
    {
        Approvals::verifyHtml($this->subject->render());
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
