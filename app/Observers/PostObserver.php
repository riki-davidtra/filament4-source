<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Services\RichEditorFileCleanupService;

class PostObserver
{
    public function __construct(
        protected RichEditorFileCleanupService $richEditorFilecleanupService
    ) {}

    public function creating(Post $post): void
    {
        if (empty($post->user_id) && Auth::check()) {
            $post->user_id = Auth::id();
        }

        $this->generateThumbnail($post);
    }

    public function updating(Post $post): void
    {
        if ($post->isDirty('image')) {
            $this->deleteFiles($post->getOriginal('image'), $post->getOriginal('thumbnail'));
        }

        $this->generateThumbnail($post);

        if ($post->isDirty('content')) {
            $oldContent = $post->getOriginal('content') ?? '';
            $newContent = $post->content ?? '';

            $oldFiles = $this->richEditorFilecleanupService->extractFilesFromContent($oldContent);
            $newFiles = $this->richEditorFilecleanupService->extractFilesFromContent($newContent);

            $deletedFiles = array_diff($oldFiles, $newFiles);

            foreach ($deletedFiles as $fileUrl) {
                $this->richEditorFilecleanupService->deleteFileByUrl($fileUrl);
            }
        }
    }

    public function deleting(Post $post): void
    {
        $this->deleteFiles($post->image, $post->thumbnail);

        if (!empty($post->content)) {
            $this->richEditorFilecleanupService->deleteFilesFromContent($post->content);
        }
    }

    protected function generateThumbnail(Post $post)
    {
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            $filePath = Storage::disk('public')->path($post->image);

            $manager   = new ImageManager(new Driver());
            $thumbnail = $manager->read($filePath)->scaleDown(width: 600);

            // Ambil folder dan nama file asli
            $originalDir = pathinfo($post->image, PATHINFO_DIRNAME);
            $filename    = pathinfo($post->image, PATHINFO_BASENAME);

            // Path lengkap thumbnail
            $thumbnailPath = $originalDir . '/thumbnail/' . $filename;

            // Simpan thumbnail ke storage
            Storage::disk('public')->put($thumbnailPath, (string) $thumbnail->encode());

            // Simpan path thumbnail ke field
            $post->thumbnail = $thumbnailPath;
        }
    }

    protected function deleteFiles(?string $image, ?string $thumbnail): void
    {
        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }

        if ($thumbnail && Storage::disk('public')->exists($thumbnail)) {
            Storage::disk('public')->delete($thumbnail);
        }
    }
}
