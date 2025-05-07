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
    /**
     * The li() stub.
     *
     * @var object
     */
    protected $liStub;

    /**
     * The hide() stub.
     *
     * @var object
     */
    protected $hideStub;

    /**
     * Sets up the default fixture.
     *
     * @return void
     *
     * @global int The index of the selected page.
     */
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

    /**
     * Sets up the default page structure.
     *
     * @return void
     *
     * @global int   The number of pages.
     * @global array The headings of the pages.
     * @global array The URLs of the pages.
     * @global array The levels of the pages.
     */
    protected function setUpPageStructure()
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

    /**
     * Sets up the default configuration options.
     *
     * @return void
     *
     * @global array The configuration of the core.
     */
    protected function setUpConfiguration()
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

    /**
     * Sets up edit resp. normal mode.
     *
     * @param bool $flag Whether to enable edit mode.
     *
     * @return void
     *
     * @global bool Whether edit mode is enabled.
     */
    protected function setUpEditMode($flag)
    {
        global $edit;

        if (defined('XH_ADM')) {
            uopz_redefine('XH_ADM', $flag);
        } else {
            define('XH_ADM', $flag);
        }
        $edit = $flag;
    }

    /**
     * Sets up the default page data router mock.
     *
     * @return void
     *
     * @global XH_PageDataRouter The page data router.
     */
    protected function setUpPageDataRouterMock()
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

    /**
     * Sets up the default function stubs.
     *
     * @return void
     */
    protected function setUpFunctionStubs()
    {
        uopz_set_return("a", function ($pageIndex, $suffix) {
            global $u;

            return '<a href="?' . $u[$pageIndex] . $suffix . '">';
        }, true);
        uopz_set_return("hide", function ($pageIndex) {
            return in_array($pageIndex, array(4, 5));
        }, true);
    }

    /**
     * Tears down the test fixture.
     *
     * @return void
     */
    public function tearDown(): void
    {
        uopz_unset_return("a");
        uopz_unset_return("hide");
    }

    /**
     * Tests that no menu items display nothing.
     *
     * @return void
     */
    public function testNoMenuItemsDisplayNothing()
    {
        $this->assertEmpty((new LiCommand(array(), 1))->render());
    }

    /**
     * Tests that a UL has a LI as child.
     *
     * @param string $class A CSS class.
     *
     * @return void
     *
     * @dataProvider dataForUnorderedListlHasListItemChild
     */
    public function testUnorderedListHasListItemChild($class)
    {
        $this->assertStringContainsString("<ul class=\"$class\">\n<li ", $this->renderAllPages());
    }

    /**
     * Provides data for dataForUnorderedListlHasListItemChild().
     *
     * @return array
     */
    public function dataForUnorderedListlHasListItemChild()
    {
        return array(
            array('menulevel1'),
            array('menulevel2'),
            array('menulevel3')
        );
    }

    /**
     * Tests that a LI has a UL child.
     *
     * @param string $class A CSS class.
     *
     * @return void
     *
     * @dataProvider dataForListItemHasUnorderedListChild
     */
    public function testListItemHasUnorderedListChild($class)
    {
        $this->assertStringMatchesFormat("%A<li%s\n<ul class=\"$class\">%A", $this->renderAllPages());
    }

    /**
     * Provides data for testListItemHasUnorderedListChild().
     *
     * @return array
     */
    public function dataForListItemHasUnorderedListChild()
    {
        return array(
            array('menulevel2'),
            array('menulevel3')
        );
    }

    /**
     * Tests that the selected page is marked up as SPAN.
     *
     * @return void
     */
    public function testSelectedPageHasSpan()
    {
        $this->assertStringContainsString("<span>Welcome</span>", $this->renderAllPages());
    }

    /**
     * Tests that a not selected page is marked up as ANCHOR.
     *
     * @return void
     */
    public function testNotSelectedPageHasAnchor()
    {
        Approvals::verifyHtml($this->renderAllPages());
    }

    /**
     * Asserts that the rendering of all pages matches a matcher.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     */
    protected function assertMatches($matcher)
    {
        @$this->assertTag($matcher, $this->renderAllPages());
    }

    /**
     * Tests that a LI without visible children has the class "doc".
     *
     * @return void
     */
    public function testLiWithoutVisibleChilrenHasClassDoc()
    {
        $this->assertStringContainsString(
            "<li class=\"doc blog\"><a href=\"?Blog:Hidden\">Hidden</a>",
            $this->renderAllPages()
        );
    }

    /**
     * Tests that UL has the proper class attribute.
     *
     * @param mixed  $forOrFrom A li() view kind or the start level.
     * @param string $class     A CSS class.
     *
     * @return void
     *
     * @dataProvider dataForHasUlWithProperClass
     */
    public function testHasUlWithProperClass($forOrFrom, $class)
    {
        $this->assertStringContainsString("<ul class=\"$class\">", $this->renderAllPages($forOrFrom));
    }

    /**
     * Provides data for testHasUlWithProperClass().
     *
     * @return array
     */
    public function dataForHasUlWithProperClass()
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

    /**
     * Tests that a selected page with children has the class "docs".
     *
     * @return void
     *
     * @global int The index of the selected page.
     */
    public function testSelectedPageHasClassSdocs()
    {
        global $s;

        $s = 1;
        $this->assertStringContainsString("<li class=\"sdocs blog\"><span>Blog</span>", $this->renderAllPages());
    }

    /**
     * Tests that a selected childless page has the class "doc".
     *
     * @return void.
     */
    public function testSelectedChildlessPageHasClassSdoc()
    {
        Approvals::verifyHtml($this->renderAllPages());
    }

    /**
     * Tests that a not selected page with children has the class "docs".
     *
     * @return void.
     */
    public function testNotSelectedPageHasClassDocs()
    {
        $this->assertStringContainsString(
            "<li class=\"docs blog\"><a href=\"?Blog\">Blog</a>",
            $this->renderAllPages()
        );
    }

    /**
     * Tests that a not selected childless page has the class "doc".
     *
     * @return void
     *
     * @global int The index of the selected page.
     */
    public function testNotSelectedChildlessPageHasClassDoc()
    {
        global $s;

        $s = 1;
        Approvals::verifyHtml($this->renderAllPages());
    }

    /**
     * Tests that the parent of the a selected page has a class depending on
     * menu_sdoc.
     *
     * @param string $sdoc  A menu_sdoc setting ('' or 'parent').
     * @param string $class A CSS class.
     *
     * @return void
     *
     * @global int   The index of the selected page.
     * @global array The configuration of the core.
     *
     * @dataProvider dataForParentOfSelectedPageHasClassDependingOnSdoc
     */
    public function testParentOfSelectedPageHasClassDependingOnSdoc($sdoc, $class)
    {
        global $s, $cf;

        $s = 2;
        $cf['menu']['sdoc'] = $sdoc;
        $this->assertStringContainsString(
            "<li class=\"$class blog\"><a href=\"?Blog\">Blog</a>",
            $this->renderAllPages()
        );
    }

    /**
     * Provides data for testParentOfSelectedPageHasClassDependingOnSdoc().
     *
     * @return array
     */
    public function dataForParentOfSelectedPageHasClassDependingOnSdoc()
    {
        return array(
            array('parent', 'sdocs'),
            array('', 'docs')
        );
    }

    /**
     * Tests that a first level page with a third level child has a class
     * depending on menu_levelcatch.
     *
     * @param string $levelcatch A menu_levelcatch setting.
     * @param string $class      A CSS class.
     *
     * @return void
     *
     * @global array The configuration of the core.
     *
     * @dataProvider dataForH1WithH3HasClassDependingOnLevelcatch
     */
    public function testH1WithH3HasClassDependingOnLevelcatch($levelcatch, $class)
    {
        global $cf;

        $cf['menu']['levelcatch'] = $levelcatch;
        $this->assertStringContainsString(
            "<li class=\"$class\"><a href=\"?About\">About</a>",
            $this->renderAllPages()
        );
    }

    /**
     * Provides data for testH1WithH3HasClassDependingOnLevelcatch().
     *
     * @return array
     */
    public function dataForH1WithH3HasClassDependingOnLevelcatch()
    {
        return array(
            array('10', 'docs'),
            array('0', 'doc')
        );
    }

    /**
     * Tests that a page opens in a new window when in normal mode.
     *
     * @return void
     */
    public function testPageOpensInNewWindowInNormalMode()
    {
        Approvals::verifyHtml($this->renderAllPages());
    }

    /**
     * Tests that a page doesn't open in a new window when in edit mode.
     *
     * @return void
     */
    public function testPageDoesntOpenInNewWindowInEditMode()
    {
        $this->setUpEditMode(true);
        Approvals::verifyHtml($this->renderAllPages());
    }

    /**
     * Returns the rendering of all pages.
     *
     * @param mixed $forOrFrom A li() view kind or the start level.
     *
     * @return string (X)HTML.
     */
    protected function renderAllPages($forOrFrom = 1)
    {
        return (new LiCommand(range(0, 10), $forOrFrom))->render();
    }

    /**
     * Tests that the "Blog" submenu has exactly three items.
     *
     * @return void
     *
     * @global int The index of the selected page.
     */
    public function testBlogSubmenuHasExactlyThreeItems()
    {
        global $s;

        $s = 1;
        Approvals::verifyHtml((new LiCommand(array(2, 4, 6), 'submenu'))->render());
    }

    /**
     * Tests that the "Blog" submenu has the proper structure.
     *
     * @return void
     */
    public function testBlogSubmenuHasProperStructure()
    {
        Approvals::verifyHtml((new LiCommand(array(2, 4, 6), 'submenu'))->render());
    }
}

?>
