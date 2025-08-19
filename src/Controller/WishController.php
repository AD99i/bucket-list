<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wishes', name: 'wish')]
final class WishController extends AbstractController
{


    #[Route('/', name: '_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();
        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/{id}', name: '_detail',requirements: ['id' => '\d+'])]
    public function detail(int $id,WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException("Souhait avec l'ID $id introuvable.");
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        // Logique pour créer un souhait
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wish = $form->getData();

            $em->persist($wish);

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Souhait créé avec succès !');

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wish_form' => $form,
        ]);
    }
    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete(): Response{

        return $this->redirectToRoute('wish_list');
    }

}
