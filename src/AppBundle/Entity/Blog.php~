<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog")
 */
class Blog {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Forum", inversedBy="blogs")
	 * @ORM\JoinColumn(name="forum_id", referencedColumnName="id")
	 */
	protected $forum;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="blogs")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $author;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="blog")
     * @OrderBy({"publishDate" = "ASC"})
     */
    protected $comments;

    /**
     * @ORM\OneToOne(targetEntity="Poll", mappedBy="blog")
     */
    protected $poll;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="blog")
     * @ORM\JoinColumn(name="vote_id", referencedColumnName="id")
     */
    protected $votes;

	/**
     * @ORM\Column(type="datetime")
     */
    protected $publishDate;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sticky = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $commentsEnabled = 1;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank()
	 */
	protected $title;

	/**
	 * @ORM\Column(type="json_array", nullable = true)
	 */
	protected $taxonomy;

	/**
	 * @ORM\Column(type="json_array", nullable = true)
	 */
	protected $customFields;

	/**
	 * @ORM\Column(type="string", length=300)
	 * @Assert\NotBlank()
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", length=300, nullable = true)
	 */
	protected $featuredImage;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	protected $post;

	/**
	 * @ORM\Column(type="integer", length=9)
	 */
	protected $views = 0;


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
     * Set publishDate
     *
     * @param \DateTime $publishDate
     * @return Blog
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate
     *
     * @return \DateTime 
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set sticky
     *
     * @param boolean $sticky
     * @return Blog
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Get sticky
     *
     * @return boolean 
     */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
     * Set commentsEnabled
     *
     * @param boolean $commentsEnabled
     * @return Blog
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;

        return $this;
    }

    /**
     * Get commentsEnabled
     *
     * @return boolean 
     */
    public function getCommentsEnabled()
    {
        return $this->commentsEnabled;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set taxonomy
     *
     * @param array $taxonomy
     * @return Blog
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return array 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Set customFields
     *
     * @param array $customFields
     * @return Blog
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * Get customFields
     *
     * @return array 
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Blog
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set featuredImage
     *
     * @param string $featuredImage
     * @return Blog
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    /**
     * Get featuredImage
     *
     * @return string 
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * Set post
     *
     * @param string $post
     * @return Blog
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Blog
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set forum
     *
     * @param \AppBundle\Entity\Forum $forum
     * @return Blog
     */
    public function setForum(\AppBundle\Entity\Forum $forum = null)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Get forum
     *
     * @return \AppBundle\Entity\Forum 
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     * @return Blog
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comments
     * @return Blog
     */
    public function addComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\Comment $comments
     */
    public function removeComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add votes
     *
     * @param \AppBundle\Entity\Vote $votes
     * @return Blog
     */
    public function addVote(\AppBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \AppBundle\Entity\Vote $votes
     */
    public function removeVote(\AppBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set poll
     *
     * @param \AppBundle\Entity\Poll $poll
     * @return Blog
     */
    public function setPoll(\AppBundle\Entity\Poll $poll = null)
    {
        $this->poll = $poll;

        return $this;
    }

    /**
     * Get poll
     *
     * @return \AppBundle\Entity\Poll 
     */
    public function getPoll()
    {
        return $this->poll;
    }
}
