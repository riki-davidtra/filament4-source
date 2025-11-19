<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class RichEditorFileCleanupService
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
        $path = str_replace(url('/storage'), '', $src);
        $path = ltrim($path, '/');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function extractFilesFromContent(string $html): array
    {
        $allFiles = [];

        preg_match_all('/<img[^>]+src=[\'"]([^\'">]+)[\'"]/', $html, $imgMatches);
        if (!empty($imgMatches[1])) {
            $allFiles = array_merge($allFiles, $imgMatches[1]);
        }

        preg_match_all('/<a[^>]+href=[\'"]([^\'">]+)[\'"]/', $html, $linkMatches);
        if (!empty($linkMatches[1])) {
            $allFiles = array_merge($allFiles, $linkMatches[1]);
        }

        preg_match_all('/data-trix-attachment="([^"]+)"/', $html, $attachmentMatches);
        if (!empty($attachmentMatches[1])) {
            foreach ($attachmentMatches[1] as $jsonString) {
                $decoded = json_decode(html_entity_decode($jsonString), true);
                if (!empty($decoded['url'])) {
                    $allFiles[] = $decoded['url'];
                }
            }
        }

        return array_unique($allFiles);
    }
}
