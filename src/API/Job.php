<?php
namespace Clivern\Monkey\API;

use Clivern\Monkey\API\Caller;
use Clivern\Monkey\API\Factory;

/**
 * CloudStack API Job Class
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API
 */
class Job {

	private $callers = [];
	private $retryPerCaller = [];
	private $trialsPerCaller = [];
	private $status;


	public function __construct($callers = [], $retryPerCaller = [])
	{
		$this->callers = $callers;
		$this->retryPerCaller = array_merge($this->retryPerCaller, $retryPerCaller);
	}

	public function addCaller($ident, Caller $caller)
	{
		$this->callers[$ident] = $caller;
	}

	public function removeCaller($ident)
	{
		if ($this->callerExists($ident)) {
			unset($this->callers[$ident]);
		}

		return !$this->callerExists($ident);
	}

	public function callerExists($ident)
	{
		return (isset($this->callers[$ident]));
	}

	public function addCallers($callers)
	{
		$this->callers = $callers;
	}

	public function getCallers()
	{
		return $this->callers;
	}

	public function getCaller($ident)
	{
		return $this->callerExists($ident) ? $this->callers[$ident] : null;
	}

	public function execute()
	{
		#~
	}


    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
    	$this->status = $status;
    }

	protected function updateCaller($ident)
	{

	}

    public function dump($type)
    {
    	#~
    }

    public function reload($data, $type)
    {
    	#~
    }
}