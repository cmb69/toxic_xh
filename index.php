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

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (
    !defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.7.0', 'lt') // @phpstan-ignore-line
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(<<<EOT
Toxic_XH detected an unsupported CMSimple_XH version.
Uninstall Toxic_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
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
