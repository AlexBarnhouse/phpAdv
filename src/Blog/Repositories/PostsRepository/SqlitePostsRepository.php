<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getPost($statement, $uuid);
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
           ':uuid' => (string)$post->uuid(),
            ':author_uuid' => (string)$post->author()->uuid(),
            ':title' => $post->title(),
            ':text' => $post->text(),
        ]);
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     */
    private function getPost(PDOStatement $statement, string $postUuid): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $postUuid"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }
}