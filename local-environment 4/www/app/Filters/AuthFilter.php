<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/sign-in')->with('errors', ['login' => 'You must be logged in to access this page.']);
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
