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

namespace Toxic\Model;

use XH\Pages;

class TocArray
{
    /** @return list<int> */
    public static function submenu(Pages $pages, int $page, int $levelCatch): array
    {
        $ta = [];
        if ($page > -1) {
            $tl = $pages->level($page) + 1 + $levelCatch;
            for ($i = $page + 1; $i < $pages->getCount(); $i++) {
                if ($pages->level($i) <= $pages->level($page)) {
                    break;
                }
                if ($pages->level($i) <= $tl) {
                    if (!$pages->isHidden($i)) {
                        $ta[] = $i;
                    }
                }
                if ($pages->level($i) < $tl) {
                    $tl = $pages->level($i);
                }
            }
        }
        return $ta;
    }
}
