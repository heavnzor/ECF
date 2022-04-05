<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
class Section
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $titre;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: Cours::class)]
    private $cours;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'section')]
    #[ORM\JoinColumn(nullable: false)]
    private $formation;

    #[ORM\OneToOne(mappedBy: 'section', targetEntity: Quizz::class, cascade: ['persist', 'remove'])]
    private $quizz;

    public function __construct()
    {
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

   



    public function __toString()
    {
        return $this->formation;
    }


    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cours): self
    {
        if (!$this->cours->contains($cours)) {
            $this->cours[] = $cours;
            $cours->setSection($this);
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        if ($this->cours->removeElement($cours)) {
            // set the owning side to null (unless already changed)
            if ($cours->getSection() === $this) {
                $cours->setSection(null);
            }
        }

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

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): self
    {
        // unset the owning side of the relation if necessary
        if ($quizz === null && $this->quizz !== null) {
            $this->quizz->setSection(null);
        }

        // set the owning side of the relation if necessary
        if ($quizz !== null && $quizz->getSection() !== $this) {
            $quizz->setSection($this);
        }

        $this->quizz = $quizz;

        return $this;
    }
}
