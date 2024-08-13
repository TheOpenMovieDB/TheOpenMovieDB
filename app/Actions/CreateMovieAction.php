<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ReleaseStatus;
use App\Models\{Company, Genre, Movie, Person};
use Chiiya\Tmdb\Entities\Common\CastCredit;
use Chiiya\Tmdb\Entities\Common\CrewCredit;
use Chiiya\Tmdb\Entities\Movies\MovieDetails;
use Chiiya\Tmdb\Repositories\PersonRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateMovieAction
{
    /**
     * Save a movie to the database.
     *
     * @param MovieDetails $movieData
     * @param int $systemUserId
     * @param PersonRepository $personRepository
     * @return Movie
     *
     * @throws Throwable
     */
    public static function handle(MovieDetails $movieData, int $systemUserId, PersonRepository $personRepository): Movie
    {
        return DB::transaction(function () use ($systemUserId, $movieData, $personRepository) {
            $movie = Movie::create([
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
                'user_id' => $systemUserId,
            ]);

            self::saveGenres($movie, $movieData, $systemUserId);
            self::saveCastMembers($movie, $movieData->credits?->cast, $systemUserId, $personRepository);
            self::saveCrewMembers($movie, $movieData->credits?->crew, $systemUserId, $personRepository);
            self::saveCompanies($movie, $movieData->production_companies, $systemUserId);

            return $movie;
        });
    }


    /**
     * Create & Save genres for the given movie.
     *
     * @param Movie $movie
     * @param MovieDetails $movieData
     * @param int $userId
     * @return void
     */
    private static function saveGenres(Movie $movie, MovieDetails $movieData, int $userId): void
    {
        $genreIds = collect($movieData->genres)->map(
            fn ($genre) => Genre::firstOrCreate([
                'tmdb_id' => $genre->id,
                'name' => $genre->name,
                'user_id' => $userId,
            ])->id
        );

        $movie->genres()->sync($genreIds);
    }

    /**
     * Create & Save cast members for the given movie.
     *
     * @param Movie $movie
     * @param array $castMembers
     * @param int $userId
     * @param PersonRepository $personRepository
     * @return void
     */
    private static function saveCastMembers(Movie $movie, array $castMembers, int $userId, PersonRepository $personRepository): void
    {

        /** @var CastCredit $castMember */
        foreach ($castMembers as $castMember) {
            $personDetails = $personRepository->getPerson($castMember->id);
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
     * Create & Save crew members for the given movie.
     *
     * @param Movie $movie
     * @param array $crewMembers
     * @param int $userId
     * @param PersonRepository $personRepository
     * @return void
     */
    private static function saveCrewMembers(Movie $movie, array $crewMembers, int $userId, PersonRepository $personRepository): void
    {
        /** @var CrewCredit $crewMember */
        foreach ($crewMembers as $crewMember) {
            $personDetails = $personRepository->getPerson($crewMember->id);
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
     * Create & Save production companies for the given movie.
     *
     * @param Movie $movie
     * @param array $companies
     * @param int $userId
     * @return void
     */
    private static function saveCompanies(Movie $movie, array $companies, int $userId): void
    {

        $companyIds = collect($companies)->map(
            fn ($company) => Company::query()->firstOrCreate([
                'tmdb_id' => $movie->id,
            ], [
                'name' => $company->name,
                'logo_path' => $company->logo_path,
                'origin_country' => $company->origin_country,
                'user_id' => $userId
            ])->id
        );

        $movie->companies()->sync($companyIds);
    }
}
