<?php

namespace App\Tests\Unit\DTO;

use App\DTO\CreateMessage;
use PHPUnit\Framework\TestCase;

class CreateMessageTest extends TestCase
{
    public function testCreateMessageWithValidData(): void
    {
        $content = 'Hello, this is a test message';
        $conversationId = 123;

        $dto = new CreateMessage($content, $conversationId);

        $this->assertEquals($content, $dto->content);
        $this->assertEquals($conversationId, $dto->conversationId);
    }

    public function testCreateMessagePropertiesAreReadonly(): void
    {
        $dto = new CreateMessage('test', 1);
        
        // Vérifier que les propriétés sont readonly
        $reflection = new \ReflectionClass($dto);
        $contentProperty = $reflection->getProperty('content');
        $conversationIdProperty = $reflection->getProperty('conversationId');
        
        $this->assertTrue($contentProperty->isReadOnly());
        $this->assertTrue($conversationIdProperty->isReadOnly());
    }

    public function testCreateMessageIsFinal(): void
    {
        $reflection = new \ReflectionClass(CreateMessage::class);
        $this->assertTrue($reflection->isFinal());
    }

    public function testCreateMessageWithEmptyString(): void
    {
        $dto = new CreateMessage('', 1);
        
        $this->assertEquals('', $dto->content);
        $this->assertEquals(1, $dto->conversationId);
    }

    public function testCreateMessageWithZeroConversationId(): void
    {
        $dto = new CreateMessage('test', 0);
        
        $this->assertEquals('test', $dto->content);
        $this->assertEquals(0, $dto->conversationId);
    }

    public function testCreateMessageWithLongContent(): void
    {
        $longContent = str_repeat('a', 1000);
        $dto = new CreateMessage($longContent, 1);
        
        $this->assertEquals($longContent, $dto->content);
        $this->assertEquals(1, $dto->conversationId);
    }

    public function testCreateMessageWithNegativeConversationId(): void
    {
        $dto = new CreateMessage('test', -1);
        
        $this->assertEquals('test', $dto->content);
        $this->assertEquals(-1, $dto->conversationId);
    }
}
