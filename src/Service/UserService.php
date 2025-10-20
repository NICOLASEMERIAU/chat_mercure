<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère tous les utilisateurs
     *
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->userRepository->findBy([], ['username' => 'ASC']);
    }

    /**
     * Récupère un utilisateur par son ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Récupère un utilisateur par son nom d'utilisateur
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    /**
     * Sauvegarde un utilisateur
     *
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Supprime un utilisateur
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
