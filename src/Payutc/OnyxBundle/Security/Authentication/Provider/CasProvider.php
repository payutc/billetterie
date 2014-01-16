<?php

namespace Payutc\OnyxBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use Ginger\Client\GingerClient;

use Payutc\OnyxBundle\Security\Authentication\Token\CasUserToken;
use Payutc\OnyxBundle\Entity\User;

class CasProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;
    private $entityManager;
    private $encoderFactory;
    private $containerInterface;
    protected $configuration;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $entityManager, $encoderFactory, $containerInterface)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->containerInterface = $containerInterface;

        // Parse the bundle config file
        $yamlParser = new Parser();
        try {
            $params = $yamlParser->parse(file_get_contents($this->containerInterface->get('kernel')->locateResource('@PayutcOnyxBundle/Resources/config/parameters.yml')));
        }
        catch (ParseException $e) {
            die(printf('Unable to parse the configuration file of PayutcOnyxBundle\Security\Provider\CasProvider : %s', $e->getMessage()));
        }

        $this->configuration = $params['parameters'];
    }

    public function authenticate(TokenInterface $token)
    {
        if ($token->getUser() instanceof User) {
            return $token;
        }
    
        // Call Ginger
        $ginger = new GingerClient($this->configuration['cas']['ginger']['name'], $this->configuration['cas']['ginger']['url']);
		$userInfo = $ginger->getUser($token->getUsername());

        try {
            $user = $this->userProvider->loadUserByUsername($userInfo->mail);
        } catch (UsernameNotFoundException $e) {
            // User doesn't already exist, we need to create him an account
            $user = new User();
            $user->setEmail($userInfo->mail);
            $user->setFirstname($userInfo->prenom);
            $user->setName($userInfo->nom);

            $password = $this->generatePassword(8);
            $user->setPassword($password);
            
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->encryptPassword($encoder);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // TODO :: pass "subject" and "from" vars as config global vars
            $message = \SwiftMessage::newInstance()
                ->setSubject($this->configuration['mailer']['subjects']['cas_authentication'])
                ->setFrom(array($this->configuration['mailer']['from']['email'] => $this->configuration['mailer']['from']['name']))
                ->setTo($user->getEmail())
                ->setBody($this->renderView('PayutcOnyxBundle:Authentication/Cas:registration.mail.html.twig', array(
                    'firstname' => $user->getFirstname(),
                    'name' => $user->getName(),
                    'login' => $user->getEmail(),
                    'password' => $password
                )), 'text/html')
            ;

            $this->get('mailer')->send($message);
        }
        
        if ($user) {
            $authenticatedToken = new CasUserToken($user->getRoles());
            $authenticatedToken->setUser($user);
            $authenticatedToken->cas_checked = true;

            return $authenticatedToken;
        }

        throw new AuthenticationException('The CAS authentication failed.');
    }

    /**
     * Generate a randomly generated password based on a php's str_shuffle method called on a characters list.
     *
     * @param int $length
     * @return string
     */
    protected function generatePassword($length = 10)
    {
        // str_shuffle gives one in all possible permutations of the shuffled string
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz-0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#+=,;:.$'), 0, $length);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof CasUserToken;
    }
}
