<?php

namespace Payutc;

use \Payutc\Client\AutoJsonClient;
use \Payutc\Client\JsonException;

class Client extends AutoJsonClient
{
    private $apiKey;
    private $session;
    protected $status;

    public function __construct($session, $apiKey, $url, $service)
    {
        $this->apiKey = $apiKey;
        $this->session = $session;
        $cookie = $session->get('payutc_cookie');
        $status = $session->get('payutc_status');
        parent::__construct($url, $service, array(), "Payutc Json PHP Client", $cookie);
        if(!$cookie) {
            $this->connectApp();
        }
    }

    /*
        Ensure that Client is authenticated over payutc
    */
    public function connectApp()
    {
        $status = $this->getStatus();
        if(!$status->application) {
            $return = $this->loginApp(array("key" => $this->apiKey));
            $this->getStatus();
            return $return;
        }
        return true;
    }
    
    /*
        Create a cache for getStatus
    */
    public function getStatus()
    {
        if(!$this->status) {
            $this->status = parent::getStatus();
        }
        return $this->status;
    }
    
    /*
        logout must triger a new getStatus
    */
    public function logout()
    {
        $return = parent::logout();
        $this->getStatus();
        return $return;
    }
    
    /*
        loginCas must triger a new getStatus
    */
    public function loginCas($ticket, $service)
    {
        $status = $this->getStatus();
        if(!$status->user) {
            $return = $this->loginCas(array("ticket" => $ticket, "service" => $service));
            $this->getStatus();
            return $return;
        }
        return $status->user;
    }

    public function __destruct()
    {
        $this->session->set("payutc_cookie", $this->cookie);
        $this->session->set("payutc_status", $this->status);
    }
}
