<section class="mx-auto p-2">
    <!-- Heading and See More Link -->
    <header class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Latest Movies</h2>
        <a href="#" class="text-neutral-500 hover:underline" aria-label="See more movies">See More</a>
    </header>

    <!-- Movie Grid -->
    <div class="overflow-x-auto">
        <div class="grid grid-cols-10 gap-6 bg-neutral-100 min-w-max">
            @foreach ($this->movies as $movie)
                <article class="movie-card-wrapper">
                    <x-movie-card :movie="$movie" :key="$movie->id"/>
                </article>
            @endforeach
        </div>
    </div>
</section>
