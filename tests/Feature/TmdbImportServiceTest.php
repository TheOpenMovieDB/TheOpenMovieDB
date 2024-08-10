<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\TmdbImportService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class TmdbImportServiceTest extends TestCase
{
    /**
     * Test the TMDb import service.
     */
    public function test_tmdb_import_service(): void
    {
        $url = 'http://files.tmdb.org/p/exports/movie_ids_%s.json.gz';
        $today = now()->format('m_d_Y');
        $tmdbMovieService = new TmdbImportService(sprintf($url, $today), 'movies');


        $disk = Storage::disk('tmdb_files');

        $disk->deleteDirectory('/');
        $disk->makeDirectory('/');

        $downloadedFilePath = $tmdbMovieService->process();

        $this->assertTrue($disk->exists($downloadedFilePath), 'File does not exist in the storage.');

        $downloadedFile = $disk->get($downloadedFilePath);
        $this->assertNotEmpty($downloadedFile, 'File is empty.');

        $disk->delete($downloadedFilePath);
    }
}
