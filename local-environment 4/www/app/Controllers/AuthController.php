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
        session()->set([
            'user_id' => 1,
            'username' => $this->request->getPost('username') ?: 'oliwia',
            'email' => $this->request->getPost('email'),
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/home');
    }

    public function signIn()
    {
        return view('auth/sign_in', [
            'title' => 'Sign In'
        ]);
    }

    public function signInPost()
    {
        session()->set([
            'user_id' => 1,
            'username' => 'oliwia',
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/home');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }

}