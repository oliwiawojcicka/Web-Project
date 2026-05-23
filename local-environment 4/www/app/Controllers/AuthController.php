<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    private function isLoggedIn(): bool
    {
        return (bool) session()->get('isLoggedIn');
    }

    private function redirectIfAuthenticated(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        return $this->isLoggedIn() ? redirect()->to('/home') : null;
    }

    public function signUp()
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        return view('auth/sign_up', ['title' => 'Sign Up']);
    }

    public function signIn()
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        return view('auth/sign_in', ['title' => 'Sign In']);
    }

    public function signUpPost()
    {
        $data = $this->getSignUpInput();

        $errors = array_merge(
            $this->validateEmail($data['email']),
            $this->validatePassword($data['password'], $data['repeatPassword'])
        );

        if (! empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $this->registerUser($data);

        return redirect()->to('/sign-in')->with('success', 'Account created successfully. You can now sign in.');
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

        $user = (new UserModel())->where('email', $email)->first();

        // Single vague error message intentionally avoids confirming whether the email exists
        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('errors', [
                'login' => 'Your email and/or password are incorrect.',
            ]);
        }

        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/home');
    }

    public function signOut()
    {
        session()->destroy();

        return redirect()->to('/');
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function getSignUpInput(): array
    {
        return [
            'email'          => trim((string) $this->request->getPost('email')),
            'password'       => (string) $this->request->getPost('password'),
            'repeatPassword' => (string) $this->request->getPost('repeat_password'),
            'username'       => trim((string) $this->request->getPost('username')),
        ];
    }

    private function validateEmail(string $email): array
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['email' => 'The email address is not valid.'];
        }

        if (! preg_match('/@(students\.salle\.url\.edu|ext\.salle\.url\.edu|salle\.url\.edu)$/', $email)) {
            return ['email' => 'Only emails from the domain @students.salle.url.edu, @ext.salle.url.edu or @salle.url.edu are accepted.'];
        }

        if ((new UserModel())->where('email', $email)->first()) {
            return ['email' => 'The email address is already registered.'];
        }

        return [];
    }

    private function validatePassword(string $password, string $repeatPassword): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors['password'] = 'The password must contain at least 8 characters.';
        } elseif (! preg_match('/[A-Z]/', $password) || ! preg_match('/[a-z]/', $password) || ! preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }

        if ($password !== $repeatPassword) {
            $errors['repeat_password'] = 'Passwords do not match.';
        }

        return $errors;
    }

    private function registerUser(array $data): void
    {
        $username = $data['username'] !== ''
            ? $data['username']
            : explode('@', $data['email'])[0]; // fall back to email local part

        $profilePic = $this->handleProfilePicUpload();

        (new UserModel())->save([
            'username'    => $username,
            'email'       => $data['email'],
            'password'    => password_hash($data['password'], PASSWORD_DEFAULT),
            'profile_pic' => $profilePic,
        ]);
    }

    private function handleProfilePicUpload(): string
    {
        $image = $this->request->getFile('profile_pic');

        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/profiles', $newName);
            return 'uploads/profiles/' . $newName;
        }

        return 'default.png';
    }
}