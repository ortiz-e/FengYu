<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity
 * @ORM\Table(name="forum")
 * @UniqueEntity(fields="title", message="There is already another forum with this title.")
 */
class Forum {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="forums")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank()
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string", length=500)
	 */
	protected $info;

	/**
	 * @ORM\Column(type="string", length=60)
	 * @Assert\NotBlank()
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", length=200)
	 * @Assert\NotBlank()
	 */
	protected $img;

	/**
	 * @ORM\Column(type="integer", length=3)
	 * @Assert\NotBlank()
	 */
	protected $weight;

	/**
	 * @ORM\OneToMany(targetEntity="Blog", mappedBy="forum")
	 * @OrderBy({"publishDate" = "DESC"})
	 */
	protected $blogs;

	public function getCategory(){
		return $this->category;
	}

	public function setCategory($category){
		$this->category = $category;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getInfo(){
		return $this->info;
	}

	public function setInfo($info){
		$this->info = $info;
	}

	public function getSlug(){
		return $this->slug;
	}

	public function setSlug($slug){
		$this->slug = $slug;
	}

	public function getImg(){
		return $this->img;
	}

	public function setImg($img){
		$this->img = $img;
	}

	public function getWeight(){
		return $this->weight;
	}

	public function setWeight($weight){
		$this->weight = $weight;
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
     * Constructor
     */
    public function __construct()
    {
        $this->blogs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add blogs
     *
     * @param \AppBundle\Entity\Blog $blogs
     * @return Forum
     */
    public function addBlog(\AppBundle\Entity\Blog $blogs)
    {
        $this->blogs[] = $blogs;

        return $this;
    }

    /**
     * Remove blogs
     *
     * @param \AppBundle\Entity\Blog $blogs
     */
    public function removeBlog(\AppBundle\Entity\Blog $blogs)
    {
        $this->blogs->removeElement($blogs);
    }

    /**
     * Get blogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogs()
    {
        return $this->blogs;
    }
}
