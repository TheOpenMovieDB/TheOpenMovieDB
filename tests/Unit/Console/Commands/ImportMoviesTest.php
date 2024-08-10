<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Models\Movie;

final class ImportMoviesTest extends \Tests\TestCase
{
    public function testImportMovies(): void
    {

        $this->assertEquals(0, Movie::count());

        $this->artisan('import:movies --limit=10')
            ->assertExitCode(0);

        $this->assertEquals(10, Movie::count());
    }
}
