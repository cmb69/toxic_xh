<?php

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

require_once './vendor/autoload.php';
require_once '../../cmsimple/functions.php';
require_once '../../cmsimple/classes/PageDataRouter.php';
require_once './classes/Presentation.php';

/**
 * Testing li().
 *
 * @category Testing
 * @package  Toxic
 * @author   The CMSimple_XH developers <devs@cmsimple-xh.org>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Toxic_XH
 */
class LiTest extends PHPUnit_Framework_TestCase
{
    private $_liStub;

    private $_hideStub;

    /**
     * Sets up the default fixture.
     *
     * @return void
     *
     * @global int The index of the selected page.
     */
    public function setUp()
    {
        global $pth, $s;

        $pth = array('folder' => array('classes' => './cmsimple/classes/'));
        $s = 0;
        $this->_setUpPageStructure();
        $this->_setUpConfiguration();
        $this->_setUpEditMode(false);
        $this->_setUpPageDataRouterMock();
        $this->_setUpFunctionStubs();
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
    private function _setUpPageStructure()
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
    private function _setUpConfiguration()
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
    private function _setUpEditMode($flag)
    {
        global $edit;

        if (defined('XH_ADM')) {
            runkit_constant_redefine('XH_ADM', $flag);
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
    private function _setUpPageDataRouterMock()
    {
        global $pd_router;

        $pd_router = $this->getMockBuilder('XH_PageDataRouter')
            ->disableOriginalConstructor()->getMock();
        $pd_router->expects($this->any())->method('find_page')->will(
            $this->returnCallback(
                function ($pageIndex) {
                    return array(
                        'toxic_class' => ($pageIndex >= 1 && $pageIndex <= 7)
                            ? 'blog' : '',
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
    private function _setUpFunctionStubs()
    {
        $this->_liStub = new PHPUnit_Extensions_MockFunction('a', $this);
        $this->_liStub->expects($this->any())->will(
            $this->returnCallback(
                function ($pageIndex, $suffix) {
                    global $u;

                    return '<a href="?' . $u[$pageIndex] . $suffix . '">';
                }
            )
        );
        $this->_hideStub = new PHPUnit_Extensions_MockFunction('hide', $this);
        $this->_hideStub->expects($this->any())->will(
            $this->returnCallback(
                function ($pageIndex) {
                    return in_array($pageIndex, array(4, 5));
                }
            )
        );
    }

    /**
     * Tears down the test fixture.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->_liStub->restore();
        $this->_hideStub->restore();
    }

    /**
     * Tests that no menu items display nothing.
     *
     * @return void
     */
    public function testNoMenuItemsDisplayNothing()
    {
        $this->assertEmpty((new Toxic_LiCommand(array(), 1))->render());
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
        $matcher = array(
            'tag' => 'ul',
            'attributes' => array('class' => $class),
            'child' => array(
                'tag' => 'li'
            )
        );
        $this->_assertMatches($matcher);
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
        $matcher = array(
            'tag' => 'li',
            'child' => array(
                'tag' => 'ul',
                'attributes' => array('class' => $class)
            )
        );
        $this->_assertMatches($matcher);
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
        $matcher = array(
            'tag' => 'span',
            'content' => 'Welcome'
        );
        $this->_assertMatches($matcher);
    }

    /**
     * Tests that a not selected page is marked up as ANCHOR.
     *
     * @return void
     */
    public function testNotSelectedPageHasAnchor()
    {
        $matcher = array(
            'tag' => 'a',
            'content' => 'Blog'
        );
        $this->_assertMatches($matcher);
    }

    /**
     * Asserts that the rendering of all pages matches a matcher.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     */
    private function _assertMatches($matcher)
    {
        @$this->assertTag($matcher, $this->_renderAllPages());
    }

    /**
     * Tests that a LI without visible children has the class "doc".
     *
     * @return void
     */
    public function testLiWithoutVisibleChilrenHasClassDoc()
    {
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => 'doc'),
            'child' => array(
                'tag' => 'a',
                'content' => 'Hidden'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
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
        $matcher = array(
            'tag' => 'ul',
            'attributes' => array('class' => $class)
        );
        @$this->assertTag($matcher, $this->_renderAllPages($forOrFrom));
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
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => 'sdocs'),
            'child' => array(
                'tag' => 'span',
                'content' => 'Blog'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
    }

    /**
     * Tests that a selected childless page has the class "doc".
     *
     * @return void.
     */
    public function testSelectedChildlessPageHasClassSdoc()
    {
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => 'sdoc'),
            'child' => array(
                'tag' => 'span',
                'content' => 'Welcome'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
    }

    /**
     * Tests that a not selected page with children has the class "docs".
     *
     * @return void.
     */
    public function testNotSelectedPageHasClassDocs()
    {
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => 'docs'),
            'child' => array(
                'tag' => 'a',
                'content' => 'Blog'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
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
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => 'doc'),
            'child' => array(
                'tag' => 'a',
                'content' => 'Welcome'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
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
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => $class),
            'child' => array(
                'tag' => 'a',
                'content' => 'Blog'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
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
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => $class),
            'child' => array(
                'tag' => 'a',
                'content' => 'About'
            )
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
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
        $matcher = array(
            'tag' => 'a',
            'content' => 'Cold',
            'attributes' => array('target' => '_blank')
        );
        @$this->assertTag($matcher, $this->_renderAllPages());
    }

    /**
     * Tests that a page doesn't open in a new window when in edit mode.
     *
     * @return void
     */
    public function testPageDoesntOpenInNewWindowInEditMode()
    {
        $this->_setUpEditMode(true);
        $matcher = array(
            'tag' => 'a',
            'content' => 'Cold',
            'attributes' => array('target' => '_blank')
        );
        @$this->assertNotTag($matcher, $this->_renderAllPages());
    }

    /**
     * Returns the rendering of all pages.
     *
     * @param mixed $forOrFrom A li() view kind or the start level.
     *
     * @return string (X)HTML.
     */
    private function _renderAllPages($forOrFrom = 1)
    {
        return (new Toxic_LiCommand(range(0, 10), $forOrFrom))->render();
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
        $matcher = array(
            'tag' => 'ul',
            'children' => array(
                'count' => 3,
                'only' => array(
                    'tag' => 'li'
                )
            )
        );
        @$this->assertTag(
            $matcher,
            (new Toxic_LiCommand(array(2, 4, 6), 'submenu'))->render()
        );
    }

    /**
     * Tests that the "Blog" submenu has the proper structure.
     *
     * @return void
     */
    public function testBlogSubmenuHasProperStructure()
    {
        $matcher = array(
            'tag' => 'li',
            'attributes' => array('class' => 'docs'),
            'child' => array(
                'tag' => 'a',
                'attributes' => array('href' => '?Blog:July'),
                'content' => 'July'
            ),
            'parent' => array(
                'tag' => 'ul',
                'class' => 'submenu'
            )
        );
        @$this->assertTag(
            $matcher,
            (new Toxic_LiCommand(array(2, 4, 6), 'submenu'))->render()
        );
    }
}

?>
