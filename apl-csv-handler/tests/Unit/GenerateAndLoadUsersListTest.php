<?php

namespace Tests\Unit;

use App\Console\Commands\AbstractUsersListCommand;
use App\Console\Commands\GenerateUsersListCommand;
use App\Console\Commands\LoadUsersListCommand;
use App\Helpers\FilesHelper;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class GenerateAndLoadUsersListTest extends TestCase
{

    /**
     *
     * Check if storage directory is writable
     */
    public function testIfStorageIsWritable()
    {
        $storage = FilesHelper::getStorageDisk();

        $tempfile = "temp.txt";

        $content = 'File created at: '. date('Y-m-d H:i');

        $this->assertTrue($storage->put($tempfile, $content));

        $this->assertTrue($storage->exists($tempfile));

        $readedFileContent = $storage->get($tempfile);

        $this->assertEquals($content, $readedFileContent);

        $storage->delete($tempfile);

        $this->assertNotTrue($storage->exists($tempfile));
    }

    /**
     * Check if GenerateUsersList and LoadUsersList Commands exits
     *
     * @return void
     */
    public function testCheckIfImportAndExportCommandsExits()
    {
        $this->assertTrue(class_exists(GenerateUsersListCommand::class));

        $this->assertTrue(class_exists(LoadUsersListCommand::class));
    }

    /**
     * Check if GenerateUsersList and LoadUsersList Commands exits
     *
     * @return void
     */
    public function testCheckIfAllFormatsAreImplemented()
    {
        $formatsMap = AbstractUsersListCommand::getFormatsMap();

        foreach ($formatsMap as $formatClass) {
            $this->assertTrue(class_exists($formatClass));
        }
    }
}
