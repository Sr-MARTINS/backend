<?php

namespace App\Controller;

use App\Entity\Listas;
use App\Entity\Usuarios;
use App\Repository\ListasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ListController extends AbstractController
{
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function index(ListasRepository $listasRepository): JsonResponse
    {
        $listas = $listasRepository->findAll();

        return $this->json(['data' => $listas], 200,[],  ['groups' => 'user'] );
 
    } 

    #[Route('/lista/creat', name: 'list_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em)
    {
        // dd($request->request->all());
        $data = json_decode($request->getContent(), true);

        $usuarioId = $data['usuario_id'] ?? null;
        $usuario = $em->getRepository(Usuarios::class)->find($usuarioId);

        if (!$usuario) {
            return new JsonResponse(['error' => 'Usuário não encontrado'], 404);
        }

        $lista = new Listas();
        $lista->setTitulo($data['titulo'] ?? '');
        $lista->setIsPublico($data['is_publico'] ?? false);
        $lista->setUsuario($usuario);
        $lista->setCreatedAt(new \DateTimeImmutable());
        $lista->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($lista);
        $em->flush();

        return new JsonResponse(['id' => $lista->getId()], 201);
    }

    #[Route('/list/delete/{id}', name: 'list_delete', methods:['DELETE'])]
    public function delete( $id, ListasRepository $listasRepository)
    {
        $list = $listasRepository->find($id);

        $listasRepository->remove($list, true);

        return $this->json([ 'message' => 'List delet successfully']);
    }
}
