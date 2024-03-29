<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;

interface PostsRepositoryInterface
{
    public function get(UUID $uuid): Post;
    public function save(Post $post): void;
}