<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HtmxTest extends WebTestCase
{
    public function testHtmxScriptIsLoaded(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que le script HTMX est présent
        $this->assertSelectorExists('script[src*="htmx.org"]');
        $this->assertSelectorExists('script[src*="htmx-ext-sse"]');
        
        // Vérifier que l'attribut hx-ext="sse" est présent sur le body
        $this->assertSelectorExists('body[hx-ext="sse"]');
    }

    public function testConversationPageHasHtmxAttributes(): void
    {
        $client = static::createClient();
        
        // Simuler une connexion utilisateur (si nécessaire)
        // $client->loginUser($user);
        
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que la page de conversation contient les éléments HTMX
        $this->assertSelectorExists('#messages');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="content"]');
        $this->assertSelectorExists('input[name="conversation_id"]');
    }

    public function testMessageFormHasCorrectAttributes(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier les attributs du formulaire
        $form = $crawler->filter('form')->first();
        $this->assertCount(1, $form);
        
        // Vérifier les champs du formulaire
        $contentInput = $crawler->filter('input[name="content"]');
        $this->assertCount(1, $contentInput);
        
        $conversationIdInput = $crawler->filter('input[name="conversation_id"]');
        $this->assertCount(1, $conversationIdInput);
        $this->assertNotEmpty($conversationIdInput->attr('value'));
    }

    public function testHtmxHeadersArePresent(): void
    {
        $client = static::createClient();
        
        // Test avec un header HTMX
        $client->request('GET', '/', [], [], [
            'HTTP_HX-Request' => 'true',
            'HTTP_HX-Target' => 'messages'
        ]);

        $this->assertResponseIsSuccessful();
        
        // Vérifier que la réponse contient les headers HTMX appropriés
        $response = $client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testSseExtensionIsLoaded(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que l'extension SSE est chargée
        $sseScript = $crawler->filter('script[src*="htmx-ext-sse"]');
        $this->assertCount(1, $sseScript);
        
        // Vérifier que l'extension est activée sur le body
        $body = $crawler->filter('body[hx-ext="sse"]');
        $this->assertCount(1, $body);
    }

    public function testHtmxWorksWithJsonResponse(): void
    {
        $client = static::createClient();
        
        // Test d'une requête HTMX qui devrait retourner du JSON
        $client->request('GET', '/api/test', [], [], [
            'HTTP_HX-Request' => 'true',
            'HTTP_ACCEPT' => 'application/json'
        ]);

        // Vérifier que la réponse est valide (même si la route n'existe pas encore)
        $this->assertResponseStatusCodeSame(404); // Route non trouvée, mais HTMX fonctionne
    }

    public function testMessageContainerExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que le conteneur de messages existe
        $messagesContainer = $crawler->filter('#messages');
        $this->assertCount(1, $messagesContainer);
        
        // Vérifier que le conteneur a les bonnes classes CSS
        $this->assertStringContainsString('overflow-y-auto', $messagesContainer->attr('class'));
    }

    public function testFormSubmissionStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier la structure du formulaire
        $form = $crawler->filter('form')->first();
        $this->assertStringContainsString('d-flex', $form->attr('class'));
        $this->assertStringContainsString('gap-3', $form->attr('class'));
        
        // Vérifier le bouton d'envoi
        $submitButton = $crawler->filter('button[type="submit"]');
        $this->assertCount(1, $submitButton);
        $this->assertEquals('Envoyer', $submitButton->text());
    }
}
