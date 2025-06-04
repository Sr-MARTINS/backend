<?php

namespace App\Entity;

use App\Repository\TarefasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TarefasRepository::class)]
class Tarefas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('user')]
    #[ORM\ManyToOne(inversedBy: 'tarefas')]
    private ?Listas $lista_id = null;

    #[Groups('user')]
    #[ORM\Column(length: 150)]
    private ?string $titulo = null;

    #[Groups('user')]
    #[ORM\ManyToOne(inversedBy: 'tarefas')]
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

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getTarefaStatus(): ?TarefaStatus
    {
        return $this->tarefa_status;
    }

    public function setTarefaStatus(?TarefaStatus $tarefa_status): static
    {
        $this->tarefa_status = $tarefa_status;
        return $this;
    }
}
