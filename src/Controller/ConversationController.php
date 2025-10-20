<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Factory\ConversationFactory;
use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null getUser()
 */
final class ConversationController extends AbstractController
{

    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly ConversationFactory $factory
    )
    {

    }

    #[Route('/conversation/users/{recipient}', name: 'conversation_show')]
    public function index(?User $recipient): Response
    {
        $sender = $this->getUser();
        $conversation = $this->conversationRepository->findByUsers($sender, $recipient);

        if (!$conversation) {
            $conversation = $this->factory->create($sender, $recipient);
        }

        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation,
            'recipient' => $recipient,
        ]);
    }
}
