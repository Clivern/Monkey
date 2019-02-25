<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API\Request;

use Clivern\Monkey\API\DumpType;
use Clivern\Monkey\API\Request\ContentType;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Request\ResponseType;
use PHPUnit\Framework\TestCase;

/**
 * Plain Request Class Test.
 *
 * @since 1.0.0
 */
class PlainRequestTest extends TestCase
{
    private $request;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        $this->request = new PlainRequest();
        $this->request->setMethod(RequestMethod::$GET)
                ->setType(RequestType::$ASYNCHRONOUS)
                ->addHeader('Content-Type', ContentType::$JSON)
                ->addHeader('AUTH-TOKEN', 'api_token_here')
                ->addParameter('token', 'foo')
                ->addParameter('id', 20)
                ->addParameter('response', ResponseType::$JSON)
                ->addItem('title', 'Hello World')
                ->addItem('tags', [['tag' => 'HTML'], ['tag' => 'PHP'], ['tag' => 'CSS']])
                ->addItem('author', 'Clivern');
    }

    /**
     * Test Class.
     */
    public function testClass()
    {
        $this->assertSame("Clivern\Monkey\API\Request\PlainRequest", \get_class(new PlainRequest()));
    }

    /**
     * Test Method.
     */
    public function testMethod()
    {
        $this->assertSame($this->request->getMethod(), RequestMethod::$GET);
    }

    /**
     * Test Type.
     */
    public function testType()
    {
        $this->assertSame($this->request->getType(), RequestType::$ASYNCHRONOUS);
    }

    /**
     * Test Items.
     */
    public function testItems()
    {
        $this->assertSame($this->request->getItem('title'), 'Hello World');
        $this->assertSame(
            $this->request->getItems(DumpType::$JSON),
            json_encode(
                [
                    'title' => 'Hello World',
                    'tags' => [['tag' => 'HTML'], ['tag' => 'PHP'], ['tag' => 'CSS']],
                    'author' => 'Clivern',
                ]
            )
        );
        $this->assertSame(
            $this->request->getItems(DumpType::$ARRAY),
            [
                'title' => 'Hello World',
                'tags' => [['tag' => 'HTML'], ['tag' => 'PHP'], ['tag' => 'CSS']],
                'author' => 'Clivern',
            ]
        );
        $this->assertSame(
            $this->request->getBody(DumpType::$JSON),
            json_encode([
                'title' => 'Hello World',
                'tags' => [['tag' => 'HTML'], ['tag' => 'PHP'], ['tag' => 'CSS']],
                'author' => 'Clivern',
            ])
        );
        $this->assertSame(
            $this->request->getBody(DumpType::$ARRAY),
            [
                'title' => 'Hello World',
                'tags' => [['tag' => 'HTML'], ['tag' => 'PHP'], ['tag' => 'CSS']],
                'author' => 'Clivern',
            ]
        );
        $this->assertTrue($this->request->itemExists('title'));
    }

    /**
     * Test Parameter.
     */
    public function testParameter()
    {
        $this->assertSame($this->request->getParameter('token'), 'foo');
        $this->assertSame(
            $this->request->getParameters(),
            ['token' => 'foo', 'id' => 20, 'response' => ResponseType::$JSON]
        );
        $this->assertTrue($this->request->parameterExists('token'));
    }

    /**
     * Test Header.
     */
    public function testHeader()
    {
        $this->assertSame($this->request->getHeader('Content-Type'), ContentType::$JSON);
        $this->assertSame(
            $this->request->getHeaders(),
            ['Content-Type' => ContentType::$JSON, 'AUTH-TOKEN' => 'api_token_here']
        );
        $this->assertTrue($this->request->headerExists('Content-Type'));
    }

    /**
     * Test Dump.
     */
    public function testDump()
    {
        $this->assertSame($this->request->dump(DumpType::$ARRAY), [
            'method' => 'GET',
            'parameters' => [
                'token' => 'foo',
                'id' => 20,
                'response' => 'json',
            ],
            'items' => [
                'title' => 'Hello World',
                'tags' => [['tag' => 'HTML'], ['tag' => 'PHP'], ['tag' => 'CSS']],
                'author' => 'Clivern',
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'AUTH-TOKEN' => 'api_token_here',
            ],
            'type' => 'ASYNCHRONOUS',
        ]);

        $this->assertSame(
            $this->request->dump(DumpType::$JSON),
            '{"method":"GET","parameters":{"token":"foo","id":20,"response":"json"},'.
            '"items":{"title":"Hello World","tags":[{"tag":"HTML"},{"tag":"PHP"},'.
            '{"tag":"CSS"}],"author":"Clivern"},"headers":{"Content-Type":"application\/json",'.
            '"AUTH-TOKEN":"api_token_here"},"type":"ASYNCHRONOUS"}'
        );
    }

    /**
     * Test Debug.
     */
    public function testDebug()
    {
        $this->assertSame(
            $this->request->debug(),
            'curl -X GET -H "Content-Type: application/json" -H "AUTH-TOKEN: '.
            "api_token_here\" -d '{\"title\":\"Hello World\",\"tags\":[{\"tag\":\"HTML\"},".
            "{\"tag\":\"PHP\"},{\"tag\":\"CSS\"}],\"author\":\"Clivern\"}'".
            ' "https://example.com?token=foo&id=20&response=json"'
        );
    }

    /**
     * Test Reload.
     */
    public function testReload()
    {
        $this->request->reload($this->request->dump(DumpType::$JSON), DumpType::$JSON);
        $this->request->addParameter('new', 'value');
        $this->assertSame(
            $this->request->dump(DumpType::$JSON),
            '{"method":"GET","parameters":{"token":"foo","id":20,"response":"json","new":"value"}'.
            ',"items":{"title":"Hello World","tags":[{"tag":"HTML"},{"tag":"PHP"},{"tag":"CSS"}]'.
            ',"author":"Clivern"},"headers":{"Content-Type":"application\/json","AUTH-TOKEN":"api_token_here"}'.
            ',"type":"ASYNCHRONOUS"}'
        );
    }
}
