<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ReleaseStatus;
use App\Models\Movie;
use App\Services\TmdbImportService;
use Chiiya\Tmdb\Entities\Movies\MovieDetails;
use Chiiya\Tmdb\Repositories\MovieRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class ImportMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:movies';

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
        private MovieRepository $movies,
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $tmdbService = new TmdbImportService($this->baseUrl, 'movies');
        $filePath = $tmdbService->process();

        if (!Storage::disk('tmdb_files')->exists($filePath)) {
            $this->error("File not found on disk: tmdb_files");
            return;
        }

        $data = Storage::disk('tmdb_files')->get($filePath);
        $lines = explode("\n", trim($data));

        collect($lines)
            ->map(fn($line) => json_decode($line))
            ->each(fn($movie) => $this->importMovie($movie));
    }

    /**
     * Import a movie if it doesn't already exist.
     *
     * @param object $movie
     * @return void
     */
    private function importMovie(object $movie): void
    {
        try {
            $existingMovie = Movie::where('tmdb_id', $movie->id)->first();

            if ($existingMovie) {
                $this->info("Movie with TMDb ID {$movie->id} already exists.");
                return;
            }

            $movieDetails = $this->movies->getMovie($movie->id);


            $this->saveMovie($movieDetails);
            $this->info("Successfully imported movie with TMDb ID {$movie->id}.");
        } catch (Exception $e) {
            $this->error("Failed to import movie with TMDb ID {$movie->id}: {$e->getMessage()}");

        }
    }


    /**
     * Save a movie to the database.
     *
     * @param MovieDetails $movieData
     * @return void
     */
    private function saveMovie(MovieDetails $movieData): void
    {
        DB::transaction(function () use ($movieData): void {
            Movie::query()->create([
                'tmdb_id' => $movieData->id,
                'imdb_id' => $movieData->imdb_id,
                'title' => $movieData->title,
                'title_sort' => $movieData->title,
                'original_language' => $movieData->original_language,
                'is_adult' => $movieData->adult,
                'backdrop_path' => $movieData->backdrop_path,
                'budget' => $movieData->budget,
                'homepage' => $movieData->homepage,
                'original_title' => $movieData->original_title,
                'overview' => $movieData->overview,
                'popularity' => $movieData->popularity,
                'poster_path' => $movieData->poster_path,
                'release_date' => $movieData->release_date,
                'revenue' => $movieData->revenue,
                'runtime' => $movieData->runtime,
                'status' => ReleaseStatus::getValue($movieData->status),
                'tagline' => $movieData->tagline,
                'vote_average' => $movieData->vote_average,
                'vote_count' => $movieData->vote_count,
            ]);
        });
    }
}
