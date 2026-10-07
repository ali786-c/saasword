<!-- Job Alerts Popup Plugin Start -->
<style>
    .job-alerts-popup-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(0, 0, 0, 0.7) !important;
        backdrop-filter: blur(4px) !important;
        -webkit-backdrop-filter: blur(4px) !important;
        z-index: 9999999 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 16px !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
        transition: opacity 0.3s ease, visibility 0.3s ease !important;
        box-sizing: border-box !important;
    }
    .job-alerts-popup-overlay.jap-visible {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }
    .job-alerts-popup-card {
        background: #ffffff !important;
        width: 100% !important;
        max-width: 440px !important;
        border-radius: 16px !important;
        padding: 32px 24px 24px 24px !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35) !important;
        position: relative !important;
        text-align: center !important;
        transform: scale(0.9) !important;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
        box-sizing: border-box !important;
    }
    .job-alerts-popup-overlay.jap-visible .job-alerts-popup-card {
        transform: scale(1) !important;
    }
    .job-alerts-popup-close-btn {
        position: absolute !important;
        top: 12px !important;
        right: 16px !important;
        background: transparent !important;
        border: none !important;
        font-size: 26px !important;
        font-weight: 400 !important;
        color: #888888 !important;
        cursor: pointer !important;
        line-height: 1 !important;
        padding: 6px !important;
        border-radius: 50% !important;
        transition: color 0.2s, background-color 0.2s !important;
    }
    .job-alerts-popup-close-btn:hover {
        color: #111111 !important;
        background-color: #f1f1f1 !important;
    }
    .job-alerts-popup-title {
        font-size: 22px !important;
        font-weight: 700 !important;
        color: #111111 !important;
        margin: 0 0 8px 0 !important;
        line-height: 1.3 !important;
    }
    .job-alerts-popup-subtitle {
        font-size: 14px !important;
        color: #666666 !important;
        margin: 0 0 24px 0 !important;
        line-height: 1.5 !important;
    }
    .job-alerts-popup-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        width: 100% !important;
        padding: 14px 20px !important;
        border-radius: 10px !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
        box-sizing: border-box !important;
    }
    .job-alerts-popup-whatsapp-btn {
        background-color: #25D366 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35) !important;
    }
    .job-alerts-popup-whatsapp-btn:hover {
        background-color: #1ebd56 !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45) !important;
    }
    .job-alerts-popup-dismiss-wrapper {
        margin-top: 18px !important;
    }
    .job-alerts-popup-no-thanks {
        background: none !important;
        border: none !important;
        color: #888888 !important;
        font-size: 13px !important;
        text-decoration: underline !important;
        cursor: pointer !important;
        padding: 4px 8px !important;
        transition: color 0.2s !important;
    }
    .job-alerts-popup-no-thanks:hover {
        color: #444444 !important;
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
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
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
    var storageKey = 'job_alerts_popup_dismissed';

    window.resetJobAlertsPopup = function() {
        sessionStorage.removeItem(storageKey);
        localStorage.removeItem(storageKey);
        console.log('Job Alerts Popup storage cleared.');
    };

    window.showJobAlertsPopup = function() {
        var popupOverlay = document.getElementById('job-alerts-popup-overlay');
        if (popupOverlay) {
            popupOverlay.classList.add('jap-visible');
        }
    };

    function initJobAlertsPopup() {
        var popupOverlay = document.getElementById('job-alerts-popup-overlay');
        if (!popupOverlay) return;

        var frequency = '{{ $frequency }}';

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
            closeCrossBtn.onclick = closePopup;
        }

        // 2. Close on "No thanks" text click
        var closeTextBtn = document.getElementById('job-alerts-popup-close-text');
        if (closeTextBtn) {
            closeTextBtn.onclick = closePopup;
        }

        // 3. Close on backdrop overlay click
        popupOverlay.onclick = function(e) {
            if (e.target === popupOverlay) {
                closePopup();
            }
        };
    }

    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        initJobAlertsPopup();
    } else {
        document.addEventListener('DOMContentLoaded', initJobAlertsPopup);
    }
})();
</script>
<!-- Job Alerts Popup Plugin End -->
