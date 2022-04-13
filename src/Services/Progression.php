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
            foreach ($formations as $formation) {
                $progressF = $this->progressRepository->findOneBy(['user' => $user, 'formationFinished' => 1]);
                if ($progressF->getFormationFinished() == 1 && $formation->getAuteur() !== $user) {
                    $formationNb++;
                }


                $cours = $formation->getCours();
                foreach ($cours as $lesson) {
                    $progress = $this->progressRepository->findOneBy(['user' => $user, 'coursFinished' => 1, 'formationFinished' => 1]);
                    if ($progress->getCoursFinished() == 1 && $progress->getCours() == $lesson) {
                        $coursNb++;
                    }
                }
            }
            $progression = ($coursNb * 100) / $formationNb;


            return array($formations, $progress, $progression);
        }
        else {
            $formations = $this->formationRepository->findAllFormationsOrderById();
            $progress = null;
            $progression = 0;
            return array($formations, $progress, $progression);
        }
    }
}
    

