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
    protected $updatedField  = '';
    protected $createdField  = 'created_at';

    public function getForPost(int $postId): array
    {
        return $this->select('comments.*, users.username')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->where('comments.post_id', $postId)
            ->orderBy('comments.created_at', 'ASC')
            ->findAll();
    }

    public function countForPost(int $postId): int
    {
        return $this->where('post_id', $postId)->countAllResults();
    }
}