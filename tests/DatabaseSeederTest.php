<?php

namespace Mainetcare\Appchup\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeederTest extends TestCase
{
    use DatabaseMigrations;

    public $table = 'test';

    public function setUp(): void {
        parent::setUp();
        Artisan::call('db:seed --class=TestSeeder');
    }

    public function test_it_seeds_from_sql_file()
    {
        $this->assertDatabaseHas('tests', ['A' => '1', 'B' => '2']);
    }


}
