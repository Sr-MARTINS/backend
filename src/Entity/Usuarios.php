<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user')]
    private ?int $id = null;

    #[Groups('user')]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Groups('user')]
    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 80)]
    private ?string $password = null;

    #[Groups('user')]
    #[ORM\Column(type: 'boolean')]
    private bool $is_admin = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
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

    public function eraseCredentials(): void
    {
        // Aqui você pode limpar dados sensíveis temporários, se houver
    }
}
