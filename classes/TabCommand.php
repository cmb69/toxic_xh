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

use Plib\Request;
use Plib\View;

class TabCommand
{
    /** @var array<string,string> */
    private $conf;

    /** @var array<string,string> */
    private $pageData;

    /** @var View */
    private $view;

    /**
     * @param array<string,string> $conf
     * @param array<string,string> $pageData
     */
    public function __construct(array $conf, array $pageData, View $view)
    {
        $this->conf = $conf;
        $this->pageData = $pageData;
        $this->view = $view;
    }

    public function __invoke(Request $request): string
    {
        $url = $request->url()->relative();
        return '<form id="toxic_tab" action="' . $url
            . '" method="post">'
            . $this->renderCategory()
            . $this->renderClassField() . $this->renderButtons()
            . '</form>';
    }

    private function renderCategory(): string
    {
        return '<p><label>' . $this->view->text("label_category") . ' '
            . '<input type="text" name="toxic_category" value="'
                . XH_hsc($this->pageData['toxic_category']) . '">'
            . '</label></p>';
    }

    private function renderClassField(): string
    {
        $result = '<p><label>' . $this->view->text("label_class") . ' ';
        if ($this->conf['classes_available'] == '') {
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
        $result = '';
        foreach ($this->getAvailableClasses() as $class) {
            $result .= '<option';
            if ($class == '') {
                 $result .= ' label="' . $this->view->plain("label_none") . '"';
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
        $classes = $this->conf['classes_available'];
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
        return '<button name="save_page_data">'
            . $this->view->text("label_save") . '</button>';
    }
}
