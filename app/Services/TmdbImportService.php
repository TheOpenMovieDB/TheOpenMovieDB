<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

final readonly class TmdbImportService
{
    private string $date;
    private string $filePath;
    private string $url;

    private string $diskName;

    public function __construct(
        private string $baseUrl,
        private string $mediaType
    ) {
        $this->date = now()->format('m_d_Y');
        $this->filePath = "{$this->mediaType}_ids_{$this->date}.json";
        $this->url = sprintf($this->baseUrl, $this->date);
        $this->diskName = 'tmdb_files';

    }


    /**
     * Import and return the file path
     *
     * @throws Exception
     */
    public function process(bool $getFileContent = false): string|false
    {

        if ( ! Storage::disk($this->diskName)->exists($this->filePath)) {
            $this->import();
        }

        if ( ! $getFileContent) {
            return $this->filePath;
        }
        return file_get_contents(Storage::disk($this->diskName)->path($this->filePath));
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        if (Storage::disk($this->diskName)->exists($this->filePath)) {
            return Storage::disk($this->diskName)->delete($this->filePath);
        }
        throw new Exception("Failed to delete the file.");
    }

    /**
     * Download and decompress the file, then store it in the disk.
     *
     * @throws Exception
     */
    private function import(): string
    {
        try {
            $response = Http::get($this->url);
            if ( ! $response->ok()) {
                throw new Exception("Failed to download the file. HTTP Status: {$response->status()}");
            }

            $gzContent = gzdecode($response->body());
            if (false === $gzContent) {
                throw new Exception("Failed to decompress the file.");
            }

            if (Storage::disk($this->diskName)->put($this->filePath, $gzContent)) {
                return $this->filePath;
            }

            throw new Exception("Failed to save the file.");


        } catch (Exception $exception) {

            if (Storage::disk($this->diskName)->exists($this->filePath)) {
                Storage::disk($this->diskName)->delete($this->filePath);
            }

            throw $exception;
        }
    }
}
