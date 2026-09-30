<?php

namespace Botble\SeoBoost\Repositories\Eloquent;

use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class IndexNowLogRepository extends RepositoriesAbstract implements IndexNowLogInterface
{
    public function __construct(IndexNowLog $model)
    {
        parent::__construct($model);
    }

    public function latestLog(?string $engine = null): ?IndexNowLog
    {
        return $this->model
            ->when($engine !== null, fn ($query) => $query->where('engine', $engine))
            ->orderByDesc('created_at')
            ->first();
    }
}
