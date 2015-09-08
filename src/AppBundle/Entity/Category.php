<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @UniqueEntity(fields="title", message="There is already another category with this title.")
 */
class Category {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank()
	 */
	protected $title;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\NotBlank()
	 */
	protected $weight;

	/**
	 * @ORM\OneToMany(targetEntity="Forum", mappedBy="category")
	 */
	protected $forums;

	public function __construct()
	{
		$this->$forums = new ArrayCollection();
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
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
     * Add forums
     *
     * @param \AppBundle\Entity\Forum $forums
     * @return Category
     */
    public function addForum(\AppBundle\Entity\Forum $forums)
    {
        $this->forums[] = $forums;

        return $this;
    }

    /**
     * Remove forums
     *
     * @param \AppBundle\Entity\Forum $forums
     */
    public function removeForum(\AppBundle\Entity\Forum $forums)
    {
        $this->forums->removeElement($forums);
    }

    /**
     * Get forums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForums()
    {
        return $this->forums;
    }
}
