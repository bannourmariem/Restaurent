<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serveur')]
class ServeurController extends AbstractController
{
    #[Route('/', name: 'app_serveur_index', methods: ['GET'])]
    public function index(ServeurRepository $serveurRepository): Response
    {
        return $this->render('serveur/index.html.twig', [
            'serveurs' => $serveurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_serveur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serveur = new Serveur();
        $form = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serveur);
            $entityManager->flush();

            return $this->redirectToRoute('app_serveur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serveur/new.html.twig', [
            'serveur' => $serveur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_serveur_show', methods: ['GET'])]
    public function show(Serveur $serveur): Response
    {
        return $this->render('serveur/show.html.twig', [
            'serveur' => $serveur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_serveur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Serveur $serveur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_serveur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serveur/edit.html.twig', [
            'serveur' => $serveur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_serveur_delete', methods: ['POST'])]
    public function delete(Request $request, Serveur $serveur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serveur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($serveur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_serveur_index', [], Response::HTTP_SEE_OTHER);
    }
}
