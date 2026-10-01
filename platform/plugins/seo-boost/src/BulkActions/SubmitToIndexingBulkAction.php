<?php

namespace Botble\SeoBoost\BulkActions;

use Botble\Base\Contracts\BaseModel;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\SeoBoost\Services\IndexNowService;
use Botble\Base\Supports\Enum;
use Botble\Table\Abstracts\TableBulkActionAbstract;
use Illuminate\Database\Eloquent\Model;

/**
 * Bulk action: "Submit to instant indexing" — manually pushes the selected
 * rows' permalinks to IndexNow (Bing/Yandex/...) and the Google Indexing
 * API from any table that indexes slugable content (Posts, Pages).
 *
 * Runs both engines; each one no-ops itself when unconfigured, and every
 * submission is recorded in the SEO Boost history regardless of outcome.
 */
class SubmitToIndexingBulkAction extends TableBulkActionAbstract
{
    public function __construct()
    {
        $this
            ->label(trans('plugins/seo-boost::seo-boost.bulk_action_label'))
            ->confirmationModalButton(trans('plugins/seo-boost::seo-boost.bulk_action_label'))
            ->confirmationModalMessage(trans('plugins/seo-boost::seo-boost.bulk_action_confirm'));
    }

    public function dispatch(BaseModel|Model $model, array $ids): BaseHttpResponse
    {
        $rows = $model->newQuery()
            ->whereKey($ids)
            ->with('slugable')
            ->get();

        $urls = [];
        $skipped = 0;

        foreach ($rows as $row) {
            // Only published, slugable rows have a public permalink to submit.
            $status = $row->status;

            $statusValue = $status instanceof Enum ? $status->getValue() : (string) $status;

            if ($statusValue !== BaseStatusEnum::PUBLISHED) {
                $skipped++;

                continue;
            }

            $slugKey = $row->slugable->key ?? '';

            if ($slugKey === '') {
                $skipped++;

                continue;
            }

            $urls[] = rtrim(url('/'), '/') . '/' . ltrim($slugKey, '/');
        }

        if (! $urls) {
            return BaseHttpResponse::make()
                ->setError()
                ->setMessage(trans('plugins/seo-boost::seo-boost.bulk_action_nothing_to_submit', ['skipped' => $skipped]));
        }

        $indexNow = app(IndexNowService::class);
        $google = app(GoogleIndexingService::class);

        // IndexNow accepts batches; Google publishes one URL per call.
        $results = [
            'indexnow' => $indexNow->submit($urls, true),
            'google' => $google->submit($urls, 'update', true),
        ];

        if (! in_array(true, $results, true)) {
            return BaseHttpResponse::make()
                ->setError()
                ->setMessage(trans('plugins/seo-boost::seo-boost.bulk_action_failed', ['count' => count($urls)]));
        }

        return BaseHttpResponse::make()
            ->setMessage(trans('plugins/seo-boost::seo-boost.bulk_action_success', [
                'count' => count($urls),
                'engines' => implode(', ', array_keys($results, true, true)),
            ]));
    }
}
