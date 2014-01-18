<?php

namespace Payutc\OnyxBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class CasToken extends AbstractToken
{
    public $ticket = "";
    public $service = "";
    public $admin = false;
    
    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // Si l'utilisateur a des rôles, on le considère comme authentifié
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}
