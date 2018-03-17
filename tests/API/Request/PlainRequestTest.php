<?php
namespace Tests\API\Request;

use PHPUnit\Framework\TestCase;
use Clivern\Monkey\API\Request\ContentType;
use Clivern\Monkey\API\Request\RequestMethod;
use Clivern\Monkey\API\Request\RequestType;
use Clivern\Monkey\API\Request\PlainRequest;

/**
 * Plain Request Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Request
 */
class PlainRequestTest extends TestCase {

    public function testClass()
    {
        $this->assertEquals("Clivern\Monkey\API\Request\PlainRequest", get_class(new PlainRequest()));

        $request = new PlainRequest();
        $request->setMethod(RequestMethod::$GET)
                ->setType(RequestType::$ASYNCHRONOUS)
                ->addHeader("Content-Type", ContentType::$JSON)
                ->addHeader("AUTH-TOKEN", "api_token_here")
                ->addParameter("token", "foo")
                ->addParameter("id", 20)
                ->addItem("title", "Hello World")
                ->addItem("tags", [["tag" => "HTML"], ["tag" => "PHP"], ["tag" => "CSS"]])
                ->addItem("author", "Clivern");

        $this->assertEquals($request->debug(), "curl -X GET -H \"Content-Type: application/json\" -H \"AUTH-TOKEN: api_token_here\" -d '{\"title\":\"Hello World\",\"tags\":[{\"tag\":\"HTML\"},{\"tag\":\"PHP\"},{\"tag\":\"CSS\"}],\"author\":\"Clivern\"}' \"https://example.com?token=foo&id=20\"");
    }
}