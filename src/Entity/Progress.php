<?php

namespace App\Entity;

use App\Entity\Cours;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProgressRepository;

#[ORM\Entity(repositoryClass: ProgressRepository::class)]
class Progress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy:'progress')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'progress')]
    #[ORM\JoinColumn(nullable: false)]
    private $formation;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $formationFinished = 0;

    #[ORM\ManyToOne(targetEntity: Cours::class, inversedBy: 'progress')]
    #[ORM\JoinColumn(nullable: false)]
    private $cours;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $coursFinished = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoursFinished(): ?bool
    {
        return $this->coursFinished;
    }

    public function setCoursFinished(?bool $coursFinished): self
    {
        $this->coursFinished = $coursFinished;

        return $this;
    }

    public function getFormationFinished(): ?bool
    {
        return $this->formationFinished;
    }

    public function setFormationFinished(?bool $formationFinished): self
    {
        $this->formationFinished = $formationFinished;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    
}
