<?php

namespace Botble\SeoBoost\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseSystemController;
use Botble\SeoBoost\Http\Requests\SubmitUrlsRequest;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\SeoBoost\Services\IndexNowService;
use Botble\SeoBoost\Tables\IndexNowLogTable;
use Botble\Setting\Facades\Setting;
use Exception;
use Illuminate\Http\Request;

class IndexNowController extends BaseSystemController
{
    public function __construct(
        protected IndexNowService $indexNowService,
        protected GoogleIndexingService $googleIndexingService,
        protected IndexNowLogInterface $logRepository
    ) {
    }

    public function index()
    {
        PageTitle::setTitle(trans('plugins/seo-boost::seo-boost.name'));

        $key = $this->indexNowService->getKey();

        if (! $key) {
            $key = $this->indexNowService->resetKey();
        }

        return view('plugins/seo-boost::index', [
            'key' => $key,
            'keyFileUrl' => $this->indexNowService->keyFileUrl(),
            'isEnabled' => $this->indexNowService->isEnabled(),
            'googleConfigured' => $this->googleIndexingService->isConfigured(),
        ]);
    }

    /**
     * Submission history page (GET) and DataTable AJAX data (POST).
     */
    public function getLogs(IndexNowLogTable $logTable)
    {
        PageTitle::setTitle(trans('plugins/seo-boost::seo-boost.history_title'));

        $this->indexNowService->trimLog();

        return $logTable->renderTable();
    }

    /**
     * Manual bulk URL submission across both engines.
     */
    public function submit(SubmitUrlsRequest $request)
    {
        $urls = array_values(array_filter(array_map(
            'trim',
            preg_split('/\R/', (string) $request->input('urls'))
        )));

        $urls = array_slice($urls, 0, 100);
        $action = $request->input('action') === 'delete' ? 'delete' : 'update';

        if (! $urls) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/seo-boost::seo-boost.no_urls_provided'));
        }

        $results = [];
        $anySucceeded = false;

        if ($request->boolean('engine_indexnow')) {
            $results['indexnow'] = $this->indexNowService->submit($urls, true);

            $anySucceeded = $anySucceeded || $results['indexnow'];
        }

        if ($request->boolean('engine_google')) {
            $results['google'] = $this->googleIndexingService->submit($urls, $action, true);

            $anySucceeded = $anySucceeded || $results['google'];
        }

        if (! $results) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/seo-boost::seo-boost.no_engine_selected'));
        }

        return $this
            ->httpResponse()
            ->setError(! $anySucceeded)
            ->setData($results)
            ->setMessage(
                $anySucceeded
                    ? trans('plugins/seo-boost::seo-boost.submitted_successfully', ['count' => count($urls)])
                    : trans('plugins/seo-boost::seo-boost.submission_failed')
            );
    }

    public function getSettings()
    {
        PageTitle::setTitle(trans('plugins/seo-boost::seo-boost.settings_name'));

        return view('plugins/seo-boost::settings', [
            'googleConfigured' => $this->googleIndexingService->isConfigured(),
            'googleAccount' => $this->googleIndexingService->parseJsonKey()['client_email'] ?? '',
        ]);
    }

    public function postSettings(Request $request)
    {
        $enabled = $request->boolean('seo_boost_enabled');

        $settings = ['seo_boost_enabled' => $enabled ? '1' : '0'];

        // Unchecked checkboxes are absent from the request: persist them as '0'.
        foreach (['post', 'page'] as $type) {
            $settings["seo_boost_post_types.$type"] = $request->input("seo_boost_post_types.$type") == 1 ? '1' : '0';
            $settings["seo_boost_google_post_types.$type"] = $request->input("seo_boost_google_post_types.$type") == 1 ? '1' : '0';
        }

        // Google-specific toggles and JSON key.
        $settings['seo_boost_google_enabled'] = $request->boolean('seo_boost_google_enabled') ? '1' : '0';

        if ($request->filled('seo_boost_google_json_key')) {
            $json = trim((string) $request->input('seo_boost_google_json_key'));

            if ($this->googleIndexingService->parseJsonKey($json) === null) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage(trans('plugins/seo-boost::seo-boost.google_key_invalid'));
            }

            Setting::set('seo_boost_google_json_key', $json);
        }

        Setting::set($settings)->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    /**
     * Validate the stored/pasted Google key by fetching an access token.
     */
    public function testGoogleConnection()
    {
        try {
            $token = $this->googleIndexingService->getAccessToken();

            return $this
                ->httpResponse()
                ->setData(['connected' => true])
                ->setMessage(trans('plugins/seo-boost::seo-boost.google_connection_ok'));
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/seo-boost::seo-boost.google_connection_failed', ['error' => $exception->getMessage()]));
        }
    }

    /**
     * Regenerate the IndexNow API key.
     */
    public function resetKey()
    {
        try {
            $key = $this->indexNowService->resetKey();
        } catch (Exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/seo-boost::seo-boost.key_reset_failed'));
        }

        return $this
            ->httpResponse()
            ->setData(['key' => $key, 'key_file_url' => $this->indexNowService->keyFileUrl()])
            ->setMessage(trans('plugins/seo-boost::seo-boost.key_reset_success'));
    }

    /**
     * Serve the IndexNow API key verification file: /{key}.txt
     * Must be plain text and excluded from indexing.
     */
    public function serveKeyFile(Request $request, string $keyFile)
    {
        $requestedKey = substr($keyFile, 0, -4);

        if (! $requestedKey || $requestedKey !== $this->indexNowService->getKey()) {
            abort(404);
        }

        return response($requestedKey, 200, [
            'Content-Type' => 'text/plain',
            'X-Robots-Tag' => 'noindex',
        ]);
    }
}
