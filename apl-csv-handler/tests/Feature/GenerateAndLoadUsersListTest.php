<?php

namespace Tests\Feature;

use App\Console\Commands\AbstractUsersListCommand;
use App\Console\Commands\GenerateUsersListCommand;
use App\Extensions\GenerateUserList\HandleCsvUserList;
use App\Extensions\GenerateUserList\HandleJsonUserList;
use App\Extensions\HandleItemsList\HandleItemsListContract;
use App\Helpers\FilesHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenerateAndLoadUsersListTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGenerateAndLoadUsers()
    {
        $count = 10;
        $format = "json";
        $file = "users-" . time();
        $filename = $file.".".$format;

        $usersInitialCount = User::withTrashed()->get()->count();

        $storage = FilesHelper::getStorageDisk();

        $this->assertNotTrue($storage->exists($filename));

        $generateCommand = 'generate:users ' . $format . ' ' . $count . ' ' . $file;
        $generateCommandExpectedOutput = 'The ' . $count . ' item where successfully generated in ' . $format . ' format to file: '.$filename;


        $this->artisan($generateCommand)
            ->expectsOutput($generateCommandExpectedOutput)
            ->assertExitCode(0);

        $this->assertTrue($storage->exists($filename));

        $loadCommand = 'load:users ' . $format . ' ' . $file;
        $loadCommandExpectedOutput = $count . ' items where handled from file: ' . $filename;

        $this->artisan($loadCommand)
            ->expectsOutput($loadCommandExpectedOutput)
            ->assertExitCode(0);

        $usersFinalCount = User::withTrashed()->get()->count();

        $this->assertEquals($usersFinalCount, $usersInitialCount + $count);

        $storage->delete($filename);

        $this->assertNotTrue($storage->exists($filename));
    }

    public function testGenerateUserListExtensions()
    {
        $count = mt_rand(10, 100);
        $items = static::createUsers($count, $this->faker);

        $formatHandlers = static::formatHandlers();
        $formatsMap = AbstractUsersListCommand::getFormatsMap();

        foreach ($formatsMap as $format => $formatClass) {

            /* @var HandleItemsListContract $formatObject */
            $formatObject = new $formatClass();

            foreach ($items as $item) {
                $formatObject->addItem($item);
            }

            $this->assertEquals(count($formatObject->getItems()), $count);

            if (key_exists($format, $formatHandlers)) {
                $invokableFormatHandler = $formatHandlers[$format];

                $this->assertEquals(
                    $invokableFormatHandler($items),
                    $formatObject->getFormatedItems()
                );
            }
        }
    }

    public static function createUsers(int $count, $faker)
    {
        $input = [];

        for($i=0; $i<$count; $i++)
        {
            $input[] = GenerateUsersListCommand::createItem($faker);
        }
        return $input;
    }

    public static function formatHandlers()
    {
        return [
            'json' => function($items){
                return json_encode($items);
            },
            'csv' => function($items){
                return HandleCsvUserList::generateCSVContent($items);
            },
        ];
    }
}
