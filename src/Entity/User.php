<?php
/**
 * Class User
 *
 * @package   src/Entity
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"},                    message="Cet email existe déjà.", groups={"registration"})
 * @UniqueEntity(fields={"username"},                 message="Cet identifiant existe déjà.", groups={"registration"})
 */
class User implements UserInterface
{

    /**
     * Id User
     *
     * @var                        integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Email User
     *
     * @var                           string
     * @ORM\Column(type="string",     length=180, unique=true)
     * @Assert\NotBlank(message="Vous devez renseigner un email.", groups={"registration"})
     * @Assert\Email(message="Vous    devez renseigner un email valide.", groups={"registration"})
     */
    private $email;

    /**
     * Username User
     *
     * @var                           string
     * @ORM\Column(type="string",     length=255, unique=true)
     * @Assert\NotBlank(message="Vous devez renseigner un username.", groups={"registration"})
     */
    private $username;

    /**
     * Roles User
     *
     * @var                     object
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * Pwd User
     *
     * @var                       string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * Get Id User
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;

    }//end getId()


    /**
     * Get email User
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;

    }//end getEmail()


    /**
     * Set email User
     *
     * @param string $email Email of this User.
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;

    }//end setEmail()


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->username;

    }//end getUsername()


    /**
     * A visual modifier that represents this user.
     *
     * @param string $username Username of this User.
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;

    }//end setUsername()


    /**
     * Get role User
     *
     * @see UserInterface
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Guarantee every user at least has ROLE_USER.
        $roles[] = 'ROLE_USER';

        return array_unique($roles);

    }//end getRoles()


    /**
     * Set role User
     *
     * @param array $roles Roles of this User.
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;

    }//end setRoles()


    /**
     * Get password User
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;

    }//end getPassword()


    /**
     * Set password User
     *
     * @param string $password Pwd of this User.
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;

    }//end setPassword()


    /**
     * Get salt User
     *
     * @see UserInterface
     *
     * @return void
     */
    public function getSalt()
    {
        // Not needed when using the "bcrypt" algorithm in security.yaml.

    }//end getSalt()


    /**
     * Erase credentials User
     *
     * @see UserInterface
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here.
        // $this->plainPassword = null;.

    }//end eraseCredentials()


}//end class
