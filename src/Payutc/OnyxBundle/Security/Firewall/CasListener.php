<?php

namespace Payutc\OnyxBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use Payutc\OnyxBundle\Security\Authentication\Token\CasToken;

class CasListener implements ListenerInterface
{
	protected $securityContext;
	protected $authenticationManager;
	protected $containerInterface;
	protected $configuration;

	public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $containerInterface)
	{
		$this->securityContext = $securityContext;
		$this->authenticationManager = $authenticationManager;
		$this->containerInterface = $containerInterface;

		// Parse the bundle config file
		$yamlParser = new Parser();
		try {
			$params = $yamlParser->parse(file_get_contents($this->containerInterface->get('kernel')->locateResource('@PayutcOnyxBundle/Resources/config/parameters.yml')));
		}
		catch (ParseException $e) {
			die(printf('Unable to parse the configuration file of PayutcOnyxBundle\Security\Firewall\CasListener : %s', $e->getMessage()));
		}

		$this->configuration = $params['parameters'];
	}

	public function handle(GetResponseEvent $event)
	{
		$request = $event->getRequest();

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
