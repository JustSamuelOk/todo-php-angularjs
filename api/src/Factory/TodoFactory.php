<?php

namespace App\Factory;

use App\Entity\Todo;

class TodoFactory
{
    public static function create(string $title, string $description, bool $completed = false): Todo
    {
        $entity = new Todo();
        $entity->setTitle($title);
        $entity->setDescription($description);
        $entity->setCompleted($completed);

        return $entity;
    }
}
