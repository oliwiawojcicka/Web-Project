<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    private $lsm_landing_cache;

    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }

        return view('landing', [
            'title' => 'LSMiniSocial'
        ]);
    }
}