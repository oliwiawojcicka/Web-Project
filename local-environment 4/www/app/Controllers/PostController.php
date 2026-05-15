<?php

namespace App\Controllers;

class PostController extends BaseController
{
    public function create()
    {
        return view('posts/create', [
            'title' => 'Create Post'
        ]);
    }
}
