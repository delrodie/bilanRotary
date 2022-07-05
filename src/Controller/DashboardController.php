<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\EffectifRepository;
use App\Repository\ExperienceRepository;
use App\Repository\FonctionnementRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
	private $experienceRepository;
	private $activiteRepository;
	private $effectifRepository;
	private $imageRepository;
	private $fonctionnementRepository;
	
	public function __construct(
		ExperienceRepository $experienceRepository,
		ActiviteRepository $activiteRepository,
		EffectifRepository $effectifRepository,
		ImageRepository $imageRepository,
		FonctionnementRepository $fonctionnementRepository
	)
	{
		$this->experienceRepository = $experienceRepository;
		$this->activiteRepository = $activiteRepository;
		$this->effectifRepository = $effectifRepository;
		$this->imageRepository = $imageRepository;
		$this->fonctionnementRepository = $fonctionnementRepository;
	}
	
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
	        'experiences' => $this->experienceRepository->findBy(['flag'=>4]),
	        'activites' => $this->activiteRepository->findBy(['flag'=>3]),
	        'effectifs' => $this->effectifRepository->findBy(['flag'=>2]),
	        'images' => $this->imageRepository->findBy(['flag'=>1]),
	        'fonctionnements' => $this->fonctionnementRepository->findAll(),
        ]);
    }
}
