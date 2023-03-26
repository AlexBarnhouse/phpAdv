<?php

namespace GeekBrains\LevelTwo\Container;

use GeekBrains\LevelTwo\Blog\Exceptions\NotFoundException;

class DIContainer
{
    public function get(string $type): object
    {
        throw new NotFoundException("Cannot resolve type: $type");
    }

}