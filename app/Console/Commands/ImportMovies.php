<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\CreateMovieAction;
use App\Models\Movie;
use App\Models\User;
use App\Services\TmdbImportService;
use Chiiya\Tmdb\Repositories\MovieRepository;
use Chiiya\Tmdb\Repositories\PersonRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Sleep;
use Throwable;

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
     * @param MovieRepository $movies
     */
    public function __construct(
        private readonly MovieRepository  $tmdbMovieRepository,
        private readonly PersonRepository $personRepository,
    ) {
        parent::__construct();
    }

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

            if ( ! Storage::disk('tmdb_files')->exists($filePath)) {
                $this->error("File not found on disk: tmdb_files");
                return;
            }

            $movies = collect(explode("\n", trim(Storage::disk('tmdb_files')->get($filePath))))
                ->map(fn ($line) => json_decode($line));

            if ($limit = (int)$this->option('limit')) {
                $movies = $movies->take($limit);
            }

            $movies->each(fn ($movie) => $this->importMovie($movie));

            $tmdbService->delete();
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * Import a movie if it doesn't already exist.
     *
     * @param object $movie
     *
     * @return void
     * @throws Throwable
     * @property int $id
     *
     */
    private function importMovie(object $movie): void
    {
        try {
            if (Movie::where('tmdb_id', $movie->id)->exists()) {
                $this->info("Movie with TMDb ID {$movie->id} already exists.");
                return;
            }

            if ($sleepDuration = (int)$this->option('sleep')) {
                Sleep::for($sleepDuration)->milliseconds();
            }

            $movieDetails = $this->tmdbMovieRepository->getMovie($movie->id, ['append_to_response' => 'credits']);

            $systemUserId = cache()->remember('system_user_id', now()->addMinutes(30), fn () => User::whereName(config('system.name'))->firstOrFail()->id);

            CreateMovieAction::handle($movieDetails, $systemUserId, $this->personRepository);

            $this->info("Successfully imported movie with TMDb ID {$movie->id}.");
        } catch (Exception $e) {
            $this->error("Failed to import movie with TMDb ID {$movie->id}: {$e->getMessage()}");
        }
    }

}
