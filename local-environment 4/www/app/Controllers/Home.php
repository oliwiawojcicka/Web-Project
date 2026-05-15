<?php

namespace App\Controllers;

use App\Models\PostModel;

class Home extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/sign-in')->with('error', 'You must sign in to access the homepage.');
        }

        $postModel = new PostModel();

        $posts = $postModel
            ->select('posts.*, users.username, users.profile_pic')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();

        foreach ($posts as &$post) {
            $post['likes_count'] = 0;
            $post['comments_count'] = 0;
            $post['comments'] = [];
        }

        return view('home', [
            'title' => 'Home',
            'posts' => $posts,
        ]);
    }
}