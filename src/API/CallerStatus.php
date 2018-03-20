<?php
namespace Clivern\Monkey\API;

/**
 * @since 1.0.0
 * @package Clivern\Monkey\API
 */
class CallerStatus {

    public static $PENDING = "PENDING";
    public static $IN_PROGRESS = "IN_PROGRESS";
    public static $ASYNC_JOB = "ASYNC_JOB";
    public static $FAILED = "FAILED";
    public static $FINISHED = "FINISHED";

}