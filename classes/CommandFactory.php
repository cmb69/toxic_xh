<?php

/**
 * The command factories.
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

namespace Toxic;

class CommandFactory
{
    public function makeTabCommand(array $pageData): TabCommand
    {
        return new TabCommand($pageData);
    }

    public function makeInfoCommand(): InfoCommand
    {
        return new InfoCommand();
    }
}
