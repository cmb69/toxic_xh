<?php

namespace Toxic;

use ApprovalTests\Approvals;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use XH\PageDataRouter;
use XH\Pages;
use XH\Publisher;

class LiTest extends TestCase
{
    /** @var Pages&Stub */
    private $pages;

    /** @var Publisher&Stub */
    private $publisher;

    public function setUp(): void
    {
        global $pth, $s;

        $pth = array('folder' => array('classes' => './cmsimple/classes/'));
        $s = 0;
        $this->pages = $this->createStub(Pages::class);
        $this->publisher = $this->createStub(Publisher::class);
        $this->setUpPageStructure();
        $this->setUpConfiguration();
        $this->setUpPageDataRouterMock();
        $this->setUpFunctionStubs();
    }

    private function setUpPageStructure(): void
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
        $this->pages->method("url")->willReturnMap([
            [0, 'Welcome'],
            [1, 'Blog'],
            [2, 'Blog:July'],
            [3, 'Blog:July:Hot'],
            [4, 'Blog:Hidden'],
            [5, 'Blog:Hidden:AlsoHidden'],
            [6, 'Blog:January'],
            [7, 'Blog:January:Cold'],
            [8, 'About'],
            [9, 'About:Contact'],
            [10, 'News'],
        ]);
        $l = array(1, 1, 2, 3, 2, 3, 2, 3, 1, 3, 1);
        $cl = count($u);
    }

    private function setUpConfiguration(): void
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

    private function setUpPageDataRouterMock(): void
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

    private function setUpFunctionStubs(): void
    {
        $this->publisher->method("getFirstPublishedPage")->willReturn(1000);
        $this->pages->method("isHidden")->willReturnCallback(function ($pageIndex) {
            return in_array($pageIndex, array(4, 5));
        });
    }

    public function testNoMenuItemsDisplayNothing(): void
    {
        $this->assertEmpty((new LiCommand($this->pages, $this->publisher, array(), 1))->render(new FakeRequest()));
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
            "<li class=\"doc blog\"><a href=\"/?Blog:Hidden\">Hidden</a>",
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
            "<li class=\"docs blog\"><a href=\"/?Blog\">Blog</a>",
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
            "<li class=\"$class blog\"><a href=\"/?Blog\">Blog</a>",
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
            "<li class=\"$class\"><a href=\"/?About\">About</a>",
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
        $request = new FakeRequest(["admin" => true, "edit" => true]);
        $response = (new LiCommand($this->pages, $this->publisher, range(0, 10), 1))->render($request);
        Approvals::verifyHtml($response);
    }

    /** @param mixed $forOrFrom */
    private function renderAllPages($forOrFrom = 1): string
    {
        return (new LiCommand($this->pages, $this->publisher, range(0, 10), $forOrFrom))->render(new FakeRequest());
    }

    public function testBlogSubmenuHasExactlyThreeItems(): void
    {
        global $s;

        $s = 1;
        Approvals::verifyHtml((new LiCommand($this->pages, $this->publisher, array(2, 4, 6), 'submenu'))->render(new FakeRequest()));
    }

    public function testBlogSubmenuHasProperStructure(): void
    {
        Approvals::verifyHtml((new LiCommand($this->pages, $this->publisher, array(2, 4, 6), 'submenu'))->render(new FakeRequest()));
    }
}
