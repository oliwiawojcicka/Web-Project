<?php

namespace App\Interfaces;

interface PostRepositoryInterface
{
    public function getAllWithAuthors(): array;
    public function findWithAuthor(int $id): ?array;
    public function createPost(int $userId, string $content, ?string $image): int;
    public function updatePost(int $id, string $content): bool;
    public function deletePost(int $id): bool;
}