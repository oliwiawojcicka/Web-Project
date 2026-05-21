<?php

namespace App\Models;

use CodeIgniter\Model;

class LikeModel extends Model
{
    protected $table         = 'likes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'post_id'];

    protected $useTimestamps = true;
    protected $updatedField  = '';
    protected $createdField  = 'created_at';

    public function hasLiked(int $userId, int $postId): bool
    {
        return $this->where('user_id', $userId)
                ->where('post_id', $postId)
                ->countAllResults() > 0;
    }

    public function countForPost(int $postId): int
    {
        return $this->where('post_id', $postId)->countAllResults();
    }
}