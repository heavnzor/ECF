<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte inscrit avec cet email.')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;
    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    private $pseudo;
    #[ORM\Column(type: 'string', nullable: true)]
    private $nom;
    #[ORM\Column(type: 'string', nullable: true)]
    private $prenom;
    #[ORM\Column(type: 'text', nullable: true)]
    private $description;
    #[ORM\Column(type: 'string', nullable: true)]
    private $photo;
    #[ORM\Column(type: 'float', nullable: true)]
    private $progress;
    #[ORM\Column(type: 'json', nullable: true)]
    private $roles = [];
    #[ORM\Column(type: 'string', nullable: false)]
    private $password;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isPostulant = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isPostulantVerified = false;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Formation::class)]
    private $formationsAuteur;

    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'apprenants')]
    private $formationsApprenants;

    #[ORM\ManyToMany(targetEntity: Cours::class, inversedBy: 'users')]
    private $cours;

    public function __construct()
    {
        $this->formationsAuteur = new ArrayCollection();
        $this->formationsApprenants = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }






    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if ($this->email === 'webmaster@waldganger.net'); {
            $roles = array('ROLE_SUPER_ADMIN');
            return $roles;
        }
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function __toString()
    {
        return $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of nom
     */
    public function getNom()
    {
        return $this->nom;
    }


    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of progress
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set the value of progress
     *
     * @return  self
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @return  self
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }




    /**
     * Get the value of isPostulant
     */
    public function getIsPostulant()
    {
        return $this->isPostulant;
    }

    /**
     * Set the value of isPostulant
     *
     * @return  self
     */
    public function setIsPostulant($isPostulant)
    {
        $this->isPostulant = $isPostulant;

        return $this;
    }

    /**
     * Get the value of isPostulantVerified
     */
    public function getIsPostulantVerified()
    {
        return $this->isPostulantVerified;
    }

    /**
     * Set the value of isPostulantVerified
     *
     * @return  self
     */
    public function setIsPostulantVerified($isPostulantVerified)
    {
        $this->isPostulantVerified = $isPostulantVerified;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }


    /**
     * @return Collection<int, Formation>
     */
    public function getFormationsAuteur(): Collection
    {
        return $this->formationsAuteur;
    }

    public function addFormationsAuteur(Formation $formationsAuteur): self
    {
        if (!$this->formationsAuteur->contains($formationsAuteur)) {
            $this->formationsAuteur[] = $formationsAuteur;
            $formationsAuteur->setAuteur($this);
        }

        return $this;
    }

    public function removeFormationsAuteur(Formation $formationsAuteur): self
    {
        if ($this->formationsAuteur->removeElement($formationsAuteur)) {
            // set the owning side to null (unless already changed)
            if ($formationsAuteur->getAuteur() === $this) {
                $formationsAuteur->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormationsApprenants(): Collection
    {
        return $this->formationsApprenants;
    }

    public function addFormationsApprenant(Formation $formationsApprenant): self
    {
        if (!$this->formationsApprenants->contains($formationsApprenant)) {
            $this->formationsApprenants[] = $formationsApprenant;
            $formationsApprenant->addApprenant($this);
        }

        return $this;
    }

    public function removeFormationsApprenant(Formation $formationsApprenant): self
    {
        if ($this->formationsApprenants->removeElement($formationsApprenant)) {
            $formationsApprenant->removeApprenant($this);
        }

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
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        $this->cours->removeElement($cour);

        return $this;
    }
}
