<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduct
 *
 * @ORM\Table(name="commande_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CommandeProduct
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**

     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="produit_commandes")

     * @ORM\JoinColumn(nullable=false)

     */

    private $product;


    /**

     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commande", inversedBy="commande_produits")

     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id", nullable=false)

     */

    private $commande;

    public function __construct()
    {
        
    }

    /**
     * @ORM\PrePersist
     */
    public function persistQuantityStock(){
        $product = $this->getProduct();
        $q_stock = $product->getQuantityStock();
        $product->setQuantityStock($q_stock - $this->quantity);
    }


    /**
     *
     * @ORM\PostRemove
     */
    public function removeQuatityStock(){
        $q_stock = 0;
        $product = $this->getProduct();
        $product->updateQuatityStok();
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
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return CommandeProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return CommandeProduct
     */
    public function setProduct(\AppBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set commande.
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return CommandeProduct
     */
    public function setCommande(\AppBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande.
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

}
