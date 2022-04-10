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
class User implements  UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\ManyToMany(targetEntity: Cours::class, inversedBy: 'users')]
    private $cours;

    #[ORM\ManyToMany(targetEntity: Section::class, mappedBy: 'auteur')]
    private $sections;

    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'user')]
    private $formations;



    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->formation = new ArrayCollection();
        $this->formations = new ArrayCollection();
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
        if ($this->email === 'webmaster@waldganger.net') {
            $roles = array('ROLE_SUPER_ADMIN', 'ROLE_INSTRUCTEUR', 'ROLE_USER');
            return array_unique($roles);
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
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCours(Cours $cours): self
    {
        if (!$this->cours->contains($cours)) {
            $this->cours[] = $cours;
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        $this->cours->removeElement($cours);
        
        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->addAuteur($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            $section->removeAuteur($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->addUser($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeUser($this);
        }

        return $this;
    }
}
