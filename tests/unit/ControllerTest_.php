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

require_once './vendor/autoload.php';
require_once '../../cmsimple/classes/PageDataRouter.php';
require_once '../../cmsimple/adminfuncs.php';
require_once './classes/Controller.php';

/**
 * Testing the controller.
 *
 * @category Testing
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class ControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Toxic_Controller
     */
    protected $subject;

    /**
     * The command factory mock.
     *
     * @var Toxic_CommandFactory
     */
    protected $commandFactory;

    /**
     * The info command mock.
     *
     * @var Toxic_InfoCommand
     */
    protected $infoCommand;

    /**
     * The pring_plugin_admin() mock.
     *
     * @var PHPUnit_Extensions_MockFunction
     */
    protected $printPluginAdmin;

    /**
     * The plugin_admin_common() mock.
     *
     * @var PHPUnit_Extensions_MockFunction
     */
    protected $pluginAdminCommon;

    protected $registerStandardPluginMenuItems;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array             The paths of system files and folders.
     * @global XH_PageDataRouter The page data router.
     * @global array             The localization of the plugins.
     */
    public function setUp()
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
        $this->printPluginAdmin = new PHPUnit_Extensions_MockFunction(
            'print_plugin_admin', $this->subject
        );
        $this->pluginAdminCommon = new PHPUnit_Extensions_MockFunction(
            'plugin_admin_common', $this->subject
        );
        $this->registerStandardPluginMenuItems
            = new PHPUnit_Extensions_MockFunction(
                'XH_registerStandardPluginMenuItems', $this->subject
            );
        $this->commandFactory = $this->getMock('Toxic_CommandFactory');
        $this->infoCommand = $this->getMock('Toxic_InfoCommand');
        $this->commandFactory->expects($this->any())
            ->method('makeInfoCommand')
            ->will($this->returnValue($this->infoCommand));
        $this->subject = new Toxic_Controller($this->commandFactory);
    }

    /**
     * Tests that the fields are registered.
     *
     * @return void
     *
     * @global XH_PageDataRouter The page data router.
     */
    public function testRegistersFields()
    {
        global $pd_router;

        $pd_router->expects($this->exactly(2))->method('add_interest')
            ->withConsecutive(['toxic_category'], ['toxic_class']);
        $this->subject->dispatch();
    }

    /**
     * Tests that the tab is registered.
     *
     * @return void
     *
     * @global XH_PageDataRouter The page data router.
     */
    public function testRegistersTab()
    {
        global $pd_router;

        $this->defineConstant('XH_ADM', true);
        $pd_router->expects($this->once())->method('add_tab')
            ->with('Toxic', './plugins/toxic/toxic_view.php');
        $this->subject->dispatch();
    }

    /**
     * Tests that the common administration is handled.
     *
     * @return void
     *
     * @global string Whether the toxic administation is requested.
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     */
    public function testHandlesCommonAdministration()
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

    /**
     * Tests that the info command is rendered.
     *
     * @return void
     *
     * @global string Whether the toxic administation is requested.
     * @global string The value of the <var>admin</var> GP parameter.
     */
    public function testRendersInfoCommand()
    {
        global $toxic, $admin;

        $toxic = 'true';
        $admin = '';
        $this->defineConstant('XH_ADM', true);
        $this->printPluginAdmin->expects($this->once())->with('off');
        $this->infoCommand->expects($this->once())->method('render');
        $this->subject->dispatch();
    }

    /**
     * Defines resp. redefines a constant.
     *
     * @param string $name  A name.
     * @param mixed  $value A value.
     *
     * @return void
     */
    protected function defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }

    }
}

?>
