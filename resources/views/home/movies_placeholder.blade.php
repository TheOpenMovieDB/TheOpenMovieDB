<div class="overflow-x-auto container">
    <div class="grid grid-cols-2 gap-6 p-5 bg-gray-100 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-10 min-w-max">
        @for ($i = 0; $i < 10; $i++)
            <article
                class=" animate-pulse movie-card relative w-48 h-[280px] bg-gray-300 rounded-xl overflow-hidden shadow-lg ">
                <figure class="movie-card__figure relative w-full h-full overflow-hidden bg-gray-400">
                    <div class="movie-card__image absolute top-0 left-0 w-full h-full bg-gray-500"></div>
                    <figcaption
                        class="movie-card__caption absolute bottom-[-45%] left-0 w-full h-full bg-gradient-to-t from-gray-600 to-transparent"></figcaption>
                </figure>
                <div
                    class="movie-card__details absolute bottom-[-100%] left-0 w-full p-3 bg-gray-700 bg-opacity-60 backdrop-blur-lg text-gray-200 transition-all duration-300 group-hover:bottom-0 z-20">
                    <div class="movie-card__title h-4 bg-gray-500 rounded w-3/4 mb-2"></div>
                    <div class="movie-card__info h-3 bg-gray-500 rounded w-1/2 mb-2"></div>
                    <div class="movie-card__rating flex items-center gap-1 my-2">
                        <div class="w-4 h-4 bg-gray-500 rounded animate-pulse"></div>
                        <div class="w-4 h-4 bg-gray-500 rounded animate-pulse"></div>
                        <div class="w-4 h-4 bg-gray-500 rounded animate-pulse"></div>
                        <div class="w-4 h-4 bg-gray-500 rounded animate-pulse"></div>
                        <div class="w-4 h-4 bg-gray-500 rounded animate-pulse"></div>
                    </div>
                    <div class="movie-card__genres flex gap-1 mb-3 text-[10px]">
                        <div class="movie-card__genre h-4 bg-gray-500 rounded-full px-2 animate-pulse"></div>
                        <div class="movie-card__genre h-4 bg-gray-500 rounded-full px-2 animate-pulse"></div>
                    </div>
                    <div class="movie-card__description h-4 bg-gray-500 rounded mb-3 animate-pulse"></div>
                    <section class="movie-card__cast">
                        <h3 class="movie-card__cast-title mb-2 font-bold text-xs bg-gray-500 h-4 rounded animate-pulse"></h3>
                        <ul class="movie-card__cast-list flex gap-2">
                            @for ($j = 0; $j < 3; $j++)
                                <li class="movie-card__cast-item w-10 h-10 rounded-full overflow-hidden border border-gray-500 animate-pulse">
                                    <div class="w-full h-full bg-gray-500"></div>
                                </li>
                            @endfor
                        </ul>
                    </section>
                </div>
            </article>
        @endfor
    </div>
</div>
