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

/**
 * The li commands.
 *
 * @category CMSimple_XH
 * @package  Toxic
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class Toxic_LiCommand
{
    /**
     * The indexes of the pages.
     *
     * @var array
     */
    private $_ta;

    /**
     * The menu level to start with or the type of menu.
     *
     * @var mixed
     */
    private $_st;

    /**
     * Initializes a new instance.
     *
     * @param array $ta The indexes of the pages.
     * @param mixed $st The menu level to start with or the type of menu.
     *
     * @return void
     */
    public function __construct($ta, $st)
    {
        $this->_ta = $ta;
        $this->_st = $st;
    }

    /**
     * Renders the li view.
     *
     * @return string (X)HTML.
     *
     * @global int    The index of the current page.
     * @global array  The menu levels of the pages.
     * @global array  The headings of the pages.
     * @global int    The number of pages.
     * @global array  The configuration of the core.
     * @global array  The URLs of the pages.
     * @global array  Whether we are in edit mode.
     * @global object The page data router.
     */
    public function render()
    {
        global $s, $l, $h, $cl, $cf, $u, $edit, $pd_router;

        $tl = count($this->_ta);
        if ($tl < 1) {
            return;
        }
        $t = '';
        if ($this->_st == 'submenu' || $this->_st == 'search') {
            $t .= '<ul class="' . $this->_st . '">' . "\n";
        }
        $b = 0;
        if ($this->_st > 0) {
            $b = $this->_st - 1;
            $this->_st = 'menulevel';
        }
        $lf = array();
        for ($i = 0; $i < $tl; $i++) {
            $tf = ($s != $this->_ta[$i]);
            if ($this->_st == 'menulevel' || $this->_st == 'sitemaplevel') {
                for ($k = (isset($this->_ta[$i - 1]) ? $l[$this->_ta[$i - 1]] : $b);
                     $k < $l[$this->_ta[$i]];
                     $k++
                ) {
                    $t .= "\n" . '<ul class="' . $this->_st . ($k + 1) . '">'
                        . "\n";
                }
            }
            $t .= '<li class="';
            if (!$tf) {
                $t .= 's';
            } elseif ($cf['menu']['sdoc'] == "parent" && $s > -1) {
                if ($l[$this->_ta[$i]] < $l[$s]) {
                    $hasChildren = substr($u[$s], 0, 1 + strlen($u[$this->_ta[$i]]))
                        == $u[$this->_ta[$i]] . $cf['uri']['seperator'];
                    if ($hasChildren) {
                        $t .= 's';
                    }
                }
            }
            $t .= 'doc';
            for ($j = $this->_ta[$i] + 1; $j < $cl; $j++) {
                if (!hide($j)
                    && $l[$j] - $l[$this->_ta[$i]] < 2 + $cf['menu']['levelcatch']
                ) {
                    if ($l[$j] > $l[$this->_ta[$i]]) {
                        $t .= 's';
                    }
                    break;
                }
            }
            $pageData = $pd_router->find_page($this->_ta[$i]);
            if ($pageData['toxic_class']) {
                $t .= ' ' . $pageData['toxic_class'];
            }
            $t .= '">';
            if ($tf) {
                $pageData = $pd_router->find_page($this->_ta[$i]);
                $x = !(XH_ADM && $edit)
                    && $pageData['use_header_location'] === '2'
                        ? '" target="_blank' : '';
                $t .= a($this->_ta[$i], $x);
            } else {
                $t .='<span>';
            }
            $t .= $h[$this->_ta[$i]];
            if ($tf) {
                $t .= '</a>';
            } else {
                $t .='</span>';
            }
            if ($this->_st == 'menulevel' || $this->_st == 'sitemaplevel') {
                $cond = (isset($this->_ta[$i + 1]) ? $l[$this->_ta[$i + 1]] : $b)
                    > $l[$this->_ta[$i]];
                if ($cond) {
                    $lf[$l[$this->_ta[$i]]] = true;
                } else {
                    $t .= '</li>' . "\n";
                    $lf[$l[$this->_ta[$i]]] = false;
                }
                for ($k = $l[$this->_ta[$i]];
                    $k > (isset($this->_ta[$i + 1]) ? $l[$this->_ta[$i + 1]] : $b);
                    $k--
                ) {
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
        if ($this->_st == 'submenu' || $this->_st == 'search') {
            $t .= '</ul>' . "\n";
        }
        return $t;
    }
}

?>
