<?php

namespace App\Extensions\HandleItemsList;


interface HandleItemsListContract
{
    /**
     * Add an item to the list of items to handle. An existing item can beoverwritten if the second position is provided
     *
     * @param array $item
     * @param int|null $position
     *
     * @return int  - Return the position of the added item.
     */
    public function addItem(array $item, int $position = null) : int;

    /**
     * Get the full array of the items;
     *
     * @return array
     */
    public function getItems() : array;

    /**
     *
     * Format the items and return as a string.
     * Additional operations can be performed, by providing a closure to the method.
     * This method will accept an array as argument, containing the full list of items and
     * must return the formated items.
     *
     * @param \Closure $closure
     *
     * @return string
     */
    public function getFormatedItems(\Closure $closure = null) : string;

    /**
     *
     * Loads the items from a file into the $items container. If provided the second argument,
     * it will handle the file reading. Closure must return a string containing the raw content of the file.
     *
     * @param string $path
     * @param \Closure $closure
     *
     * @return bool
     */
    public function readItemsFormFile(string $path, \Closure $closure = null) : bool;
}