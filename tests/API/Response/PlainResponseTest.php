<?php
namespace Tests\API\Response;

use PHPUnit\Framework\TestCase;
use Clivern\Monkey\API\Request\ContentType;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Response\PlainResponse;
use Clivern\Monkey\API\Request\ResponseType;
use Clivern\Monkey\API\DumpType;

/**
 * Plain Response Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Response
 */
class PlainResponseTest extends TestCase {

    private $response;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->response = new PlainResponse("\Clivern::Method", ["key" => "value"]);
    }

    /**
     * Test All Methods
     */
    public function testAll()
    {
        $this->assertEquals("Clivern\Monkey\API\Response\PlainResponse", get_class($this->response->setResponse(["incoming" => "data"])));
        $this->assertEquals(["incoming" => "data"], $this->response->getResponse());
        $this->assertEquals("Clivern\Monkey\API\Response\PlainResponse", get_class($this->response->setCallback("\Clivern::Method", ["key" => "new_value"])));
        $this->assertEquals(["method" => "\Clivern::Method", "arguments" => ["key" => "new_value"]], $this->response->getCallback());
        $this->assertEquals("Clivern\Monkey\API\Response\PlainResponse", get_class($this->response->setAsyncJob(["job" => "async", "id" => 20])));
        $this->assertEquals("Clivern\Monkey\API\Response\PlainResponse", get_class($this->response->setAsyncJobId(300)));
        $this->assertEquals("Clivern\Monkey\API\Response\PlainResponse", get_class($this->response->setError(["parsed" => ["id" => "M200", "message" => "hello"], "plain" => "Error! Bla Bla.", "code" => "M200", "message" => "Hey I am Error"])));
        $this->assertEquals("Clivern\Monkey\API\Response\PlainResponse", get_class($this->response->addItem("user", "john")));
        $this->assertTrue($this->response->itemExists("user"));
        $this->assertEquals($this->response->getAsyncJob(), ["job" => "async", "id" => 20]);
        $this->assertEquals($this->response->getAsyncJobId(), 300);
        $this->assertEquals($this->response->getItem("user"), "john");
        $this->assertEquals($this->response->getError(), ["parsed" => ["id" => "M200", "message" => "hello"], "plain" => "Error! Bla Bla.", "code" => "M200", "message" => "Hey I am Error"]);
        $this->assertEquals($this->response->getPlainError(), "Error! Bla Bla.");
        $this->assertEquals($this->response->getParsedError(), ["id" => "M200", "message" => "hello"]);
        $this->assertEquals($this->response->getErrorCode(), "M200");
        $this->assertEquals($this->response->getErrorMessage(), "Hey I am Error");
        $this->assertEquals($this->response->dump(DumpType::$ARRAY), [
            "response" => ["incoming" => "data"],
            "asyncJob" => ["job" => "async", "id" => 20],
            "asyncJobId" => 300,
            "callback" => ["method" => "\Clivern::Method", "arguments" => ["key"=> "new_value"]],
            "items" => ["user" => "john"],
            "error" => ["parsed" => ["id" => "M200", "message" => "hello"], "plain" => "Error! Bla Bla.", "code"=> "M200", "message" => "Hey I am Error"]
        ]);
        $this->assertEquals($this->response->dump(DumpType::$JSON), '{"response":{"incoming":"data"},"asyncJob":{"job":"async","id":20},"asyncJobId":300,"callback":{"method":"\\\Clivern::Method","arguments":{"key":"new_value"}},"items":{"user":"john"},"error":{"parsed":{"id":"M200","message":"hello"},"plain":"Error! Bla Bla.","code":"M200","message":"Hey I am Error"}}');
    }
}