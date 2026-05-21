<?php

namespace App\Services;

use App\Interfaces\AIServiceInterface;
use GuzzleHttp\Client;

class AIService implements AIServiceInterface
{
    private Client $client;
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        // Set up the HTTP client and load AI configuration from the .env file
        $this->client = new Client(['timeout' => 15.0]);
        $this->apiKey = env('AI_API_KEY', '');
        $this->apiUrl = env('AI_API_URL', 'https://api.groq.com/openai/v1/chat/completions');
        $this->model  = env('AI_MODEL', 'llama-3.1-8b-instant');
    }

    // Send text to the AI model to make it sound more professional
    public function improve(string $text): string
    {
        $response = $this->client->post($this->apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'    => $this->model,
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a helpful writing assistant. Improve the following social media post. Return only the improved text, without any explanation or extra formatting.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $text,
                    ],
                ],
                'max_tokens'  => 500,
                'temperature' => 0.7,
            ],
        ]);

        $body = json_decode((string) $response->getBody(), true);

        // Return the AI's response, or fallback to the original text if something goes wrong
        return $body['choices'][0]['message']['content'] ?? $text;
    }
}