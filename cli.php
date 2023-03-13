<?php
require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;

$faker = Faker\Factory::create('ru_RU');

$name = new Name(
    $faker->firstName('male'),
    $faker->lastName('male')
);

$user = new User(1, $name);

if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'user':
            echo $user;
            break;
        case 'post':
            echo new Post(1,
                $user,
                $faker->words(5, true),
                $faker->realText());
            break;
        case 'comment':
            echo new Comment(1,
                $user,
                new Post(1,
                    $user,
                    $faker->words(5, true),
                    $faker->realText()),
                $faker->text());
            break;
    }
}