<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $question;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reponse1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reponse2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reponse3;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $bonnereponse;

    #[ORM\OneToOne(inversedBy: 'quizz', targetEntity: Section::class, cascade: ['persist', 'remove'])]
    private $section;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion1(): ?string
    {
        return $this->question1;
    }

    public function setQuestion1(?string $question1): self
    {
        $this->question1 = $question1;

        return $this;
    }

    public function getReponse1(): ?string
    {
        return $this->reponse1;
    }

    public function setReponse1(?string $reponse1): self
    {
        $this->reponse1 = $reponse1;

        return $this;
    }

    public function getQuestion2(): ?string
    {
        return $this->question2;
    }

    public function setQuestion2(?string $question2): self
    {
        $this->question2 = $question2;

        return $this;
    }

    public function getReponse2(): ?string
    {
        return $this->reponse2;
    }

    public function setReponse2(?string $reponse2): self
    {
        $this->reponse2 = $reponse2;

        return $this;
    }

    public function getQuestion3(): ?string
    {
        return $this->question3;
    }

    public function setQuestion3(?string $question3): self
    {
        $this->question3 = $question3;

        return $this;
    }

    public function getReponse3(): ?string
    {
        return $this->reponse3;
    }

    public function setReponse3(?string $reponse3): self
    {
        $this->reponse3 = $reponse3;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get the value of bonnereponse
     */ 
    public function getBonnereponse()
    {
        return $this->bonnereponse;
    }

    /**
     * Set the value of bonnereponse
     *
     * @return  self
     */ 
    public function setBonnereponse($bonnereponse)
    {
        $this->bonnereponse = $bonnereponse;

        return $this;
    }

    /**
     * Get the value of question
     */ 
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set the value of question
     *
     * @return  self
     */ 
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }
}
