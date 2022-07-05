<?php
	
	namespace App\Utility;
	
	use App\Repository\ActiviteRepository;
	use App\Repository\EffectifRepository;
	use App\Repository\ExperienceRepository;
	use App\Repository\ImageRepository;
	use Doctrine\Persistence\ManagerRegistry;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	
	class Utility
	{
		private $managerRegistry;
		private $experienceRepository;
		private $activiteRepository;
		/**
		 * @var EffectifRepository
		 */
		private $effectifRepository;
		/**
		 * @var ImageRepository
		 */
		private $imageRepository;
		/**
		 * @var SessionInterface
		 */
		private $session;
		/**
		 * @var UrlGeneratorInterface
		 */
		private $urlGenerator;
		
		public function __construct(
			ManagerRegistry $managerRegistry,
			ExperienceRepository $experienceRepository,
			ActiviteRepository $activiteRepository,
			EffectifRepository $effectifRepository,
			ImageRepository  $imageRepository,
			SessionInterface $session,
			UrlGeneratorInterface $urlGenerator
		)
		{
			$this->managerRegistry = $managerRegistry;
			$this->experienceRepository = $experienceRepository;
			$this->activiteRepository = $activiteRepository;
			$this->effectifRepository = $effectifRepository;
			$this->imageRepository = $imageRepository;
			$this->session = $session;
			$this->urlGenerator = $urlGenerator;
		}
		
		/**
		 * @param $id
		 * @param $flag
		 * @return bool
		 */
		public function addFlag($id, $flag): bool
		{
			if($flag === 4){
				$image = $this->imageRepository->findOneBy(['id' => $id]);
				$effectif = $this->effectifRepository->findOneBy(['id' => $image->getEffectif()->getId()]);
				$activite = $this->activiteRepository->findOneBy(['id' => $effectif->getActivite()->getId()]);
				$experience = $this->experienceRepository->findOneBy(['id' => $activite->getExperience()->getId()]);
				$image->setFlag(1);
				$effectif->setFlag(2);
				$activite->setFlag(3);
				$experience->setFlag($flag);
			}
			elseif ($flag === 3){
				$effectif = $this->effectifRepository->findOneBy(['id' => $id]); //dd($effectif);
				$activite = $this->activiteRepository->findOneBy(['id' => $effectif->getActivite()->getId()]);
				$experience = $this->experienceRepository->findOneBy(['id' => $activite->getExperience()->getId()]);
				$effectif->setFlag(1);
				$activite->setFlag(2);
				$experience->setFlag($flag);
			}
			elseif($flag === 2){
				$activite = $this->activiteRepository->findOneBy(['id' => $id]);
				$experience = $this->experienceRepository->findOneBy(['id' => $activite->getExperience()->getId()]);
				$activite->setFlag(1);
				$experience->setFlag($flag);
			}
			elseif ($flag === 1){
				$experience = $this->experienceRepository->findOneBy(['id'=>$id]);
				$experience->setFlag($flag);
			}else{
				return false;
			}
			
			$this->managerRegistry->getManager()->flush();
			
			return true;
		}
		
		/**
		 * @param $experience
		 * @return bool
		 */
		public function newSession($experience): bool
		{
			$this->session->set('encours', $experience);
			
			return true;
		}
		
		/**
		 * @throws \Doctrine\ORM\NonUniqueResultException
		 */
		public function currentSession()
		{
			$experience = $this->experienceRepository->findOneBy(['id' => $this->session->get('encours')]);
			
			// Si aucune experience alors initialiser le formulaire
			// Sinon rediriger vers le formulaire adequat
			if (!$experience) return false;
			else{
				$flag = $experience->getFlag();
				if (!$flag){
					$url = $this->urlGenerator->generate('app_activite_new',['experience' => $experience->getId()]);
				}
				elseif($flag === 1){
					$activite = $this->activiteRepository->findOneBy(['experience' => $experience->getId()]);
					$url = $this->urlGenerator->generate('app_effectif_new',['activite' => $activite->getId()]);
				}
				elseif($flag === 2){
					$effectif = $this->effectifRepository->findOneByExperience($experience->getId());
					$url = $this->urlGenerator->generate('app_image_new', ['effectif' => $effectif->getId()]);
				}
				elseif ($flag === 3){
					$image = $this->imageRepository->findOneByExperience($experience->getId());
					$url = $this->urlGenerator->generate('app_fonctionnement_new',['image' => $image->getId()]);
				}
				else{
					$url = $this->urlGenerator->generate('app_home_bilan');
				}
				
				return $url;
			}
		}
		
		public function clearSession(): bool
		{
			$this->session->clear();
			
			return true;
		}
	}
