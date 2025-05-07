<?php

namespace Toxic;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;

class TabCommandTest extends TestCase
{
    /** @var array<string,string> */
    private $conf;

    public function setUp(): void
    {
        global $plugin_tx;

        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["toxic"];
        $plugin_tx = XH_includeVar("./languages/en.php", "plugin_tx");
    }

    private function sut(): TabCommand
    {
        $pageData = array('toxic_class' => 'test', "toxic_category" => "");
        return new TabCommand($this->conf, $pageData);
    }

    public function testRendersForm(): void
    {
        $request = new FakeRequest(["url" => "http://example.com/xh/?Welcome"]);
        Approvals::verifyHtml($this->sut()->render($request));
    }

    public function testRendersClassInput(): void
    {
        $this->conf['classes_available'] = '';
        $request = new FakeRequest(["url" => "http://example.com/xh/?Welcome"]);
        Approvals::verifyHtml($this->sut()->render($request));
    }

    public function testRendersClassSelect(): void
    {
        $this->conf['classes_available'] = 'one,two,three,test';
        $request = new FakeRequest(["url" => "http://example.com/xh/?Welcome"]);
        Approvals::verifyHtml($this->sut()->render($request));
    }

    public function testRendersSubmitButton(): void
    {
        $request = new FakeRequest(["url" => "http://example.com/xh/?Welcome"]);
        Approvals::verifyHtml($this->sut()->render($request));
    }
}
