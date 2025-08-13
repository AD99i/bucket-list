<?php

namespace App\Controller;

use App\Repository\WishRepository;
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
}
