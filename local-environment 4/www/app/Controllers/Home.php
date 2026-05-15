<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (! session()->has('user_id')) {
            return redirect()->to('/sign-in')->with('error', 'You must sign in to access the homepage.');
        }

        return view('home');
    }
}
