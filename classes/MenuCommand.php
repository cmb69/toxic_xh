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
        $tl = count($ta);
        if ($tl < 1) {
            return Response::create();
        }
        $t = '';
        $b = $level > 0 ? $level - 1 : 0;
        $lf = [];
        for ($i = 0; $i < $tl; $i++) {
            for ($k = $this->pageLevelOrDefault($ta, $i - 1, $b); $k < $this->pages->level($ta[$i]); $k++) {
                $t .= "\n" . '<ul class="menulevel' . ($k + 1) . '">' . "\n";
            }
            $t .= $this->renderCategoryItem($ta[$i]);
            $t .= '<li class="' . $this->renderClasses($request, $ta[$i]) . '">';
            $t .= $this->renderMenuItem($request, $ta[$i]);
            $cond = $this->pageLevelOrDefault($ta, $i + 1, $b) > $this->pages->level($ta[$i]);
            if ($cond) {
                $lf[$this->pages->level($ta[$i])] = true;
            } else {
                $t .= '</li>' . "\n";
                $lf[$this->pages->level($ta[$i])] = false;
            }
            for ($k = $this->pages->level($ta[$i]); $k > $this->pageLevelOrDefault($ta, $i + 1, $b); $k--) {
                $t .= '</ul>' . "\n";
                if (isset($lf[$k - 1])) {
                    if ($lf[$k - 1]) {
                        $t .= '</li>' . "\n";
                        $lf[$k - 1] = false;
                    }
                }
            }
        }
        return Response::create($t);
    }

    /** @param list<int> $ta */
    private function pageLevelOrDefault(array $ta, int $i, int $default): int
    {
        return isset($ta[$i]) ? $this->pages->level($ta[$i]) : $default;
    }
}
