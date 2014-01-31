<?php

namespace Payutc\OnyxBundle\Entity;

use Serializable;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

use Payutc\OnyxBundle\Entity\Base\BaseEntity;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Payutc\OnyxBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="Cet email est déjà utilisé.")
 */
class User extends BaseEntity implements AdvancedUserInterface, Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registred_at", type="datetime")
     */
    private $registredAt;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="is_email_validated", type="boolean")
     */
    private $isEmailValidated;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * Magic constructor
     */
    public function __construct()
    {
        $this->registredAt = new \DateTime();
        $this->salt = sha1(uniqid(null, true));
        $this->isEmailValidated = false;
        $this->generateToken();

        return $this;
    }

    public function __toString()
    {
        return $this->getFirstname() . ' ' . $this->getName();
    }

    public function toString()
    {
        return $this->getFirstname() . ' ' . $this->getName();
    }

    public function encryptPassword($encoder) {
        $this->setPassword($encoder->encodePassword($this->getPassword(), $this->getSalt()));
    }

    /**
     * Get a list of the user's groups
     *
     * 
     */
    public function getMyGroups()
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set registredAt
     *
     * @param \DateTime $registredAt
     * @return User
     */
    public function setRegistredAt($registredAt)
    {
        $this->registredAt = $registredAt;
    
        return $this;
    }

    /**
     * Get registredAt
     *
     * @return \DateTime 
     */
    public function getRegistredAt()
    {
        return $this->registredAt;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isEmailValidated
     *
     * @param boolean $isEmailValidated
     * @return User
     */
    public function setIsEmailValidated($isEmailValidated)
    {
        $this->isEmailValidated = $isEmailValidated;

        return $this;
    }

    /**
     * Get isEmailValidated
     *
     * @return boolean
     */
    public function getIsEmailValidated()
    {
        return $this->isEmailValidated;
    }

    /**
     * Get isEmailValidated alias
     *
     * @return boolean
     */
    public function isEmailValidated()
    {
        return $this->getIsEmailValidated();
    }

    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Generate an user token
     *
     * @return User
     */
    public function generateToken()
    {
        return $this->setToken(sha1(microtime(TRUE) . rand(0, 100000)));
    }

    /**
     * [UNUSED] check if user account has expired or not
     * Required as implementation of AdvancedUserInterface
     *
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * [UNUSED] check if user account is locked or not
     * Required as implementation of AdvancedUserInterface
     *
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * [UNUSED] check if user password has expired or not
     * Required as implementation of AdvancedUserInterface
     *
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * check if user account is enabled or not
     * Required as implementation of AdvancedUserInterface
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->isEmailValidated();
    }
}