<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use UserBundle\UserBundle;

/**
 * @ORM\Entity
 * @UniqueEntity("link", message="This link already exists in the database")
 * @ORM\Table(name="links")
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="links")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="UserBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="comments")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Link category is required")
     * @Assert\Type(type="AppBundle\Entity\Category")
     * @Assert\Valid()
     */
    protected $category;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Link title is required")
     * @Assert\Length(
     *     min = "3",
     *     minMessage = "Link title is too short ({{ limit }} characters minimum)",
     *     max = "50",
     *     maxMessage = "Link title is too long ({{ limit }} characters maximum)"
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Assert\NotBlank(message="Link is required")
     * @Assert\Url(message="Not a valid link provided")
     */
    protected $link;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="Not a valid date provided")
     */
    private $publishedAt;

    public function __construct(){
        $this->publishedAt = new \DateTime();
    }

    public function isAuthor(\UserBundle\Entity\User $user = null) {
        return $user->getId() == $this->getUser()->getId();
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
     * Set name
     *
     * @param string $name
     * @return Link
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Link
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Link
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Link
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Link
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
