<?php

namespace App\Controllers;

// LS-MiniSocial-Core
class HomeController extends BaseController
{
    public function index()
    {
        return view('home', [
            'title' => 'Home - LSMiniSocial'
        ]);
    }
}
