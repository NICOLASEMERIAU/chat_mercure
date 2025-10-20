<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Debug: log current user status
        $currentUser = $this->getUser();
        error_log('Current user: ' . ($currentUser ? $currentUser->getUserIdentifier() : 'null'));
        
        if ($currentUser) {
            error_log('User is authenticated, redirecting to home');
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Debug: log authentication attempts
        if ($error) {
            error_log('Login error: ' . $error->getMessageKey() . ' - ' . $error->getMessage());
        }

        error_log('Rendering login form for user: ' . $lastUsername);

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/login_check', name: 'app_login_check')]
    public function check(): void
    {
        error_log('Login check method called - this should be intercepted by Symfony Security');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
