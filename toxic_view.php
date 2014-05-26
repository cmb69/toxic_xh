<?php

/**
 * The page data tab view.
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

/**
 * Renders the page data tab view.
 *
 * @param array $pageData A page data array.
 *
 * @return string (X)HTML.
 */
function Toxic_view($pageData)
{
    $tabCommand = new Toxic_TabCommand($pageData);
    return $tabCommand->render();
}

?>
