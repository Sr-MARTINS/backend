<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function index(UsuariosRepository $usuariosRepository): JsonResponse
    {
        return $this->json([
            'data' => $usuariosRepository->findAll()
        ]);

    }

    #[Route('/users/create', name: 'user_create', methods: ['POST'])]
    public function create( Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new Usuarios();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        // $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        // $user->setPassword($hashedPassword);

        $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
        ], 201);
    }
}