<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Blog", inversedBy="comments")
	 * @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
	 */
	protected $blog;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $author;

	/**
     * @ORM\Column(type="datetime")
     */
    protected $publishDate;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank()
	 */
	protected $title;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	protected $text;


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
     * @return Comment
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
     * Set title
     *
     * @param string $title
     * @return Comment
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
     * Set text
     *
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set blog
     *
     * @param \AppBundle\Entity\Blog $blog
     * @return Comment
     */
    public function setBlog(\AppBundle\Entity\Blog $blog = null)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return \AppBundle\Entity\Blog 
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     * @return Comment
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
}
