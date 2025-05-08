<?php

namespace Toxic;

use ApprovalTests\Approvals;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;
use XH\PageDataRouter;
use XH\Pages;
use XH\Publisher;

class SubmenuCommandTest extends TestCase
{
    /** @var array<string,string> */
    private $conf;

    /** @var Pages&Stub */
    private $pages;

    /** @var View */
    private $view;

    /** @var Publisher&Stub */
    private $publisher;

    /** @var PageDataRouter&Stub */
    private $pageData;

    /** @var LiCommand */
    private $liCommand;

    public function setUp(): void
    {
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["toxic"];
        $this->conf["menu_levelcatch"] = "10";
        $this->conf["menu_levels"] = "3";
        $this->pages = $this->createStub(Pages::class);
        $this->setUpPageStructure();
        $lang = XH_includeVar("./languages/en.php", "plugin_tx")["toxic"];
        $lang["submenu_heading"] = "Submenu";
        $this->view = new View("./views/", $lang);
        $this->publisher = $this->createStub(Publisher::class);
        $this->pageData = $this->createStub(PageDataRouter::class);
        $this->pageData->method("find_page")->willReturnCallback(function ($pageIndex) {
            return [
                "toxic_class" => ($pageIndex >= 1 && $pageIndex <= 7) ? "blog" : "",
                "toxic_category" => ($pageIndex === 8) ? "About category" : "",
                "use_header_location" => ($pageIndex == 7) ? "2" : "0"
            ];
        });
        $this->liCommand = new LiCommand($this->pages, $this->publisher, $this->pageData);
        $this->setUpConfiguration();
    }

    private function setUpPageStructure(): void
    {
        $this->pages->method("heading")->willReturnMap([
            [0, 'Welcome'],
            [1, 'Blog'],
            [2, 'July'],
            [3, 'Hot'],
            [4, 'Hidden'],
            [5, 'AlsoHidden'],
            [6, 'January'],
            [7, 'Cold'],
            [8, 'About'],
            [9, 'Contact'],
            [10, 'News'],
        ]);
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
        $this->pages->method("level")->willReturnMap([
            [0, 1],
            [1, 1],
            [2, 2],
            [3, 3],
            [4, 2],
            [5, 3],
            [6, 2],
            [7, 3],
            [8, 1],
            [9, 3],
            [10, 1],
        ]);
        $this->pages->method("getCount")->willReturn(11);
    }

    private function setUpConfiguration(): void
    {
        global $cf;

        $cf = [
            'locator' => ['show_homepage' => 'true'],
            'menu' => [
                'levelcatch' => '10',
                'levels' => '3',
                'sdoc' => 'parent'
            ],
            'show_hidden' => [
                'pages_toc' => 'true'
            ],
            'uri' => [
                'seperator' => ':'
            ],
        ];
    }

    private function sut(): SubmenuCommand
    {
        return new SubmenuCommand($this->conf, $this->pages, $this->view, $this->liCommand);
    }

    public function testDoesNotRenderEmptySubmenu(): void
    {
        $request = new FakeRequest(["s" => 0]);
        $response = $this->sut()($request, "");
        $this->assertSame("", $response->output());
    }

    public function testRendersSubmenu(): void
    {
        $request = new FakeRequest(["s" => 1]);
        $response = $this->sut()($request, "");
        Approvals::verifyHtml($response->output());
    }

    public function testRendersCustomSubmenuHeading(): void
    {
        $request = new FakeRequest(["s" => 1]);
        $response = $this->sut()($request, "<h4 class=\"submenu_heading\">%s</h4>");
        $this->assertStringContainsString("<h4 class=\"submenu_heading\">Submenu</h4>", $response->output());
    }
}
