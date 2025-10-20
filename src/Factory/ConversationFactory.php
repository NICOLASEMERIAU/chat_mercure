<?php

namespace App\Factory;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;

class ConversationFactory
{
    private ConversationRepository $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }
    /**
     * Crée une nouvelle conversation entre deux utilisateurs
     *
     * @param User $sender
     * @param User $recipient
     * @return Conversation
     */
    public function create(User $sender, User $recipient): Conversation
    {
        $conversation = new Conversation();
        
        $conversation->setSender($sender);
        $conversation->setRecipient($recipient);
        
        // Ajouter les deux utilisateurs à la collection users
        $conversation->addUser($sender);
        $conversation->addUser($recipient);

        $this->conversationRepository->save($conversation);
        
        return $conversation;
    }


}
