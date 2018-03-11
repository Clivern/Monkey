<?php
namespace Clivern\CloudStackMonkey\API\Contract;

/**
 * API Request Interface
 *
 * @since 1.0.0
 * @package Clivern\CloudStackMonkey\API\Contract
 */
interface RequestInterface {

    /**
     * Set Method
     */
    public function setMethod($method);

    /**
     * Add URL Parameter
     */
    public function addParameter($key, $value);

    /**
     * Add Request Body Item
     */
    public function addItem($key, $value);

    /**
     * Add Header Item
     */
    public function addHeader($key, $value);

    /**
     * Get Request Method
     */
    public function getMethod();

    /**
     * Get Request URL Parameter
     */
    public function getParameter($key);

    /**
     * Get Request Body Item
     */
    public function getItem($key);

    /**
     * Get Request Header Item
     */
    public function getHeader($key);

    /**
     * Check if Header Item Exists
     */
    public function headerExists($key);

    /**
     * Check if URL Parameter Exists
     */
    public function parameterExists($key);

    /**
     * Check if Body Item Exists
     */
    public function itemExists($key);

    /**
     * Debug The Request Object
     */
    public function debug();

}