<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Progress;
use App\Repository\ProgressRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class Progression extends AbstractController
{
    private $formationRepository;
    private $progressRepository;
    private $progress;


    public function __construct(FormationRepository $formationRepository, ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
        $this->formationRepository = $formationRepository;
    }

    public function getProgress(): array
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();

        if ($this->isGranted('ROLE_USER')) {
            $formations = $user->getFormations();
            $formationNb = 0;
            $coursNb = 0;
            $this->progressRepository->findOneBy(['user' => $user, 'formationFinished' => 1]) ? $progressF = $this->progressRepository->findOneBy(['user' => $user, 'formationFinished' => 1]) : $progressF = new Progress();
                $this->progressRepository->findOneBy(['user' => $user, 'coursFinished' => 1]) ? $progress = $this->progressRepository->findOneBy(['user' => $user, 'coursFinished' => 1]) : $progress = new Progress();

            foreach ($formations as $formation) {
                if ($formation->getAuteur() !== $user && $progressF->getFormationFinished() == 1) {
                    $formationNb++;
                }
                $cours = $formation->getCours();
                foreach ($cours as $lesson) {
                    if ($progress->getCours() == $lesson && $progress->getCoursFinished() == 1) {
                        $coursNb++;
                    }
                }
            }
            $progression = ($coursNb * 100) / $formationNb;
           $user->getProgress() ?  $progress = $user->getProgress() : $progress = new Progress;

            return array($formations, $progressF, $progression);
        }
        else {
            $formations = $this->formationRepository->findAllFormationsOrderById();
            $progress = null;
            $progression = 0;
            return array($formations, $progress, $progression);
        }
    }
}
    

