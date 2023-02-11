<?php

declare(strict_types=1);

namespace WirelessLogic\App\Collection;

/**
 * Responsible for holding a collection of data and performing operations on it.
 */
class Collection
{
    public function __construct(
        private array $data = []
    ) {}

    /**
     * Return data as json.
     */
    public function getAsJson(): string
    {
        return json_encode($this->data);
    }

    /**
     * Return data stored as array.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set data in the collection object.
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
