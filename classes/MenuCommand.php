<?php

/**
 * Copyright 1999-2009 Peter Harteg
 * Copyright 2014 The CMSimple_XH developers <https://cmsimple-xh.org/?The_Team>
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
use Plib\Response;
use Toxic\Model\Page;
use Toxic\Model\Pages;

class MenuCommand
{
    use ListRendering;

    /** @var array<string,string> */
    private $conf;

    /** @var Pages */
    private $pages;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        Pages $pages
    ) {
        $this->conf = $conf;
        $this->pages = $pages;
    }

    /** @param list<int> $ta */
    public function __invoke(Request $request, array $ta, int $level): Response
    {
        $page = Page::fromTocArray($ta, $level, $this->pages);
        return Response::create($this->renderMenu($request, $page, 1));
    }

    private function renderMenu(Request $request, ?Page $page, int $indent = 1): string
    {
        if ($page === null) {
            return "";
        }
        $res = str_repeat("", $indent) . "\n<ul class=\"menulevel{$indent}\">\n";
        $indent++;
        do {
            if ($page->index() !== null) {
                $res .= $this->renderCategoryItem($page->index());
                $res .= str_repeat("", $indent) . "<li class=\"{$this->renderClasses($request, $page->index())}\">{$this->renderMenuItem($request, $page->index())}";
            }
            $res .= $this->renderMenu($request, $page->child(), $indent);
            if ($page->index() !== null) {
                $res .= "</li>\n";
            }
        } while ($page = $page->next());
        $indent--;
        $res .= str_repeat("", $indent) . "</ul>\n";
        return $res;
    }
}
