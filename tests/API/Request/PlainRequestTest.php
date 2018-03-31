<?php
namespace Tests\API\Request;

use PHPUnit\Framework\TestCase;
use Clivern\Monkey\API\Request\ContentType;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Request\ResponseType;
use Clivern\Monkey\API\DumpType;

/**
 * Plain Request Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Request
 */
class PlainRequestTest extends TestCase {

    private $request;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->request = new PlainRequest();
        $this->request->setMethod(RequestMethod::$GET)
                ->setType(RequestType::$ASYNCHRONOUS)
                ->addHeader("Content-Type", ContentType::$JSON)
                ->addHeader("AUTH-TOKEN", "api_token_here")
                ->addParameter("token", "foo")
                ->addParameter("id", 20)
                ->addParameter("response", ResponseType::$JSON)
                ->addItem("title", "Hello World")
                ->addItem("tags", [["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]])
                ->addItem("author", "Clivern");
    }

    /**
     * Test Class
     */
    public function testClass()
    {
        $this->assertEquals("Clivern\Monkey\API\Request\PlainRequest", get_class(new PlainRequest()));
    }

    /**
     * Test Method
     */
    public function testMethod()
    {
        $this->assertEquals($this->request->getMethod(), RequestMethod::$GET);
    }

    /**
     * Test Type
     */
    public function testType()
    {
        $this->assertEquals($this->request->getType(), RequestType::$ASYNCHRONOUS);
    }

    /**
     * Test Items
     */
    public function testItems()
    {
        $this->assertEquals($this->request->getItem("title"), "Hello World");
        $this->assertEquals($this->request->getItems(DumpType::$JSON), json_encode(["title"=>"Hello World","tags" =>[["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]],"author"=>"Clivern"]));
        $this->assertEquals($this->request->getItems(DumpType::$ARRAY), ["title"=>"Hello World","tags" =>[["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]],"author"=>"Clivern"]);
        $this->assertEquals($this->request->getBody(DumpType::$JSON), json_encode(["title"=>"Hello World","tags" =>[["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]],"author"=>"Clivern"]));
        $this->assertEquals($this->request->getBody(DumpType::$ARRAY), ["title"=>"Hello World","tags" =>[["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]],"author"=>"Clivern"]);
        $this->assertTrue($this->request->itemExists("title"));
    }

    /**
     * Test Parameter
     */
    public function testParameter()
    {
        $this->assertEquals($this->request->getParameter("token"), "foo");
        $this->assertEquals($this->request->getParameters(), ["token"=>"foo","id"=>20,"response"=>ResponseType::$JSON]);
        $this->assertTrue($this->request->parameterExists("token"));
    }

    /**
     * Test Header
     */
    public function testHeader()
    {
        $this->assertEquals($this->request->getHeader("Content-Type"), ContentType::$JSON);
        $this->assertEquals($this->request->getHeaders(), ["Content-Type"=>ContentType::$JSON, "AUTH-TOKEN"=>"api_token_here"]);
        $this->assertTrue($this->request->headerExists("Content-Type"));
    }

    /**
     * Test Dump
     */
    public function testDump()
    {
        $this->assertEquals($this->request->dump(DumpType::$ARRAY), [
            "method" => "GET",
            "parameters" => [
                "token" => "foo",
                "id" => 20,
                "response" => "json"
            ],
            "items"=> [
                "title"=> "Hello World",
                "tags" => [["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]],
                "author" => "Clivern"
            ],
            "headers" => [
                "Content-Type" => "application/json",
                "AUTH-TOKEN" => "api_token_here"
            ],
            "type" => "ASYNCHRONOUS"
        ]);

        $this->assertEquals($this->request->dump(DumpType::$JSON), '{"method":"GET","parameters":{"token":"foo","id":20,"response":"json"},"items":{"title":"Hello World","tags":[{"tag":"HTML"},{"tag":"PHP"},{"tag":"CSS"}],"author":"Clivern"},"headers":{"Content-Type":"application\/json","AUTH-TOKEN":"api_token_here"},"type":"ASYNCHRONOUS"}');
    }

    /**
     * Test Debug
     */
    public function testDebug()
    {
        $this->assertEquals($this->request->debug(), "curl -X GET -H \"Content-Type: application/json\" -H \"AUTH-TOKEN: api_token_here\" -d '{\"title\":\"Hello World\",\"tags\":[{\"tag\":\"HTML\"},{\"tag\":\"PHP\"},{\"tag\":\"CSS\"}],\"author\":\"Clivern\"}' \"https://example.com?token=foo&id=20&response=json\"");
    }

    /**
     * Test Reload
     */
    public function testReload()
    {
        $this->request->reload($this->request->dump(DumpType::$JSON), DumpType::$JSON);
        $this->request->addParameter("new", "value");
        $this->assertEquals($this->request->dump(DumpType::$JSON), '{"method":"GET","parameters":{"token":"foo","id":20,"response":"json","new":"value"},"items":{"title":"Hello World","tags":[{"tag":"HTML"},{"tag":"PHP"},{"tag":"CSS"}],"author":"Clivern"},"headers":{"Content-Type":"application\/json","AUTH-TOKEN":"api_token_here"},"type":"ASYNCHRONOUS"}');
    }
}