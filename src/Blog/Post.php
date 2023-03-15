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

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }


    /**
     * @return User
     */
    public function author(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->title . ' >>> ' .$this->text;
    }
}