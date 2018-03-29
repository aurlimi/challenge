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

            $this->addFlash('success', 'commande.created_successfully');

            return $this->redirectToRoute('app_index');
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
        $editForm = $this->createForm('AppBundle\Form\CommandeType', $commande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $commande->setPrixTotal();
            $commandeProduct = $commande->getCommandeProduits();
            $commandeProduct[0]->getProduct()->updateQuatityStok();
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'commande.created_successfully');

            return $this->redirectToRoute('app_commande_edit', array('id' => $commande->getId()));
        }

        return $this->render('app/edit.html.twig', array(
            'commande' => $commande,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a commande entity.
     *
     * @Route("/{id}", name="app_commande_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, Commande $commande)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('app_index');
        }
        
        $em = $this->getDoctrine()->getManager();
        $data = $commande->getCommandeProduits();

        foreach ($data as $data){
           $em->remove($data);
        }
        $em->remove($commande);
        $em->flush();

        return $this->redirectToRoute('app_index');
    }
}
