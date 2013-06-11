<?php

namespace Framework\Tests;

use Symfony\Component\HttpFoundation\Request;

class FrontTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        ob_end_clean();
        unset($_GLOBALS);
        parent::tearDown();
    }

    public function testHelloRouteDefaultsToWorld()
    {
        $request = Request::create('/hello');
        include realpath(APPLICATION_PATH . '/public/front.php');
        $body = ob_get_contents();
        $this->assertContains('Hello World', $body);
    }

    public function testHelloRouteJeremy()
    {
        $request = Request::create('/hello/Jeremy');
        include realpath(APPLICATION_PATH . '/public/front.php');
        $body = ob_get_contents();
        $this->assertContains('Hello Jeremy', $body);
    }
}
