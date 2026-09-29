$(document).on('click', '.newsletter-form button[type=submit]', function (event) {
    event.preventDefault();
    event.stopPropagation();

    let _self = $(this);

    _self.addClass('button-loading');

    $.ajax({
        type: 'POST',
        cache: false,
        url: _self.closest('form').prop('action'),
        data: new FormData(_self.closest('form')[0]),
        contentType: false,
        processData: false,
        success: res => {
            _self.removeClass('button-loading');

            if (typeof refreshRecaptcha !== 'undefined') {
                refreshRecaptcha();
            }

            if (res.error) {
                Theme.showError(res.message);
                return false;
            }

            _self.closest('form').find('input[type=email]').val('');
            Theme.showSuccess(res.message);
        },
        error: (res) => {
            if (typeof refreshRecaptcha !== 'undefined') {
                refreshRecaptcha();
            }
            _self.removeClass('button-loading');
            Theme.handleError(res);
        }
    });
});

$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: $('#sidebar-wrapper').data('load-url'),
        success: (res) =>  {
            if (res.error) {
                return false;
            }

            $('.sidebar-inner').html(res.data);
        }
    });
});
