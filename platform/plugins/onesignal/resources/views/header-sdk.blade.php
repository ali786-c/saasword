@if (setting('onesignal_enabled') && setting('onesignal_app_id'))
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            var options = {
                appId: "{{ setting('onesignal_app_id') }}",
                @if (setting('onesignal_safari_web_id'))
                    safari_web_id: "{{ setting('onesignal_safari_web_id') }}",
                @endif
                allowLocalhostAsSecureOrigin: true,
                serviceWorkerPath: 'OneSignalSDKWorker.js',
                serviceWorkerParam: { scope: '/' }
            };

            // Prompt Customization Options
            options.promptOptions = {
                actionMessage: {!! json_encode(setting('onesignal_prompt_action_message', "We'd like to send you notifications for the latest updates.")) !!},
                acceptButtonText: {!! json_encode(setting('onesignal_prompt_accept_button_text', 'Allow')) !!},
                cancelButtonText: {!! json_encode(setting('onesignal_prompt_cancel_button_text', 'No Thanks')) !!}
            };

            // Floating Bell Widget Options
            @if (setting('onesignal_notify_button_enable', 1))
                options.notifyButton = {
                    enable: true,
                    position: "{{ setting('onesignal_notify_button_position', 'bottom-right') }}",
                    size: "{{ setting('onesignal_notify_button_size', 'medium') }}",
                    theme: "{{ setting('onesignal_notify_button_theme', 'default') }}"
                };
            @else
                options.notifyButton = { enable: false };
            @endif

            // Welcome Notification Options
            @if (setting('onesignal_welcome_notification_enable', 1))
                options.welcomeNotification = {
                    title: {!! json_encode(setting('onesignal_welcome_notification_title', 'Thanks for subscribing!')) !!},
                    message: {!! json_encode(setting('onesignal_welcome_notification_message', 'You will now receive latest job alerts & updates.')) !!}
                    @if (setting('onesignal_welcome_notification_url'))
                        ,url: {!! json_encode(setting('onesignal_welcome_notification_url')) !!}
                    @endif
                };
            @else
                options.welcomeNotification = { disable: true };
            @endif

            await OneSignal.init(options);
        });
    </script>
@endif
