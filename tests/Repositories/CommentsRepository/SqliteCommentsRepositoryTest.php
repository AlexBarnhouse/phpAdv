<?php

namespace Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItSavesCommentToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174001',
                ':post_uuid' => '123e4567-e89b-12d3-a456-426614174099',
                ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':text' => 'SomeText',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteCommentsRepository($connectionStub);

        $user = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            'username',
            new Name('first', 'last')
        );

        $post = new Post(
            new UUID('123e4567-e89b-12d3-a456-426614174099'),
            $user,
            'SomeTitle',
            'SomeText'
        );

        $repository->save(
            new Comment(
                new UUID('123e4567-e89b-12d3-a456-426614174001'),
                $user,
                $post,
                'SomeText'
            )
        );
    }

    public function testItGetsCommentByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);


        $statementMock->method('fetch')->willReturn([
            'uuid' => '123e4567-e89b-12d3-a456-426614174001',
            'author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'post_uuid' => '123e4567-e89b-12d3-a456-426614174099',
            'text' => 'SomeText',
            'title' => 'SomeTitle',
            'username' => 'ivan123',
            'first_name' => 'Ivan',
            'last_name' => 'Nikitin',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub);

        $comment = $repository->get(
            new UUID('123e4567-e89b-12d3-a456-426614174001')
        );

        $this->assertSame('123e4567-e89b-12d3-a456-426614174001',
            (string)$comment->uuid());
    }

    public function testItThrowsPostNotFoundException(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementStub);
        $repository = new SqliteCommentsRepository($connectionStub);

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment: 123e4567-e89b-12d3-a456-426614174001');

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174001'));
    }
}