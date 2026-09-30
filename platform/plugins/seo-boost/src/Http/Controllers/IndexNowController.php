<?php

namespace Botble\SeoBoost\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\SeoBoost\Services\IndexNowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexNowController extends BaseController
{
    public function __construct(
        protected IndexNowService $indexNowService,
        protected IndexNowLogInterface $logRepository
    ) {
    }

    public function index(): View
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
            'latestLog' => $this->logRepository->latestLog(),
        ]);
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
