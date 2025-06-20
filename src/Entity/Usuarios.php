<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user')]
    private ?int $id = null;

    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'O campo deve ter no minimo: 3 caracters!',
        maxMessage: 'O campo deve ter no maximo: 50 caracters!',
    )]
    #[Groups('user')]
    #[ORM\Column(length: 50)]
    private ?string $name = null;


    #[Assert\Email(message: 'Necessario que o email seja valido.')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'O campo deve ter no minimo: 3 caracters!',
        maxMessage: 'O campo deve ter no maximo: 50 caracters!',
    )]
    #[Groups('user')]
    #[ORM\Column(length: 150)]
    private ?string $email = null;

   #[Assert\NotBlank([], 'O campo deve ser preenchido')]
    #[ORM\Column(length: 80)]
    private ?string $password = null;

    // #[Assert\NotBlank]
    #[Groups('user')]
    #[ORM\Column(type: 'boolean')]
    private bool $is_admin = false;


    /**
     * @var Collection<int, Listas>
     */
    #[Groups('user')]
    #[ORM\OneToMany(targetEntity: Listas::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $listas;

    public function __construct()
    {
        $this->listas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->is_admin = $isAdmin;
        return $this;
    }



    /**
     * @return Collection<int, Listas>
     */
    public function getListas(): Collection
    {
        return $this->listas;
    }

    public function addLista(Listas $lista): static
    {
        if (!$this->listas->contains($lista)) {
            $this->listas->add($lista);
            $lista->setUsuario($this);
        }

        return $this;
    }

    public function removeLista(Listas $lista): static
    {
        if ($this->listas->removeElement($lista)) {
            if ($lista->getUsuario() === $this) {
                $lista->setUsuario(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? '';
    }

    public function getRoles(): array
    {
        return $this->is_admin ? ['ROLE_ADMIN'] : ['ROLE_USER'];
    }

    public function eraseCredentials(): void {}
    
}
