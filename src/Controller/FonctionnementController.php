<?php

namespace App\Controller;

use App\Entity\Fonctionnement;
use App\Form\FonctionnementType;
use App\Repository\FonctionnementRepository;
use App\Utility\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fonctionnement')]
class FonctionnementController extends AbstractController
{
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    #[Route('/', name: 'app_fonctionnement_index', methods: ['GET'])]
    public function index(FonctionnementRepository $fonctionnementRepository): Response
    {
        return $this->render('fonctionnement/index.html.twig', [
            'fonctionnements' => $fonctionnementRepository->findAll(),
        ]);
    }

    #[Route('/{image}/new', name: 'app_fonctionnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FonctionnementRepository $fonctionnementRepository, $image): Response
    {
        $fonctionnement = new Fonctionnement();
        $form = $this->createForm(FonctionnementType::class, $fonctionnement, ['image' => $image]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fonctionnementRepository->add($fonctionnement, true);
			
			$this->utility->addFlag($image, 4);

            return $this->redirectToRoute('app_home_bilan', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fonctionnement/new.html.twig', [
            'fonctionnement' => $fonctionnement,
            'form' => $form,
	        'image' => $image
        ]);
    }

    #[Route('/{id}', name: 'app_fonctionnement_show', methods: ['GET'])]
    public function show(Fonctionnement $fonctionnement): Response
    {
        return $this->render('fonctionnement/show.html.twig', [
            'fonctionnement' => $fonctionnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fonctionnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fonctionnement $fonctionnement, FonctionnementRepository $fonctionnementRepository): Response
    {
        $form = $this->createForm(FonctionnementType::class, $fonctionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fonctionnementRepository->add($fonctionnement, true);

            return $this->redirectToRoute('app_fonctionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fonctionnement/edit.html.twig', [
            'fonctionnement' => $fonctionnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonctionnement_delete', methods: ['POST'])]
    public function delete(Request $request, Fonctionnement $fonctionnement, FonctionnementRepository $fonctionnementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fonctionnement->getId(), $request->request->get('_token'))) {
            $fonctionnementRepository->remove($fonctionnement, true);
        }

        return $this->redirectToRoute('app_fonctionnement_index', [], Response::HTTP_SEE_OTHER);
    }
}
