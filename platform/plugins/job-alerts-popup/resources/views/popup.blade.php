<!-- Job Alerts Popup Plugin Start -->
<style>
    .job-alerts-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .job-alerts-popup-overlay.jap-visible {
        opacity: 1;
        visibility: visible;
    }
    .job-alerts-popup-card {
        background: #ffffff;
        width: 100%;
        max-width: 440px;
        border-radius: 16px;
        padding: 32px 24px 24px 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        position: relative;
        text-align: center;
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    .job-alerts-popup-overlay.jap-visible .job-alerts-popup-card {
        transform: scale(1);
    }
    .job-alerts-popup-close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        background: transparent;
        border: none;
        font-size: 24px;
        font-weight: 400;
        color: #888888;
        cursor: pointer;
        line-height: 1;
        padding: 6px;
        border-radius: 50%;
        transition: color 0.2s, background-color 0.2s;
    }
    .job-alerts-popup-close-btn:hover {
        color: #111111;
        background-color: #f1f1f1;
    }
    .job-alerts-popup-title {
        font-size: 22px;
        font-weight: 700;
        color: #111111;
        margin: 0 0 8px 0;
        line-height: 1.3;
    }
    .job-alerts-popup-subtitle {
        font-size: 14px;
        color: #666666;
        margin: 0 0 24px 0;
        line-height: 1.5;
    }
    .job-alerts-popup-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .job-alerts-popup-whatsapp-btn {
        background-color: #00897b;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(0, 137, 123, 0.35);
    }
    .job-alerts-popup-whatsapp-btn:hover {
        background-color: #00796b;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 137, 123, 0.45);
    }
    .job-alerts-popup-dismiss-wrapper {
        margin-top: 18px;
    }
    .job-alerts-popup-no-thanks {
        background: none;
        border: none;
        color: #888888;
        font-size: 13px;
        text-decoration: underline;
        cursor: pointer;
        padding: 4px 8px;
        transition: color 0.2s;
    }
    .job-alerts-popup-no-thanks:hover {
        color: #444444;
    }
</style>

<div id="job-alerts-popup-overlay" class="job-alerts-popup-overlay" role="dialog" aria-modal="true">
    <div class="job-alerts-popup-card">
        <button type="button" class="job-alerts-popup-close-btn" id="job-alerts-popup-close-cross" aria-label="Close">&times;</button>
        <div class="job-alerts-popup-header">
            <h3 class="job-alerts-popup-title">{{ $title }}</h3>
            <p class="job-alerts-popup-subtitle">{{ $subtitle }}</p>
        </div>
        <div class="job-alerts-popup-body">
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="job-alerts-popup-btn job-alerts-popup-whatsapp-btn">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 2.152.68 4.145 1.836 5.776L2.5 21.5l3.865-1.262A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm-8 10c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8a7.95 7.95 0 01-4.225-1.208l-.303-.18-2.28.745.758-2.205-.196-.312A7.953 7.953 0 014 12z"/>
                </svg>
                <span>{{ $whatsappText }}</span>
            </a>
            <div class="job-alerts-popup-dismiss-wrapper">
                <button type="button" class="job-alerts-popup-no-thanks" id="job-alerts-popup-close-text">No thanks</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function initJobAlertsPopup() {
        var popupOverlay = document.getElementById('job-alerts-popup-overlay');
        if (!popupOverlay) return;

        var frequency = '{{ $frequency }}';
        var storageKey = 'job_alerts_popup_dismissed';

        // Check frequency restrictions
        if (frequency === 'once_per_session' && sessionStorage.getItem(storageKey)) {
            return;
        }
        if (frequency === 'once_per_day') {
            var lastClosed = localStorage.getItem(storageKey);
            if (lastClosed && (Date.now() - parseInt(lastClosed, 10) < 86400000)) {
                return;
            }
        }

        // Show popup after delay
        setTimeout(function() {
            popupOverlay.classList.add('jap-visible');
        }, {{ $delay }});

        function closePopup() {
            popupOverlay.classList.remove('jap-visible');
            if (frequency === 'once_per_session') {
                sessionStorage.setItem(storageKey, '1');
            } else if (frequency === 'once_per_day') {
                localStorage.setItem(storageKey, Date.now().toString());
            }
        }

        // 1. Close on cross icon click
        var closeCrossBtn = document.getElementById('job-alerts-popup-close-cross');
        if (closeCrossBtn) {
            closeCrossBtn.addEventListener('click', closePopup);
        }

        // 2. Close on "No thanks" text click
        var closeTextBtn = document.getElementById('job-alerts-popup-close-text');
        if (closeTextBtn) {
            closeTextBtn.addEventListener('click', closePopup);
        }

        // 3. Close on backdrop overlay click (clicking outside popup card)
        popupOverlay.addEventListener('click', function(e) {
            if (e.target === popupOverlay) {
                closePopup();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initJobAlertsPopup);
    } else {
        initJobAlertsPopup();
    }
})();
</script>
<!-- Job Alerts Popup Plugin End -->
