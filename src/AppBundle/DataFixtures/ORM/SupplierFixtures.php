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

use AppBundle\Entity\Product;
use AppBundle\Entity\Supplier;
use UserBundle\Entity\User;
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
class SupplierFixtures extends AbstractFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');



        $fournisseur = new Supplier();
        $fournisseur->setName('Fournisseur2');
        $fournisseur->setAddress('Tana 102');
        $fournisseur->setPhone('0331245689');
        $manager->persist($fournisseur);

        $hobianUser = new User();
        $hobianUser->setName('Aninomenjanahary');
        $hobianUser->setFirstName('Hobiana');
        $hobianUser->setUsername('hobiana_user');
        $hobianUser->setEmail('hobiana@gmail.com');
        $hobianUser->setPhone('0330409164');
        $hobianUser->setAddress('Lot IVW MHA');
        $hobianUser->setEnabled(1);
        $hobianUser->setRoles(['ROLE_USER']);
        $encodedPassword = $passwordEncoder->encodePassword($hobianUser, '123456');
        $hobianUser->setPassword($encodedPassword);
        $manager->persist($hobianUser);

        $product = new Product();
        $product->setTitle('Product test');
        $product->setQuantity('10');
        $product->setGenre('Homme');
        $product->setMark('MR1');
        $product->setPrice('10');
        $product->setTypes('Soleil');
        $product->setSupplier($fournisseur);
        $product->setUser($hobianUser);
        $manager->persist($product);




        $manager->flush();
    }
}
