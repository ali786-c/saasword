<div class="onesignal-meta-box-wrap">
    <div class="mb-3">
        <label class="form-check form-switch me-3">
            <input
                type="hidden"
                name="onesignal_send_notification"
                value="0"
            />
            <input
                type="checkbox"
                class="form-check-input"
                name="onesignal_send_notification"
                value="1"
                id="onesignal_send_notification"
                @checked(old('onesignal_send_notification', $sendNotification ?? setting('onesignal_auto_send_on_post_publish', 1)))
            />
            <span class="form-check-label font-weight-bold">
                {{ trans('plugins/onesignal::onesignal.send_push_for_this_post') }}
            </span>
        </label>
    </div>

    <div class="mb-3">
        <label for="onesignal_custom_title" class="form-label">
            {{ trans('plugins/onesignal::onesignal.custom_push_title') }}
        </label>
        <input
            type="text"
            class="form-control"
            name="onesignal_custom_title"
            id="onesignal_custom_title"
            value="{{ old('onesignal_custom_title', $customTitle ?? '') }}"
            placeholder="{{ trans('plugins/onesignal::onesignal.custom_push_title_placeholder') }}"
        />
    </div>

    <div class="mb-3">
        <label for="onesignal_custom_message" class="form-label">
            {{ trans('plugins/onesignal::onesignal.custom_push_message') }}
        </label>
        <textarea
            class="form-control"
            name="onesignal_custom_message"
            id="onesignal_custom_message"
            rows="2"
            placeholder="{{ trans('plugins/onesignal::onesignal.custom_push_message_placeholder') }}"
        >{{ old('onesignal_custom_message', $customMessage ?? '') }}</textarea>
    </div>
</div>
