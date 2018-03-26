<?php

namespace Clivern\Monkey\API;

use Clivern\Monkey\API\Caller;
use Clivern\Monkey\API\Factory;
use Clivern\Monkey\API\JobStatus;
use Clivern\Monkey\API\CallerStatus;


/**
 * CloudStack API Job Class
 *
 * @since 1.0.0
 * @package Clivern\Monkey\API
 */
class Job {

    protected $status;
    protected $callers = [];
    protected $defaultRetry = 1;
    protected $retryPerCaller = [];
    protected $trialsPerCaller = [];


    /**
     * Class Constructor
     *
     * @param array   $callers        The Caller List
     * @param integer $defaultRetry   The Default Number of Trials if Caller Failed
     * @param array   $retryPerCaller The Trials Per Caller
     */
    public function __construct($callers = [], $defaultRetry = 1, $retryPerCaller = [])
    {
        $this->callers = $this->organizeCaller($callers);
        $this->defaultRetry = $defaultRetry;
        $this->retryPerCaller = array_merge(array_fill_keys(array_keys($callers), $this->defaultRetry), $retryPerCaller);
        $this->trialsPerCaller = array_fill_keys(array_keys($callers), 0);
        $this->status = JobStatus::$PENDING;
    }

    /**
     * Execute Job
     *
     * @return Job
     */
    public function execute()
    {
        $this->status = JobStatus::$IN_PROGRESS;

        foreach ($this->callers as $ident => $caller) {

            $this->trialsPerCaller[$ident] = (isset($this->trialsPerCaller[$ident])) ? $this->trialsPerCaller[$ident] : 0;
            $this->retryPerCaller[$ident] = (isset($this->retryPerCaller[$ident])) ? $this->retryPerCaller[$ident] : $this->defaultRetry;

            if ($caller->getStatus() == CallerStatus::$SUCCEEDED){
                continue;
            }

            if (($caller->getStatus() == CallerStatus::$FAILED) && ($this->trialsPerCaller[$ident] >= $this->retryPerCaller[$ident])){
                $this->status = JobStatus::$FAILED;
                return $this;
            }

            $result = $this->updateCaller($ident);

            if ($result) {
                $this->callers[$ident]->execute();
                $this->trialsPerCaller[$ident] += 1;
                return $this;
            }else{
                $this->status = JobStatus::$FAILED;
                return $this;
            }
        }

        $this->status = ($this->status == JobStatus::$IN_PROGRESS) ? JobStatus::$SUCCEEDED : JobStatus::$FAILED;

        return $this;
    }

    /**
     * Add Caller
     *
     * @param string $ident  The Caller Ident
     * @param Caller $caller The Caller Instance
     * @return Job
     */
    public function addCaller($ident, Caller $caller)
    {
        $this->callers[$ident] = $caller;

        return $this;
    }

    /**
     * Remove Caller
     *
     * @param  string $ident the caller ident
     * @return boolean whether caller removed or not
     */
    public function removeCaller($ident)
    {
        if ($this->callerExists($ident)) {
            unset($this->callers[$ident]);
        }

        return !$this->callerExists($ident);
    }

    /**
     * Check if Caller Exists or Not
     *
     * @param  string $ident The caller ident
     * @return boolean whether caller exists or not
     */
    public function callerExists($ident)
    {
        return (isset($this->callers[$ident]));
    }

    /**
     * Add and Override Callers
     *
     * @param array $callers The callers list
     * @return Job
     */
    public function addCallers($callers)
    {
        $this->callers = $callers;

        return $this;
    }

    /**
     * Get Callers
     *
     * @return array a list of callers
     */
    public function getCallers()
    {
        return $this->callers;
    }

    /**
     * Get Caller
     *
     * @param  string $ident The caller ident
     * @return  mixed
     */
    public function getCaller($ident)
    {
        return $this->callerExists($ident) ? $this->callers[$ident] : null;
    }

    /**
     * Get Job Status
     *
     * @return string the job status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Job Status
     *
     * @param string $status The job status
     * @return Job
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Dump The Job Instance
     *
     * @param string $type The dump type
     * @return mixed
     */
    public function dump($type)
    {
        $callersDump = [];

        foreach ($this->callers as $ident => $caller) {
            $callersDump[$ident] = $caller->dump(DumpType::$ARRAY);
        }

        $data = [
            "defaultRetry" => $this->defaultRetry,
            "retryPerCaller" => $this->retryPerCaller,
            "trialsPerCaller" => $this->trialsPerCaller,
            "status" => $this->status,
            "callers" => $callersDump
        ];

        return ($type == DumpType::$JSON) ? json_encode($data) : $data;
    }

    /**
     * Reload The Job Instance from data
     *
     * @param  mixed $data The job instance data
     * @param  string $type The data type
     * @return Job
     */
    public function reload($data, $type)
    {
        $data = ($type == DumpType::$JSON) ? json_decode($data, true) : $data;

        $this->defaultRetry = $data["defaultRetry"];
        $this->retryPerCaller = $data["retryPerCaller"];
        $this->trialsPerCaller = $data["trialsPerCaller"];
        $this->status = $data["status"];

        foreach ($data["callers"] as $ident => $callerDump) {
            $this->addCaller($ident, Factory::caller()->reload($callerDump, DumpType::$ARRAY));
        }

        return $this;
    }

    /**
     * Organize Callers To Be Caller and Ident
     *
     * @param  array $callers The Caller List
     * @return array
     */
    protected function organizeCaller($callers)
    {
        $organizedCaller = [];
        foreach ($callers as $caller) {
            $organizedCaller[$caller->getIdent()] = $caller;
        }
        return $organizedCaller;
    }

    /**
     * Update Caller With Other Callers Shared Data
     *
     * @param  string $ident The Caller Ident
     * @return boolean Whether all detected variables replaced or not
     */
    protected function updateCaller($ident)
    {
        $status = true;
        $dataToReplace = [];
        $dump = $this->callers[$ident]->dump(DumpType::$JSON);
        preg_match_all('/"@(.*?)->(.*?)"/', $dump, $matches);

        foreach ($matches[0] as $match) {

            $match = trim($match, '"');
            $matchArr = explode("->", $match);
            $matchArr[0] = trim($matchArr[0], "@");

            if( (isset($this->callers[$matchArr[0]])) && ($this->callers[$matchArr[0]]->getStatus() == CallerStatus::$SUCCEEDED) ){
                if( !($this->callers[$matchArr[0]]->itemExists($matchArr[1])) && !($this->callers[$matchArr[0]]->response()->itemExists($matchArr[1]))  ){
                    $status &= false;
                    return $status;
                }

                $dataToReplace[$match] = ($this->callers[$matchArr[0]]->itemExists($matchArr[1])) ? ($this->callers[$matchArr[0]]->getItem($matchArr[1])) : $this->callers[$matchArr[0]]->response()->getItem($matchArr[1]);
            }else{
                $status &= false;
                return $status;
            }
        }

        $dump = str_replace(array_keys($dataToReplace), array_values($dataToReplace), $dump);
        $this->callers[$ident]->reload($dump, DumpType::$JSON);

        return $status;
    }
}