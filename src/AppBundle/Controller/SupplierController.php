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
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request)
    {
        $supplier = new Supplier();
        //@todo add author $post->setAuthor($this->getUser());

        // See https://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(SupplierType::class, $supplier)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($slugger->slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'supplier.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_supplier_new');
            }

            return $this->redirectToRoute('admin_index_index');
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
        // This security check can also be performed
        // using an annotation: @Security("is_granted('show', supplier)")
        //$this->denyAccessUnlessGranted('show', $supplier, 'Suppliers can only be shown to their authors.');

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
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     */
    public function deleteAction(Request $request, Supplier $supplier)
    {
        //@todo security
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_supplier_index');
        }*/

        $em = $this->getDoctrine()->getManager();
        $em->remove($supplier);
        $em->flush();

        $this->addFlash('success', 'supplier.deleted_successfully');

        return $this->redirectToRoute('admin_supplier_index');
    }
}
