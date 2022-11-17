<?php

namespace App\Controller;

use App\Entity\Disc;
use App\Form\DiscType;
use App\Repository\DiscRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Asset\Package;
use symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormTypeInterface;

use function PHPUnit\Framework\throwException;

#[Route('/disc')]
class DiscController extends AbstractController
{
    #[Route('/index', name: 'app_disc_index', methods: ['GET'])]
    public function index(DiscRepository $discRepository): Response
    {
        $package = new Package(new EmptyVersionStrategy());
        
        return $this->render('disc/index.html.twig', [
            'discs' => $discRepository->findAll(),
        ]);
    }
    
    #[Route('/new', name: 'app_disc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DiscRepository $discRepository): Response
    {
        $disc = new Disc();
        $form = $this->createForm(DiscType::class, $disc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {    
            $discRepository->save($disc, true);
            $this->addFlash('success', 'Création du disque réussie');
            return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disc/new.html.twig', [
            'disc' => $disc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disc_show', methods: ['GET'])]
    public function show(Disc $disc): Response
    {
        return $this->render('disc/show.html.twig', [
            'disc' => $disc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_disc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Disc $disc, DiscRepository $discRepository, SluggerInterface $slugger): Response
    {
        $idDisc = $disc->getId();
        $form = $this->createForm(DiscType::class, $disc);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $pictureFile */
            $pictureFile = $form['picture']->getData();
            
            if($pictureFile) 
            {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newPicture = $safeFileName.'-'.uniqid().'.'.$pictureFile->guessExtension();
                
                $disc->setPicture($newPicture);
                
                try
                {
                    $pictureFile->move(
                        $this->getParameter('cover_directory'),
                        $newPicture
                    );
                } catch (FileException $e) {
                    
                }
                
            }
            
            $discRepository->save($disc, true);

            $this->addFlash('success', 'Modification(s) réussie(s)');
            return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('disc/edit.html.twig', [
            'disc' => $disc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disc_delete', methods: ['POST'])]
    public function delete(Request $request, Disc $disc, DiscRepository $discRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$disc->getId(), $request->request->get('_token'))) {
            $discRepository->remove($disc, true);
        }

        return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
    }
}
