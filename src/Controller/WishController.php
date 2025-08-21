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
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/wishes', name: 'wish')]
final class WishController extends AbstractController
{


    #[Route('/', name: '_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findWishesWithCategory();
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

    #[Route('/update/{id}', name: '_update')]
    public function update(Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(WishType::class,$wish); // Création du formulaire pour la création d'une série
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush(); // Envoi des données en base de données

            $this->addFlash('success', 'Souhait mis à jour avec succès !'); // Message flash de succès

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]); // Redirection vers la liste des souhaits
        }

        return $this->render('wish/edit.html.twig',[
            'wish_form' => $form]);
    }




    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete (Wish $wish, EntityManagerInterface $em, Request $request):Response
    {

        if($this->isCsrfTokenValid('delete'.$wish->getId(), $request->get('token'))){
            $em->remove($wish);
            $em->flush();

            $this->addFlash('success', 'Le souhait a été supprimé avec succès');


        }else{
            $this->addFlash('danger', 'Suppression impossible');
        }


        return $this->redirectToRoute('wish_list');

    }

}
