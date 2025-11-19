<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class TiptapEditorFileCleanupService
{
    public function deleteFilesFromContent(string $html): void
    {
        $files = $this->extractFilesFromContent($html);

        foreach ($files as $src) {
            $this->deleteFileByUrl($src);
        }
    }

    public function deleteFileByUrl(string $src): void
    {
        // Hapus base URL /storage
        $path = str_replace('/storage/', '', $src);
        $path = ltrim($path, '/');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function extractFilesFromContent(string $html): array
    {
        $allFiles = [];

        // Ambil semua gambar <img src="...">
        preg_match_all('/<img[^>]+src=[\'"]([^\'">]+)[\'"]/', $html, $imgMatches);
        if (!empty($imgMatches[1])) {
            $allFiles = array_merge($allFiles, $imgMatches[1]);
        }

        // Ambil semua link <a href="...">
        preg_match_all('/<a[^>]+href=[\'"]([^\'">]+)[\'"]/', $html, $linkMatches);
        if (!empty($linkMatches[1])) {
            $allFiles = array_merge($allFiles, $linkMatches[1]);
        }

        return array_unique($allFiles);
    }
}
