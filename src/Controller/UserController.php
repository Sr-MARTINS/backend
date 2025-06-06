<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserController extends AbstractController
{
    #[Route('/usuario', name: 'usuario.index', methods: ['GET'])]
    public function index(UsuariosRepository $usuariosRepository): JsonResponse 
    {
        $usuarios = $usuariosRepository->findAll(['name' => $this->getUser()]);

        return $this->json(['data' => $usuarios], 200,[],  ['groups' => 'user'] );

    }

    #[Route('/usuario', name: 'usuario.create', methods: ['POST'])]
    public function create( Request $request, EntityManagerInterface $em,
     UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $usuarioAdm = $this->getUser();

        if(!$usuarioAdm->isAdmin()) {
            return $this->json([
                'message' => 'Você não tem permissão para essa ação.'
            ]);
        }

        $data = json_decode($request->getContent(), true);

        $usuario = new Usuarios();
        $usuario->setName($data['name']);
        $usuario->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($usuario, $data['password']);
        $usuario->setPassword($hashedPassword);
        $usuario->setIsAdmin($data['is_admin'] ?? false);

        $$em->persist($usuario);
        $$em->flush();

        return $this->json([
            'message' => 'Usuario criado com sucesso!.',
        ], 201);
        
    }

    #[Route('/usuario/{id}', name: 'usuario.update', methods: ['PUT'])]
    public function update( $id, Request $request,
     ManagerRegistry $doctrine,
     UsuariosRepository $usuariosRepository,
     UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $usuario = $usuariosRepository->find($id);

        if (!$usuario) {
            return $this->json(['error' => 'Usuário não encontrado'], 404);
        }

        $usuario->setName($data['name']);
        $usuario->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($usuario, $data['password']);
        $usuario->setPassword($hashedPassword);
        $usuario->setIsAdmin($data['is_admin']);

        $doctrine->getManager()->flush();

        return $this->json([
            'message' => 'Usuario editado com sucesso',
        ], 201);
    }

    #[Route('/usuario/{id}', name: 'usuario.delete', methods: ['DELETE'])]
    public function delete( $id, UsuariosRepository $usuariosRepository): JsonResponse
    {
        $usuario = $usuariosRepository->find($id);

        $usuariosRepository->remove($usuario, true);

        return $this->json([ 'message' => 'Usuario deletado com sucesso']);
    }
}