<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\LikeModel;
use App\Models\CommentModel;

class PostController extends BaseController
{
    // Show the post creation form
    public function create()
    {
        return view('posts/create', ['title' => 'Create Post']);
    }

    // Show the post edit form
    public function edit(int $id)
    {
        $postModel = new PostModel();
        $post      = $postModel->find($id);

        if (! $post) {
            return redirect()->to('/home')->with('error', 'Post not found.');
        }

        if ((int) $post['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/home')->with('error', 'You can only edit your own posts.');
        }

        return view('posts/edit', ['title' => 'Edit Post', 'post' => $post]);
    }

    // Fetch all posts (useful for AJAX feeds)
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel    = new PostModel();
        $likeModel    = new LikeModel();
        $commentModel = new CommentModel();
        $userId       = (int) session()->get('user_id');

        $posts = $postModel->getAllWithAuthors();

        foreach ($posts as &$post) {
            $post['likes_count']    = $likeModel->countForPost((int) $post['id']);
            $post['comments_count'] = $commentModel->countForPost((int) $post['id']);
            $post['liked_by_user']  = $likeModel->hasLiked($userId, (int) $post['id']);
        }

        return $this->response->setJSON($posts);
    }

    // Save a new post
    public function store()
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $content = trim((string) $this->request->getPost('content'));

        if ($content === '') {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(422)->setJSON(['error' => 'Post content cannot be empty.']);
            }
            return redirect()->back()->withInput()->with('error', 'Post content cannot be empty.');
        }

        // Handle optional post image upload
        $imagePath = null;
        $image     = $this->request->getFile('image');

        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/posts', $newName);
            $imagePath = 'uploads/posts/' . $newName;
        }

        $postModel = new PostModel();
        $postId    = $postModel->createPost((int) session()->get('user_id'), $content, $imagePath);

        if ($this->request->isAJAX()) {
            $post = $postModel->findWithAuthor($postId);
            return $this->response->setJSON(['success' => true, 'post' => $post]);
        }

        return redirect()->to('/home')->with('success', 'Post created successfully.');
    }

    // Fetch details for a single post
    public function show(int $id)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel = new PostModel();
        $post      = $postModel->findWithAuthor($id);

        if (! $post) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Post not found.']);
        }

        $likeModel    = new LikeModel();
        $commentModel = new CommentModel();
        $userId       = (int) session()->get('user_id');

        $post['likes_count']    = $likeModel->countForPost($id);
        $post['comments_count'] = $commentModel->countForPost($id);
        $post['liked_by_user']  = $likeModel->hasLiked($userId, $id);

        return $this->response->setJSON($post);
    }

    // Update an existing post
    public function update(int $id)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel = new PostModel();
        $post      = $postModel->find($id);

        if (! $post) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Post not found.']);
        }

        if ((int) $post['user_id'] !== (int) session()->get('user_id')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'You can only edit your own posts.']);
        }

        $json = null;
        if (str_contains(strtolower($this->request->getHeaderLine('Content-Type')), 'json')) {
            $json = $this->request->getJSON(true);
        }


        $rawInput = $this->request->getRawInput();
        $content  = trim((string) ($json['content'] ?? $rawInput['content'] ?? $this->request->getPost('content') ?? ''));

        if ($content === '') {
            if ($this->request->isAJAX() || $json !== null) {
                return $this->response->setStatusCode(422)->setJSON(['error' => 'Content cannot be empty.']);
            }
            return redirect()->back()->withInput()->with('error', 'Content cannot be empty.');
        }

        $postModel->updatePost($id, $content);

        if ($this->request->isAJAX() || $json !== null) {
            return $this->response->setJSON(['success' => true]);
        }

        return redirect()->to('/home')->with('success', 'Post updated successfully.');
    }

    // Delete a post
    public function delete(int $id)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized.']);
        }

        $postModel = new PostModel();
        $post      = $postModel->find($id);

        if (! $post) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Post not found.']);
        }

        if ((int) $post['user_id'] !== (int) session()->get('user_id')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'You can only delete your own posts.']);
        }

        $postModel->deletePost($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true]);
        }

        return redirect()->to('/home')->with('success', 'Post deleted.');
    }
}