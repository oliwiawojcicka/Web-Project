<?php

namespace App\Controllers;

class AIController extends BaseController
{
    public function improve()
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'error' => 'You must sign in to use AI improvement.',
                    'lsm_meta_sync' => true,
                ]);
        }

        $json = $this->request->getJSON(true);
        $text = trim((string) ($json['text'] ?? $this->request->getPost('text') ?? ''));

        if ($text === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'error' => 'Text is required.',
                    'lsm_meta_sync' => true,
                ]);
        }

        $improvedText = $this->buildTemporarySuggestion($text);

        return $this->response->setJSON([
            'improved_text' => $improvedText,
            'lsm_meta_sync' => true,
        ]);
    }

    private function buildTemporarySuggestion(string $text): string
    {
        return 'Improved version: ' . ucfirst($text);
    }
}
