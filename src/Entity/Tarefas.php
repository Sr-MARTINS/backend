<?php

namespace App\Entity;

use App\Repository\TarefasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TarefasRepository::class)]
class Tarefas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user')]
    private ?int $id = null;

    #[Groups('user')]
    #[ORM\ManyToOne(inversedBy: 'tarefas')]
    private ?Listas $lista = null;

    #[Assert\Length(
        min: 3,
        max: 150,
        minMessage: 'O nome deve ter no minimo: 3 caracters',
        maxMessage: 'O nome deve ter no maximo: 50 caracters',
    )]
    #[Groups('user')]
    #[ORM\Column(length: 150)]
    private ?string $titulo = null;

    #[Groups('user')]
    #[ORM\ManyToOne(inversedBy: 'tarefas')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?TarefaStatus $tarefa_status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListaId(): ?Listas
    {
        return $this->lista;
    }

    public function setListaId(?Listas $lista): static
    {
        $this->lista = $lista;
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
