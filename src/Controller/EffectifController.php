<?php

namespace App\Controller;

use App\Entity\Effectif;
use App\Form\EffectifType;
use App\Repository\EffectifRepository;
use App\Repository\ImageRepository;
use App\Utility\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/effectif')]
class EffectifController extends AbstractController
{
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    #[Route('/', name: 'app_effectif_index', methods: ['GET'])]
    public function index(EffectifRepository $effectifRepository): Response
    {
        return $this->render('effectif/index.html.twig', [
            'effectifs' => $effectifRepository->findAll(),
        ]);
    }

    #[Route('/{activite}/new', name: 'app_effectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EffectifRepository $effectifRepository, $activite): Response
    {
        $effectif = new Effectif();
        $form = $this->createForm(EffectifType::class, $effectif, ['activite'=>$activite]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $effectifRepository->add($effectif, true);
			
			$this->utility->addFlag($activite, 2);

            return $this->redirectToRoute('app_image_new', ['effectif' => $effectif->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('effectif/new.html.twig', [
            'effectif' => $effectif,
            'form' => $form,
	        'activite' => $activite
        ]);
    }

    #[Route('/{id}', name: 'app_effectif_show', methods: ['GET'])]
    public function show(Effectif $effectif): Response
    {
        return $this->render('effectif/show.html.twig', [
            'effectif' => $effectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_effectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Effectif $effectif, EffectifRepository $effectifRepository, ImageRepository $imageRepository): Response
    {
        $form = $this->createForm(EffectifType::class, $effectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $effectifRepository->add($effectif, true);
			
			// Si l'effectif n'est pas associé a une image alors new sinon edit
	        $image = $imageRepository->findOneBy(['effectif' => $effectif->getId()]);
			if (!$image) return $this->redirectToRoute('app_image_new', ['effectif' => $effectif->getId()]);
			else return $this->redirectToRoute('app_image_edit', ['id' => $image->getId()]);

            return $this->redirectToRoute('app_effectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('effectif/edit.html.twig', [
            'effectif' => $effectif,
            'form' => $form,
	        'activite' => $effectif->getActivite()->getId()
        ]);
    }

    #[Route('/{id}', name: 'app_effectif_delete', methods: ['POST'])]
    public function delete(Request $request, Effectif $effectif, EffectifRepository $effectifRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$effectif->getId(), $request->request->get('_token'))) {
            $effectifRepository->remove($effectif, true);
        }

        return $this->redirectToRoute('app_effectif_index', [], Response::HTTP_SEE_OTHER);
    }
}
