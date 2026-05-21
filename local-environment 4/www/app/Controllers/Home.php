<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\LikeModel;
use App\Models\CommentModel;

class Home extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/sign-in')->with('error', 'You must sign in to access the homepage.');
        }

        $postModel    = new PostModel();
        $likeModel    = new LikeModel();
        $commentModel = new CommentModel();
        $userId       = (int) session()->get('user_id');

        $posts = $postModel
            ->select('posts.*, users.username, users.profile_pic')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();

        foreach ($posts as &$post) {
            $post['likes_count']    = $likeModel->countForPost((int) $post['id']);
            $post['comments_count'] = $commentModel->countForPost((int) $post['id']);
            $post['liked_by_user']  = $likeModel->hasLiked($userId, (int) $post['id']);
            $post['comments']       = $commentModel->getForPost((int) $post['id']);
        }

        return view('home', [
            'title' => 'Home',
            'posts' => $posts,
        ]);
    }
}