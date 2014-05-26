<?php

/**
 * main ;)
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Toxic
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * The presentation layer.
 */
require_once $pth['folder']['plugin_classes'] . 'Presentation.php';

require_once $pth['folder']['plugin'] . 'li.php';

/**
 * The plugin version.
 */
define('TOXIC_VERSION', '@TOXIC_VERSION@');

/**
 * The controller.
 */
$_Toxic_controller = new Toxic_Controller(
    new Toxic_CommandFactory()
);
$_Toxic_controller->dispatch();

?>