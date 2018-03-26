<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Entity\CommandeProduct;
use AppBundle\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * App controller.
 *
 * @Route("/app")
 *
 * @Security("has_role('ROLE_USER')")
 */
class AppController extends Controller
{
    /**
     * Lists all commande entities.
     *
     * @Route("/", name="app_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository('AppBundle:Commande')->findBy(['user' => $this->getUser()]);

        return $this->render('app/index.html.twig', array(
            'commandes' => $commandes,
        ));
    }

    /**
     * Creates a new commande entity.
     *
     * @Route("/new", name="app_commande_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($form);
            $em = $this->getDoctrine()->getManager();
            //
            $data = $form->get('commande_produits')->getData();

            foreach ($data as $data) {
                $data->setCommande($commande);
                $em->persist($data);
            }
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('app_commande_show', array('id' => $commande->getId()));
        }

        return $this->render('app/new.html.twig', array(
            'commande' => $commande,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="app_commande_show")
     * @Method("GET")
     */
    public function showAction(Commande $commande)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $commande_produits = $commande->getCommandeProduits();

        return $this->render('app/show.html.twig', array(
            'commande_produits' => $commande_produits,
        ));
    }

    /**
     * Displays a form to edit an existing commande entity.
     *
     * @Route("/{id}/edit", name="app_commande_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Commande $commande)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $editForm = $this->createForm('AppBundle\Form\CommandeType', $commande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_commande_edit', array('id' => $commande->getId()));
        }

        return $this->render('app/edit.html.twig', array(
            'commande' => $commande,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commande entity.
     *
     * @Route("/{id}", name="app_commande_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Commande $commande)
    {
        $form = $this->createDeleteForm($commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
             $data = $commande->getCommandeProduits();
            //@todo debug
            foreach ($data as $data){
               $em->remove($data);
            }
            $em->remove($commande);
            $em->flush();
        }

        return $this->redirectToRoute('app_index');
    }

    /**
     * Creates a form to delete a commande entity.
     *
     * @param Commande $commande The commande entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commande $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_commande_delete', array('id' => $commande->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
