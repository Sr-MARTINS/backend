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
    #[Route('/users', name: 'users.index', methods: ['GET'])]
    public function index(UsuariosRepository $usuariosRepository): JsonResponse 
    {
        // dd($usuariosRepository);
        return $this->json(
           $usuariosRepository->findAll(), 200, [], ['groups' => 'users']
        );

    }

    #[Route('/users', name: 'users.create', methods: ['POST'])]
    public function create( Request $request, EntityManagerInterface $entityManagerInterface,
     UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new Usuarios();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        return $this->json([
            'message' => 'User created successfully',
        ], 201);
    }

    #[Route('/users/{id}', name: 'users.update', methods: ['PUT'])]
    public function update( $id, Request $request,
     ManagerRegistry $doctrine,
     UsuariosRepository $usuariosRepository,
     UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $usuariosRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'Usuário não encontrado'], 404);
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $doctrine->getManager()->flush();

        return $this->json([
            'message' => 'User edit successfully',
        ], 201);
    }

    #[Route('/users/{id}', name: 'users.delete', methods: ['DELETE'])]
    public function delete( $id, UsuariosRepository $usuariosRepository): JsonResponse
    {
        $user = $usuariosRepository->find($id);

        $usuariosRepository->remove($user, true);

        return $this->json([ 'message' => 'User delet successfully']);
    }
}