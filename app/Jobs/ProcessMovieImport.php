<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\CreateMovieAction;
use App\Models\Movie;
use App\Models\User;
use Chiiya\Tmdb\Repositories\MovieRepository;
use Chiiya\Tmdb\Repositories\PersonRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

final class ProcessMovieImport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly int $movieId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(MovieRepository $movieRepository, PersonRepository $personRepository): void
    {
        try {
            if (Movie::whereTmdbId($this->movieId)->exists()) {
                return;
            }
            $movieDetails = $movieRepository->getMovie($this->movieId, ['append_to_response' => 'credits']);

            $systemUserId = cache()->remember('system_user_id', now()->addMinutes(30), fn () => User::whereName(config('system.name'))->firstOrFail()->id);

            CreateMovieAction::handle($movieDetails, $systemUserId, $personRepository);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
