<?php

namespace App\Controller;

use App\DTO\CreateMessage;
use App\Entity\User;
use App\Factory\MessageFactory;
use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @method User|null getUser()
 */
final class MessageController extends AbstractController
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly MessageFactory         $factory,
    )
    {

    }
    #[Route('/messages', name: 'message.create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateMessage $payload): Response
    {
        error_log('=== MESSAGE CONTROLLER DEBUG ===');
        error_log('User authenticated: ' . ($this->getUser() ? 'YES' : 'NO'));
        error_log('User ID: ' . ($this->getUser() ? $this->getUser()->getId() : 'NULL'));
        error_log('Payload content: ' . $payload->content);
        error_log('Payload conversationId: ' . $payload->conversationId);
        
        $conversation = $this->conversationRepository->find($payload->conversationId);
        error_log('Conversation found: ' . ($conversation ? 'YES (ID: ' . $conversation->getId() . ')' : 'NO'));
        
        if (!$conversation) {
            error_log('ERROR: Conversation not found for ID: ' . $payload->conversationId);
            return new Response('Conversation not found', Response::HTTP_NOT_FOUND);
        }
        
        if (!$this->getUser()) {
            error_log('ERROR: User not authenticated');
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        
        error_log('Creating message...');
        $message = $this->factory->create(
            author: $this->getUser(),
            conversation: $conversation,
            content: $payload->content
        );
        error_log('Message created with ID: ' . $message->getId());
        error_log('=== END MESSAGE CONTROLLER DEBUG ===');

        return new Response('', Response::HTTP_CREATED);
    }
}
