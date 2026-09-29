<?php

namespace Theme\Stories\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Http\Request;

class StoriesController extends PublicController
{
    public function ajaxGetPanelInner(Request $request, BaseHttpResponse $response)
    {
        abort_unless($request->ajax(), 404);

        if (theme_option('panel_sidebar_enabled', 'yes') !== 'yes') {
            return $response->setData('');
        }

        return $response->setData(Theme::partial('components.panel-inner'));
    }

    public function ajaxGetBlogPostsSearch(Request $request, PostInterface $postRepository): BaseHttpResponse
    {
        $query = BaseHelper::stringify($request->input('q'));

        $posts = collect();

        if (! empty($query)) {
            $posts = $postRepository->getSearch($query);

            $posts = $posts->take(4);
        }

        return $this
            ->httpResponse()
            ->setData(
                view(Theme::getThemeNamespace('partials.blog.search-results'), compact('posts'))->render()
            );
    }
}
