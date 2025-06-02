<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 80)]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_admin = false;

    /**
     * @var Collection<int, Listas>
     */
    #[ORM\OneToMany(targetEntity: Listas::class, mappedBy: 'usuarios', orphanRemoval: true)]
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
            $lista->setUsuarios($this);
        }

        return $this;
    }

    public function removeLista(Listas $lista): static
    {
        if ($this->listas->removeElement($lista)) {
            // set the owning side to null (unless already changed)
            if ($lista->getUsuarios() === $this) {
                $lista->setUsuarios(null);
            }
        }

        return $this;
    }
}
