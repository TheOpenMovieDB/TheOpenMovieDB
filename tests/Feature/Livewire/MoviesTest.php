<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Movies;
use Livewire\Livewire;
use Tests\TestCase;

final class MoviesTest extends TestCase
{
    /** @test */
    public function renders_successfully(): void
    {
        Livewire::test(Movies::class)
            ->assertStatus(200);
    }
}
