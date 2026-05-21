<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // Show the sign-up form
    public function signUp()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }

        return view('auth/sign_up', ['title' => 'Sign Up']);
    }

    // Handle new user registration
    public function signUpPost()
    {
        $email          = trim((string) $this->request->getPost('email'));
        $password       = (string) $this->request->getPost('password');
        $repeatPassword = (string) $this->request->getPost('repeat_password');
        $username       = trim((string) $this->request->getPost('username'));
        $errors         = [];

        // Validate email format and domain
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
        } elseif (! preg_match('/@(students\.salle\.url\.edu|ext\.salle\.url\.edu|salle\.url\.edu)$/', $email)) {
            $errors['email'] = 'Only emails from the domain @students.salle.url.edu, @ext.salle.url.edu or @salle.url.edu are accepted.';
        } else {
            $userModel = new UserModel();
            if ($userModel->where('email', $email)->first()) {
                $errors['email'] = 'The email address is already registered.';
            }
        }

        // Validate password strength
        if (strlen($password) < 8) {
            $errors['password'] = 'The password must contain at least 8 characters.';
        } elseif (! preg_match('/[A-Z]/', $password) || ! preg_match('/[a-z]/', $password) || ! preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }

        if ($password !== $repeatPassword) {
            $errors['repeat_password'] = 'Passwords do not match.';
        }

        if (! empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Use part of the email as the default username if none is provided
        if ($username === '') {
            $username = explode('@', $email)[0];
        }

        // Handle optional profile picture upload
        $profilePic = 'default.png';
        $image      = $this->request->getFile('profile_pic');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/profiles', $newName);
            $profilePic = 'uploads/profiles/' . $newName;
        }

        $userModel = new UserModel();
        $userModel->save([
            'username'    => $username,
            'email'       => $email,
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'profile_pic' => $profilePic,
        ]);

        return redirect()->to('/sign-in')->with('success', 'Account created successfully. You can now sign in.');
    }

    // Show the sign-in form
    public function signIn()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }

        return view('auth/sign_in', ['title' => 'Sign In']);
    }

    // Handle login attempt
    public function signInPost()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'The email address is not valid.',
            ]);
        }

        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        // Check if user exists and password is correct
        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('errors', [
                'login' => 'Your email and/or password are incorrect.',
            ]);
        }

        // Set session data
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/home');
    }

    // Log the user out and destroy the session
    public function signOut()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}