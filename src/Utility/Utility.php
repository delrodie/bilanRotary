<?php
	
	namespace App\Utility;
	
	use App\Repository\ActiviteRepository;
	use App\Repository\ExperienceRepository;
	use Doctrine\Persistence\ManagerRegistry;
	
	class Utility
	{
		private $managerRegistry;
		private $experienceRepository;
		private $activiteRepository;
		
		public function __construct(
			ManagerRegistry $managerRegistry,
			ExperienceRepository $experienceRepository,
			ActiviteRepository $activiteRepository
		)
		{
			$this->managerRegistry = $managerRegistry;
			$this->experienceRepository = $experienceRepository;
			$this->activiteRepository = $activiteRepository;
		}
		
		/**
		 * @param $id
		 * @param $flag
		 * @return bool
		 */
		public function addFlag($id, $flag): bool
		{
			if($flag === 2){
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
	}
