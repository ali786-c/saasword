<?php

namespace Botble\SeoBoost\Providers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\SeoBoost\Listeners\SubmitUrlToIndexNow;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreatedContentEvent::class => [
            SubmitUrlToIndexNow::class,
        ],
        UpdatedContentEvent::class => [
            SubmitUrlToIndexNow::class,
        ],
        DeletedContentEvent::class => [
            SubmitUrlToIndexNow::class,
        ],
    ];
}
