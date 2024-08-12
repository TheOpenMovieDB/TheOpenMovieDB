<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ReleaseStatus;
use App\Models\Company;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\User;
use App\Services\TmdbImportService;
use Chiiya\Tmdb\Entities\Common\CastCredit;
use Chiiya\Tmdb\Entities\Genre as TmdbGenre;
use Chiiya\Tmdb\Entities\Movies\MovieDetails;
use Chiiya\Tmdb\Repositories\MovieRepository;
use Chiiya\Tmdb\Repositories\PersonRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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

            $data = Storage::disk('tmdb_files')->get($filePath);
            $lines = explode("\n", trim($data));


            $movies = collect($lines)
                ->map(fn ($line) => json_decode($line));

            $limit = $this->option('limit');
            if ($limit && (int)$limit > 0) {
                $movies = $movies->take((int)$limit);
            }

            $movies->each(fn ($movie) => $this->importMovie($movie));

            $tmdbService->delete();
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
            return;
        }
    }

    /**
     * Save & sync genres to the database.
     *
     * @param Movie $movie
     * @param MovieDetails $movieData
     * @return void
     *
     */
    public function saveGenres(Movie $movie, MovieDetails $movieData, int $userId): void
    {
        $genreIds = collect($movieData->genres)->map(function (TmdbGenre $genre) use ($userId) {
            return Genre::query()->firstOrCreate([
                'tmdb_id' => $genre->id,
                'name' => $genre->name,
                'user_id' => $userId,
            ])->id;
        });

        $movie->genres()->sync($genreIds);
    }

    /**
     * Import a movie if it doesn't already exist.
     *
     * @param object $movie
     * @return void
     * @property int $id
     *
     */
    private function importMovie(object $movie): void
    {
        try {
            $existingMovie = Movie::where('tmdb_id', $movie->id)->first();

            if ($existingMovie) {
                $this->info("Movie with TMDb ID {$movie->id} already exists.");
                return;
            }

            if ($sleepDuration = $this->option('sleep')) {
                // To avoid getting rate-limited by the TMDb API
                // https://developer.themoviedb.org/docs/rate-limiting
                Sleep::for((int)$sleepDuration)->milliseconds();
            }

            $movieDetails = $this->tmdbMovieRepository->getMovie($movie->id, ['append_to_response' => 'credits']);

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
     * @throws Throwable
     */
    private function saveMovie(MovieDetails $movieData): void
    {
        $systemUserId = cache()->remember("system_user_id", now()->addMinutes(30), fn () => User::whereName(config('system.name'))->firstOrFail()->id);

        DB::transaction(function () use ($systemUserId, $movieData): void {
            $movie = Movie::query()->create([
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
                'user_id' => $systemUserId
            ]);


            $this->saveGenres($movie, $movieData, $systemUserId);
            $this->saveCastMembers($movie, $movieData->credits?->cast ?? [], $systemUserId);
            $this->saveCrewMembers($movie, $movieData->credits?->crew ?? [], $systemUserId);
            $this->saveCompanies($movie, $movieData->production_companies ?? [], $systemUserId);
        });
    }

    /**
     * Save cast members to the database.
     *
     * @param Movie $movie
     * @param array $castMembers
     * @return void
     */
    private function saveCastMembers(Movie $movie, array $castMembers, int $userId): void
    {
        /** @var CastCredit $castMember */
        foreach ($castMembers as $castMember) {
            $personDetails = $this->personRepository->getPerson($castMember->id);
            $person = Person::query()->firstOrCreate(
                ['tmdb_id' => $castMember->id],
                [
                    'name' => $personDetails->name,
                    'birthday' => $personDetails->birthday ?? null,
                    'biography' => $personDetails->biography ?? null,
                    'profile_path' => $personDetails->profile_path ?? null,
                    'imdb_id' => $personDetails->imdb_id,
                    'is_adult' => $personDetails->adult,
                    'popularity' => $personDetails->popularity,
                    'gender' => $personDetails->gender,
                    'known_for_department' => $personDetails->known_for_department,
                    'user_id' => $userId
                ]
            );

            $movie->people()->attach($person->id, [
                'role' => 'cast',
                'character' => $castMember->character ?? null,
                'credit_id' => $castMember->credit_id ?? null,
                'order' => $castMember->order ?? null,
            ]);
        }
    }

    /**
     * Save crew members to the database.
     *
     * @param Movie $movie
     * @param array $crewMembers
     * @return void
     */
    private function saveCrewMembers(Movie $movie, array $crewMembers, int $userId): void
    {
        foreach ($crewMembers as $crewMember) {
            $personDetails = $this->personRepository->getPerson($crewMember->id);
            $person = Person::query()->firstOrCreate(
                ['tmdb_id' => $crewMember->id],
                [
                    'name' => $personDetails->name,
                    'birthday' => $personDetails->birthday ?? null,
                    'biography' => $personDetails->biography ?? null,
                    'profile_path' => $personDetails->profile_path ?? null,
                    'imdb_id' => $personDetails->imdb_id ?? null,
                    'is_adult' => $personDetails->adult,
                    'popularity' => $personDetails->popularity,
                    'gender' => $personDetails->gender,
                    'known_for_department' => $personDetails->known_for_department,
                    'user_id' => $userId,

                ]
            );

            $movie->people()->attach($person->id, [
                'role' => 'crew',
                'character' => $crewMember->job ?? null,
                'credit_id' => $crewMember->credit_id ?? null,
            ]);
        }
    }

    /**
     * Save production companies to the database and associate them with the movie.
     *
     * @param Movie $movie
     * @param array $companies
     * @return void
     */
    private function saveCompanies(Movie $movie, array $companies, int $userId): void
    {
        $companyIds = collect($companies)->map(function ($companyData) use ($userId) {
            return Company::query()->firstOrCreate([
                'tmdb_id' => $companyData->id,
            ], [
                'name' => $companyData->name,
                'logo_path' => $companyData->logo_path,
                'origin_country' => $companyData->origin_country,
                'user_id' => $userId
            ])->id;
        });

        $movie->companies()->sync($companyIds);
    }


}
