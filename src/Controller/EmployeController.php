<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employe;
use App\Form\EmployeType;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function listemploye(EntityManagerInterface $entityManager)
    {
        $employes = $entityManager->getRepository(Employe::class)->findAll();
        
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employe/ajouter', name: 'ajouter_employe')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();

            //return $this->redirectToRoute('/');
        }

        return $this->render('employe/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employe/{id}/edit', name: 'edit_employe')]
      public function editEmploye(Employe $employe, Request $request, EntityManagerInterface $em): Response
      {
          $form = $this->createForm(EmployeType::class, $employe);
          $form->handleRequest($request);
 
          if ($form->isSubmitted() && $form->isValid()) {
              $em->flush();
          }
 
          return $this->render('employe/edit.html.twig', [
              'form' => $form->createView(),
          ]);
      }

      #[Route('/employe/{id}/efface', name: 'efface_employe')]
      public function efface(Employe $employe, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($employe);
        $entityManager->flush();
        return $this->render('base.html.twig');
    }
}
