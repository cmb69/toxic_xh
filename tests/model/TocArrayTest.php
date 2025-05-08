<?php

namespace Toxic\Model;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use XH\Pages;

class TocArrayTest extends TestCase
{
    /** @var Pages&Stub */
    private $pages;

    public function setUp(): void
    {
        $this->pages = $this->createStub(Pages::class);
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

    /** @dataProvider submenuData */
    public function testSubmenu(int $page, int $levelCatch, array $expected): void
    {
        $this->assertEquals($expected, TocArray::submenu($this->pages, $page, $levelCatch));
    }

    public function submenuData(): array
    {
        return [
            [0, 10, []],
            [1, 10, [2, 4, 6]],
        ];
    }
}
