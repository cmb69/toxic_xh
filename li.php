<?php

/**
 * The li() function.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Toxic
 * @author    The CMSimple_XH developers <devs@cmsimple-xh.org>
 * @copyright 2014 The CMSimple_XH developers <http://cmsimple-xh.org/?The_Team>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

/**
 * Returns a menu structure of the pages.
 *
 * @param array $ta The indexes of the pages.
 * @param mixed $st The menu level to start with or the type of menu.
 *
 * @return string The (X)HTML.
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
function toxic_li($ta, $st)
{
    global $s, $l, $h, $cl, $cf, $u, $edit, $pd_router;

    $tl = count($ta);
    if ($tl < 1) {
        return;
    }
    $t = '';
    if ($st == 'submenu' || $st == 'search') {
        $t .= '<ul class="' . $st . '">' . "\n";
    }
    $b = 0;
    if ($st > 0) {
        $b = $st - 1;
        $st = 'menulevel';
    }
    $lf = array();
    for ($i = 0; $i < $tl; $i++) {
        $tf = ($s != $ta[$i]);
        if ($st == 'menulevel' || $st == 'sitemaplevel') {
            for ($k = (isset($ta[$i - 1]) ? $l[$ta[$i - 1]] : $b);
                 $k < $l[$ta[$i]];
                 $k++
            ) {
                $t .= "\n" . '<ul class="' . $st . ($k + 1) . '">' . "\n";
            }
        }
        $t .= '<li class="';
        if (!$tf) {
            $t .= 's';
        } elseif ($cf['menu']['sdoc'] == "parent" && $s > -1) {
            if ($l[$ta[$i]] < $l[$s]) {
                $hasChildren = substr($u[$s], 0, 1 + strlen($u[$ta[$i]]))
                    == $u[$ta[$i]] . $cf['uri']['seperator'];
                if ($hasChildren) {
                    $t .= 's';
                }
            }
        }
        $t .= 'doc';
        for ($j = $ta[$i] + 1; $j < $cl; $j++) {
            if (!hide($j)
                && $l[$j] - $l[$ta[$i]] < 2 + $cf['menu']['levelcatch']
            ) {
                if ($l[$j] > $l[$ta[$i]]) {
                    $t .= 's';
                }
                break;
            }
        }
        $pageData = $pd_router->find_page($ta[$i]);
        if ($pageData['toxic_classes']) {
            $t .= ' ' . $pageData['toxic_classes'];
        }
        $t .= '">';
        if ($tf) {
            $pageData = $pd_router->find_page($ta[$i]);
            $x = !(XH_ADM && $edit) && $pageData['use_header_location'] === '2'
                ? '" target="_blank' : '';
            $t .= a($ta[$i], $x);
        } else {
            $t .='<span>';
        }
        $t .= $h[$ta[$i]];
        if ($tf) {
            $t .= '</a>';
        } else {
            $t .='</span>';
        }
        if ($st == 'menulevel' || $st == 'sitemaplevel') {
            if ((isset($ta[$i + 1]) ? $l[$ta[$i + 1]] : $b) > $l[$ta[$i]]) {
                $lf[$l[$ta[$i]]] = true;
            } else {
                $t .= '</li>' . "\n";
                $lf[$l[$ta[$i]]] = false;
            }
            for ($k = $l[$ta[$i]];
                $k > (isset($ta[$i + 1]) ? $l[$ta[$i + 1]] : $b);
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
    if ($st == 'submenu' || $st == 'search') {
        $t .= '</ul>' . "\n";
    }
    return $t;
}

?>
