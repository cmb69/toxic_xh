<?php

/**
 * The presentation layer.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Toxic
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

/**
 * Controllers.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Toxic_Controller
{
    /**
     * The command factory.
     *
     * @var Toxic_CommandFactory
     */
    private $_commandFactory;

    /**
     * Initializes a new instance.
     *
     * @param Toxic_CommandFactory $commandFactory A command factory.
     *
     * @return void
     */
    public function __construct(Toxic_CommandFactory $commandFactory)
    {
        $this->_commandFactory = $commandFactory;
    }

    /**
     * Dispatch according to the request.
     *
     * @return void
     *
     * @global array             The paths of system files and folders.
     * @global string            Whether the toxic administration is requested.
     * @global XH_PageDataRouter The page data router.
     * @global array             The localization of the plugins.
     */
    public function dispatch()
    {
        global $pth, $toxic, $pd_router, $plugin_tx;

        $pd_router->add_interest('toxic_classes');
        if (XH_ADM) {
            $pd_router->add_tab(
                $plugin_tx['toxic']['label_tab'],
                $pth['folder']['plugins'] . 'toxic/toxic_view.php'
            );
            if (isset($toxic) && $toxic == 'true') {
                $this->_handleAdministration();
            }
        }
    }

    /**
     * Handles the administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string The HTML for the contents area.
     */
    private function _handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->_commandFactory->makeInfoCommand()->render();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'toxic');
        }
    }
}

/**
 * Command factories.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Toxic_CommandFactory
{
    /**
     * Makes a tab command.
     *
     * @param array $pageData A page data array.
     *
     * @return Toxic_TabCommand
     */
    public function makeTabCommand($pageData)
    {
        return new Toxic_TabCommand($pageData);
    }

    /**
     * Makes an info command.
     *
     * @return Toxic_InfoCommand
     */
    public function makeInfoCommand()
    {
        return new Toxic_InfoCommand();
    }
}

/**
 * Tab commands.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Toxic_TabCommand
{
    /**
     * The page data of the current page.
     *
     * @var array
     */
    private $_pageData;

    /**
     * Initializes a new instance.
     *
     * @param array $pageData An array of page data.
     *
     * @return void
     */
    public function __construct($pageData)
    {
        $this->_pageData = $pageData;
    }

    /**
     * Renders the command.
     *
     * @return string (X)HTML.
     *
     * @global string The script name.
     * @global string The selected URL.
     */
    public function render()
    {
        global $sn, $su;

        return '<form id="toxic_tab" action="' . $sn . '?' . $su
            . '" method="post">'
            . $this->_renderClassesField() . $this->_renderButtons()
            . '</form>';
    }

    /**
     * Renders the classes field.
     *
     * @return string (X)HTML.
     */
    private function _renderClassesField()
    {
        return '<label>Classes '
            . tag(
                'input type="text" name="toxic_classes" value="'
                . $this->_pageData['toxic_classes'] . '"'
            )
            . '</label>';
    }

    /**
     * Renders the buttons.
     *
     * @return string ()XHTML.
     */
    private function _renderButtons()
    {
        return '<div class="toxic_tab_buttons">'
            . $this->_renderSubmitButton() . '</div>';
    }

    /**
     * Renders the submit button.
     *
     * @return void
     *
     * @global array  The localization of the plugins.
     */
    private function _renderSubmitButton()
    {
        global $plugin_tx;

        return '<button name="save_page_data">'
            . $plugin_tx['toxic']['label_save'] . '</button>';
    }
}

/**
 * Info commands.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Toxic_InfoCommand
{
    /**
     * Renders the info view.
     *
     * @return string (X)HTML.
     */
    public function render()
    {
        return $this->_renderHeading() . $this->_renderVersion()
            . $this->_renderCopyright() . $this->_renderLicense();
    }

    /**
     * Renders the heading.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    private function _renderHeading()
    {
        global $plugin_tx;

        return '<h1>Toxic &ndash; ' . $plugin_tx['toxic']['caption_info']
            . '</h1>';
    }

    /**
     * Renders the version information.
     *
     * @return string (X)HTML.
     */
    private function _renderVersion()
    {
        return '<p>Version: ' . TOXIC_VERSION . '</p>';
    }

    /**
     * Renders the copyright information.
     *
     * @return string (X)HTML.
     */
    private function _renderCopyright()
    {
        return '<p>Copyright &copy; 2014'
            . ' <a href="http://3-magi.net/">Christoph M. Becker</a>';
    }

    /**
     * Renders the license information.
     *
     * @return string (X)HTML.
     */
    private function _renderLicense()
    {
        return <<<EOT
<p class="toxic_license">This program is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.</p>
<p class="toxic_license">This program is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.</p>
<p class="toxic_license">You should have received a copy of the GNU
General Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
EOT;
    }
}

?>
