<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Repository\EffectifRepository;
use App\Utility\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activite')]
class ActiviteController extends AbstractController
{
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    #[Route('/', name: 'app_activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/{experience}/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActiviteRepository $activiteRepository, $experience): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite,['experience'=>$experience]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteRepository->add($activite, true);
			
			$this->utility->addFlag($experience, 1);

            return $this->redirectToRoute('app_effectif_new', ['activite'=> $activite->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
	        'experience' => $experience
        ]);
    }

    #[Route('/{id}', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, ActiviteRepository $activiteRepository, EffectifRepository $effectifRepository): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteRepository->add($activite, true);
			
			// Si une activité est liée a cet effectif alors redirigé vers edit sinon new
	        $effectif = $effectifRepository->findOneBy(['activite' => $activite->getId()]);
			if ($effectif) return $this->redirectToRoute('app_effectif_edit',['id' => $effectif->getId()]);
			else return  $this->redirectToRoute('app_effectif_new',['activite'=> $activite->getId()]);

            //return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
	        'experience' => $activite->getExperience()->getId()
        ]);
    }

    #[Route('/{id}', name: 'app_activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, ActiviteRepository $activiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $activiteRepository->remove($activite, true);
        }

        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }
}
