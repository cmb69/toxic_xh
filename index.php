<?php

/**
 * The plugin entry.
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

use Toxic\CommandFactory;
use Toxic\Controller;
use Toxic\LiCommand;

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (!defined('CMSIMPLE_XH_VERSION')
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

function toxic(?int $start = null, ?int$end = null): string
{
    return toc($start, $end, 'Toxic_li');
}

function Toxic_li(array $ta, $st): string
{
    $liCommand = new LiCommand($ta, $st);
    return $liCommand->render();
}

$_Toxic_controller = new Controller(
    new CommandFactory()
);
$_Toxic_controller->dispatch();

?>
