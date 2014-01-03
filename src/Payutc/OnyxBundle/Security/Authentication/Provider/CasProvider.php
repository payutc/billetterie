<?php
namespace Payutc\OnyxBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Payutc\OnyxBundle\Security\Authentication\Token\CasUserToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Payutc\OnyxBundle\Entity\User;
use Ginger\Client\GingerClient;

class CasProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $entityManager, $encoderFactory)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    public function authenticate(TokenInterface $token)
    {
        // Call Ginger
        # TODO :: Mettre la config de ginger dans un fichier de config
        $ginger = new GingerClient("fauxginger", "http://localhost/faux-ginger/index.php/v1/");
		$userInfo = $ginger->getUser($token->getUsername());

        try {
            $user = $this->userProvider->loadUserByUsername($userInfo->mail);
        } catch (UsernameNotFoundException $e) {
            // User doesn't already exist, we need to create him an account
            $user = new User();
            $user->setEmail($userInfo->mail);
            $user->setFirstname($userInfo->prenom);
            $user->setName($userInfo->nom);
 
            // TODO: Generate a better password, than $login and send it by email.
            $password = $userInfo->login;
            $user->setPassword($password);
            
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->encryptPassword($encoder);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        
        if ($user) {
            $authenticatedToken = new CasUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The CAS authentication failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof CasUserToken;
    }
}
