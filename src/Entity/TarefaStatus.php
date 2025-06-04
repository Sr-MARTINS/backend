<?php

namespace App\Entity;

use App\Repository\TarefaStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarefaStatusRepository::class)]
class TarefaStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'tarefa_status', targetEntity: Tarefas::class)]
    private Collection $tarefas;

    public function __construct()
    {
        $this->tarefas = new ArrayCollection();
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
            $this->tarefas[] = $tarefa;
            $tarefa->setTarefaStatus($this);
        }

        return $this;
    }

    public function removeTarefa(Tarefas $tarefa): static
    {
        if ($this->tarefas->removeElement($tarefa)) {
            // Set the owning side to null (unless already changed)
            if ($tarefa->getTarefaStatus() === $this) {
                $tarefa->setTarefaStatus(null);
            }
        }

        return $this;
    }
}
