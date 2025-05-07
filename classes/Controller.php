<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Toxic_XH.
 *
 * Toxic_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Toxic_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Toxic_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Toxic;

class Controller
{
    /** @var CommandFactory */
    private $commandFactory;

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

    private function registerFields(): void
    {
        global $pd_router;

        $pd_router->add_interest('toxic_category');
        $pd_router->add_interest('toxic_class');
    }

    private function addPageDataTab(): void
    {
        global $pth, $pd_router, $plugin_tx;

        $pd_router->add_tab(
            $plugin_tx['toxic']['label_tab'],
            $pth['folder']['plugins'] . 'toxic/toxic_view.php'
        );
    }

    private function isAdministrationRequested(): bool
    {
        global $toxic;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('toxic')
            || isset($toxic) && $toxic == 'true';
    }

    private function handleAdministration(): void
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $o .= $this->commandFactory->makeInfoCommand()->render();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }
}
