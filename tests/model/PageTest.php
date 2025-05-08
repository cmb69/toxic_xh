<?php

namespace Toxic\Model;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;
use XH\Pages as XHPages;
use XH\Publisher;

class PageTest extends TestCase
{
    /** @var XHPages&Stub */
    private $pages;

    /** @var Publisher&Stub */
    private $publisher;

    /** @var PageDataRouter&Stub */
    private $pageData;

    public function setUp(): void
    {
        $this->pages = $this->createStub(XHPages::class);
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
        $this->publisher = $this->createStub(Publisher::class);
        $this->pageData = $this->createStub(PageDataRouter::class);
    }

    private function pages(): Pages
    {
        return new Pages($this->pages, $this->publisher, $this->pageData);
    }

    public function testFromTocArray(): void
    {
        $page = Page::fromTocArray(range(0, 10), 1, $this->pages());
        $expected = new Page(0);
        $p = $expected->addSibling(1)->addChild(2);
        $p->addChild(3);
        $p = $p->addSibling(4);
        $p->addChild(5);
        $p->addSibling(6)->addChild(7);
        $expected->next()->addSibling(8)->addChild(null)->addChild(9);
        $expected->next()->next()->addSibling(10);
        $this->assertEquals($expected, $page);
    }
}
