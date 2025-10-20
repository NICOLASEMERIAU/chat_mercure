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
        $conversation = $this->conversationRepository->find($payload->conversationId);
        $message = $this->factory->create(
            author: $this->getUser(),
            conversation: $conversation,
            content: $payload->content
            );

        return new Response('', Response::HTTP_CREATED);
    }
}
