<?php

namespace App\DataFixtures;

use App\Entity\TarefaStatus;
use App\Entity\Usuarios;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usuario = new Usuarios();
        $usuario->setName('admin');
        $usuario->setEmail('admin@admin.com');
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, '123456');
        $usuario->setPassword($hashedPassword);
        $usuario->setIsAdmin(true);
        $manager->persist($usuario);

        $statusPendete = new TarefaStatus();
        $statusPendete->setName('pendente');
        
        $statusConcluido = new TarefaStatus();
        $statusConcluido->setName('concluido');

        $manager->persist($statusPendete);
        $manager->persist($statusConcluido);

        $manager->flush();
    }
}
