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

class TabCommand
{
    /** @var array<string,string> */
    private $pageData;

    /** @param array<string,string> $pageData */
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

    private function renderCategory(): string
    {
        global $plugin_tx;

        return '<p><label>' . $plugin_tx['toxic']['label_category'] . ' '
            . '<input type="text" name="toxic_category" value="'
                . XH_hsc($this->pageData['toxic_category']) . '">'
            . '</label></p>';
    }

    private function renderClassField(): string
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

    private function renderClassInput(): string
    {
        return '<input type="text" name="toxic_class" value="'
            . $this->pageData['toxic_class'] . '">';
    }

    private function renderClassSelect(): string
    {
        return '<select name="toxic_class">' . $this->renderOptions()
            . '</select>';
    }

    private function renderOptions(): string
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

    /** @return list<string> */
    private function getAvailableClasses(): array
    {
        global $plugin_cf;

        $classes = $plugin_cf['toxic']['classes_available'];
        $classes = explode(',', $classes);
        array_unshift($classes, '');
        return array_map('trim', $classes);
    }

    private function renderButtons(): string
    {
        return '<p class="toxic_tab_buttons">'
            . $this->renderSubmitButton() . '</p>';
    }

    private function renderSubmitButton(): string
    {
        global $plugin_tx;

        return '<button name="save_page_data">'
            . $plugin_tx['toxic']['label_save'] . '</button>';
    }
}
