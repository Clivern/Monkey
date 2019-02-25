<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Monkey\API;

/**
 * Job Statuses Class.
 *
 * @since 1.0.0
 */
class JobStatus
{
    public static $PENDING = 'PENDING';
    public static $IN_PROGRESS = 'IN_PROGRESS';
    public static $FAILED = 'FAILED';
    public static $SUCCEEDED = 'SUCCEEDED';
}
