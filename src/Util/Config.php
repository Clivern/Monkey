<?php
namespace Clivern\CloudStackMonkey\Util;

/**
 * Config Class
 *
 * @since 1.0.0
 * @package Clivern\CloudStackMonkey\Util
 */
class Config {

    private $logging = [];
    private $cloudStackNodes = [];

    /**
     * Class Constructor
     *
     * @param array $cloudStackNodes A List of CloudStack Nodes, Credentials and More Info
     * @param array $logging         Package Logging Settings
     */
    public function __construct($cloudStackNodes = [], $logging = [])
    {
        $this->cloudStackNodes = $cloudStackNodes;
        $this->logging = $logging;
    }

    /**
     * Add CloudStack Node
     *
     * @param String $nodeIdent       CloudStack Node Ident
     * @param Array $nodeCredentials  CloudStack Node Credentials
     * @return Config An instance of Config Class
     */
    public function addCloudStackNode($nodeIdent, $nodeCredentials)
    {
        $this->cloudStackNodes[$nodeIdent] = $nodeCredentials;

        return $this;
    }

    /**
     * Remove CloudStack Node Info
     *
     * @param String $nodeIdent The CloudStack Node Ident
     * @return Boolean Whether node removed or not
     */
    public function removeCloudStackNode($nodeIdent)
    {
        if( isset($this->cloudStackNodes[$nodeIdent]) ){
            unset($this->cloudStackNodes[$nodeIdent]);
        }

        return !(isset($this->cloudStackNodes[$nodeIdent]));
    }

    /**
     * Get All CloudStack Nodes
     *
     * @return Array A list of CloudStack Nodes
     */
    public function getCloudStackNodes()
    {
        return $this->cloudStackNodes;
    }

    /**
     * Check if Node Exist
     *
     * @param  String  $nodeIdent The Node Ident
     * @return boolean  Whether Node Exist or Not
     */
    public function isCloudStackNodeExists($nodeIdent)
    {
        return (isset($this->cloudStackNodes[$nodeIdent])) ? true : false;
    }

    /**
     * Get CloudStack Node Info
     *
     * @param  String $nodeIdent The Node Ident
     * @return Array The Node Info
     */
    public function getCloudStackNode($nodeIdent)
    {
        return (isset($this->cloudStackNodes[$nodeIdent])) ? $this->cloudStackNodes[$nodeIdent] : [];
    }

    /**
     * Config The Package Logging
     *
     * @param Array $logging The Logging Settings
     */
    public function configLogging($logging)
    {
        $this->logging = array_merge($this->logging, $logging);
    }

    /**
     * Get Logging Settings
     *
     * @return Array Logging Settings
     */
    public function getLoggingConfigs()
    {
        return $this->logging;
    }
}