<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 27/05/22
 * Time: 15:52
 */

namespace App\Entity;

/**
 * Class Product
 * @package App\Entity
 */
class Product
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var float
     */
    private $Price;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var CarsCollection
     */
    private $carsCollection;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    /**
     * @return CarsCollection
     */
    public function getCarsCollection(): CarsCollection
    {
        return $this->carsCollection;
    }

    /**
     * @param CarsCollection $carsCollection
     */
    public function setCarsCollection(CarsCollection $carsCollection): void
    {
        $this->carsCollection = $carsCollection;
    }
}