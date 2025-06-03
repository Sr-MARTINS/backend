<?php

namespace App\Entity;

use App\Repository\TarefasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarefasRepository::class)]
class Tarefas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tarefas')]
    private ?Listas $lista_id = null;

    #[ORM\Column(length: 150)]
    private ?string $tiulo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TarefaStatus $tarefa_status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListaId(): ?Listas
    {
        return $this->lista_id;
    }

    public function setListaId(?Listas $lista_id): static
    {
        $this->lista_id = $lista_id;

        return $this;
    }

    public function getTiulo(): ?string
    {
        return $this->tiulo;
    }

    public function setTiulo(string $tiulo): static
    {
        $this->tiulo = $tiulo;

        return $this;
    }

    public function getTarefaStatusId(): ?TarefaStatus
    {
        return $this->tarefa_status;
    }

    public function setTarefaStatusId(TarefaStatus $tarefa_status): static
    {
        $this->tarefa_status = $tarefa_status;

        return $this;
    }
}
