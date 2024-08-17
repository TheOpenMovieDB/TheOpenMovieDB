<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Movie;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class MovieCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Movie $movie)
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movie-card');
    }
}
