<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Supplier;
use AppBundle\Form\SupplierType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * controller manage supplier backend
 *
 * @Route("/admin/supplier")
 *
 * Class SupplierController
 * @package AppBundle\Controller
 */
class SupplierController extends Controller
{
    /**
     * @Route("/", name="admin_supplier_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $suppliers = $em->getRepository(Supplier::class)->findAll();
        return $this->render('admin/supplier/index.html.twig', ['suppliers' => $suppliers]);
    }

    /**
     * Creates a new Supplier entity.
     *
     * @Route("/new", name="admin_supplier_new")
     * @Method({"GET", "POST"})
     *
     */
    public function newAction(Request $request)
    {
        $supplier = new Supplier();

        // See https://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(SupplierType::class, $supplier)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();

            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'supplier.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_supplier_new');
            }

            return $this->redirectToRoute('admin_supplier_index');
        }

        return $this->render('admin/supplier/new.html.twig', [
            'post' => $supplier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Supplier entity.
     *
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_supplier_show")
     * @Method("GET")
     */
    public function showAction(Supplier $supplier)
    {

        return $this->render('admin/supplier/show.html.twig', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Displays a form to edit an existing Supplier entity.
     *
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_supplier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Supplier $supplier)
    {
        // $this->denyAccessUnlessGranted('edit', $supplier, 'Suppliers can only be edited by their authors.');

        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'supplier.updated_successfully');

            return $this->redirectToRoute('admin_supplier_edit', ['id' => $supplier->getId()]);
        }

        return $this->render('admin/supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Supplier entity.
     *
     * @Route("/{id}/delete", name="admin_supplier_delete")
     * @Method("POST")
     *
     */
    public function deleteAction(Request $request, Supplier $supplier)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $supplier->getProducts();
        
        if (!empty($products[0])) {
            $this->addFlash('danger', 'supplier.deleted_reset');
            return $this->redirectToRoute('admin_supplier_index');
        }
        $em->remove($supplier);
        $em->flush();

        $this->addFlash('success', 'supplier.deleted_successfully');

        return $this->redirectToRoute('admin_supplier_index');
    }
}
