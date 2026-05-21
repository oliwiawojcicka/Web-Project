<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\PostModel;

class CommentController extends BaseController
{
    // ─── API: GET /posts/{id}/comments ───────────────────────────────────────

    public function index(int $postId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel = new PostModel();
        if (! $postModel->find($postId)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Post not found.']);
        }

        $commentModel = new CommentModel();

        return $this->response->setJSON($commentModel->getForPost($postId));
    }

    // ─── API: POST /posts/{id}/comments ─────────────────────────────────────

    public function store(int $postId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel = new PostModel();
        if (! $postModel->find($postId)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Post not found.']);
        }

        $json    = $this->request->getJSON(true);
        $content = trim((string) ($json['content'] ?? $this->request->getPost('content') ?? ''));

        if ($content === '') {
            return $this->response->setStatusCode(422)->setJSON(['error' => 'Content must not be empty.']);
        }

        $commentModel = new CommentModel();
        $commentId    = $commentModel->insert([
            'user_id' => session()->get('user_id'),
            'post_id' => $postId,
            'content' => $content,
        ], true);

        $comment = $commentModel
            ->select('comments.*, users.username')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->find($commentId);

        return $this->response->setStatusCode(201)->setJSON([
            'success'        => true,
            'comment'        => $comment,
            'comments_count' => $commentModel->countForPost($postId),
        ]);
    }

    // ─── API: DELETE /comments/{id} ──────────────────────────────────────────

    public function delete(int $id)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $commentModel = new CommentModel();
        $comment      = $commentModel->find($id);

        if (! $comment) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Comment not found.']);
        }

        if ((int) $comment['user_id'] !== (int) session()->get('user_id')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'You can only delete your own comments.']);
        }

        $commentModel->delete($id);

        return $this->response->setJSON([
            'success'        => true,
            'comments_count' => $commentModel->countForPost((int) $comment['post_id']),
        ]);
    }
}