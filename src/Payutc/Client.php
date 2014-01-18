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
        $this->status = $session->get('payutc_status');
        parent::__construct($url, $service, array(), "Payutc Json PHP Client", $cookie);
        if(!$cookie) {
            $this->connectApp();
            $this->session->set("payutc_cookie", $this->cookie);
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
            $this->getStatus(true);
            return $return;
        }
        return true;
    }
    
    /*
        Create a cache for getStatus
    */
    public function getStatus($force=false)
    {
        if(!$this->status || $force) {
            $this->status = parent::getStatus();
            $this->session->set("payutc_status", $this->status);
            if(!$this->status->application) {
                $this->connectApp();
            }
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
        $return = parent::loginCas(array("ticket" => $ticket, "service" => $service));
        $this->getStatus(true);
        return $return;
    }
}
