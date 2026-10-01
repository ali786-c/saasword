<?php

namespace Botble\SeoBoost\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Blog\Tables\PostTable;
use Botble\Page\Tables\PageTable;
use Botble\SeoBoost\BulkActions\SubmitToIndexingBulkAction;
use Botble\SeoBoost\Models\IndexNowLog;
use Botble\SeoBoost\Repositories\Eloquent\IndexNowLogRepository;
use Botble\SeoBoost\Repositories\Interfaces\IndexNowLogInterface;
use Botble\SeoBoost\Services\GoogleIndexingService;
use Botble\SeoBoost\Services\IndexNowService;
use Botble\Setting\PanelSections\SettingOthersPanelSection;

class SeoBoostServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(IndexNowLogInterface::class, function () {
            return new IndexNowLogRepository(new IndexNowLog());
        });

        $this->app->singleton(IndexNowService::class);
        $this->app->singleton(GoogleIndexingService::class);
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
                        ->permissions('seo-boost.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-seo-boost-overview')
                        ->priority(5)
                        ->parentId('cms-plugins-seo-boost')
                        ->name('plugins/seo-boost::seo-boost.overview')
                        ->icon('ti ti-rocket')
                        ->route('seo-boost.index')
                        ->permissions('seo-boost.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-seo-boost-logs')
                        ->priority(10)
                        ->parentId('cms-plugins-seo-boost')
                        ->name('plugins/seo-boost::seo-boost.history_title')
                        ->icon('ti ti-history')
                        ->route('seo-boost.logs')
                        ->permissions('seo-boost.logs')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-seo-boost-settings')
                        ->priority(20)
                        ->parentId('cms-plugins-seo-boost')
                        ->name('plugins/seo-boost::seo-boost.settings_name')
                        ->icon('ti ti-settings')
                        ->route('seo-boost.settings')
                        ->permissions('seo-boost.settings')
                );
        });

        // Bulk action: "Submit to instant indexing" on the Posts and Pages
        // tables. Filtered through the core table hook so each table decides
        // its own bulk actions; the allowlist keeps unknown models out (the
        // action only understands Post/Page permalinks).
        add_filter('base_filter_table_bulk_actions', function (array $actions, $table = null) {
            if (! in_array($table::class, [PostTable::class, PageTable::class], true)) {
                return $actions;
            }

            $actions[] = SubmitToIndexingBulkAction::class;

            return $actions;
        }, 10, 2);

        PanelSectionManager::beforeRendering(function (): void {
            PanelSectionManager::default()
                ->registerItem(
                    SettingOthersPanelSection::class,
                    fn () => PanelSectionItem::make('seo-boost-settings')
                        ->setTitle(trans('plugins/seo-boost::seo-boost.settings_name'))
                        ->withIcon('ti ti-rocket')
                        ->withDescription(trans('plugins/seo-boost::seo-boost.settings_description'))
                        ->withPriority(999)
                        ->withRoute('seo-boost.settings')
                );
        });
    }
}
