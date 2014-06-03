<?php
namespace UCDTweet\TweetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="ucdtweet_user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="user", cascade={"persist", "merge", "refresh", "detach"}, indexBy="tag")
     */
    private $tags;    

    /**
     * @ORM\Column(type="string")
     */
    protected $login;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $trust;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set trust
     *
     * @param string $trust
     * @return User
     */
    public function setTrust($trust)
    {
        $this->trust = $trust;
    
        return $this;
    }

    /**
     * Get trust
     *
     * @return string 
     */
    public function getTrust()
    {
        return $this->trust;
    }

    /**
     * Add tags
     *
     * @param \UCDTweet\TweetBundle\Entity\Tag $tags
     * @return User
     */
    public function addTag(\UCDTweet\TweetBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \UCDTweet\TweetBundle\Entity\Tag $tags
     */
    public function removeTag(\UCDTweet\TweetBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
	$metadata->addPropertyConstraint('login', new Assert\Length(array(
	    'min'        => 6,
	    'max'        => 20,
	    'minMessage' => 'Your login must be at least {{ limit }} characters length',
	    'maxMessage' => 'Your login cannot be longer than {{ limit }} characters length',)));
	$metadata->addPropertyConstraint('password', new Assert\Length(array(
	    'min'        => 6,
	    'max'        => 20,
	    'minMessage' => 'Your passowrd must be at least {{ limit }} characters length',
	    'maxMessage' => 'Your password cannot be longer than {{ limit }} characters length',)));
	$metadata->addPropertyConstraint('email', new Assert\Email(array(
	    'message' => 'The email {{ value }} is invalid',
	    'checkMX' => true)));
    }
}
