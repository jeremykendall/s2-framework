<?php

namespace Framework\Tests;

use Framework\Event\ResponseEvent;
use Framework\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends \PHPUnit_Framework_TestCase
{
    protected $dispatcher;
    protected $matcher;
    protected $resolver;
    protected $request;

    protected function setUp()
    {
        parent::setUp();
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $this->matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $this->resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
        $this->request = new Request();
    }

    public function testInstanceOf()
    {
        $framework = new Framework($this->dispatcher, $this->matcher, $this->resolver);
        $this->assertInstanceOf('Symfony\Component\HttpKernel\HttpKernelInterface', $framework);
    }

    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $response = $framework->handle($this->request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $response = $framework->handle($this->request);

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testControllerResponse()
    {
        $controller = function ($name) {
            return new Response('Hello, ' . $name);
        };

        $this->matcher->expects($this->once())
            ->method('match')
            ->will($this->returnValue(array(
                '_route' => 'foo',
                'name' => 'Jeremy',
                '_controller' => $controller
        )));

        $this->resolver->expects($this->once())
            ->method('getController')
            ->will($this->returnValue($controller));

        $this->resolver->expects($this->once())
            ->method('getArguments')
            ->will($this->returnValue(array(
                'Jeremy',
            )));

        $resolver = new ControllerResolver();
        $framework = new Framework($this->dispatcher, $this->matcher, $this->resolver);

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $response = $framework->handle($this->request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello, Jeremy', $response->getContent());
    }

    public function getFrameworkForException(\Exception $exception)
    {
        $this->matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;

        $this->resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');

        return new Framework($this->dispatcher, $this->matcher, $this->resolver);
    }
}
