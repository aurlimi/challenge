<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Commande;
use AppBundle\Entity\Product;
use UserBundle\Entity\User;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\CommandeProduct;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Defines the sample users to load in the database before running the unit and
 * functional tests. Execute this command to load the data.
 *
 *   $ php bin/console doctrine:fixtures:load
 *
 * See https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class CommandeFixtures extends AbstractFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $commande = new Commande();

        $hobianUser = new User();
        $hobianUser->setFirstName('Aninomenjanahary');
        $hobianUser->setUsername('hobiana_user');
        $hobianUser->setEmail('hobiana@gmail.com');
        $hobianUser->setPhone('0330409164');
        $hobianUser->setAddress('Lot IVW MHA');
        $hobianUser->setEnabled(1);
        $hobianUser->setRoles(['ROLE_USER']);
        $encodedPassword = $passwordEncoder->encodePassword($hobianUser, '123456');
        $hobianUser->setPassword($encodedPassword);
        $manager->persist($hobianUser);

        $produit = new Product();
        $produit->setTitle('Produit1');
        $produit->setUser($hobianUser);
        $produit->setGenre('Homme');
        $produit->setTypes('Soleil');
        $produit->setQuantity(1);

        $fournisseur1 = new Supplier();
        $fournisseur1->setName('Fournisseur1');
        $fournisseur1->setAddress('Tana 101');
        $fournisseur1->setPhone('0331245689');
        $manager->persist($fournisseur1);

        $produit->setSupplier($fournisseur1);
        $produit->setPrice(40);
        $produit->setMark("mdop");
        $manager->persist($produit);

        $commande->setUser($hobianUser);
        $commandeProduit = new CommandeProduct();
        $commandeProduit->setProduct($produit) ;
        $commandeProduit->setCommande($commande);

        $manager->persist($commande);
        $manager->persist($commandeProduit);




        $manager->flush();
    }
}
