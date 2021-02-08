<?php

namespace App\Extensions\GenerateUserList;

use App\Extensions\HandleItemsList\AbstractHandleItemsList;

class HandleJsonUserList extends AbstractHandleItemsList
{

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

        return json_encode($items);
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
            $itemsRaw = $closure($path);
            $this->items = json_decode($itemsRaw ?? '[]', true);
            return is_array($this->items) && count($this->items) != $initialCount;
        }


        $filesize = filesize($path);

        $fp = @fopen($path, "r");

        $chunkSize = (1<<24); // 16MB arbitrary

        $position = 0;

        $fileContent = '';

        if ($fp) {
            while (!feof($fp)){
                fseek($fp, $position);

                $chunk = fread($fp,$chunkSize);

                $last_lf_pos = strrpos($chunk, "\n");


                $fileContent .= mb_substr($chunk, 0, $last_lf_pos);

                $position += $last_lf_pos;

                if (($position + $chunkSize) > $filesize) {
                    $chunk_size = $filesize - $position;
                }

                $buffer = NULL;
            }
            fclose($fp);
            $this->items = json_decode($fileContent, true);
            return true;
        }

        return false;
    }
}
