<?php

namespace Botble\SeoBoost\Repositories\Interfaces;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface IndexNowLogInterface extends RepositoryInterface
{
    public function latestLog(): ?IndexNowLog;
}
