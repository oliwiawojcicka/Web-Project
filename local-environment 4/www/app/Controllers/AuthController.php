<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function signUp()
    {
        // Redirect already-authenticated users away from the form
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }

        return view('auth/sign_up', ['title' => 'Sign Up']);
    }

    public function signUpPost()
    {
        $email          = trim((string) $this->request->getPost('email'));
        $password       = (string) $this->request->getPost('password');
        $repeatPassword = (string) $this->request->getPost('repeat_password');
        $username       = trim((string) $this->request->getPost('username'));
        $errors         = [];

        // Validate email format, then restrict to allowed institutional domains
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

        // Enforce minimum length and character-class requirements
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

        // Fall back to the local part of the email when no username is supplied
        if ($username === '') {
            $username = explode('@', $email)[0];
        }

        // Move uploaded profile picture; keep default.png if none provided
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
            'password'    => password_hash($password, PASSWORD_DEFAULT), // never store plaintext
            'profile_pic' => $profilePic,
        ]);

        return redirect()->to('/sign-in')->with('success', 'Account created successfully. You can now sign in.');
    }

    public function signIn()
    {
        // Redirect already-authenticated users away from the form
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }

        return view('auth/sign_in', ['title' => 'Sign In']);
    }

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

        // Single vague error message intentionally avoids confirming whether the email exists
        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('errors', [
                'login' => 'Your email and/or password are incorrect.',
            ]);
        }

        // Persist minimal identity data in the session
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/home');
    }

    public function signOut()
    {
        // Wipe the entire session on logout
        session()->destroy();

        return redirect()->to('/');
    }
}