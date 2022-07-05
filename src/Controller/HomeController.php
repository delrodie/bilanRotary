<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_experience_new');
    }
	
	#[Route('/bilan', name:'app_home_bilan')]
	public function bilan()
	{
		return $this->render('home/index.html.twig');
	}
}
