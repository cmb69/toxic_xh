<?php

/**
 * The li commands.
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

class LiCommand
{
    /** @var array */
    private $ta;

    /** @var mixed */
    private $st;

    /** @param mixed $st */
    public function __construct(array $ta, $st)
    {
        $this->ta = $ta;
        $this->st = $st;
    }

    public function render(): string
    {
        global $s, $l, $h, $cl, $cf, $u, $edit, $pd_router;

        $tl = count($this->ta);
        if ($tl < 1) {
            return "";
        }
        $t = '';
        if ($this->st == 'submenu' || $this->st == 'search') {
            $t .= '<ul class="' . $this->st . '">' . "\n";
        }
        $b = 0;
        if ($this->st > 0) {
            $b = $this->st - 1;
            $this->st = 'menulevel';
        }
        $lf = array();
        for ($i = 0; $i < $tl; $i++) {
            $tf = ($s != $this->ta[$i]);
            if ($this->st == 'menulevel' || $this->st == 'sitemaplevel') {
                for ($k = (isset($this->ta[$i - 1]) ? $l[$this->ta[$i - 1]] : $b); $k < $l[$this->ta[$i]]; $k++) {
                    $t .= "\n" . '<ul class="' . $this->st . ($k + 1) . '">'
                        . "\n";
                }
            }
            $t .= $this->renderCategoryItem($i);
            $t .= '<li class="';
            if (!$tf) {
                $t .= 's';
            } elseif ($cf['menu']['sdoc'] == "parent" && $s > -1) {
                if ($l[$this->ta[$i]] < $l[$s]) {
                    $hasChildren = substr($u[$s], 0, 1 + strlen($u[$this->ta[$i]]))
                        == $u[$this->ta[$i]] . $cf['uri']['seperator'];
                    if ($hasChildren) {
                        $t .= 's';
                    }
                }
            }
            $t .= 'doc';
            for ($j = $this->ta[$i] + 1; $j < $cl; $j++) {
                if (
                    !hide($j)
                    && $l[$j] - $l[$this->ta[$i]] < 2 + $cf['menu']['levelcatch']
                ) {
                    if ($l[$j] > $l[$this->ta[$i]]) {
                        $t .= 's';
                    }
                    break;
                }
            }
            $t .= $this->renderClass($i);
            $t .= '">';
            if ($tf) {
                $pageData = $pd_router->find_page($this->ta[$i]);
                $x = !(XH_ADM && $edit) // @phpstan-ignore-line
                    && $pageData['use_header_location'] === '2'
                        ? '" target="_blank' : '';
                $t .= a($this->ta[$i], $x);
            } else {
                $t .= '<span>';
            }
            $t .= $h[$this->ta[$i]];
            if ($tf) {
                $t .= '</a>';
            } else {
                $t .= '</span>';
            }
            if ($this->st == 'menulevel' || $this->st == 'sitemaplevel') {
                $cond = (isset($this->ta[$i + 1]) ? $l[$this->ta[$i + 1]] : $b)
                    > $l[$this->ta[$i]];
                if ($cond) {
                    $lf[$l[$this->ta[$i]]] = true;
                } else {
                    $t .= '</li>' . "\n";
                    $lf[$l[$this->ta[$i]]] = false;
                }
                for ($k = $l[$this->ta[$i]]; $k > (isset($this->ta[$i + 1]) ? $l[$this->ta[$i + 1]] : $b); $k--) {
                    $t .= '</ul>' . "\n";
                    if (isset($lf[$k - 1])) {
                        if ($lf[$k - 1]) {
                            $t .= '</li>' . "\n";
                            $lf[$k - 1] = false;
                        }
                    }
                }
            } else {
                $t .= '</li>' . "\n";
            }
        }
        if ($this->st == 'submenu' || $this->st == 'search') {
            $t .= '</ul>' . "\n";
        }
        return $t;
    }

    private function renderCategoryItem(int $index): string
    {
        global $pd_router;

        $html = '';
        $pageData = $pd_router->find_page($this->ta[$index]);
        if ($pageData['toxic_category']) {
            $html .= '<li class="toxic_category">' . $pageData['toxic_category']
                . '</li>';
        }
        return $html;
    }

    private function renderClass(int $index): string
    {
        global $pd_router;

        $pageData = $pd_router->find_page($this->ta[$index]);
        if ($pageData['toxic_class']) {
            return ' ' . $pageData['toxic_class'];
        } else {
            return '';
        }
    }
}
