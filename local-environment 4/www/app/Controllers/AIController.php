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

    // Improve post text using AI
    public function improve()
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['error' => 'You must sign in to use AI improvement.']);
        }

        $json = $this->request->getJSON(true);
        $text = trim((string) ($json['text'] ?? $this->request->getPost('text') ?? ''));

        if ($text === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Text is required.']);
        }

        try {
            $improved = $this->aiService->improve($text);
        } catch (\Throwable $e) {
            log_message('error', 'AI improve error: ' . $e->getMessage());
            return $this->response
                ->setStatusCode(502)
                ->setJSON(['error' => 'AI service is currently unavailable. Please try again later.']);
        }

        return $this->response->setJSON(['improved_text' => $improved]);
    }
}