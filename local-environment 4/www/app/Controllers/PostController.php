<?php

namespace App\Controllers;
use App\Models\PostModel;

class PostController extends BaseController
{
    public function create()
    {
        return view('posts/create', [
            'title' => 'Create Post'
        ]);
    }

    public function edit($id)
    {
        return view('posts/edit', [
            'title' => 'Edit Post',
            'postId' => $id
        ]);
    }

    public function store()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/sign-in')->with('error', 'You must sign in to create a post.');
        }

        $content = trim((string) $this->request->getPost('content'));

        if ($content === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Post content cannot be empty.');
        }

        $imagePath = null;
        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/posts', $newName);

            $imagePath = 'uploads/posts/' . $newName;
        }

        $postModel = new PostModel();

        $postModel->insert([
            'user_id' => session()->get('user_id'),
            'content' => $content,
            'image' => $imagePath,
        ]);

        return redirect()->to('/home')->with('success', 'Post created successfully.');
    }
}
