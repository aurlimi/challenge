<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * controller manage product backend
 *
 * @Route("/admin/product")
 *@Security("has_role('ROLE_ADMIN')")
 * Class ProductController
 * @package AppBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Route("/", name="admin_product_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findBy(['user' => $this->getUser()]);
        return $this->render('admin/product/index.html.twig', ['products' => $products]);
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/new", name="admin_product_new")
     * @Method({"GET", "POST"})
     *
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $product->setUser($this->getUser());

        // See https://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(ProductType::class, $product)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($slugger->slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'product.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_product_new');
            }

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'post' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {

        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
       // $this->denyAccessUnlessGranted('edit', $product, 'Products can only be edited by their authors.');

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'product.updated_successfully');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}/delete", name="admin_product_delete")
     * @Method("POST")
     *
     */
    public function deleteAction(Request $request, Product $product)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_product_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'product.deleted_successfully');

        return $this->redirectToRoute('admin_product_index');
    }
}


