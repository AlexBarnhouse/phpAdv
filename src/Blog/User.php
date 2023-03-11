<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;
class User
{
    public function __construct(
        private int $id,
        private Name $name
    )
    {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}