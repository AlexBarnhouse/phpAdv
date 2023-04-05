<?php

namespace Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once()) // Ожидаем, что будет вызван один раз
            ->method('execute') // метод execute
            ->with([ // с единственным аргументом - массивом
                ':uuid' => '123e4567-e89b-12d3-a456-426614174001',
                ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':title' => 'SomeTitle',
                ':text' => 'SomeText',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqlitePostsRepository($connectionStub);

        $user = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            'username',
            new Name('first', 'last')
        );

        $repository->save(
            new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174001'),
                $user,
                'SomeTitle',
                'SomeText'
            )
        );
    }

    public function testItGetsPostByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);


        $statementMock->method('fetch')->willReturn([
                'uuid' => '123e4567-e89b-12d3-a456-426614174001',
                'author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                'title' => 'SomeTitle',
                'text' => 'SomeText',
                'username' => 'ivan123',
                'first_name' => 'Ivan',
                'last_name' => 'Nikitin',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $post = $repository->get(
            new UUID('123e4567-e89b-12d3-a456-426614174001')
        );

        $this->assertSame('123e4567-e89b-12d3-a456-426614174001',
            (string)$post->uuid());
    }

    public function testItThrowsPostNotFoundException(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementStub);
        $repository = new SqlitePostsRepository($connectionStub);

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: 123e4567-e89b-12d3-a456-426614174001');

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174001'));
    }
}