$(function () {
    'use strict';

    // Copy API key to clipboard
    $(document).on('click', '.seo-boost-copy-key', function (event) {
        event.preventDefault();
        const button = $(this);
        navigator.clipboard.writeText(button.data('key')).then(function () {
            Botble.showSuccess($('body').data('copy-success-text') || 'Copied!');
        });
    });

    // Regenerate API key
    $(document).on('click', '.seo-boost-reset-key', function (event) {
        event.preventDefault();
        const button = $(this);

        $httpClient
            .make()
            .withButtonLoading(button)
            .post(button.data('url'))
            .then(function (res) {
                const data = res.data;
                $('.seo-boost-key').text(data.key);
                $('.seo-boost-copy-key').data('key', data.key);
                Botble.showSuccess(data.message);
            });
    });

    // Manual URL submission
    $(document).on('submit', '.seo-boost-submit-form', function (event) {
        event.preventDefault();
        const form = $(this);
        const button = form.find('[type=submit]');

        $httpClient
            .make()
            .withButtonLoading(button)
            .post(form.data('url'), form.serialize())
        .then(function (res) {
            const data = res.data;
            if (data.error) {
                Botble.showError(data.message);
            } else {
                Botble.showSuccess(data.message);
                form.find('textarea').val('');

                const tableId = $('.seo-boost-submit-form').closest('.row').find('.tbl-not-set table.dataTable').prop('id');
                if (tableId) {
                    $('#' + tableId).DataTable().draw();
                } else {
                    $('table.dataTable').first().DataTable().draw();
                }
            }
        });
    });
});
