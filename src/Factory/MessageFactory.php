<?php

namespace App\Factory;

use App\Entity\Message;
use App\Entity\User;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;

class MessageFactory
{

    public function __construct(
        private readonly EntityManagerInterface $em        )
    {
    }

    public function create(User $author, Conversation $conversation, string $content): Message
    {
        error_log('=== MESSAGE FACTORY DEBUG ===');
        error_log('Author ID: ' . $author->getId());
        error_log('Conversation ID: ' . $conversation->getId());
        error_log('Content: ' . $content);
        
        $message = new Message();
        $message->setAuthor($author);
        $message->setConversation($conversation);
        $message->setContent($content);
        $message->setCreatedAt(new \DateTimeImmutable());
        
        error_log('Message object created, persisting...');
        $this->em->persist($message);
        error_log('Message persisted, flushing...');
        $this->em->flush();
        error_log('Message flushed successfully, ID: ' . $message->getId());
        error_log('=== END MESSAGE FACTORY DEBUG ===');
        
        return $message;
    }
}
