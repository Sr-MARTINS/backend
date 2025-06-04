<?php

namespace App\Controller;

use App\Entity\Listas;
use App\Entity\Tarefas;
use App\Entity\TarefaStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TarefaController extends AbstractController
{
    #[Route('/tarefa', name: 'app_tarefa')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TarefaController.php',
        ]);
    }

    #[Route('/lista/{id}/tarefa', name: 'tarefa.create', methods: ['POST'])]
    public function create(Request $request, $id, EntityManagerInterface  $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $lista = $em->getRepository(Listas::class)->find($id);
        if (!$lista) {
            return $this->json(['error' => 'Lista não encontrada'], 404);
        }

        $status = $em->getRepository(TarefaStatus::class)->findOneBy(['name' => $data['status']]);

        if (!$status) {
            return $this->json(['error' => 'Status não encontrado'], 404);
        }

        $tarefa = new Tarefas();
        $tarefa->setListaId($lista);
        $tarefa->setTitulo($data['titulo']);
        $tarefa->setTarefaStatus($status);

        $em->persist($tarefa);
        $em->flush();

        return $this->json(['data' => $tarefa], 201,[],  ['groups' => 'user']);
    }
}
