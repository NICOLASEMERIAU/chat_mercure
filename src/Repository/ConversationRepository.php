<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * Trouve une conversation entre deux utilisateurs spécifiques
     *
     * @param User $sender
     * @param User $recipient
     * @return Conversation|null
     */
    public function findByUsers(User $sender, User $recipient): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('(c.sender = :sender AND c.recipient = :recipient) OR (c.sender = :recipient AND c.recipient = :sender)')
            ->setParameter('sender', $sender)
            ->setParameter('recipient', $recipient)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Conversation $conversation): void
    {
        $this->getEntityManager()->persist($conversation);
        $this->getEntityManager()->flush();
    }
}
