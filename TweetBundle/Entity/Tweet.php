<?php
namespace UCDTweet\TweetBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ucdtweet_tweet")
 */
class Tweet 
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @ORM\Column(type="string")
    */
    protected $author;

    /**
    * @ORM\Column(type="datetime")
    */
    protected $created;

    /**
    *@ORM\Column(type="string")
    */
    protected $category;
 
   /**
    * @ORM\Column(type="text", nullable=true)
    */
    protected $ats;

   /**
    * @ORM\Column(type="text", nullable=true)
    */
    protected $hashtags;

    /**
    * @ORM\Column(type="text")
    */
    protected $json_blob;

    /**
    * @ORM\Column(type="bigint")
    */
    protected $tweet_id;

    /**
    * @ORM\Column(type="text")
    */
    protected $tweet_text;

    /**
    * @ORM\Column(type="boolean")
    */
    protected $interesting;

    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="tweet", cascade={"persist", "merge", "refresh", "detach"})
     */
    protected $tags;

     /**
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="linked_tweets", cascade={"persist", "merge", "refresh", "detach"})
     */
    protected $linked_tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->linked_tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set author
     *
     * @param string $author
     * @return Tweet
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Tweet
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Tweet
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set ats
     *
     * @param string $ats
     * @return Tweet
     */
    public function setAts($ats)
    {
        $this->ats = $ats;
    
        return $this;
    }

    /**
     * Get ats
     *
     * @return string 
     */
    public function getAts()
    {
        return $this->ats;
    }

    /**
     * Set hashtags
     *
     * @param string $hashtags
     * @return Tweet
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;
    
        return $this;
    }

    /**
     * Get hashtags
     *
     * @return string 
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * Set json_blob
     *
     * @param string $jsonBlob
     * @return Tweet
     */
    public function setJsonBlob($jsonBlob)
    {
        $this->json_blob = $jsonBlob;
    
        return $this;
    }

    /**
     * Get json_blob
     *
     * @return string 
     */
    public function getJsonBlob()
    {
        return $this->json_blob;
    }

    /**
     * Set tweet_id
     *
     * @param integer $tweetId
     * @return Tweet
     */
    public function setTweetId($tweetId)
    {
        $this->tweet_id = $tweetId;
    
        return $this;
    }

    /**
     * Get tweet_id
     *
     * @return integer 
     */
    public function getTweetId()
    {
        return $this->tweet_id;
    }

    /**
     * Set tweet_text
     *
     * @param string $tweetText
     * @return Tweet
     */
    public function setTweetText($tweetText)
    {
        $this->tweet_text = $tweetText;
    
        return $this;
    }

    /**
     * Get tweet_text
     *
     * @return string 
     */
    public function getTweetText()
    {
        return $this->tweet_text;
    }

    /**
     * Set interesting
     *
     * @param boolean $interesting
     * @return Tweet
     */
    public function setInteresting($interesting)
    {
        $this->interesting = $interesting;
    
        return $this;
    }

    /**
     * Get interesting
     *
     * @return boolean 
     */
    public function getInteresting()
    {
        return $this->interesting;
    }

    /**
     * Add tags
     *
     * @param \UCDTweet\TweetBundle\Entity\Tag $tags
     * @return Tweet
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

    /**
     * Add linked_tags
     *
     * @param \UCDTweet\TweetBundle\Entity\Tag $linkedTags
     * @return Tweet
     */
    public function addLinkedTag(\UCDTweet\TweetBundle\Entity\Tag $linkedTags)
    {
        $this->linked_tags[] = $linkedTags;
    
        return $this;
    }

    /**
     * Remove linked_tags
     *
     * @param \UCDTweet\TweetBundle\Entity\Tag $linkedTags
     */
    public function removeLinkedTag(\UCDTweet\TweetBundle\Entity\Tag $linkedTags)
    {
        $this->linked_tags->removeElement($linkedTags);
    }

    /**
     * Get linked_tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkedTags()
    {
        return $this->linked_tags;
    }
}