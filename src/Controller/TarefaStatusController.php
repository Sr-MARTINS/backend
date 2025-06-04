<?php

namespace App\Controller;

use App\Entity\TarefaStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TarefaStatusController extends AbstractController
{
    #[Route('/tarefa/status', name: 'app_tarefa_status')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TarefaStatusController.php',
        ]);
    }

    #[Route('/status', name: 'status.create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tarefaStatus = new TarefaStatus();
        $tarefaStatus->setName($data['name']);

        $em->persist($tarefaStatus);
        $em->flush();

        return $this->json(['data' => $tarefaStatus], 201,[],  ['groups' => 'user']);
    }
}
