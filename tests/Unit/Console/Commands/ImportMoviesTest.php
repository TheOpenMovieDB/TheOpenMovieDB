<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\ImportMovies;
use App\Models\Movie;
use PHPUnit\Framework\TestCase;

class ImportMoviesTest extends \Tests\TestCase
{
    public function testImportMovies()
    {

        $this->assertEquals(0, Movie::count());

        $this->artisan('import:movies --limit=10')
            ->assertExitCode(0);

        $this->assertEquals(10, Movie::count());
    }
}
