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
            . $this->_renderClassField() . $this->_renderButtons()
            . '</form>';
    }

    /**
     * Renders the class field.
     *
     * @return string (X)HTML.
     *
     * @global array The configuration of the plugins.
     */
    private function _renderClassField()
    {
        global $plugin_cf;

        $result = '<label>Classes ';
        if ($plugin_cf['toxic']['classes_available'] == '') {
            $result .= $this->_renderClassInput();
        } else {
            $result .= $this->_renderClassSelect();
        }
        $result .= '</label>';
        return $result;
    }

    /**
     * Renders the class input element.
     *
     * @return string (X)HTML.
     */
    private function _renderClassInput()
    {
        return tag(
            'input type="text" name="toxic_classes" value="'
            . $this->_pageData['toxic_classes'] . '"'
        );
    }

    /**
     * Renders the class select element.
     *
     * @return string (X)HTML.
     */
    private function _renderClassSelect()
    {
        return '<select name="toxic_classes">' . $this->_renderOptions()
            . '</select>';
    }

    /**
     * Renders the class option elements.
     *
     * @return string (X)HTML.
     */
    private function _renderOptions()
    {
        $result = '';
        foreach ($this->_getAvailableClasses() as $class) {
            $result .= '<option';
            if ($class == $this->_pageData['toxic_classes']) {
                $result .= ' selected="selected"';
            }
            $result .= '>' . $class . '</option>';
        }
        return $result;
    }

    /**
     * Returns the available classes.
     *
     * @return array
     *
     * @global array The configuration of the plugins.
     */
    private function _getAvailableClasses()
    {
        global $plugin_cf;

        $classes = $plugin_cf['toxic']['classes_available'];
        $classes = explode(',', $classes);
        return array_map('trim', $classes);
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

/**
 * Li commands.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Toxic_LiCommand
{
    /**
     * The indexes of the pages.
     *
     * @var array
     */
    private $_ta;

    /**
     * The menu level to start with or the type of menu.
     *
     * @var mixed
     */
    private $_st;

    /**
     * Initializes a new instance.
     *
     * @param array $ta The indexes of the pages.
     * @param mixed $st The menu level to start with or the type of menu.
     *
     * @return void
     */
    public function __construct($ta, $st)
    {
        $this->_ta = $ta;
        $this->_st = $st;
    }

    /**
     * Renders the li view.
     *
     * @return string (X)HTML.
     *
     * @global int    The index of the current page.
     * @global array  The menu levels of the pages.
     * @global array  The headings of the pages.
     * @global int    The number of pages.
     * @global array  The configuration of the core.
     * @global array  The URLs of the pages.
     * @global array  Whether we are in edit mode.
     * @global object The page data router.
     */
    public function render()
    {
        global $s, $l, $h, $cl, $cf, $u, $edit, $pd_router;

        $tl = count($this->_ta);
        if ($tl < 1) {
            return;
        }
        $t = '';
        if ($this->_st == 'submenu' || $this->_st == 'search') {
            $t .= '<ul class="' . $this->_st . '">' . "\n";
        }
        $b = 0;
        if ($this->_st > 0) {
            $b = $this->_st - 1;
            $this->_st = 'menulevel';
        }
        $lf = array();
        for ($i = 0; $i < $tl; $i++) {
            $tf = ($s != $this->_ta[$i]);
            if ($this->_st == 'menulevel' || $this->_st == 'sitemaplevel') {
                for ($k = (isset($this->_ta[$i - 1]) ? $l[$this->_ta[$i - 1]] : $b);
                     $k < $l[$this->_ta[$i]];
                     $k++
                ) {
                    $t .= "\n" . '<ul class="' . $this->_st . ($k + 1) . '">'
                        . "\n";
                }
            }
            $t .= '<li class="';
            if (!$tf) {
                $t .= 's';
            } elseif ($cf['menu']['sdoc'] == "parent" && $s > -1) {
                if ($l[$this->_ta[$i]] < $l[$s]) {
                    $hasChildren = substr($u[$s], 0, 1 + strlen($u[$this->_ta[$i]]))
                        == $u[$this->_ta[$i]] . $cf['uri']['seperator'];
                    if ($hasChildren) {
                        $t .= 's';
                    }
                }
            }
            $t .= 'doc';
            for ($j = $this->_ta[$i] + 1; $j < $cl; $j++) {
                if (!hide($j)
                    && $l[$j] - $l[$this->_ta[$i]] < 2 + $cf['menu']['levelcatch']
                ) {
                    if ($l[$j] > $l[$this->_ta[$i]]) {
                        $t .= 's';
                    }
                    break;
                }
            }
            $pageData = $pd_router->find_page($this->_ta[$i]);
            if ($pageData['toxic_classes']) {
                $t .= ' ' . $pageData['toxic_classes'];
            }
            $t .= '">';
            if ($tf) {
                $pageData = $pd_router->find_page($this->_ta[$i]);
                $x = !(XH_ADM && $edit)
                    && $pageData['use_header_location'] === '2'
                        ? '" target="_blank' : '';
                $t .= a($this->_ta[$i], $x);
            } else {
                $t .='<span>';
            }
            $t .= $h[$this->_ta[$i]];
            if ($tf) {
                $t .= '</a>';
            } else {
                $t .='</span>';
            }
            if ($this->_st == 'menulevel' || $this->_st == 'sitemaplevel') {
                $cond = (isset($this->_ta[$i + 1]) ? $l[$this->_ta[$i + 1]] : $b)
                    > $l[$this->_ta[$i]];
                if ($cond) {
                    $lf[$l[$this->_ta[$i]]] = true;
                } else {
                    $t .= '</li>' . "\n";
                    $lf[$l[$this->_ta[$i]]] = false;
                }
                for ($k = $l[$this->_ta[$i]];
                    $k > (isset($this->_ta[$i + 1]) ? $l[$this->_ta[$i + 1]] : $b);
                    $k--
                ) {
                    $t .= '</ul>' . "\n";
                    if (isset($lf[$k - 1])) {
                        if ($lf[$k - 1]) {
                            $t .= '</li>' . "\n";
                            $lf[$k - 1] = false;
                        }
                    }
                }
            } else {
                $t .= '</li>' . "\n";
            }
        }
        if ($this->_st == 'submenu' || $this->_st == 'search') {
            $t .= '</ul>' . "\n";
        }
        return $t;
    }
}


?>
