<?php

/**
 * Copyright 1999-2009 Peter Harteg
 * Copyright 2014 The CMSimple_XH developers <https://cmsimple-xh.org/?The_Team>
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
use Plib\Response;
use Toxic\Model\Pages;

class LiCommand
{
    /** @var array<string,string> */
    private $conf;

    /** @var Pages */
    private $pages;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        Pages $pages
    ) {
        $this->conf = $conf;
        $this->pages = $pages;
    }

    /**
     * @param list<int> $ta
     * @param int|string $st
     * @phpstan-param positive-int|'submenu'|'search'|'menulevel'|'sitemaplevel' $st
     */
    public function __invoke(Request $request, array $ta, $st): Response
    {
        $tl = count($ta);
        if ($tl < 1) {
            return Response::create();
        }
        $t = '';
        if ($st == 'submenu' || $st == 'search') {
            $t .= '<ul class="' . $st . '">' . "\n";
        }
        $b = 0;
        if ((int) $st > 0) {
            $b = (int) $st - 1;
            $st = 'menulevel';
        }
        $lf = [];
        for ($i = 0; $i < $tl; $i++) {
            $tf = ($request->s() != $ta[$i]);
            if ($st == 'menulevel' || $st == 'sitemaplevel') {
                for ($k = $this->pageLevelOrDefault($ta, $i - 1, $b); $k < $this->pages->level($ta[$i]); $k++) {
                    $t .= "\n" . '<ul class="' . $st . ($k + 1) . '">'
                        . "\n";
                }
            }
            $t .= $this->renderCategoryItem($ta[$i]);
            $t .= '<li class="';
            if (!$tf) {
                $t .= 's';
            } elseif ($this->conf["menu_sdoc"] == "parent" && $request->s() > -1) {
                if ($this->pages->level($ta[$i]) < $this->pages->level($request->s())) {
                    $hasChildren = substr($this->pages->url($request->s()), 0, 1 + strlen($this->pages->url($ta[$i])))
                        == $this->pages->url($ta[$i]) . $this->conf["uri_separator"];
                    if ($hasChildren) {
                        $t .= 's';
                    }
                }
            }
            $t .= 'doc';
            for ($j = $ta[$i] + 1; $j < $this->pages->count(); $j++) {
                if (
                    !$this->pages->hidden($j)
                    && $this->pages->level($j) - $this->pages->level($ta[$i]) < 2 + (int) $this->conf["menu_levelcatch"]
                ) {
                    if ($this->pages->level($j) > $this->pages->level($ta[$i])) {
                        $t .= 's';
                    }
                    break;
                }
            }
            $t .= $this->renderClass($ta[$i]);
            $t .= '">';
            if ($tf) {
                $pageData = $this->pages->data($ta[$i]);
                $x = !($request->admin() && $request->edit())
                    && $pageData['use_header_location'] === '2'
                        ? '" target="_blank' : '';
                $t .= $this->a($request, $ta[$i], $x);
            } else {
                $t .= '<span>';
            }
            $t .= $this->pages->heading($ta[$i]);
            if ($tf) {
                $t .= '</a>';
            } else {
                $t .= '</span>';
            }
            if ($st == 'menulevel' || $st == 'sitemaplevel') {
                $cond = $this->pageLevelOrDefault($ta, $i + 1, $b) > $this->pages->level($ta[$i]);
                if ($cond) {
                    $lf[$this->pages->level($ta[$i])] = true;
                } else {
                    $t .= '</li>' . "\n";
                    $lf[$this->pages->level($ta[$i])] = false;
                }
                for ($k = $this->pages->level($ta[$i]); $k > $this->pageLevelOrDefault($ta, $i + 1, $b); $k--) {
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
        return Response::create($t);
    }

    /** @param list<int> $ta */
    private function pageLevelOrDefault(array $ta, int $i, int $default): int
    {
        return isset($ta[$i]) ? $this->pages->level($ta[$i]) : $default;
    }

    private function a(Request $request, int $i, string $x): string
    {
        $sn = $request->url()->page("")->relative();
        if ($i === $this->pages->firstPublished() && !($request->admin())) {
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
        $html = '';
        $pageData = $this->pages->data($index);
        if ($pageData['toxic_category']) {
            $html .= "<li class=\"toxic_category\">" . $pageData["toxic_category"] . "</li>\n";
        }
        return $html;
    }

    private function renderClass(int $index): string
    {
        $pageData = $this->pages->data($index);
        if ($pageData['toxic_class']) {
            return ' ' . $pageData['toxic_class'];
        } else {
            return '';
        }
    }
}
