<?php

namespace App\Entity;

use App\Repository\ListasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListasRepository::class)]
class Listas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $titulo = null;

    #[ORM\ManyToOne(inversedBy: 'listas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuarios $usuario_id = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_publico = false;

    /**
     * @var Collection<int, Tarefas>
     */
    #[ORM\OneToMany(targetEntity: Tarefas::class, mappedBy: 'lista_id')]
    private Collection $tarefas;

    public function __construct()
    {
        $this->tarefas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getUsuarios(): ?Usuarios
    {
        return $this->usuario_id;
    }

    public function setUsuarios(?Usuarios $usuario_id): static
    {
        $this->usuario_id = $usuario_id;

        return $this;
    }

     public function isAdmin(): bool
    {
        return $this->is_publico;
    }

    public function setIsAdmin(bool $isPublico): static
    {
        $this->is_publico = $isPublico;
        return $this;
    }

    /**
     * @return Collection<int, Tarefas>
     */
    public function getTarefas(): Collection
    {
        return $this->tarefas;
    }

    public function addTarefa(Tarefas $tarefa): static
    {
        if (!$this->tarefas->contains($tarefa)) {
            $this->tarefas->add($tarefa);
            $tarefa->setListaId($this);
        }

        return $this;
    }

    public function removeTarefa(Tarefas $tarefa): static
    {
        if ($this->tarefas->removeElement($tarefa)) {
            // set the owning side to null (unless already changed)
            if ($tarefa->getListaId() === $this) {
                $tarefa->setListaId(null);
            }
        }

        return $this;
    }
}
