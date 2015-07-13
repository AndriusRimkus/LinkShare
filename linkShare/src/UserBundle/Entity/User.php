<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Link", mappedBy="user")
     */
    protected $links;

    public function __construct()
    {
        parent::__construct();
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
     * Add links
     *
     * @param \AppBundle\Entity\Link $links
     * @return User
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
