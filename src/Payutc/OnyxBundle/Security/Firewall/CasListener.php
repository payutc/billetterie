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

use Payutc\OnyxBundle\Security\Authentication\Token\CasUserToken;
use Payutc\OnyxBundle\Security\Cas;

class CasListener implements ListenerInterface
{
	protected $securityContext;
	protected $authenticationManager;
	protected $containerInterface;
	protected $configuration;

	public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $containerInterface, $devConfig, $casConfig)
	{
		$this->securityContext = $securityContext;
		$this->authenticationManager = $authenticationManager;
		$this->containerInterface = $containerInterface;

		$this->configuration = array_merge(array('dev' => $devConfig), array('cas' => $casConfig));
	}

	public function handle(GetResponseEvent $event)
	{
		$request = $event->getRequest();

		// Check ticket CAS, if OK analyze it, if not, return;
		$ticket = $request->get('ticket');
		if($ticket) {
			$cas = new Cas($this->configuration['cas']['base_url']);
			try {
				$user = $cas->authenticate($ticket, $this->configuration['dev']['base_url']);
			} catch (\Exception $e) {
				# TODO Log error + check exception, it's not really 403.
				// Deny authentication with a '403 Forbidden' HTTP response
				$response = new Response();
				$response->setStatusCode(403);
				$event->setResponse($response);
				return;
			}
		} else {
			return;
		}

		$token = new CasUserToken();
		$token->setUser($user);

		try {
			$authToken = $this->authenticationManager->authenticate($token);

			$this->securityContext->setToken($authToken);
		} catch (AuthenticationException $failed) {
			echo "Failed<pre>";
			var_dump($failed);
			die();
			// ... you might log something here

			// To deny the authentication clear the token. This will redirect to the login page.
			// $this->securityContext->setToken(null);
			// return;

			// Deny authentication with a '403 Forbidden' HTTP response
			$response = new Response();
			$response->setStatusCode(403);
			$event->setResponse($response);

		}
	}
}
