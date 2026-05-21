<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LanguageFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $supported = ['en', 'es'];
        $default   = 'en';

        // Check session first (user already has a language set)
        $sessionLang = session()->get('lang');
        if ($sessionLang && in_array($sessionLang, $supported, true)) {
            service('request')->setLocale($sessionLang);
            return;
        }

        // Auto-detect from browser Accept-Language header
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $detected       = $default;

        if ($acceptLanguage) {
            // Parse: "es-ES,es;q=0.9,en;q=0.8" → ['es', 'en']
            preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?(?:;q=[\d.]+)?/i', $acceptLanguage, $matches);
            foreach ($matches[1] as $lang) {
                $lang = strtolower($lang);
                if (in_array($lang, $supported, true)) {
                    $detected = $lang;
                    break;
                }
            }
        }

        session()->set('lang', $detected);
        service('request')->setLocale($detected);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}