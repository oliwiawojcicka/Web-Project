<?php

namespace App\Controllers;

use App\Models\LikeModel;
use App\Models\PostModel;

class LikeController extends BaseController
{
    // ─── API: POST /posts/{id}/like ──────────────────────────────────────────

    public function like(int $postId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel = new PostModel();
        if (! $postModel->find($postId)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Post not found.']);
        }

        $likeModel = new LikeModel();
        $userId    = (int) session()->get('user_id');

        if ($likeModel->hasLiked($userId, $postId)) {
            return $this->response->setStatusCode(409)->setJSON(['error' => 'You have already liked this post.']);
        }

        $likeModel->insert(['user_id' => $userId, 'post_id' => $postId]);

        return $this->response->setJSON([
            'success'     => true,
            'likes_count' => $likeModel->countForPost($postId),
        ]);
    }

    // ─── API: DELETE /posts/{id}/like ────────────────────────────────────────

    public function unlike(int $postId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $likeModel = new LikeModel();
        $userId    = (int) session()->get('user_id');

        if (! $likeModel->hasLiked($userId, $postId)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Like not found.']);
        }

        $likeModel->where('user_id', $userId)->where('post_id', $postId)->delete();

        return $this->response->setJSON([
            'success'     => true,
            'likes_count' => $likeModel->countForPost($postId),
        ]);
    }
}