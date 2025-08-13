<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    private array $fakeWishes = [
        ['id' => 1, 'title' => 'Voir les aurores boréales', 'description' => 'Voyager en Islande pour observer les aurores boréales.'],
        ['id' => 2, 'title' => 'Faire un saut en parachute', 'description' => 'Expérience extrême à vivre au moins une fois dans sa vie.'],
        ['id' => 3, 'title' => 'Écrire un roman', 'description' => 'Publier un livre qui raconte une histoire inspirante.'],
    ];

    #[Route('/wishes', name: 'wish_list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', [
            'wishes' => $this->fakeWishes,
        ]);
    }

    #[Route('/wishes/{id}', name: 'wish_detail')]
    public function detail(int $id): Response
    {
        $wish = array_filter($this->fakeWishes, fn($w) => $w['id'] === $id);
        $wish = reset($wish); // récupère le premier élément

        if (!$wish) {
            throw $this->createNotFoundException("Souhait avec l'ID $id introuvable.");
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }
}
