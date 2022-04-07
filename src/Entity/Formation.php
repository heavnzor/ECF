<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Repository\FormationRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Section::class, orphanRemoval: true)]
    private $section;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'formationsAuteur', cascade: ['persist', 'remove'])]
    private $auteur;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'formationsApprenants')]
    private $apprenants;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $learnState;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Cours::class)]
    private $cours;

  
    public function __construct()
    {
        $this->section = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


    /**
     * @return Collection<int, User>
     */
    public function getusersCours(): Collection
    {
        return $this->usersCours;
    }

   
   

    /**
     * @return Collection<int, Section>
     */
    public function getSection(): Collection
    {
        return $this->section;
    }

    public function addSection(Section $section): self
    {
        if (!$this->section->contains($section)) {
            $this->section[] = $section;
            $section->setFormation($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->section->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getFormation() === $this) {
                $section->setFormation(null);
            }
        }

        return $this;
    }

  



    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(User $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(User $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    public function getLearnState(): ?int
    {
        return $this->learnState;
    }

    public function setLearnState(?int $learnState): self
    {
        $this->learnState = $learnState;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setFormation($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getFormation() === $this) {
                $cour->setFormation(null);
            }
        }

        return $this;
    }

}