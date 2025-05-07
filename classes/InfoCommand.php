<?php

/**
 * The info commands.
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

 /**
 * The info commands.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class InfoCommand
{
    public function render(): string
    {
        return $this->renderHeading()
            . $this->renderLogo() . $this->renderVersion()
            . $this->renderCopyright() . $this->renderLicense();
    }

    protected function renderHeading(): string
    {
        global $plugin_tx;

        return '<h1>Toxic &ndash; ' . $plugin_tx['toxic']['caption_info']
            . '</h1>';
    }

    protected function renderLogo(): string
    {
        global $pth, $plugin_tx;

        return tag(
            'img class="toxic_logo" src="' . $pth['folder']['plugins']
            . 'toxic/toxic.png" alt="' . $plugin_tx['toxic']['alt_logo'] . '"'
        );
    }

    protected function renderVersion(): string
    {
        return '<p>Version: ' . TOXIC_VERSION . '</p>';
    }

    protected function renderCopyright(): string
    {
        return '<p>Copyright &copy; 2014-2015'
            . ' <a href="http://3-magi.net/">Christoph M. Becker</a>';
    }

    protected function renderLicense(): string
    {
        return <<<EOT
<p class="toxic_license">This program is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.</p>
<p class="toxic_license">This program is distributed in the hope that it will be
useful, but <em>without any warranty</em>; without even the implied warranty of
<em>merchantability</em> or <em>fitness for a particular purpose</em>. See the
GNU General Public License for more details.</p>
<p class="toxic_license">You should have received a copy of the GNU
General Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
EOT;
    }
}

?>
