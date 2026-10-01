<?php

namespace Botble\SeoBoost\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\SeoBoost\Services\IndexNowService;
use Exception;
use Illuminate\Support\Facades\Cache;

/**
 * Runs synchronously on purpose: queued events carry the live Request
 * object (with Closures) and fail to serialize when QUEUE_CONNECTION=sync,
 * which 500s every post save. Both engines dedupe via wasRecentlySubmitted
 * and no-op instantly when disabled, so sync execution is cheap.
 */
class SubmitUrlToIndexNow
{
    protected const PERMALINK_CACHE_TTL = 604800;

    public function __construct(
        protected IndexNowService $indexNowService,
        protected GoogleIndexingService $googleIndexingService
    ) {
    }

    public function handle(CreatedContentEvent|UpdatedContentEvent|DeletedContentEvent $event): void
    {
        $model = $event->data;

        if (! $model instanceof Post && ! $model instanceof Page) {
            return;
        }

        if ($event instanceof DeletedContentEvent) {
            $this->handleDeleted($model);

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

        if (! $url || $this->isLocalUrl($url)) {
            return;
        }

        // Cache the permalink so a later delete can still notify Google
        // even though the model (and its slug relation) are already gone.
        Cache::put($this->permalinkCacheKey($model), $url, self::PERMALINK_CACHE_TTL);

        // IndexNow engine (Bing, Yandex, Naver, Seznam, Yep).
        $type = $model instanceof Post ? 'post' : 'page';

        if (setting('seo_boost_post_types.' . $type, '1') == '1') {
            $this->indexNowService->submitAuto($url);
        }

        // Google Indexing API engine, with its own toggle.
        if (setting('seo_boost_google_post_types.' . $type, '1') == '1') {
            $this->googleIndexingService->submitAuto($url);
        }
    }

    /**
     * Google-only URL_DELETED notification (IndexNow's protocol has no
     * delete; Rank Math likewise only sends deletes to Google).
     * Requires the permalink captured at the last publish/update.
     */
    protected function handleDeleted(Post|Page $model): void
    {
        $type = $model instanceof Post ? 'post' : 'page';

        if (setting('seo_boost_google_post_types.' . $type, '1') != '1') {
            return;
        }

        $url = (string) Cache::pull($this->permalinkCacheKey($model));

        if ($url === '' || $this->isLocalUrl($url)) {
            return;
        }

        $this->googleIndexingService->submitAuto($url, 'delete');
    }

    protected function permalinkCacheKey(Post|Page $model): string
    {
        return 'seo_boost_permalink_' . md5($model::class . '|' . $model->getKey());
    }

    /**
     * Search engines can never reach loopback/private hosts: submitting
     * those URLs only burns quota and logs SSL/connection noise.
     * Deliberately DNS-free (a hanging resolver must not block the
     * queue worker): checks IP literals and reserved names only.
     */
    protected function isLocalUrl(string $url): bool
    {
        $host = strtolower((string) (parse_url($url, PHP_URL_HOST) ?: ''));

        if ($host === '') {
            return true;
        }

        // Strip brackets from IPv6 literals, e.g. [::1].
        $host = trim($host, '[]');

        if (in_array($host, ['localhost', 'localhost.localdomain'], true)) {
            return true;
        }

        // Reserved suffixes used for local/testing environments.
        foreach (['.local', '.test', '.localhost', '.invalid'] as $suffix) {
            if (str_ends_with($host, $suffix)) {
                return true;
            }
        }

        // IP literal: local when loopback, private, link-local or unspecified.
        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
        }

        return false;
    }
}
