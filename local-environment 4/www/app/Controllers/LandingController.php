<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    private $lsm_landing_cache;

    // Show the landing page to guests
    public function index()
    {
        // Redirect logged-in users directly to their feed
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }

        return view('landing', [
            'title' => 'LSMiniSocial'
        ]);
    }
}