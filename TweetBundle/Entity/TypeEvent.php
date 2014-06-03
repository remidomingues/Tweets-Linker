<?php
namespace UCDTweet\TweetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ucdtweet_typeevent")
 */
class TypeEvent 
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $typeEvent;

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
     * Set typeEvent
     *
     * @param string $typeEvent
     * @return TypeEvent
     */
    public function setTypeEvent($typeEvent)
    {
        $this->typeEvent = $typeEvent;
    
        return $this;
    }

    /**
     * Get typeEvent
     *
     * @return string 
     */
    public function getTypeEvent()
    {
        return $this->typeEvent;
    }
}