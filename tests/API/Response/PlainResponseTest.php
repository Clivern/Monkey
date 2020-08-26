<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API\Response;

use Clivern\Monkey\API\DumpType;
use Clivern\Monkey\API\Response\PlainResponse;
use PHPUnit\Framework\TestCase;

/**
 * Plain Response Class Test.
 *
 * @since 1.0.0
 */
class PlainResponseTest extends TestCase
{
    private $response;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->response = new PlainResponse("\Clivern::Method", ['key' => 'value']);
    }

    /**
     * Test All Methods.
     */
    public function testAll()
    {
        $this->assertSame(
            "Clivern\Monkey\API\Response\PlainResponse",
            \get_class($this->response->setResponse(['incoming' => 'data']))
        );
        $this->assertSame(['incoming' => 'data'], $this->response->getResponse());
        $this->assertSame(
            "Clivern\Monkey\API\Response\PlainResponse",
            \get_class($this->response->setCallback("\Clivern::Method", ['key' => 'new_value']))
        );
        $this->assertSame(
            ['method' => "\Clivern::Method", 'arguments' => ['key' => 'new_value']],
            $this->response->getCallback()
        );
        $this->assertSame(
            "Clivern\Monkey\API\Response\PlainResponse",
            \get_class($this->response->setAsyncJob(['job' => 'async', 'id' => 20]))
        );
        $this->assertSame(
            "Clivern\Monkey\API\Response\PlainResponse",
            \get_class($this->response->setAsyncJobId(300))
        );
        $this->assertSame(
            "Clivern\Monkey\API\Response\PlainResponse",
            \get_class($this->response->setError([
                'parsed' => ['id' => 'M200', 'message' => 'hello'],
                'plain' => 'Error! Bla Bla.',
                'code' => 'M200',
                'message' => 'Hey I am Error',
            ]))
        );
        $this->assertSame(
            "Clivern\Monkey\API\Response\PlainResponse",
            \get_class($this->response->addItem('user', 'john'))
        );
        $this->assertTrue($this->response->itemExists('user'));
        $this->assertSame($this->response->getAsyncJob(), ['job' => 'async', 'id' => 20]);
        $this->assertSame($this->response->getAsyncJobId(), 300);
        $this->assertSame($this->response->getItem('user'), 'john');
        $this->assertSame(
            $this->response->getError(),
            [
                'parsed' => ['id' => 'M200', 'message' => 'hello'],
                'plain' => 'Error! Bla Bla.', 'code' => 'M200', 'message' => 'Hey I am Error',
            ]
        );
        $this->assertSame($this->response->getPlainError(), 'Error! Bla Bla.');
        $this->assertSame($this->response->getParsedError(), ['id' => 'M200', 'message' => 'hello']);
        $this->assertSame($this->response->getErrorCode(), 'M200');
        $this->assertSame($this->response->getErrorMessage(), 'Hey I am Error');
        $this->assertSame($this->response->dump(DumpType::$ARRAY), [
            'response' => ['incoming' => 'data'],
            'asyncJob' => ['job' => 'async', 'id' => 20],
            'asyncJobId' => 300,
            'callback' => ['method' => "\Clivern::Method", 'arguments' => ['key' => 'new_value']],
            'items' => ['user' => 'john'],
            'error' => [
                'parsed' => ['id' => 'M200', 'message' => 'hello'],
                'plain' => 'Error! Bla Bla.', 'code' => 'M200', 'message' => 'Hey I am Error',
            ],
        ]);
        $this->assertSame(
            $this->response->dump(DumpType::$JSON),
            '{"response":{"incoming":"data"},"asyncJob":{"job":"async","id":20}'.
            ',"asyncJobId":300,"callback":{"method":"\\\Clivern::Method","arguments":'.
            '{"key":"new_value"}},"items":{"user":"john"},"error":{"parsed":'.
            '{"id":"M200","message":"hello"},"plain":"Error! Bla Bla.","code":"M200","message":"Hey I am Error"}}'
        );
    }
}
