<?php

namespace App\Controller;

use App\Entity\TarefaStatus;
use App\Repository\TarefaStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TarefaStatusController extends AbstractController
{
    #[Route('/status', name: 'status.index')]
    public function index(TarefaStatusRepository $tarefaStatusRepository): JsonResponse
    {
        $status = $tarefaStatusRepository->findAll(['name' => $this->getUser()]);

        return $this->json(['data' => $status], 200,[],  ['groups' => 'user'] );

    }

    #[Route('/status', name: 'status.create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em,
     ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tarefaStatus = new TarefaStatus();
        $tarefaStatus->setName($data['name']);

        $erro = $validator->validate($tarefaStatus);

        if(count($erro) > 0) {
            $mensagens = [];

            foreach ($erro as $violacao) {
                $mensagens[] = [
                    'mensagem' => $violacao->getMessage()
                ];
            }

            return $this->json([
                'message' => 'Existem erros de validação.',
                'errors' => $mensagens
            ], 400);
        }

        $em->persist($tarefaStatus);
        $em->flush();

        return $this->json(['data' => $tarefaStatus], 201,[],  ['groups' => 'user']);
    }

    #[Route('/status/{id}', name: 'status.update', methods: ['PUT'])]
    public function update(Request $request, $id, EntityManagerInterface $em,
    ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tarefaStatus = $em->getRepository(TarefaStatus::class)->find($id);

        $tarefaStatus->setName($data['name'] ?? $tarefaStatus->getName());

        $erro = $validator->validate($tarefaStatus);

        if(count($erro) > 0) {
            $mensagens = [];

            foreach ($erro as $violacao) {
                $mensagens[] = [
                    'mensagem' => $violacao->getMessage()
                ];
            }

            return $this->json([
                'message' => 'Existem erros de validação.',
                'errors' => $mensagens
            ], 400);
        }

        $em->persist($tarefaStatus);
        $em->flush();

        return $this->json(['data' => $tarefaStatus], 201,[],  ['groups' => 'user']);
    }

    #[Route('/status/{id}', name: 'status.delete', methods: ['DELETE'])]
    public function delete( $id, TarefaStatusRepository $tarefaStatusRepository): JsonResponse
    {
        $tarefaStatus = $tarefaStatusRepository->find($id);

        $tarefaStatusRepository->remove($tarefaStatus, true);

        return $this->json([ 'message' => 'Status deletado']);
    }
}
