@if (setting('onesignal_enabled') && setting('onesignal_app_id'))
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "{{ setting('onesignal_app_id') }}",
                @if (setting('onesignal_safari_web_id'))
                    safari_web_id: "{{ setting('onesignal_safari_web_id') }}",
                @endif
                notifyButton: {
                    enable: true,
                },
            });
        });
    </script>
@endif
