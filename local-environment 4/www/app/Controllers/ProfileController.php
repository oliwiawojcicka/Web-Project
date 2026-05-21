<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;

class ProfileController extends BaseController
{
    // ─── GET /profile ────────────────────────────────────────────────────────

    public function index()
    {
        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        $postModel = new PostModel();
        $posts     = $postModel
            ->select('posts.*, users.username, users.profile_pic')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->where('posts.user_id', $userId)
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();

        return view('profile', [
            'title' => 'My Profile',
            'user'  => $user,
            'posts' => $posts,
        ]);
    }

    // ─── POST /profile ───────────────────────────────────────────────────────

    public function update()
    {
        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();

        $username = trim((string) $this->request->getPost('username'));
        $password = trim((string) $this->request->getPost('password'));

        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Username must not be empty.';
        }

        if ($password !== '') {
            if (strlen($password) < 8) {
                $errors['password'] = 'The password must contain at least 8 characters.';
            } elseif (! preg_match('/[A-Z]/', $password) || ! preg_match('/[a-z]/', $password) || ! preg_match('/[0-9]/', $password)) {
                $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
            }
        }

        if (! empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $data = ['username' => $username];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Handle profile picture upload
        $image = $this->request->getFile('profile_pic');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/profiles', $newName);
            $data['profile_pic'] = 'uploads/profiles/' . $newName;
        }

        $userModel->update($userId, $data);

        // Refresh session username
        session()->set('username', $username);

        return redirect()->to('/profile')->with('success', 'Profile updated successfully.');
    }

    // ─── POST /profile/delete ────────────────────────────────────────────────

    public function deleteAccount()
    {
        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();

        $userModel->delete($userId);

        session()->destroy();

        return redirect()->to('/');
    }
}
