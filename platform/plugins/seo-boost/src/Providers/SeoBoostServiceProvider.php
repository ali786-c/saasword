<?php

namespace Botble\SeoBoost\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Eloquent\IndexNowLogRepository;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\SeoBoost\Services\IndexNowService;

class SeoBoostServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(IndexNowLogInterface::class, function () {
            return new IndexNowLogRepository(new IndexNowLog());
        });

        $this->app->singleton(IndexNowService::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/seo-boost')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets()
            ->loadRoutes();

        $this->app->register(EventServiceProvider::class);

        DashboardMenu::beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-seo-boost')
                        ->priority(990)
                        ->name('plugins/seo-boost::seo-boost.menu_name')
                        ->icon('ti ti-rocket')
                        ->route('seo-boost.index')
                        ->permissions('seo-boost.index')
                );
        });
    }
}
