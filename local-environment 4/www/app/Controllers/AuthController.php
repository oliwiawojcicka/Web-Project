<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function signUp()
    {
        return view('auth/sign_up', [
            'title' => 'Sign Up'
        ]);
    }

    public function signUpPost()
    {
        return redirect()->back()->withInput();
    }

    public function signIn()
    {
        return view('auth/sign_in', [
            'title' => 'Sign In'
        ]);
    }

    public function signInPost()
    {
        return redirect()->back()->withInput();
    }
}