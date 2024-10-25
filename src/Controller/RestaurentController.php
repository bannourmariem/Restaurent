<?php

namespace App\Controller;

use App\Entity\Restaurent;
use App\Form\RestaurentType;
use App\Repository\RestaurentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurent')]
class RestaurentController extends AbstractController
{
    #[Route('/', name: 'app_restaurent_index', methods: ['GET'])]
    public function index(RestaurentRepository $restaurentRepository): Response
    {
        return $this->render('restaurent/index.html.twig', [
            'restaurents' => $restaurentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_restaurent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurent = new Restaurent();
        $form = $this->createForm(RestaurentType::class, $restaurent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($restaurent);
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('restaurent/new.html.twig', [
            'restaurent' => $restaurent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurent_show', methods: ['GET'])]
    public function show(Restaurent $restaurent): Response
    {
        return $this->render('restaurent/show.html.twig', [
            'restaurent' => $restaurent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_restaurent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Restaurent $restaurent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RestaurentType::class, $restaurent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('restaurent/edit.html.twig', [
            'restaurent' => $restaurent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurent_delete', methods: ['POST'])]
    public function delete(Request $request, Restaurent $restaurent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($restaurent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_restaurent_index', [], Response::HTTP_SEE_OTHER);
    }
}
