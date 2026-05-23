<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table         = 'comments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'post_id', 'content'];

    protected $useTimestamps = true;
    protected $updatedField  = ''; // disabled – comments are immutable
    protected $createdField  = 'created_at';

    // Fetch all comments for a given post, joined with username
    public function getForPost(int $postId): array
    {
        return $this->select('comments.*, users.username')
            ->join('users', 'users.id = comments.user_id', 'left') // left join keeps comments even if user was deleted
            ->where('comments.post_id', $postId)
            ->orderBy('comments.created_at', 'ASC') // oldest first
            ->findAll();
    }

    // Returns total comment count for a post without fetching rows
    public function countForPost(int $postId): int
    {
        return $this->where('post_id', $postId)->countAllResults();
    }
}