<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artist/crud')]
class ArtistCrudController extends AbstractController
{
    #[Route('/', name: 'app_artist_crud_index', methods: ['GET'])]
    public function index(ArtistRepository $artistRepository): Response
    {
        return $this->render('artist_crud/index.html.twig', [
            'artists' => $artistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_artist_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArtistRepository $artistRepository): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artistRepository->save($artist, true);

            return $this->redirectToRoute('app_artist_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artist_crud/new.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_crud_show', methods: ['GET'])]
    public function show(Artist $artist): Response
    {
        return $this->render('artist_crud/show.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artist_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artist $artist, ArtistRepository $artistRepository): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artistRepository->save($artist, true);

            return $this->redirectToRoute('app_artist_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artist_crud/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Artist $artist, ArtistRepository $artistRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artist->getId(), $request->request->get('_token'))) {
            $artistRepository->remove($artist, true);
        }

        return $this->redirectToRoute('app_artist_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
