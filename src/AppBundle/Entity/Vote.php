<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OrderBy;


/**
 * @ORM\Entity
 * @ORM\Table(name="vote")
 */
class Vote {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="votes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $voter;

	/**
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="choices")
     */
    protected $poll;

    /**
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="votes")
     */
    protected $blog;

    /**
     * @ORM\Column(type="integer")
     */
    protected $voteValue;

    /**
     * @ORM\ManyToOne(targetEntity="Choice", inversedBy="votes")
     */
    protected $choice;


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
     * Set voteValue
     *
     * @param integer $voteValue
     * @return Vote
     */
    public function setVoteValue($voteValue)
    {
        $this->voteValue = $voteValue;

        return $this;
    }

    /**
     * Get voteValue
     *
     * @return integer 
     */
    public function getVoteValue()
    {
        return $this->voteValue;
    }

    /**
     * Set Choice
     *
     * @param integer $Choice
     * @return Vote
     */
    public function setChoice($Choice)
    {
        $this->choice = $Choice;

        return $this;
    }

    /**
     * Get Choice
     *
     * @return integer 
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * Set voter
     *
     * @param \AppBundle\Entity\User $voter
     * @return Vote
     */
    public function setVoter(\AppBundle\Entity\User $voter = null)
    {
        $this->voter = $voter;

        return $this;
    }

    /**
     * Get voter
     *
     * @return \AppBundle\Entity\User 
     */
    public function getVoter()
    {
        return $this->voter;
    }

    /**
     * Set poll
     *
     * @param \AppBundle\Entity\Poll $poll
     * @return Vote
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

    /**
     * Set blog
     *
     * @param \AppBundle\Entity\Blog $blog
     * @return Vote
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
}
