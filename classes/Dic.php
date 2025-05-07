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

class Dic
{
    public const VERSION = "1alpha1";

    /** @param array<string,string> $pageData */
    public static function makeTabCommand(array $pageData): TabCommand
    {
        global $plugin_cf;
        return new TabCommand($plugin_cf["toxic"], $pageData);
    }

    public static function makeInfoCommand(): InfoCommand
    {
        return new InfoCommand();
    }
}
