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

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $question1;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $question2;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $reponse1;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $reponse2;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $reponse3;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $reponse4;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $bonnereponse1;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $bonnereponse2;

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
    public function getBonnereponse1()
    {
        return $this->bonnereponse1;
    }

    /**
     * Set the value of bonnereponse
     *
     * @return  self
     */ 
    public function setBonnereponse1($bonnereponse1)
    {
        $this->bonnereponse1 = $bonnereponse1;

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

    /**
     * Get the value of reponse4
     */ 
    public function getReponse4()
    {
        return $this->reponse4;
    }

    /**
     * Set the value of reponse4
     *
     * @return  self
     */ 
    public function setReponse4($reponse4)
    {
        $this->reponse4 = $reponse4;

        return $this;
    }

    /**
     * Get the value of bonnereponse2
     */ 
    public function getBonnereponse2()
    {
        return $this->bonnereponse2;
    }

    /**
     * Set the value of bonnereponse2
     *
     * @return  self
     */ 
    public function setBonnereponse2($bonnereponse2)
    {
        $this->bonnereponse2 = $bonnereponse2;

        return $this;
    }
}
