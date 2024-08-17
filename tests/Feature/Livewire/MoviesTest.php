<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Movies;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class MoviesTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(Movies::class)
            ->assertStatus(200);
    }
}
