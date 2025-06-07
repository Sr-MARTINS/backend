<?php

namespace App\Controller;

use App\Entity\Tarefas;
use App\Entity\TarefaStatus;
use App\Repository\ListasRepository;
use App\Repository\TarefasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TarefaController extends AbstractController
{
    #[Route('/lista/{id}/tarefas', name: 'tarefa.index', methods: ['GET'])]
    public function index($id, ListasRepository $listasRepository ): JsonResponse
    {
        $lista = $listasRepository->findOneBy(['id' => $id, 'usuario' => $this->getUser() ]);

        if(null == $lista) {
            return $this->json([
                'message' => 'Lista não encontrada'
            ], 404);
        }

        return $this->json(['data' => $lista->getTarefas()], 200,[],  ['groups' => 'user'] );
    }

    #[Route('/lista/{lista_id}/tarefas', name: 'tarefa.create', methods: ['POST'])]
    public function create( $lista_id, Request $request, EntityManagerInterface  $em,
     ListasRepository $listasRepository, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $lista = $listasRepository->findOneBy([
            'id' => $lista_id,
            'usuario' => $this->getUser()
        ]);

        if (!$lista) {
            return $this->json(['error' => 'Lista não encontrada'], 404);
        }

        $status = $em->getRepository(TarefaStatus::class)->find($data['status']);

        if (!$status) {
            return $this->json(['error' => 'Status não encontrado'], 404);
        }

        $tarefa = new Tarefas();
        $tarefa->setListaId($lista);
        $tarefa->setTitulo($data['titulo']);
        $tarefa->setTarefaStatus($status);

        $erros = $validator->validate($tarefa);

        if(count($erros) > 0) {
            $mensagens = [];

            foreach ($erros as $violacao) {
                $mensagens[] = [
                    'mensagem' => $violacao->getMessage()
                ];
            }

            return $this->json([
                'message' => 'Existem erros de validação.',
                'errors' => $mensagens
            ], 400);
        }

        $em->persist($tarefa);
        $em->flush();

        return $this->json(['data' => $tarefa], 201,[],  ['groups' => 'user']);
    }

    #[Route('/lista/{lista_id}/tarefas/{id}', name: 'tarefa.show', methods: ['GET'])]
    public function show($lista_id, $id, ListasRepository $listasRepository ,TarefasRepository $tarefasRepository): JsonResponse
    {

        $lista = $listasRepository->findOneBy(['id' => $lista_id, 'usuario' => $this->getUser()]);

        if (!$lista) {
            return $this->json(['error' => 'Status não encontrado'], 404);
        }

        $tarefa = $tarefasRepository->find($id);

        return $this->json(['data' => $tarefa], 200,[],  ['groups' => 'user'] );
    }

    #[Route('/lista/{lista_id}/tarefas/{id}/status', name: 'tarefa.status', methods: ['PUT'])]
    public function status($lista_id, $id, Request $request ,ListasRepository $listasRepository ,TarefasRepository $tarefasRepository, EntityManagerInterface  $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $lista = $listasRepository->findOneBy(['id' => $lista_id, 'usuario' => $this->getUser()]);

        if (!$lista) {
            return $this->json(['error' => 'Lista não encontrada'], 404);
        }

        $tarefa = $tarefasRepository->find($id);

        $status = $em->getRepository(TarefaStatus::class)->find($data['status']);

        if (!$status) {
            return $this->json(['error' => 'Status não encontrado'], 404);
        }
         
        if (!empty($data['titulo'])) {
            $tarefa->setTitulo($data['titulo']);
        }
        $tarefa->setTarefaStatus($status);

        $em->persist($tarefa);
        $em->flush();

        return $this->json(['data' => $tarefa], 201,[],  ['groups' => 'user']);
    }

    #[Route('/lista/{lista_id}/tarefa/{id}', name: 'tarefa.update', methods: ['PUT'])]
    public function update($lista_id, $id, Request $request, TarefasRepository $tarefasRepository,
    ListasRepository $listasRepository, EntityManagerInterface  $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $lista = $listasRepository->findOneBy(['id' => $lista_id, 'usuario' => $this->getUser()]);

        if (!$lista) {
            return $this->json(['error' => 'Lista não encontrada'], 404);
        }

        $tarefa = $tarefasRepository->find($id);

        $status = $em->getRepository(TarefaStatus::class)->find($data['status']);

        if (!$status) {
            return $this->json(['error' => 'Status não encontrado'], 404);
        }
         
        if (!empty($data['titulo'])) {
            $tarefa->setTitulo($data['titulo']);
        }
        $tarefa->setTarefaStatus($status);

        $em->persist($tarefa);
        $em->flush();

        return $this->json(['data' => $tarefa], 201,[],  ['groups' => 'user']);
    }

    #[Route('/lista/{lista_id}/tarefa/{id}', name: 'tarefa.delet', methods: ['DELETE'])]
    public function delete($lista_id, $id, TarefasRepository $tarefasRepository ,ListasRepository $listasRepository ): JsonResponse
    {
        $lista = $listasRepository->findOneBy(['id' => $lista_id, 'usuario' => $this->getUser()]);
        
        if (!$lista) {
            return $this->json(['error' => 'Lista não encontrada'], 404);
        }

        $tarefa = $tarefasRepository->find($id);

        $tarefasRepository->remove($tarefa, true);

        return $this->json([ 'message' => 'Tarefa deletada com successo']);
    }
}
