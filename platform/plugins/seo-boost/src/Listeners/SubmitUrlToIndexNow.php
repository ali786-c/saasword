<?php

namespace Botble\SeoBoost\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\SeoBoost\Services\IndexNowService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubmitUrlToIndexNow implements ShouldQueue
{
    public function __construct(protected IndexNowService $indexNowService)
    {
    }

    public function handle(CreatedContentEvent|UpdatedContentEvent $event): void
    {
        $model = $event->data;

        if (! $model instanceof Post && ! $model instanceof Page) {
            return;
        }

        // Only fire for published content (drafts/pending are skipped).
        $status = $model->status;

        if (! $status instanceof BaseStatusEnum || $status->getValue() !== BaseStatusEnum::PUBLISHED) {
            return;
        }

        try {
            $url = $model->url;

            // Prefer the slug key when available: freshly saved models can
            // resolve $model->url to the homepage before the slug is attached.
            $slugKey = $model->slugable->key ?? '';

            if ($slugKey !== '') {
                $url = rtrim(url('/'), '/') . '/' . ltrim($slugKey, '/');
            }
        } catch (Exception) {
            return;
        }

        if (! $url || str_contains($url, 'localhost')) {
            return;
        }

        $this->indexNowService->submitAuto($url);
    }
}
