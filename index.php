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

use Toxic\CommandFactory;
use Toxic\Controller;
use Toxic\LiCommand;

if (!defined("CMSIMPLE_XH_VERSION")) {
    http_response_code(403);
    exit;
}

define('TOXIC_VERSION', '1alpha1');

function toxic(?int $start = null, ?int $end = null): string
{
    return toc($start, $end, 'Toxic_li');
}

/**
 * @param list<int> $ta
 * @param int|string $st
 */
function Toxic_li(array $ta, $st): string
{
    $liCommand = new LiCommand($ta, $st);
    return $liCommand->render();
}

$_Toxic_controller = new Controller(
    new CommandFactory()
);
$_Toxic_controller->dispatch();
