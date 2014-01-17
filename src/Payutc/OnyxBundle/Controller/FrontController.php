<?php

namespace Payutc\OnyxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
	/**
	 * Renders a view.
	 *
	 * @param string   $view       The view name
	 * @param array    $parameters An array of parameters to pass to the view
	 * @param Response $response   A response instance
	 *
	 * @return Response A Response instance
	 */
	public function render($view, array $parameters = array(), Response $response = null)
	{
		$parameters['months'] = array(1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
		$parameters['days'] = array(1 => 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');

		return $this->container->get('templating')->renderResponse($view, $parameters, $response);
	}
}