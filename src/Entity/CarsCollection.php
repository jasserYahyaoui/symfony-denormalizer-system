<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 27/05/22
 * Time: 15:52
 */

namespace App\Entity;

class CarsCollection
{
    /**
     * @var Car[]
     */
    protected $items;

    /**
     * CarCollection constructor.
     *
     * @param array $carItems
     */
    public function __construct(array $carItems = [])
    {
        $this->setItems($carItems);
    }

    /**
     * @param array $items
     * @return CarsCollection
     */
    public function setItems(array $items = []): self
    {
        $this->items = [];

        foreach ($items as $carItem) {
            $this->add($carItem);
        }

        return $this;
    }

    /**
     * @param Car $carItem
     * @return CarsCollection
     */
    public function add(Car $carItem): self
    {
        $this->items[$carItem->getName()] = $carItem;

        return $this;
    }

    /**
     * @param string $code
     * @return CarsCollection
     */
    public function remove(string $code): self
    {
        if ($this->has($code)) {
            unset($this->items[$code]);
        }

        return $this;
    }

    /**
     * @param string $code
     *
     * @return mixed|Car|null
     */
    public function get(string $code)
    {
        return $this->items[$code] ?? null;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function has(string $code): bool
    {
        return isset($this->items[$code]);
    }

    /**
     * @return Car[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return Car|null
     */
    public function first()
    {
        return reset($this->items) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }
}
