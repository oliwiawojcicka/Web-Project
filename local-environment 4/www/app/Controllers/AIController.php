<?php

namespace App\Controllers;

use App\Services\AIService;


class AIController extends BaseController
{
    private AIService $aiService;


    public function __construct()
    {
        $this->aiService = new AIService();
    }

    public function improve()
    {
        // Only authenticated users may use the AI improvement feature
        if (! session()->get('isLoggedIn')) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['error' => 'You must sign in to use AI improvement.']);
        }

        // Prefer JSON body; fall back to form POST, then default to empty string
        $json = $this->request->getJSON(true);
        $text = trim((string) ($json['text'] ?? $this->request->getPost('text') ?? ''));

        // Reject empty or whitespace-only input early
        if ($text === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Text is required.']);
        }

        try {
            // Delegate the actual AI call to the service layer
            $improved = $this->aiService->improve($text);
        } catch (\Throwable $e) {
            // Log the underlying exception for debugging, but expose only a generic message to the client to avoid leaking internal details
            log_message('error', 'AI improve error: ' . $e->getMessage());

            return $this->response
                ->setStatusCode(502)
                ->setJSON(['error' => 'AI service is currently unavailable. Please try again later.']);
        }

        // Return the improved text wrapped in a predictable JSON envelope
        return $this->response->setJSON(['improved_text' => $improved]);
    }
}