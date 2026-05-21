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

        // Check if the user already has a preferred language saved in their session
        $sessionLang = session()->get('lang');
        if ($sessionLang && in_array($sessionLang, $supported, true)) {
            service('request')->setLocale($sessionLang);
            return;
        }

        // If not, try to detect the language from their browser settings
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $detected       = $default;

        if ($acceptLanguage) {
            preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?(?:;q=[\d.]+)?/i', $acceptLanguage, $matches);
            foreach ($matches[1] as $lang) {
                $lang = strtolower($lang);
                if (in_array($lang, $supported, true)) {
                    $detected = $lang;
                    break;
                }
            }
        }

        // Save the detected language for future requests
        session()->set('lang', $detected);
        service('request')->setLocale($detected);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the response is generated
    }
}