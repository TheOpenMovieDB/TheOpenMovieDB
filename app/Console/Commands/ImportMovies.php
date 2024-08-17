<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessMovieImport;
use App\Services\TmdbImportService;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

final class ImportMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:movies {--S|sleep= : Sleep duration between requests in milliseconds} {--L|limit= : Limit the number of movies to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import movies from TMDb daily export';

    /**
     * Base URL for TMDb movie exports.
     *
     * @var string
     */
    private string $baseUrl = 'http://files.tmdb.org/p/exports/movie_ids_%s.json.gz';

    /**
     * Create a new command instance.
     *
     */

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $tmdbService = new TmdbImportService($this->baseUrl, 'movies');
            $filePath = $tmdbService->process();

            $movieIds = collect(explode("\n", trim(Storage::disk('tmdb_files')->get($filePath))))
                ->map(fn ($line) => json_decode($line))
                ->pluck('id');

            if ($limit = (int)$this->option('limit')) {
                $movieIds = $movieIds->take($limit);
            }

            $movieIds->each(fn ($movieId) => ProcessMovieImport::dispatch($movieId));

            $tmdbService->delete();
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
