<?php

/**
 * Copyright 1999-2009 Peter Harteg
 * Copyright 2009-2023 The CMSimple_XH developers <https://cmsimple-xh.org/?The_Team>
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
use Plib\View;
use Toxic\Model\Pages as ModelPages;

class SubmenuCommand
{
    /** @var array<string,string> */
    private $conf;

    /** @var ModelPages */
    private $pages;

    /** @var View */
    private $view;

    /** @var LiCommand */
    private $liCommand;

    /** @param array<string,string> $conf */
    public function __construct(array $conf, ModelPages $pages, View $view, LiCommand $liCommand)
    {
        $this->conf = $conf;
        $this->pages = $pages;
        $this->view = $view;
        $this->liCommand = $liCommand;
    }

    public function __invoke(Request $request, string $html): Response
    {
        $tocArray = $this->pages->children($request->s(), (int) $this->conf["menu_levelcatch"]);
        if (count($tocArray) <= 0) {
            return Response::create();
        }
        if ($html === "") {
            $level = min(6, (int) $this->conf["menu_levels"] + 1);
            return Response::create("<h$level>" . $this->view->plain("submenu_heading") . "</h$level>\n"
                . ($this->liCommand)($request, $tocArray, 'submenu')());
        }
        return Response::create(sprintf($html, $this->view->plain("submenu_heading")) . "\n"
            . ($this->liCommand)($request, $tocArray, "submenu")());
    }
}
