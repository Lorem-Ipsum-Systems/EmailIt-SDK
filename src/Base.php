<?php

namespace LoremIpsum\EmailIt;

use GuzzleHttp\Client;
use InvalidArgumentException;

class Base
{
    private string $apiKey;

    public array $headers;

    protected Client $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client(
            [
                'base_uri' => 'https://api.emailit.com/v1/',
                'timeout' => 10.0,
            ]
        );
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ];
    }

    protected function validateAttachments(array $attachments): void
    {
        foreach ($attachments as $index => $attachment) {
            // Check if required keys are present
            $requiredKeys = ['filename', 'content', 'content_type'];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $attachment)) {
                    throw new InvalidArgumentException(
                        sprintf('Attachment at index %d is missing the required key: %s', $index, $key)
                    );
                }

                // Additional checks for values (e.g., non-empty strings)
                if (empty($attachment[$key]) || !is_string($attachment[$key])) {
                    throw new InvalidArgumentException(
                        sprintf('Attachment at index %d has an invalid value for key: %s', $index, $key)
                    );
                }
            }
        }
    }
}