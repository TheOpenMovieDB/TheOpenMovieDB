<article class="movie-card relative w-48 h-[280px] bg-black rounded-xl overflow-hidden shadow-lg group"
         itemscope
         itemtype="https://schema.org/Movie">
    <figure class="movie-card__figure relative w-full h-full overflow-hidden">
        <img loading="lazy" src="{{ \App\Helpers\Common::ImagePath($movie->poster_path) }}"
             alt="{{ $movie->title }} movie poster"
             class="movie-card__image absolute top-0 left-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
             itemprop="image">
        <figcaption
            class="movie-card__caption absolute bottom-[-45%] left-0 w-full h-full bg-gradient-to-t from-black to-transparent transition-all duration-300 group-hover:bottom-0 z-10"></figcaption>
    </figure>
    <div class="movie-card__details absolute bottom-[-100%] left-0 w-full p-3 bg-black bg-opacity-60 backdrop-blur-lg text-white transition-all duration-300 group-hover:bottom-0 z-20">
        <h1 class="movie-card__title text-sm font-bold" itemprop="name">{{ $movie->title }}</h1>
        <h2 class="movie-card__info text-xs font-medium opacity-60">
            <span itemprop="releaseDate">{{ substr($movie->release_date, 0, 4) }}</span> • n/a •
            <span itemprop="duration">{{ \App\Helpers\Common::FormatRuntime($movie->runtime) }}</span> •
            <span itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                <meta itemprop="ratingValue" content="{{ $movie->vote_average }}">
                {{ number_format($movie->vote_average, 2) }}
            </span>
        </h2>
        <div class="movie-card__rating flex items-center gap-1 my-2">
            @php
                $rating = round($movie->vote_average / 2);
                $fullStars = floor($rating);
                $hasHalfStar = ($rating - $fullStars) >= 0.5;
            @endphp
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $fullStars)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="movie-card__star w-4 h-4 text-yellow-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                    </svg>
                @elseif ($i == $fullStars + 1 && $hasHalfStar)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="movie-card__star w-4 h-4 text-yellow-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="movie-card__star w-4 h-4 text-yellow-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                    </svg>
                @endif
            @endfor
        </div>
      @if($movie->genres->isNotEmpty())
            <div class="movie-card__genres flex gap-1 mb-3 text-[10px]" itemprop="genre">
                @foreach ($movie->genres as $genre)
                    <span class="movie-card__genre px-1 py-1 border border-white border-opacity-40 rounded-full">{{ $genre->name }}</span>
                @endforeach
            </div>
      @endif
        @if($movie->overview != null)
            <p class="movie-card__description text-xs opacity-80 mb-3" itemprop="description">
                {{ \Illuminate\Support\Str::limit($movie->overview,50) }}
            </p>
        @endif
        @if($movie->cast->isNotEmpty())
            <section class="movie-card__cast">
                <h3 class="movie-card__cast-title mb-2 font-bold text-xs">Cast</h3>
                <ul class="movie-card__cast-list flex gap-2">
                    @foreach ($movie->cast as $actor)
                        <li class="movie-card__cast-item w-10 h-10 rounded-full overflow-hidden border border-white"
                            itemscope itemtype="https://schema.org/Person">
                            <img src="{{ \App\Helpers\Common::ImagePath( $actor->profile_path) }}"
                                 alt="{{ $actor->name }}"
                                 title="{{ $actor->name }}"
                                 class="movie-card__cast-image w-full h-full object-cover"
                                 itemprop="image">
                            <meta itemprop="name" content="{{ $actor->name }}">
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</article>
