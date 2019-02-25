<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Monkey\Util;

/**
 * Config Class.
 *
 * @since 1.0.0
 */
class Config
{
    private $cloudStackServers = [];

    /**
     * Class Constructor.
     *
     * @param array $cloudStackServers A List of CloudStack Servers, Credentials and More Info
     */
    public function __construct($cloudStackServers = [])
    {
        $this->cloudStackServers = $cloudStackServers;
    }

    /**
     * Add CloudStack Server.
     *
     * @param string $serverIdent       CloudStack Server Ident
     * @param array  $serverCredentials CloudStack Server Credentials
     *
     * @return Config An instance of Config Class
     */
    public function addCloudStackServer($serverIdent, $serverCredentials)
    {
        $this->cloudStackServers[$serverIdent] = $serverCredentials;

        return $this;
    }

    /**
     * Remove CloudStack Server.
     *
     * @param string $serverIdent The CloudStack Server Ident
     *
     * @return bool Whether node removed or not
     */
    public function removeCloudStackServer($serverIdent)
    {
        if (isset($this->cloudStackServers[$serverIdent])) {
            unset($this->cloudStackServers[$serverIdent]);
        }

        return !(isset($this->cloudStackServers[$serverIdent]));
    }

    /**
     * Get All CloudStack Servers.
     *
     * @return array A list of CloudStack Servers
     */
    public function getCloudStackServers()
    {
        return $this->cloudStackServers;
    }

    /**
     * Check if Server Exist.
     *
     * @param string $serverIdent The Server Ident
     *
     * @return bool Whether Server Exist or Not
     */
    public function isCloudStackServerExists($serverIdent)
    {
        return (isset($this->cloudStackServers[$serverIdent])) ? true : false;
    }

    /**
     * Get CloudStack Server Info.
     *
     * @param string $serverIdent The Server Ident
     *
     * @return array The Server Info
     */
    public function getCloudStackServer($serverIdent)
    {
        return (isset($this->cloudStackServers[$serverIdent])) ? $this->cloudStackServers[$serverIdent] : [];
    }
}
