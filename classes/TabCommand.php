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

namespace Toxic;

/**
 * The tab commands.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class TabCommand
{
    /** @var array */
    protected $pageData;

    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    public function render(): string
    {
        global $sn, $su;

        return '<form id="toxic_tab" action="' . $sn . '?' . $su
            . '" method="post">'
            . $this->renderCategory()
            . $this->renderClassField() . $this->renderButtons()
            . '</form>';
    }

    protected function renderCategory(): string
    {
        global $plugin_tx;

        return '<p><label>' . $plugin_tx['toxic']['label_category'] . ' '
            . tag(
                'input type="text" name="toxic_category" value="'
                . XH_hsc($this->pageData['toxic_category']) . '"'
            )
            . '</label></p>';
    }

    protected function renderClassField(): string
    {
        global $plugin_tx, $plugin_cf;

        $result = '<p><label>' . $plugin_tx['toxic']['label_class'] . ' ';
        if ($plugin_cf['toxic']['classes_available'] == '') {
            $result .= $this->renderClassInput();
        } else {
            $result .= $this->renderClassSelect();
        }
        $result .= '</label></p>';
        return $result;
    }

    protected function renderClassInput(): string
    {
        return tag(
            'input type="text" name="toxic_class" value="'
            . $this->pageData['toxic_class'] . '"'
        );
    }

    protected function renderClassSelect(): string
    {
        return '<select name="toxic_class">' . $this->renderOptions()
            . '</select>';
    }

    protected function renderOptions(): string
    {
        global $plugin_tx;

        $result = '';
        foreach ($this->getAvailableClasses() as $class) {
            $result .= '<option';
            if ($class == '') {
                 $result .= ' label="' . $plugin_tx['toxic']['label_none'] . '"';
            }
            if ($class == $this->pageData['toxic_class']) {
                $result .= ' selected="selected"';
            }
            $result .= '>' . $class . '</option>';
        }
        return $result;
    }

    protected function getAvailableClasses(): array
    {
        global $plugin_cf;

        $classes = $plugin_cf['toxic']['classes_available'];
        $classes = explode(',', $classes);
        array_unshift($classes, '');
        return array_map('trim', $classes);
    }

    protected function renderButtons(): string
    {
        return '<p class="toxic_tab_buttons">'
            . $this->renderSubmitButton() . '</p>';
    }

    protected function renderSubmitButton(): string
    {
        global $plugin_tx;

        return '<button name="save_page_data">'
            . $plugin_tx['toxic']['label_save'] . '</button>';
    }
}

?>
