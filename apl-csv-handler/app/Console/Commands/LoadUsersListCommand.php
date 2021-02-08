<?php

namespace App\Console\Commands;

use App\Helpers\FilesHelper;
use App\Jobs\CreateUserRecord;
use Faker\Generator as Faker;

class LoadUsersListCommand extends AbstractUsersListCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:users {format=csv} {filename=users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the list of users from different formats and save it in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Faker $faker)
    {
        $format = $this->argument('format');

        $filename = $this->argument('filename').'.'.$format;

        if (!$this->validateCommand() || !$this->validateFile($filename)) {
            return 1;
        }

        $extension = $this->getExtensionForFormat($format);

        $storage = FilesHelper::getStorageDisk();

        $extension->readItemsFormFile($filename, function($path) use ($storage) {
            return $storage->get($path);
        });

        $items = $extension->getItems();

        foreach ($items as $item) {
            dispatch(new CreateUserRecord($item))->onQueue('users');
        }

        $this->info(count($items) . ' items where handled from file: ' . $filename);

        return 0;
    }

    /**
     *
     * Validate the existence of the file
     *
     * @return bool
     */
    protected function validateFile(string $path)
    {
        $storage = FilesHelper::getStorageDisk();

        if (!$storage->exists($path)) {
            $this->error("File not found: " . $path);
            return false;
        }

        return true;
    }
}
