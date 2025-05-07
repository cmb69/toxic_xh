<?php

/**
 * Testing the controller.
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

namespace Toxic;

use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /** @var Controller */
    private $subject;

    /** @var CommandFactory */
    private $commandFactory;

    /** @var InfoCommand */
    private $infoCommand;

    /** @var PHPUnit_Extensions_MockFunction */
    private $printPluginAdmin;

    /** @var PHPUnit_Extensions_MockFunction */
    private $pluginAdminCommon;

    private $registerStandardPluginMenuItems;

    public function setUp(): void
    {
        global $pth, $pd_router, $plugin_tx;

        $this->defineConstant('XH_ADM', false);
        $pth = array(
            'folder' => array(
                'plugins' => './plugins/'
            )
        );
        $plugin_tx = array(
            'toxic' => array(
                'label_tab' => 'Toxic'
            )
        );
        $pd_router = $this->getMockBuilder('XH_PageDataRouter')
            ->disableOriginalConstructor()->getMock();
        $this->printPluginAdmin = new PHPUnit_Extensions_MockFunction('print_plugin_admin', $this->subject);
        $this->pluginAdminCommon = new PHPUnit_Extensions_MockFunction('plugin_admin_common', $this->subject);
        $this->registerStandardPluginMenuItems = new PHPUnit_Extensions_MockFunction(
            'XH_registerStandardPluginMenuItems',
            $this->subject
        );
        $this->commandFactory = $this->getMock(CommandFactory::class);
        $this->infoCommand = $this->getMock(InfoCommand::class);
        $this->commandFactory->expects($this->any())
            ->method('makeInfoCommand')
            ->will($this->returnValue($this->infoCommand));
        $this->subject = new Controller($this->commandFactory);
    }

    public function testRegistersFields(): void
    {
        global $pd_router;

        $pd_router->expects($this->exactly(2))->method('add_interest')
            ->withConsecutive(['toxic_category'], ['toxic_class']);
        $this->subject->dispatch();
    }

    public function testRegistersTab(): void
    {
        global $pd_router;

        $this->defineConstant('XH_ADM', true);
        $pd_router->expects($this->once())->method('add_tab')
            ->with('Toxic', './plugins/toxic/toxic_view.php');
        $this->subject->dispatch();
    }

    public function testHandlesCommonAdministration(): void
    {
        global $toxic, $admin, $action;

        $toxic = 'true';
        $admin = 'plugin_language';
        $action = 'plugin_edit';
        $this->defineConstant('XH_ADM', true);
        $this->printPluginAdmin->expects($this->once())->with('off');
        $this->pluginAdminCommon->expects($this->once())
            ->with($action, $admin, 'toxic');
        $this->subject->dispatch();
    }

    public function testRendersInfoCommand(): void
    {
        global $toxic, $admin;

        $toxic = 'true';
        $admin = '';
        $this->defineConstant('XH_ADM', true);
        $this->printPluginAdmin->expects($this->once())->with('off');
        $this->infoCommand->expects($this->once())->method('render');
        $this->subject->dispatch();
    }

    /** @param mixed $value */
    private function defineConstant(string $name, $value): void
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}
