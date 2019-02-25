<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Monkey\API\Contract;

/**
 * API Request Interface.
 *
 * @since 1.0.0
 */
interface RequestInterface
{
    /**
     * Set Method.
     *
     * @param mixed $method
     */
    public function setMethod($method);

    /**
     * Set Type.
     *
     * @param mixed $type
     */
    public function setType($type);

    /**
     * Add URL Parameter.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function addParameter($key, $value);

    /**
     * Add Request Body Item.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function addItem($key, $value);

    /**
     * Add Header Item.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function addHeader($key, $value);

    /**
     * Get Request Method.
     */
    public function getMethod();

    /**
     * Get Request Type.
     */
    public function getType();

    /**
     * Get Request URL Parameter.
     *
     * @param mixed $key
     */
    public function getParameter($key);

    /**
     * Get Request Body Item.
     *
     * @param mixed $key
     */
    public function getItem($key);

    /**
     * Get Request Body Items.
     *
     * @param mixed $type
     */
    public function getItems($type);

    /**
     * Get Request Body.
     *
     * @param mixed $type
     */
    public function getBody($type);

    /**
     * Get Request Header Item.
     *
     * @param mixed $key
     */
    public function getHeader($key);

    /**
     * Get Headers.
     */
    public function getHeaders();

    /**
     * Get Parameters.
     */
    public function getParameters();

    /**
     * Check if Header Item Exists.
     *
     * @param mixed $key
     */
    public function headerExists($key);

    /**
     * Check if URL Parameter Exists.
     *
     * @param mixed $key
     */
    public function parameterExists($key);

    /**
     * Check if Body Item Exists.
     *
     * @param mixed $key
     */
    public function itemExists($key);

    /**
     * Debug The Request Object.
     */
    public function debug();

    /**
     * Dump The PlainRequest Instance Data.
     *
     * @param mixed $type
     */
    public function dump($type);

    /**
     * Reload The PlainRequest Instance Data.
     *
     * @param mixed $data
     * @param mixed $type
     */
    public function reload($data, $type);
}
