<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ActiviteRepository;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/experience')]
class ExperienceController extends AbstractController
{
	private $activiteRepository;
	
	public function __construct(ActiviteRepository $activiteRepository)
	{
		$this->activiteRepository = $activiteRepository;
	}
	
    #[Route('/', name: 'app_experience_index', methods: ['GET'])]
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('experience/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_experience_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExperienceRepository $experienceRepository): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experienceRepository->add($experience, true);

            return $this->redirectToRoute('app_activite_new', ['experience' => $experience->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experience/new.html.twig', [
            'experience' => $experience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_experience_show', methods: ['GET'])]
    public function show(Experience $experience): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_experience_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Experience $experience, ExperienceRepository $experienceRepository): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experienceRepository->add($experience, true);
			
			// Si une activité est liée à cette experience alors rediriger vers edit sinon vers new
	        $activite = $this->activiteRepository->findOneBy(['experience' => $experience->getId()]);
			if ($activite) return $this->redirectToRoute('app_activite_edit',['id' => $activite->getId(), 'experience'=>$experience->getId()]);
			else return $this->redirectToRoute('app_activite_new',['experience' => $experience->getId()]);

            return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_experience_delete', methods: ['POST'])]
    public function delete(Request $request, Experience $experience, ExperienceRepository $experienceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $experienceRepository->remove($experience, true);
        }

        return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
