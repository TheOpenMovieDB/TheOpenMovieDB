<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;

final class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): void
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): void
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie): void
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie): void
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): void
    {
    }
}
