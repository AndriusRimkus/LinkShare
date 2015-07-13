<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * @UniqueEntity("name", message="This category name already exists in the database")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Link", mappedBy="category")
     */
    protected $links;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Category name is required")
     * @Assert\Length(
     *     min = "3",
     *     minMessage = "Category name is too short ({{ limit }} characters minimum)",
     *     max = "50",
     *     maxMessage = "Category name is too long ({{ limit }} characters maximum)"
     * )
     */
    protected $name;

    public function __construct()
    {
        $this->links = new ArrayCollection();
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
     * @return Category
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
     * Add links
     *
     * @param \AppBundle\Entity\Link $links
     * @return Category
     */
    public function addLink(\AppBundle\Entity\Link $links)
    {
        $this->links[] = $links;

        return $this;
    }

    /**
     * Remove links
     *
     * @param \AppBundle\Entity\Link $links
     */
    public function removeLink(\AppBundle\Entity\Link $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinks()
    {
        return $this->links;
    }
}
