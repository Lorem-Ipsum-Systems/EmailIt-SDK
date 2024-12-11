<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use LoremIpsum\EmailIt\EmailItMailer;
use PHPUnit\Framework\TestCase;

class EmailItMailerTest extends TestCase
{
    public function testSendEmailSuccessfully(): void
    {
        // Arrange
        $mockApiKey = 'test-api-key';
        $mockResponse = new Response(200, [], json_encode(['success' => true]));

        // Mock Guzzle Client
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'emails',
                $this->callback(function ($options) {
                    // Validate payload structure
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('json', $options);
                    $this->assertEquals('application/json', $options['headers']['Content-Type']);
                    $this->assertEquals('Bearer test-api-key', $options['headers']['Authorization']);
                    $this->assertEquals('Name <test@email.com>', $options['json']['from']);
                    $this->assertEquals('acme@email.com', $options['json']['to']);
                    $this->assertEquals('Hello World', $options['json']['subject']);
                    return true;
                })
            )
            ->willReturn($mockResponse);

        // Act
        $mailer = $this->getMockBuilder(EmailItMailer::class)
            ->setConstructorArgs([$mockApiKey])
            ->onlyMethods([])
            ->getMock();

        // Inject mock client into the mailer
        $reflectionClient = new \ReflectionProperty($mailer, 'client');
        $reflectionClient->setAccessible(true);
        $reflectionClient->setValue($mailer, $mockClient);

        $response = $mailer->send(
            'Name <test@email.com>',
            'acme@email.com',
            'Hello World',
            '<h1>Hello</h1>',
            'Hello plain text'
        );

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['success' => true], json_decode($response->getBody(), true));
    }
}