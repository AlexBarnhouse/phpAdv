<?php

namespace GeekBrains\LevelTwo\Blog;

class Post
{
    public function __construct(
        private int $id,
        private User $author,
        private string $title,
        private string $text
    )
    {
    }

    public function __toString()
    {
        return $this->title . ' >>> ' .$this->text;
    }
}