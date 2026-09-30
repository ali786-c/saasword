<?php

namespace Botble\SeoBoost\Repositories\Interfaces;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface IndexNowLogInterface extends RepositoryInterface
{
    /**
     * Latest log row for one engine (engines have independent throttles).
     */
    public function latestLog(?string $engine = null): ?IndexNowLog;
}
