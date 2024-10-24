<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;


class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'affiche_entreprise')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/entreprise/{id}/employes', name: 'liste_employes')]
    public function listeEmployes(int $id, EntityManagerInterface $entityManager): Response
    {

        $entreprise = $entityManager->getRepository(Entreprise::class)->find($id);

        $employes = $entreprise->getEmployes();

        return $this->render('entreprise/employes.html.twig', [
            'entreprise' => $entreprise,
            'employes' => $employes,
        ]);
    }

    #[Route('/entreprise/ajouter', name: 'ajouter_entreprise')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            //return $this->redirectToRoute('/');
        }

        return $this->render('entreprise/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]
      public function editEntreprise(Entreprise $product, Request $request, EntityManagerInterface $em): Response
      {
          $form = $this->createForm(EntrepriseType::class, $product);
          $form->handleRequest($request);
 
          if ($form->isSubmitted() && $form->isValid()) {
              $em->flush();
          }
 
          return $this->render('entreprise/edit.html.twig', [
              'form' => $form->createView(),
          ]);
      }

      #[Route('/entreprise/{id}/efface', name: 'efface_entreprise')]
      public function efface(Entreprise $entreprise, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($entreprise);
        $entityManager->flush();
        return $this->render('base.html.twig');
    }
}
