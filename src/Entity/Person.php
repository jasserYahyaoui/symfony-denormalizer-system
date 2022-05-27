<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 27/05/22
 * Time: 15:52
 */

namespace App\Entity;

class Person
{
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}