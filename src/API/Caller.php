<?php
namespace Clivern\CloudStackMonkey\API;

use Clivern\CloudStackMonkey\API\Contract\ResponseInterface;
use Clivern\CloudStackMonkey\API\Contract\RequestInterface;

/**
 * CloudStack API Caller Class
 *
 * @since 1.0.0
 * @package Clivern\CloudStackMonkey\API
 */
class Caller {

    private $ident;
    private $nodeUrl;
    private $response;
    private $request;
    private $data;
    private $status;
    private $shared;

    public function __construct($ident, $nodeUrl, RequestInterface $response, ResponseInterface $request)
    {
        $this->ident = $ident;
        $this->nodeUrl = $nodeUrl;
        $this->request = $request;
        $this->response = $response;
    }

    public function setData($data)
    {
        if( is_array($data) ){
            $this->data = $data;
        }else{
            $this->data = json_decode($data);
        }
    }

    public function getData()
    {
        return json_encode($data);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function execute()
    {

    }
}