<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Interfaces\PostRepositoryInterface;

class PostModel extends Model implements PostRepositoryInterface
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'content', 'image'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ── Interface methods ─────────────────────────────────────────────────────

    public function getAllWithAuthors(): array
    {
        return $this->select('posts.*, users.username, users.profile_pic')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();
    }

    public function findWithAuthor(int $id): ?array
    {
        return $this->select('posts.*, users.username, users.profile_pic')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->find($id);
    }

    public function createPost(int $userId, string $content, ?string $image): int
    {
        return $this->insert([
            'user_id' => $userId,
            'content' => $content,
            'image'   => $image,
        ], true);
    }

    public function updatePost(int $id, string $content): bool
    {
        return $this->update($id, ['content' => $content]);
    }

    public function deletePost(int $id): bool
    {
        return $this->delete($id);
    }
}