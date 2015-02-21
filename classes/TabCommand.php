<?php

/**
 * The tab commands.
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

/**
 * The tab commands.
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
     * @global array The localization of the plugins.
     * @global array The configuration of the plugins.
     */
    private function _renderClassField()
    {
        global $plugin_tx, $plugin_cf;

        $result = '<label>' . $plugin_tx['toxic']['label_class'] . ' ';
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
            'input type="text" name="toxic_class" value="'
            . $this->_pageData['toxic_class'] . '"'
        );
    }

    /**
     * Renders the class select element.
     *
     * @return string (X)HTML.
     */
    private function _renderClassSelect()
    {
        return '<select name="toxic_class">' . $this->_renderOptions()
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
            if ($class == $this->_pageData['toxic_class']) {
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
        array_unshift($classes, '');
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

?>
