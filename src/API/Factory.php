<?php

namespace Clivern\Monkey\API;

use Clivern\Monkey\API\Job;
use Clivern\Monkey\API\Caller;
use Clivern\Monkey\API\Request\PlainRequest;
use Clivern\Monkey\API\Response\PlainResponse;


/**
 * CloudStack API Factory Class
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API
 */
class Factory {

    /**
     * Get an Instance Of Caller
     *
     * @return Caller
     */
    public static function caller()
    {
        return new Caller(
            new PlainRequest(),
            new PlainResponse()
        );
    }

    /**
     * Get an Instance of Job
     *
     * @return Job
     */
    public static function job()
    {
        return new Job();
    }
}