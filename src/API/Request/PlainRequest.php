<?php
namespace Clivern\CloudStackMonkey\API\Request;

use Clivern\CloudStackMonkey\API\Contract\RequestInterface;

/**
 * @since 1.0.0
 * @package Clivern\CloudStackMonkey\API\Request
 */
class PlainRequest implements RequestInterface {

    private $method;
    private $parameters = [];
    private $items = [];
    private $headers = [];
    private $request;

    /**
     * Class Constructor
     *
     * @param String $method     The Request Method
     * @param array  $parameters The Request URL Parameters
     * @param array  $items      The Request Body Items
     */
    public function __construct($method = null, $parameters = [], $items = [])
    {
        $this->method = $method;
        $this->parameters = $parameters;
        $this->items = $items;
    }

    /**
     * Set Method
     *
     * @param String $method The Request Method
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Add URL Parameter
     *
     * @param String $key   The Parameter Key
     * @param String $value The Parameter Value
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Add Request Body Item
     *
     * @param String $key  The Body Item Key
     * @param Mixed $value The Body Item Value
     */
    public function addItem($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Add Header Item
     *
     * @param String $key   The Header Item Key
     * @param String $value The Header Item Value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Get Request Method
     *
     * @return String
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get Request URL Parameter
     *
     * @param String $key   The Parameter Key
     * @return Array
     */
    public function getParameter($key)
    {
        return ($this->parameterExists($key)) ? $this->parameters[$key] : null;
    }

    /**
     * Get Request Body Item
     *
     * @param String $key  The Body Item Key
     * @return Array
     */
    public function getItem($key)
    {
        return ($this->itemExists($key)) ? $this->items[$key] : null;
    }

    /**
     * Get Request Header Item
     *
     * @param String $key   The Header Item Key
     * @return Array
     */
    public function getHeader($key)
    {
        return ($this->headerExists($key)) ? $this->headers[$key] : null;
    }

    /**
     * Check if Header Item Exists
     *
     * @param  String $key The Header Item Key
     * @return Boolean
     */
    public function headerExists($key)
    {
        return (isset($this->headers[$key]));
    }

    /**
     * Check if URL Parameter Exists
     *
     * @param  String $key The URL Parameter Key
     * @return Boolean
     */
    public function parameterExists($key)
    {
        return (isset($this->parameters[$key]));
    }

    /**
     * Check if Body Item Exists
     *
     * @param  String $key The Body Item Key
     * @return Boolean
     */
    public function itemExists($key)
    {
        return (isset($this->items[$key]));
    }

    /**
     * Debug The Request Object
     *
     * @return String
     */
    public function debug()
    {
        $body = json_encode($this->items);
        $url = "https://example.com?" . http_build_query($this->parameters);
        $headers = "";
        foreach ($this->headers as $key => $value) {
            $headers .= " -H \"{$key}: {$value}\"";
        }

        return "curl -X {$this->method} " . trim($headers) . " -d '{$body}'" . " \"{$url}\"";
    }
}