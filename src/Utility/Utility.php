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
		
		public function currentSession()
		{
			$id = $this->session->get('encours');
			 return true;
		}
	}
