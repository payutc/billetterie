<?php

namespace Payutc\OnyxBundle\CAS;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class CASUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        // Call the CAS service here
        $userData = null;

        // Parse the callback and return a CASUser if something is found

        if ($userData) {
            return new CASUser($userData->username, $userData->password);
            // return new CASUser($userData['username'], $userData['password']);
        }

        // Throw any Exception otherwise
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Payutc\Onyx\CAS\CASUser';
        // return ($class instanceof CASUser || $class === 'Payutc\Onyx\CAS\CASUser' || is_subclass_of($class, 'Payutc\Onyx\CAS\CASUser'));
    }
}