<?php

// Custom routes
use Botble\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;
use Theme\Stories\Http\Controllers\StoriesController;

Route::group(['namespace' => 'Theme\Stories\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function (): void {
        Route::get('ajax/get-panel-inner', 'StoriesController@ajaxGetPanelInner')
            ->name('theme.ajax-get-panel-inner');
    });
});

Theme::registerRoutes(function (): void {
    Route::group([
        'prefix' => 'ajax',
        'as' => 'public.ajax.',
        'middleware' => RequiresJsonRequestMiddleware::class,
        'controller' => StoriesController::class,
    ], function (): void {
        Route::get('posts/search', 'ajaxGetBlogPostsSearch')
            ->name('posts.search');
    });
});

Theme::routes();
