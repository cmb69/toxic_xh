<?php

/**
 * The controllers.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Toxic
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

namespace Toxic;

 /**
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Controller
{
    /** @var CommandFactory */
    protected $commandFactory;

    public function __construct(CommandFactory $commandFactory)
    {
        $this->commandFactory = $commandFactory;
    }

    public function dispatch(): void
    {
        $this->registerFields();
        if (XH_ADM) { // @phpstan-ignore-line
            $this->addPageDataTab();
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
            if ($this->isAdministrationRequested()) {
                $this->handleAdministration();
            }
        }
    }

    protected function registerFields(): void
    {
        global $pd_router;

        $pd_router->add_interest('toxic_category');
        $pd_router->add_interest('toxic_class');
    }

    protected function addPageDataTab(): void
    {
        global $pth, $pd_router, $plugin_tx;

        $pd_router->add_tab(
            $plugin_tx['toxic']['label_tab'],
            $pth['folder']['plugins'] . 'toxic/toxic_view.php'
        );
    }

    protected function isAdministrationRequested(): bool
    {
        global $toxic;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('toxic')
            || isset($toxic) && $toxic == 'true';
    }

    protected function handleAdministration(): void
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $o .= $this->commandFactory->makeInfoCommand()->render();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'toxic'); // @phpstan-ignore-line
        }
    }
}
