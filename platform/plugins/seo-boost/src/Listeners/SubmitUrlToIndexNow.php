<?php

namespace Botble\SeoBoost\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\SeoBoost\Services\IndexNowService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubmitUrlToIndexNow implements ShouldQueue
{
    public function __construct(
        protected IndexNowService $indexNowService,
        protected GoogleIndexingService $googleIndexingService
    ) {
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

        if (! $url || $this->isLocalUrl($url)) {
            return;
        }

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
     * Search engines can never reach loopback/private hosts: submitting
     * those URLs only burns quota and logs SSL/connection noise.
     */
    protected function isLocalUrl(string $url): bool
    {
        $host = (string) (parse_url($url, PHP_URL_HOST) ?: '');

        if ($host === '' || in_array(strtolower($host), ['localhost', 'localhost.localdomain'], true)) {
            return true;
        }

        $ip = gethostbyname($host);

        // gethostbyname returns the hostname unchanged when DNS fails;
        // only treat it as an IP when it actually resolved.
        if ($ip === $host) {
            return false;
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
