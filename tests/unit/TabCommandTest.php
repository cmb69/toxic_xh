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
    /** @var TabCommand */
    private $subject;

    public function setUp(): void
    {
        global $sn, $su, $plugin_cf, $plugin_tx;

        $sn = '/xh/';
        $su = 'Welcome';
        $plugin_cf = XH_includeVar("./config/config.php", "plugin_cf");
        $plugin_tx = XH_includeVar("./languages/en.php", "plugin_tx");
        $pageData = array('toxic_class' => 'test', "toxic_category" => "");
        $this->subject = new TabCommand($pageData);
    }

    public function testRendersForm(): void
    {
        Approvals::verifyHtml($this->subject->render());
    }

    public function testRendersClassInput(): void
    {
        global $plugin_cf;

        $plugin_cf['toxic']['classes_available'] = '';
        Approvals::verifyHtml($this->subject->render());
    }

    public function testRendersClassSelect(): void
    {
        global $plugin_cf;

        $plugin_cf['toxic']['classes_available'] = 'one,two,three,test';
        Approvals::verifyHtml($this->subject->render());
    }

    public function testRendersSubmitButton(): void
    {
        Approvals::verifyHtml($this->subject->render());
    }
}
