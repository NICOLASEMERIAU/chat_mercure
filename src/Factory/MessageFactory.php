<?php

namespace App\Factory;

use App\Entity\Message;
use App\Entity\User;
use App\Entity\Conversation;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageFactory
{

    public function __construct(
        private readonly EntityManagerInterface $em        )
    {
    }

    public function create(User $author, Conversation $conversation, string $content): Message
    {
        $message = new Message();
        $message->setAuthor($author);
        $message->setConversation($conversation);
        $message->setContent($content);
        $message->setCreatedAt(new \DateTimeImmutable());
        $this->em->persist($message);
        $this->em->flush();
        return $message;
    }
}
