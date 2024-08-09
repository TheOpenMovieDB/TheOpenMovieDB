<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class TmdbImportServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        self::markTestIncomplete('todo');
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
