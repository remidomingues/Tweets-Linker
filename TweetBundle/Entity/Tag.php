<?php
namespace UCDTweet\TweetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ucdtweet_tag")
 */
class Tag 
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $relevant;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $center_lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $center_lon;    

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $radius;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $start_timestamp;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $end_timestamp;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    protected $highlighted_time;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    protected $highlighted_location;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    protected $highlighted_type;
    
     /**
     * @ORM\ManyToOne(targetEntity="TypeEvent")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=true)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tags")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tweet", inversedBy="tags")
     */
    protected $tweet;

     /**
     * @ORM\ManyToMany(targetEntity="Tweet", inversedBy="linked_tags")
     */
    protected $linked_tweets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->linked_tweets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set relevant
     *
     * @param boolean $relevant
     * @return Tag
     */
    public function setRelevant($relevant)
    {
        $this->relevant = $relevant;
    
        return $this;
    }

    /**
     * Get relevant
     *
     * @return boolean 
     */
    public function getRelevant()
    {
        return $this->relevant;
    }

    /**
     * Set center_lat
     *
     * @param float $centerLat
     * @return Tag
     */
    public function setCenterLat($centerLat)
    {
        $this->center_lat = $centerLat;
    
        return $this;
    }

    /**
     * Get center_lat
     *
     * @return float 
     */
    public function getCenterLat()
    {
        return $this->center_lat;
    }

    /**
     * Set center_lon
     *
     * @param float $centerLon
     * @return Tag
     */
    public function setCenterLon($centerLon)
    {
        $this->center_lon = $centerLon;
    
        return $this;
    }

    /**
     * Get center_lon
     *
     * @return float 
     */
    public function getCenterLon()
    {
        return $this->center_lon;
    }

    /**
     * Set radius
     *
     * @param float $radius
     * @return Tag
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    
        return $this;
    }

    /**
     * Get radius
     *
     * @return float 
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Set start_timestamp
     *
     * @param \DateTime $startTimestamp
     * @return Tag
     */
    public function setStartTimestamp($startTimestamp)
    {
        $this->start_timestamp = $startTimestamp;
    
        return $this;
    }

    /**
     * Get start_timestamp
     *
     * @return \DateTime 
     */
    public function getStartTimestamp()
    {
        return $this->start_timestamp;
    }

    /**
     * Set end_timestamp
     *
     * @param \DateTime $endTimestamp
     * @return Tag
     */
    public function setEndTimestamp($endTimestamp)
    {
        $this->end_timestamp = $endTimestamp;
    
        return $this;
    }

    /**
     * Get end_timestamp
     *
     * @return \DateTime 
     */
    public function getEndTimestamp()
    {
        return $this->end_timestamp;
    }

    /**
     * Set highlighted_time
     *
     * @param string $highlightedTime
     * @return Tag
     */
    public function setHighlightedTime($highlightedTime)
    {
        $this->highlighted_time = $highlightedTime;
    
        return $this;
    }

    /**
     * Get highlighted_time
     *
     * @return string 
     */
    public function getHighlightedTime()
    {
        return $this->highlighted_time;
    }

    /**
     * Set highlighted_location
     *
     * @param string $highlightedLocation
     * @return Tag
     */
    public function setHighlightedLocation($highlightedLocation)
    {
        $this->highlighted_location = $highlightedLocation;
    
        return $this;
    }

    /**
     * Get highlighted_location
     *
     * @return string 
     */
    public function getHighlightedLocation()
    {
        return $this->highlighted_location;
    }

    /**
     * Set highlighted_type
     *
     * @param string $highlightedType
     * @return Tag
     */
    public function setHighlightedType($highlightedType)
    {
        $this->highlighted_type = $highlightedType;
    
        return $this;
    }

    /**
     * Get highlighted_type
     *
     * @return string 
     */
    public function getHighlightedType()
    {
        return $this->highlighted_type;
    }

    /**
     * Set type
     *
     * @param \UCDTweet\TweetBundle\Entity\TypeEvent $type
     * @return Tag
     */
    public function setType(\UCDTweet\TweetBundle\Entity\TypeEvent $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \UCDTweet\TweetBundle\Entity\TypeEvent 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \UCDTweet\TweetBundle\Entity\User $user
     * @return Tag
     */
    public function setUser(\UCDTweet\TweetBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \UCDTweet\TweetBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tweet
     *
     * @param \UCDTweet\TweetBundle\Entity\Tweet $tweet
     * @return Tag
     */
    public function setTweet(\UCDTweet\TweetBundle\Entity\Tweet $tweet = null)
    {
        $this->tweet = $tweet;
    
        return $this;
    }

    /**
     * Get tweet
     *
     * @return \UCDTweet\TweetBundle\Entity\Tweet 
     */
    public function getTweet()
    {
        return $this->tweet;
    }

    /**
     * Add linked_tweets
     *
     * @param \UCDTweet\TweetBundle\Entity\Tweet $linkedTweets
     * @return Tag
     */
    public function addLinkedTweet(\UCDTweet\TweetBundle\Entity\Tweet $linkedTweets)
    {
        $this->linked_tweets[] = $linkedTweets;
    
        return $this;
    }

    /**
     * Remove linked_tweets
     *
     * @param \UCDTweet\TweetBundle\Entity\Tweet $linkedTweets
     */
    public function removeLinkedTweet(\UCDTweet\TweetBundle\Entity\Tweet $linkedTweets)
    {
        $this->linked_tweets->removeElement($linkedTweets);
    }

    /**
     * Get linked_tweets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkedTweets()
    {
        return $this->linked_tweets;
    }
}
