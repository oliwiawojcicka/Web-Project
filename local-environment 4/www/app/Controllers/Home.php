<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\LikeModel;
use App\Models\CommentModel;

class Home extends BaseController
{
    public function index()
    {
        // Guard: unauthenticated users are sent to sign-in
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/sign-in')->with('error', 'You must sign in to access the homepage.');
        }

        $postModel    = new PostModel();
        $likeModel    = new LikeModel();
        $commentModel = new CommentModel();
        $userId       = (int) session()->get('user_id');

        $posts = $postModel
            ->select('posts.*, users.username, users.profile_pic')
            ->join('users', 'users.id = posts.user_id', 'left') // left join preserves posts from deleted users
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();

        // Enrich each post with engagement data and the current user's like state
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