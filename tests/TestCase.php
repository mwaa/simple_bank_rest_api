<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();
        $this->refreshDB();
    }

    private function refreshDB() // Reset database for each test
    {
        exec('cp "' . database_path('database.sqlite') . '" "' . database_path('testing.sqlite') . '"');
    }
}
