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

namespace Toxic;

use Plib\Request;
use XH\Pages;
use XH\Publisher;

class LiCommand
{
    /** @var Pages */
    private $pages;

    /** @var Publisher */
    private $publisher;

    /** @var list<int> */
    private $ta;

    /** @var int|string */
    private $st;

    /**
     * @param list<int> $ta
     * @param int|string $st
     */
    public function __construct(Pages $pages, Publisher $publisher, array $ta, $st)
    {
        $this->pages = $pages;
        $this->publisher = $publisher;
        $this->ta = $ta;
        $this->st = $st;
    }

    public function render(Request $request): string
    {
        global $s, $l, $h, $cl, $cf, $u, $pd_router;

        $tl = count($this->ta);
        if ($tl < 1) {
            return "";
        }
        $t = '';
        if ($this->st == 'submenu' || $this->st == 'search') {
            $t .= '<ul class="' . $this->st . '">' . "\n";
        }
        $b = 0;
        if ((int) $this->st > 0) {
            $b = (int) $this->st - 1;
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
                    !$this->pages->isHidden($j)
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
                $x = !($request->admin() && $request->edit())
                    && $pageData['use_header_location'] === '2'
                        ? '" target="_blank' : '';
                $t .= $this->a($request, $this->ta[$i], $x);
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

    private function a(Request $request, int $i, string $x): string
    {
        $sn = $request->url()->page("")->relative();
        if ($i === $this->publisher->getFirstPublishedPage() && !($request->admin())) {
            $a_href = $sn;
        } else {
            $a_href = $sn . '?' . $this->pages->url($i);
        }
        if (stripos($a_href, '?') === false) {
            ($x ? $x = '?' . $x : '');
        }
        return '<a href="' . $a_href . $x . '">';
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
