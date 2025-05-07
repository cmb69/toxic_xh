<?php

namespace Toxic;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;

/**
 * Testing li().
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Toxic
 * @author    The CMSimple_XH developers <devs@cmsimple-xh.org>
 * @copyright 2014-2015 The CMSimple_XH developers <http://cmsimple-xh.org/?The_Team>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Toxic_XH
 */

/**
 * Testing li().
 *
 * @category Testing
 * @package  Toxic
 * @author   The CMSimple_XH developers <devs@cmsimple-xh.org>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class LiTest extends TestCase
{
    /** @var object */
    protected $liStub;

    /** @var object */
    protected $hideStub;

    public function setUp(): void
    {
        global $pth, $s;

        $pth = array('folder' => array('classes' => './cmsimple/classes/'));
        $s = 0;
        $this->setUpPageStructure();
        $this->setUpConfiguration();
        $this->setUpEditMode(false);
        $this->setUpPageDataRouterMock();
        $this->setUpFunctionStubs();
    }

    protected function setUpPageStructure(): void
    {
        global $cl, $h, $u, $l;

        $h = array(
            'Welcome',
            'Blog',
            'July',
            'Hot',
            'Hidden',
            'AlsoHidden',
            'January',
            'Cold',
            'About',
            'Contact',
            'News'
        );
        $u = array(
            'Welcome',
            'Blog',
            'Blog:July',
            'Blog:July:Hot',
            'Blog:Hidden',
            'Blog:Hidden:AlsoHidden',
            'Blog:January',
            'Blog:January:Cold',
            'About',
            'About:Contact',
            'News'
        );
        $l = array(1, 1, 2, 3, 2, 3, 2, 3, 1, 3, 1);
        $cl = count($u);
    }

    protected function setUpConfiguration(): void
    {
        global $cf;

        $cf = array(
            'locator' => array('show_homepage' => 'true'),
            'menu' => array(
                'levelcatch' => '10',
                'levels' => '3',
                'sdoc' => 'parent'
            ),
            'show_hidden' => array(
                'pages_toc' => 'true'
            ),
            'uri' => array(
                'seperator' => ':'
            )
        );
    }

    protected function setUpEditMode(bool $flag): void
    {
        global $edit;

        if (defined('XH_ADM')) {
            uopz_redefine('XH_ADM', $flag);
        } else {
            define('XH_ADM', $flag);
        }
        $edit = $flag;
    }

    protected function setUpPageDataRouterMock(): void
    {
        global $pd_router;

        $pd_router = $this->getMockBuilder(PageDataRouter::class)
            ->disableOriginalConstructor()->getMock();
        $pd_router->expects($this->any())->method('find_page')->will(
            $this->returnCallback(
                function ($pageIndex) {
                    return array(
                        'toxic_class' => ($pageIndex >= 1 && $pageIndex <= 7)
                            ? 'blog' : '',
                        'toxic_category' => '',
                        'use_header_location' => ($pageIndex == 7) ? '2' : '0'
                    );
                }
            )
        );
    }

    protected function setUpFunctionStubs(): void
    {
        uopz_set_return("a", function ($pageIndex, $suffix) {
            global $u;

            return '<a href="?' . $u[$pageIndex] . $suffix . '">';
        }, true);
        uopz_set_return("hide", function ($pageIndex) {
            return in_array($pageIndex, array(4, 5));
        }, true);
    }

    public function tearDown(): void
    {
        uopz_unset_return("a");
        uopz_unset_return("hide");
    }

    public function testNoMenuItemsDisplayNothing(): void
    {
        $this->assertEmpty((new LiCommand(array(), 1))->render());
    }

    /** @dataProvider dataForUnorderedListlHasListItemChild */
    public function testUnorderedListHasListItemChild(string $class): void
    {
        $this->assertStringContainsString("<ul class=\"$class\">\n<li ", $this->renderAllPages());
    }

    public function dataForUnorderedListlHasListItemChild(): array
    {
        return array(
            array('menulevel1'),
            array('menulevel2'),
            array('menulevel3')
        );
    }

    /** @dataProvider dataForListItemHasUnorderedListChild */
    public function testListItemHasUnorderedListChild(string $class): void
    {
        $this->assertStringMatchesFormat("%A<li%s\n<ul class=\"$class\">%A", $this->renderAllPages());
    }

    public function dataForListItemHasUnorderedListChild(): array
    {
        return array(
            array('menulevel2'),
            array('menulevel3')
        );
    }

    public function testSelectedPageHasSpan(): void
    {
        $this->assertStringContainsString("<span>Welcome</span>", $this->renderAllPages());
    }

    public function testNotSelectedPageHasAnchor(): void
    {
        Approvals::verifyHtml($this->renderAllPages());
    }

    public function testLiWithoutVisibleChilrenHasClassDoc(): void
    {
        $this->assertStringContainsString(
            "<li class=\"doc blog\"><a href=\"?Blog:Hidden\">Hidden</a>",
            $this->renderAllPages()
        );
    }

    /**
     * @param mixed $forOrFrom
     * @dataProvider dataForHasUlWithProperClass
     */
    public function testHasUlWithProperClass($forOrFrom, string $class): void
    {
        $this->assertStringContainsString("<ul class=\"$class\">", $this->renderAllPages($forOrFrom));
    }

    public function dataForHasUlWithProperClass(): array
    {
        return array(
            array('menulevel', 'menulevel1'),
            array(1, 'menulevel1'),
            array(1, 'menulevel2'),
            array(1, 'menulevel3'),
            array('sitemaplevel', 'sitemaplevel1'),
            array('sitemaplevel', 'sitemaplevel2'),
            array('sitemaplevel', 'sitemaplevel3'),
            array('submenu', 'submenu'),
            array('search', 'search')
        );
    }

    public function testSelectedPageHasClassSdocs(): void
    {
        global $s;

        $s = 1;
        $this->assertStringContainsString("<li class=\"sdocs blog\"><span>Blog</span>", $this->renderAllPages());
    }

    public function testSelectedChildlessPageHasClassSdoc(): void
    {
        Approvals::verifyHtml($this->renderAllPages());
    }

    public function testNotSelectedPageHasClassDocs(): void
    {
        $this->assertStringContainsString(
            "<li class=\"docs blog\"><a href=\"?Blog\">Blog</a>",
            $this->renderAllPages()
        );
    }

    public function testNotSelectedChildlessPageHasClassDoc(): void
    {
        global $s;

        $s = 1;
        Approvals::verifyHtml($this->renderAllPages());
    }

    /** @dataProvider dataForParentOfSelectedPageHasClassDependingOnSdoc */
    public function testParentOfSelectedPageHasClassDependingOnSdoc(string $sdoc, string $class): void
    {
        global $s, $cf;

        $s = 2;
        $cf['menu']['sdoc'] = $sdoc;
        $this->assertStringContainsString(
            "<li class=\"$class blog\"><a href=\"?Blog\">Blog</a>",
            $this->renderAllPages()
        );
    }

    public function dataForParentOfSelectedPageHasClassDependingOnSdoc(): array
    {
        return array(
            array('parent', 'sdocs'),
            array('', 'docs')
        );
    }

    /** @dataProvider dataForH1WithH3HasClassDependingOnLevelcatch */
    public function testH1WithH3HasClassDependingOnLevelcatch(string $levelcatch, string $class): void
    {
        global $cf;

        $cf['menu']['levelcatch'] = $levelcatch;
        $this->assertStringContainsString(
            "<li class=\"$class\"><a href=\"?About\">About</a>",
            $this->renderAllPages()
        );
    }

    public function dataForH1WithH3HasClassDependingOnLevelcatch(): array
    {
        return array(
            array('10', 'docs'),
            array('0', 'doc')
        );
    }

    public function testPageOpensInNewWindowInNormalMode(): void
    {
        Approvals::verifyHtml($this->renderAllPages());
    }

    public function testPageDoesntOpenInNewWindowInEditMode(): void
    {
        $this->setUpEditMode(true);
        Approvals::verifyHtml($this->renderAllPages());
    }

    /** @param mixed $forOrFrom */
    protected function renderAllPages($forOrFrom = 1): string
    {
        return (new LiCommand(range(0, 10), $forOrFrom))->render();
    }

    public function testBlogSubmenuHasExactlyThreeItems(): void
    {
        global $s;

        $s = 1;
        Approvals::verifyHtml((new LiCommand(array(2, 4, 6), 'submenu'))->render());
    }

    public function testBlogSubmenuHasProperStructure(): void
    {
        Approvals::verifyHtml((new LiCommand(array(2, 4, 6), 'submenu'))->render());
    }
}

?>
