<?php

namespace App\Console\Commands;

use App\Extensions\GenerateUserList\HandleCsvUserList;
use App\Extensions\GenerateUserList\HandleJsonUserList;
use App\Extensions\HandleItemsList\HandleItemsListContract;
use Illuminate\Console\Command;
use Faker\Generator as Faker;

abstract class AbstractUsersListCommand extends Command
{
    /**
     * An array containing the supported format handlers. The keys must corrspond to the format keys used on command.
     *
     * @var array
     */
    protected static $formatsMap = [
        'csv' => HandleCsvUserList::class,
        'json' => HandleJsonUserList::class,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    abstract public function handle(Faker $faker);

    /**
     * Validate the handled format given as argument to method or to command.
     *
     * @param string|null $format
     * @return bool
     */
    protected function isValidFormat(string $format = null) : bool
    {
        $format = $format ?? $this->argument('format');

        $formatsMap = static::getFormatsMap();

        if (!key_exists($format, $formatsMap)) {
            return false;
        }

        return true;
    }

    /**
     * Get the instance of the extension according to a format previously validated.
     *
     * @param string|null $format
     * @return HandleItemsListContract
     */
    protected function getExtensionForFormat(string $format) : HandleItemsListContract
    {
        $formatsMap = static::getFormatsMap();
        return (new $formatsMap[$format]);
    }

    /**
     * Validate the the inputs for the command.
     * @return bool
     */
    protected function validateCommand() : bool
    {
        $format = $this->argument('format');

        if (!$this->isValidFormat($format)) {
            $this->error("Error: Format not supported.");
            $this->info("Supported formats: " . join(', ', array_keys(static::getFormatsMap())));
            return false;
        }

        return true;
    }

    public static function getFormatsMap()
    {
        return static::$formatsMap;
    }
}
