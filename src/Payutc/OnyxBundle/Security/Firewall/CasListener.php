<?php

namespace Payutc\OnyxBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

use Payutc\OnyxBundle\Security\Authentication\Token\CasToken;

class CasListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;
    protected $casUrl;
    protected $devBaseUrl;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $casUrl, $devBaseUrl)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->casUrl = $casUrl;
        $this->devBaseUrl = $devBaseUrl;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // Check ticket CAS, if OK analyze it, if not, return;
        $ticket = $request->get('ticket');
        if ($ticket) {
            $cas = new Cas($this->casUrl);
            try {
                $user = $cas->authenticate($ticket, $this->devBaseUrl);
            } catch (\Exception $e) {
                // Deny authentication with a '403 Forbidden' HTTP response
                $response = new Response();
                $response->setStatusCode(403);
                $event->setResponse($response);
                return;
            }
        } else {
            return;
        }

        // Check ticket CAS, if OK analyze it, if not, return;
        $ticket = $request->get('ticket');
        
        if(!$ticket) {
            return;
        }
        
        $admin = $request->get('admin');
        
        $token = new CasToken();
        $token->ticket = $ticket;
        
        if($admin) {
            $token->admin = true;
        }
        
        // Remove the ticket parameters to get the ticket
        $service = $request->getUri();
        $service = preg_replace('/&?\??ticket=[^&]*/', '', $service);
        
        $token->service = $service;

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);
        } catch (AuthenticationException $failed) {
            // To deny the authentication clear the token. This will redirect to the login page.
            //$this->securityContext->setToken(null);
            //return;
        }
    }
}