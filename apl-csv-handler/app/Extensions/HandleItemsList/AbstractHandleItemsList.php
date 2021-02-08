<?php
namespace App\Extensions\HandleItemsList;


abstract class AbstractHandleItemsList implements HandleItemsListContract
{
    protected $items = [];

    /**
     * Add an item to the list of items to handle. An existing item can beoverwritten if the second position is provided
     *
     * @param array $item
     * @param int|null $position
     *
     * @return int  - Return the position of the added item.
     */
    public function addItem(array $item, int $position = null) : int
    {
        if ($position) {
            $this->items[$position] = $item;
            return $position;
        }
        $this->items[] = $item;
        return array_key_last($this->items);
    }

    /**
     * Get the full array of the items;
     *
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }
}