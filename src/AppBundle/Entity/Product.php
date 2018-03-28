<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="mark", type="string", length=100)
     */
    private $mark;

    /**
     * @var string
     *
     * @ORM\Column(name="types", type="string", length=100)
     */
    private $types;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=100)
     */
    private $genre;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity_stock", type="integer", nullable=true)
     */
    private $quantity_stock;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var Supplier
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $supplier;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Userbundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     *
     * @ORM\OneToMany(targetEntity="CommandeProduct", mappedBy="product", cascade={"persist"})
     */
    private $produit_commandes;

    /**
     * @ORM\PrePersist
     */
    public function persistQuantityStock(){
        $this->setQuantityStock($this->quantity);
    }

    /**
     *
     * @ORM\PreUpdate
     */

     public function updateQuatityStok(){
         $q_stock = 0;
         foreach ($this->getProduitCommandes() as $commande)
         {
            $q_stock = $q_stock + $commande->getQuantity();
         }

         $this->setQuantityStock($this->quantity - $q_stock);
     }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set mark
     *
     * @param string $mark
     *
     * @return Product
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return string
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set supplier
     *
     * @param \AppBundle\Entity\Supplier $supplier
     * @return Product
     */
    public function setSupplier(\AppBundle\Entity\Supplier $supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }
    /**
     * Get supplier
     *
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
    
    

    /**
     * Set user.
     *
     * @param \Userbundle\Entity\User $user
     *
     * @return Product
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

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set types.
     *
     * @param string $types
     *
     * @return Product
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * Get types.
     *
     * @return string
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Set genre.
     *
     * @param string $genre
     *
     * @return Product
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre.
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produit_commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add produitCommande.
     *
     * @param \AppBundle\Entity\CommandeProduct $produitCommande
     *
     * @return Product
     */
    public function addProduitCommande(\AppBundle\Entity\CommandeProduct $produitCommande)
    {
        $this->produit_commandes[] = $produitCommande;

        return $this;
    }

    /**
     * Remove produitCommande.
     *
     * @param \AppBundle\Entity\CommandeProduct $produitCommande
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduitCommande(\AppBundle\Entity\CommandeProduct $produitCommande)
    {
        return $this->produit_commandes->removeElement($produitCommande);
    }

    /**
     * Get produitCommandes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduitCommandes()
    {
        return $this->produit_commandes;
    }

    /**
     * Set quantityStock.
     *
     * @param int|null $quantityStock
     *
     * @return Product
     */
    public function setQuantityStock($quantityStock = null)
    {
        $this->quantity_stock = $quantityStock;

        return $this;
    }

    /**
     * Get quantityStock.
     *
     * @return int|null
     */
    public function getQuantityStock()
    {
        return $this->quantity_stock;
    }
}
