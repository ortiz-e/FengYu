<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\JoinColumn;



/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="There is already another user with this email address.")
 * @UniqueEntity(fields="username", message="There is already another user with this login name.")
 */
class User implements UserInterface, \Serializable {

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
	protected $username;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 * @Assert\Email(
	 *		message = "The email '{{ value }}' is not a valid email address.",
	 *      checkMX = true
	 * )
	 */
	protected $email;

	/**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    protected $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank()
     */
    protected $roles;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $modForums = array();

    /**
     * @ORM\Column(type="datetime")
     */
    protected $joinDate;

    /**
     * @ORM\OneToMany(targetEntity="Blog", mappedBy="author")
     */
    protected $blogs;

     /**
      * @ORM\OneToMany(targetEntity="Comment", mappedBy="author")
      */
    protected $comments;

    /**
      * @ORM\OneToMany(targetEntity="Poll", mappedBy="author")
      */
    protected $polls;

    /**
      * @ORM\OneToMany(targetEntity="Vote", mappedBy="voter")
      */
    protected $votes;

    /**
     * @ORM\Column(type="string", length=200, nullable = true)
     */
    protected $userpic;

    /**
     * @ORM\Column(type="string", length=80, nullable = true)
     */
    protected $location;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $gender = 0;

    /**
     * @ORM\Column(type="string", length=200, nullable = true)
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $lastActivity;

    /**
     * @ORM\Column(type="string", length=100, nullable = true)
     */
    protected $realName;

    /**
     * @ORM\Column(type="json_array", nullable = true)
     */
    protected $favoriteGames;

    /**
     * @ORM\Column(type="json_array", nullable = true)
     */
    protected $nowPlaying;

    /**
     * @ORM\Column(type="text", nullable = true)
     */
    protected $aboutMe;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $birthDate;

    /**
     * @ORM\ManyToOne(targetEntity="Theme")
     * @JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $theme;

    /**
     * @ORM\Column(type="integer")
     */
    protected $views = 0;


    public function __construct()
    {
    	$this->isActive = true;
    }


    public function getSalt()
    {
    	return null;
    }

    public function getRoles()
    {
        if($this->id == 1) $this->roles = array('ROLE_ROOT');
    	return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    	return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
    	return serialize(array(
    			$this->id,
    			$this->username,
    			$this->password
    		));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
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
     * Set Username
     *
     * @param string $Username
     * @return User
     */
    public function setUsername($Username)
    {
        $this->username = $Username;

        return $this;
    }

    /**
     * Get Username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
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
     * Set userPassword
     *
     * @param string $userPassword
     * @return User
     */
    public function setPassword($userPassword)
    {
        $this->password = $userPassword;

        return $this;
    }

    /**
     * Get userPassword
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set joinDate
     *
     * @param \DateTime $joinDate
     * @return User
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    /**
     * Get joinDate
     *
     * @return \DateTime 
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * Add blogs
     *
     * @param \AppBundle\Entity\Blog $blogs
     * @return User
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

    /**
     * Set userpic
     *
     * @param string $userpic
     * @return User
     */
    public function setUserpic($userpic)
    {
        $this->userpic = $userpic;

        return $this;
    }

    /**
     * Get userpic
     *
     * @return string 
     */
    public function getUserpic()
    {
        return $this->userpic;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return User
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return User
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime 
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comments
     * @return User
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
     * Set realName
     *
     * @param string $realName
     * @return User
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * Get realName
     *
     * @return string 
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Set favoriteGames
     *
     * @param array $favoriteGames
     * @return User
     */
    public function setFavoriteGames($favoriteGames)
    {
        $this->favoriteGames = json_encode(explode(', ', $favoriteGames));

        return $this;
    }

    /**
     * Get favoriteGames
     *
     * @return array 
     */
    public function getFavoriteGames()
    {
        if(empty($this->favoriteGames)) return '';
        return implode(', ', json_decode($this->favoriteGames));
    }

    /**
     * Set nowPlaying
     *
     * @param array $nowPlaying
     * @return User
     */
    public function setNowPlaying($nowPlaying)
    {
        $this->nowPlaying = json_encode(explode(', ', $nowPlaying));

        return $this;
    }

    /**
     * Get nowPlaying
     *
     * @return array 
     */
    public function getNowPlaying()
    {
        if(empty($this->nowPlaying)) return '';
        return implode(', ', json_decode($this->nowPlaying));
    }

    /**
     * Set aboutMe
     *
     * @param string $aboutMe
     * @return User
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * Get aboutMe
     *
     * @return string 
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set theme
     *
     * @param \AppBundle\Entity\Theme $theme
     * @return User
     */
    public function setTheme(\AppBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \AppBundle\Entity\Theme 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Add polls
     *
     * @param \AppBundle\Entity\Poll $polls
     * @return User
     */
    public function addPoll(\AppBundle\Entity\Poll $polls)
    {
        $this->polls[] = $polls;

        return $this;
    }

    /**
     * Remove polls
     *
     * @param \AppBundle\Entity\Poll $polls
     */
    public function removePoll(\AppBundle\Entity\Poll $polls)
    {
        $this->polls->removeElement($polls);
    }

    /**
     * Get polls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPolls()
    {
        return $this->polls;
    }

    /**
     * Add votes
     *
     * @param \AppBundle\Entity\Vote $votes
     * @return User
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
     * Set views
     *
     * @param integer $views
     * @return User
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
     * Adds a view
     *
     * @return User
     */
    public function addView()
    {
        $this->views++;

        return $this;
    }

    /**
     * Set modForums
     *
     * @param array $modForums
     * @return User
     */
    public function setModForums($modForums)
    {
        $this->modForums = $modForums;

        return $this;
    }

    /**
     * Get modForums
     *
     * @return array 
     */
    public function getModForums()
    {
        return $this->modForums;
    }
}
