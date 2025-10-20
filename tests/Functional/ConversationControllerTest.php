<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConversationControllerTest extends WebTestCase
{
    public function testConversationShowPageLoads(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Conversation avec');
    }

    public function testConversationPageHasRequiredElements(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier les éléments essentiels de la page
        $this->assertSelectorExists('.card');
        $this->assertSelectorExists('.card-header');
        $this->assertSelectorExists('.card-body');
        $this->assertSelectorExists('#messages');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="content"]');
        $this->assertSelectorExists('input[name="conversation_id"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    public function testConversationFormHasCorrectAction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que le formulaire a les bons attributs
        $form = $crawler->filter('form')->first();
        $this->assertCount(1, $form);
        
        // Vérifier les champs du formulaire
        $contentInput = $crawler->filter('input[name="content"]');
        $this->assertCount(1, $contentInput);
        $this->assertEquals('Message', $contentInput->attr('placeholder'));
        
        $conversationIdInput = $crawler->filter('input[name="conversation_id"]');
        $this->assertCount(1, $conversationIdInput);
        $this->assertNotEmpty($conversationIdInput->attr('value'));
    }

    public function testConversationPageWithHtmxHeaders(): void
    {
        $client = static::createClient();
        
        // Simuler une requête HTMX
        $crawler = $client->request('GET', '/conversation/users/1', [], [], [
            'HTTP_HX-Request' => 'true',
            'HTTP_HX-Target' => 'messages'
        ]);

        $this->assertResponseIsSuccessful();
        
        // Vérifier que la page répond correctement aux requêtes HTMX
        $this->assertSelectorExists('#messages');
    }

    public function testConversationPageWithDifferentRecipient(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/2');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que la page se charge avec un autre destinataire
        $this->assertSelectorExists('.card');
        $this->assertSelectorExists('input[name="conversation_id"]');
    }

    public function testConversationPageHasBootstrapClasses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier que les classes Bootstrap sont présentes
        $card = $crawler->filter('.card');
        $this->assertCount(1, $card);
        
        $cardHeader = $crawler->filter('.card-header');
        $this->assertCount(1, $cardHeader);
        
        $cardBody = $crawler->filter('.card-body');
        $this->assertCount(1, $cardBody);
        
        $form = $crawler->filter('form.d-flex');
        $this->assertCount(1, $form);
    }

    public function testConversationPageHasCorrectFormStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conversation/users/1');

        $this->assertResponseIsSuccessful();
        
        // Vérifier la structure du formulaire
        $form = $crawler->filter('form')->first();
        $this->assertStringContainsString('d-flex', $form->attr('class'));
        $this->assertStringContainsString('gap-3', $form->attr('class'));
        
        // Vérifier les éléments du formulaire
        $contentInput = $crawler->filter('input[name="content"]');
        $this->assertStringContainsString('form-control', $contentInput->attr('class'));
        
        $submitButton = $crawler->filter('button[type="submit"]');
        $this->assertStringContainsString('btn', $submitButton->attr('class'));
        $this->assertStringContainsString('btn-primary', $submitButton->attr('class'));
    }
}
