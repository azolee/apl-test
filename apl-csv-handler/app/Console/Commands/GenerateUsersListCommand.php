<?php

namespace App\Console\Commands;

use Faker\Generator as Faker;
use App\Helpers\FilesHelper;
use Illuminate\Support\Facades\Hash;

class GenerateUsersListCommand extends AbstractUsersListCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:users {format} {count=10} {filename=users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a list of users and savi it in different formats';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Faker $faker)
    {
        if (!$this->validateCommand()) {
            return 1;
        }

        $format = $this->argument('format');

        $filename = $this->argument('filename').'.'.$format;

        $extension = $this->getExtensionForFormat($format);

        $totalCount = $this->argument('count');

        for ($i = 0; $i < $totalCount; $i++)
        {
            $extension->addItem(
                static::createItem($faker)
            );
        }

        $formatedItems = $extension->getFormatedItems();

        $storage = FilesHelper::getStorageDisk();

        $storage->put($filename, $formatedItems);

        $this->info('The ' . $totalCount . ' item where successfully generated in ' . $format . ' format to file: ' . $filename);

        return 0;
    }

    public static function createItem(Faker $faker) : array
    {
        return [
            "name" => $faker->firstName . " " . $faker->lastName,
            "email" => $faker->unique()->safeEmail,
            "phone" => $faker->phoneNumber,
            "password" => Hash::make($faker->password),
            "deleted" => ((mt_rand(0, 9) * mt_rand(0, 9)) % 50) == 0 ? 1 : 0
        ];
    }
}
