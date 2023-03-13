<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment
{
    public function __construct(
        private int $id,
        private User $author,
        private Post $post,
        private string $text
    )
    {
    }

    public function __toString(): string
    {
        return $this->text;
    }
}