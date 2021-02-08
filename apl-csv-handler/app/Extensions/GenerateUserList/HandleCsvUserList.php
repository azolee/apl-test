<?php

namespace App\Extensions\GenerateUserList;

use App\Extensions\HandleItemsList\AbstractHandleItemsList;

class HandleCsvUserList extends AbstractHandleItemsList
{

    protected $headerNames = ['name', 'email', 'phone', 'password', 'deleted'];
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
    public function getFormatedItems(\Closure $closure = null): string
    {
        $items = $this->getItems();

        if ($closure) {
            return $closure($items);
        }
        return static::generateCSVContent($items);
    }

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
    public function readItemsFormFile(string $path, \Closure $closure = null): bool
    {

        if ($closure) {
            $initialCount = count($this->items);

            $itemsRaw = (string) $closure($path);

            $lines = explode("\n", $itemsRaw);

            foreach ($lines as $line) {
                $item = str_getcsv($line);

                $this->addItem(array_combine($this->headerNames, $item));
            }

            return is_array($this->items) && count($this->items) != $initialCount;
        }

        $handle = fopen($path, "r") or die("Couldn't get handle");

        if ($handle) {
            while (!feof($handle)) {

                $item = str_getcsv(
                    fgets($handle, 4096)
                );

                $this->addItem(array_combine($this->headerNames, $item));
            }
            fclose($handle);
            return true;
        }
        return false;
    }

    /**
     *
     * Generate a line for the CSV file
     * @return string
     */
    public static function generateCSVLine(array $item) : string
    {
        $delimiter = '"';
        $separator = ',';
        return $delimiter.join($delimiter.$separator.$delimiter, $item).$delimiter;
    }

    public static function generateCSVContent(array $items) : string
    {
        $lines = [];
        foreach($items as $item) {
            array_push($lines, static::generateCSVLine($item));
        }
        return join("\n", $lines);
    }
}
