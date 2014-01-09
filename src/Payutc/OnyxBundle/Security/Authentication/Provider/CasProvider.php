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
    private $entityManager;
    private $encoderFactory;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $entityManager, $encoderFactory)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    public function authenticate(TokenInterface $token)
    {
        if ($token->getUser() instanceof User) {
            return $token;
        }
    
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

            $password = $this->generatePassword(8);
            $user->setPassword($password);
            
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->encryptPassword($encoder);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // TODO :: pass "subject" and "from" vars as config global vars
            $message = \SwiftMessage::newInstance()
                ->setSubject('Billeterie UTC - Inscription')
                ->setFrom('noreply@utc.fr')
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
