<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\User;

use App\Entity\Cours;
use App\Entity\Section;
use App\Entity\Formation;
use Doctrine\Common\Collections\Collection;

/**
 * Represents the interface that all user classes must implement.
 *
 * This interface is useful because the authentication layer can deal with
 * the object through its lifecycle, using the object to get the hashed
 * password (for checking against a submitted password), assigning roles
 * and so on.
 *
 * Regardless of how your users are loaded or where they come from (a database,
 * configuration, web service, etc.), you will have a class that implements
 * this interface. Objects that implement this interface are created and
 * loaded by different objects that implement UserProviderInterface.
 *
 * @see UserProviderInterface
 *
 * @method string getUserIdentifier() returns the identifier for this user (e.g. its username or email address)
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface UserInterface
{
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles();

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the hashed password. On authentication, a plain-text
     * password will be hashed, and then compared to this value.
     *
     * This method is deprecated since Symfony 5.3, implement it from {@link PasswordAuthenticatedUserInterface} instead.
     *
     * @return string|null
     */
    public function getPassword();

    /**
     * Returns the salt that was originally used to hash the password.
     *
     * This can return null if the password was not hashed using a salt.
     *
     * This method is deprecated since Symfony 5.3, implement it from {@link LegacyPasswordAuthenticatedUserInterface} instead.
     *
     * @return string|null
     */
    public function getSalt();

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials();

    /**
     * @return string
     *
     * @deprecated since Symfony 5.3, use getUserIdentifier() instead
     */
    public function getUsername();

    /**
     * @return string
     *
     */
    public function getUserIdentifier();

    /**
     * @return ?string
     */
    public function getEmail();

    /**
     * @return ?int
     */
    public function getId();

    /**
     * @return self
     */
    public function setIsVerified(bool $isVerified);



    public function setEmail(string $email): self;


    public function setRoles(array $roles): self;



    /**
     * @see PasswordAuthenticatedUserInterface
     */

    public function setPassword(string $password): self;

    /**
     * Get the value of prenom
     */
    public function getPrenom();

    /**
     * Set the value of prenom
     *@return  self
     */
    public function setPrenom($prenom);

    /**
     * Set the value of nom
     *
     * @return  self
     */
    public function setNom($nom);

    /**
     * Get the value of nom
     */
    public function getNom();


    /**
     * Get the value of description
     */
    public function getDescription();

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description);

    /**
     * Get the value of photo
     */
    public function getPhoto();
    /**
     * Set the value of photo
     *
     * @return  self
     */
    public function setPhoto($photo);

    /**
     * Get the value of progress
     */
    public function getProgress();
    /**
     * Set the value of progress
     *
     * @return  self
     */
    public function setProgress($progress);
    public function isVerified(): bool;
    /**
     * Get the value of pseudo
     */
    public function getPseudo();
    /**
     * Set the value of pseudo
     *
     * @return  self
     */
    public function setPseudo($pseudo);
    /**
     * @return Collection<int, Formations>
     */
    /**
     * @return Collection<int, Cours>
     */

    /**
     * Get the value of isPostulant
     */
    public function getIsPostulant();

    /**
     * Set the value of isPostulant
     *
     * @return  self
     */
    public function setIsPostulant($isPostulant);

    /**
     * @return Collection<int, Formation>
     */
    public function getFormationsAuteur(): Collection;

    public function addFormationsAuteur(Formation $formationsAuteur): self;

    public function removeFormationsAuteur(Formation $formationsAuteur): self;
    /**
     * @return Collection<int, Formation>
     */
    public function getFormationsApprenants(): Collection;
    public function addFormationsApprenant(Formation $formationsApprenant): self;
    public function removeFormationsApprenant(Formation $formationsApprenant): self;
    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection;

    public function addSection(Section $section): self;

    public function removeSection(Section $section): self;
    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection;
    public function addCour(Cours $cour): self;

    public function removeCour(Cours $cour): self;
}
