<?php

namespace Botble\OneSignal\Providers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Blog\Models\Post;
use Botble\Media\Facades\RvMedia;
use Botble\OneSignal\Services\OneSignalService;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class OneSignalServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(OneSignalService::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/onesignal')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        // Register Admin Settings Panel section
        PanelSectionManager::beforeRendering(function (): void {
            PanelSectionManager::default()
                ->registerItem(
                    SettingOthersPanelSection::class,
                    fn () => PanelSectionItem::make('onesignal-settings')
                        ->setTitle(trans('plugins/onesignal::onesignal.name'))
                        ->withIcon('ti ti-bell')
                        ->withDescription(trans('plugins/onesignal::onesignal.settings_description'))
                        ->withPriority(980)
                        ->withRoute('onesignal.settings')
                );
        });

        // Register front-end SDK in <head>
        if (BaseHelper::isFrontendRequest()) {
            add_filter(THEME_FRONT_HEADER, function (?string $html) {
                if (setting('onesignal_enabled') && setting('onesignal_app_id')) {
                    return $html . view('plugins/onesignal::header-sdk')->render();
                }

                return $html;
            }, 99);
        }

        // Register Post Editor MetaBox
        add_action(BASE_ACTION_META_BOXES, [$this, 'addOneSignalMetaBox'], 120, 2);

        // Register Event Listener for Post Creation / Update
        Event::listen([CreatedContentEvent::class, UpdatedContentEvent::class], function (CreatedContentEvent|UpdatedContentEvent $event): void {
            $data = $event->data;

            if (! $data instanceof Post) {
                return;
            }

            // Must be published status
            if ($data->status != 'published') {
                return;
            }

            // Check if push notification enabled globally
            if (! setting('onesignal_enabled')) {
                return;
            }

            $request = request();
            $shouldSend = false;

            if ($request->has('onesignal_send_notification')) {
                $shouldSend = (bool) $request->input('onesignal_send_notification');
            } else {
                $shouldSend = (bool) setting('onesignal_auto_send_on_post_publish', 1);
            }

            if (! $shouldSend) {
                return;
            }

            // Prevent duplicate sending if already pushed in current request lifecycle
            if (property_exists($data, 'onesignal_pushed') && $data->onesignal_pushed) {
                return;
            }
            $data->onesignal_pushed = true;

            $customTitle = $request->input('onesignal_custom_title');
            $customMessage = $request->input('onesignal_custom_message');

            $prefix = setting('onesignal_default_title_prefix');
            $title = $customTitle ?: ($prefix ? trim($prefix) . ' ' . $data->name : $data->name);
            $message = $customMessage ?: ($data->description ? Str::limit(strip_tags($data->description), 140) : $data->name);
            $url = $data->url;
            $image = $data->image ? RvMedia::getImageUrl($data->image, null, false, RvMedia::getDefaultImage()) : null;

            /** @var OneSignalService $service */
            $service = app(OneSignalService::class);
            $service->sendNotification(
                title: $title,
                message: $message,
                url: $url,
                image: $image
            );
        });
    }

    public function addOneSignalMetaBox(string $context, array|string|Model|null $object = null): void
    {
        if (
            AdminHelper::isInAdmin(true) &&
            $object instanceof Post &&
            $context == 'advanced' &&
            setting('onesignal_enabled')
        ) {
            MetaBox::addMetaBox(
                'onesignal_post_box',
                trans('plugins/onesignal::onesignal.meta_box_title'),
                function () use ($object) {
                    $sendNotification = setting('onesignal_auto_send_on_post_publish', 1);
                    return view('plugins/onesignal::meta-box', compact('sendNotification'))->render();
                },
                Post::class,
                $context
            );
        }
    }
}
