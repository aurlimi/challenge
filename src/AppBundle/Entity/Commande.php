<?php

namespace AppBundle\Entity;

use AppBundle\Form\CommandeProductType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Userbundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     *
     * @ORM\OneToMany(targetEntity="CommandeProduct", mappedBy="commande")
     */
    private $commande_produits;


    public function __construct()
    {
        $this->date = new \DateTime();
        $this->commande_produits = new  ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user.
     *
     * @param \Userbundle\Entity\User $user
     *
     * @return Commande
     */
    public function setUser(\Userbundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Userbundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add commandeProduit.
     *
     * @param \AppBundle\Entity\CommandeProduct $commandeProduit
     *
     * @return Commande
     */
    public function addCommandeProduit(\AppBundle\Entity\CommandeProduct $commandeProduit)
    {
        $this->commande_produits[] = $commandeProduit;

        return $this;
    }

    /**
     * Remove commandeProduit.
     *
     * @param \AppBundle\Entity\CommandeProduct $commandeProduit
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCommandeProduit(\AppBundle\Entity\CommandeProduct $commandeProduit)
    {
        return $this->commande_produits->removeElement($commandeProduit);
    }

    /**
     * Get commandeProduits.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandeProduits()
    {
        return $this->commande_produits;
    }
}