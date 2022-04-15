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



    public function __construct(FormationRepository $formationRepository, ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
        $this->formationRepository = $formationRepository;
    }

    public function getProgress(): array
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();

        if ($this->isGranted('ROLE_USER')) {
            $formations = $this->formationRepository->findAll();
            $cours = $user->getCours();
            $formationNb = count($formations);
            $coursNb = 0;
            foreach ($cours as $lesson) {
                $this->progressRepository->findOneBy(['user' => $user, 'cours' => $lesson]) ? $progressC = $this->progressRepository->findOneBy(['user' => $user, 'cours' => $lesson]) : $progressC = new Progress();
                if ($progressC->getCoursFinished() == 1) {
                    $coursNb++;
                }
            }
            $progress = $this->progressRepository->findBy(['formationFinished' => 1, 'coursFinished' => 1]);

            $progression = ($coursNb * 100) / $formationNb;
            return array($formations, $progress, $progression);
        } else {
            $formations = $this->formationRepository->findAllFormationsOrderById();
            $progress = null;
            $progression = 0;
            return array($formations, $progress, $progression);
        }
    }
}
