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
                @checked(old('onesignal_send_notification', $sendNotification ?? 1))
            />
            <span class="form-check-label font-weight-bold text-dark">
                Send OneSignal Push Notification when post is saved/published
            </span>
        </label>
    </div>

    <div class="mb-3">
        <label for="onesignal_target_segment" class="form-label font-weight-bold">
            Target Audience Segment
        </label>
        <select name="onesignal_target_segment" id="onesignal_target_segment" class="form-select">
            @foreach($segments as $segment)
                <option value="{{ $segment }}" @selected(old('onesignal_target_segment', $selectedSegment ?? 'All') == $segment)>
                    {{ $segment }}
                </option>
            @endforeach
        </select>
        <small class="text-muted d-block mt-1">Select target OneSignal segment to send push notification to.</small>
    </div>

    <div class="mb-3">
        <label for="onesignal_custom_title" class="form-label">
            Custom Push Title (Optional)
        </label>
        <input
            type="text"
            class="form-control"
            name="onesignal_custom_title"
            id="onesignal_custom_title"
            value="{{ old('onesignal_custom_title', $customTitle ?? '') }}"
            placeholder="Leave empty to use Post Title"
        />
    </div>

    <div class="mb-3">
        <label for="onesignal_custom_message" class="form-label">
            Custom Push Content / Description (Optional)
        </label>
        <textarea
            class="form-control"
            name="onesignal_custom_message"
            id="onesignal_custom_message"
            rows="2"
            placeholder="Leave empty to use Post Excerpt"
        >{{ old('onesignal_custom_message', $customMessage ?? '') }}</textarea>
    </div>

    @if(setting('onesignal_send_to_mobile_platforms'))
        <div class="mb-3">
            <label for="onesignal_mobile_url" class="form-label">
                Mobile App Deep Link URL (Optional)
            </label>
            <input
                type="text"
                class="form-control"
                name="onesignal_mobile_url"
                id="onesignal_mobile_url"
                value="{{ old('onesignal_mobile_url', $mobileUrl ?? '') }}"
                placeholder="e.g. app://jobs/123 or https://careerinpak.com/jobs/123"
            />
        </div>
    @endif
</div>
