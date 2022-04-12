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
            $formationsApp = $user->getFormations();
            $formationNb = count($formationsApp);
            $this->progressRepository->findOneBy(['user' => $user]) ? $progress = $this->progressRepository->findOneBy(['user' => $user]) : $progress = $user->getProgress();
            if ($progress !== $user->getProgress()) {
                foreach ($formationsApp as $formation) {
                    if ($formation->getProgress() && $user->getProgress() && $progress->getFormationFinished() == 1 && $formation->getAuteur() !== $user) {
                        $formationNb++;
                    }
                }
                $cours = $formation->getCours();
                $coursNb = count($cours);
                foreach ($cours as $lesson) {
                    if ($lesson->getProgress() && $user->getProgress() && $progress->getCoursFinished() == 1 && $formation->getAuteur() !== $user) {
                        $coursNb++;
                    }
                }
                $progression = ($coursNb * 100) / $formationNb;
            }else{
                $progression = 0;
            }
            $formations = $user->getFormations();
            return array($formations, $progress, $progression);
        } else {
            $formations = $this->formationRepository->findAllFormationsOrderById();
            $progress = null;
            $progression = 0;
            return array($formations, $progress, $progression);
        };
    }
}
