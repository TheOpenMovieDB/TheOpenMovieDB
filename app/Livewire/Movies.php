<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Movie;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class Movies extends Component
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<Movie>
     */
    #[Computed]
    public function movies(): \Illuminate\Database\Eloquent\Collection
    {

        return Movie::query()
            ->with([
                'genres' => fn ($query) => $query->limit(3),
                'cast' => fn ($query) => $query->limit(3)
            ])
            ->orderBy('release_date', 'desc')
            ->limit(10)
            ->get();
    }

    public function Placeholder()
    {
        return view('home.movies_placeholder');
    }

    public function render()
    {
        return view('livewire.movies');
    }
}
