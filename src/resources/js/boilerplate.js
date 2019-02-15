require('bootstrap');
require('admin-lte');
require('jquery-validation');
require('jquery-validation/dist/additional-methods');
require('datatables.net-bs4');

require('./i18n/boilerplate/pt-br');

$('form').each((index, form) => {
    $(form).submit(() => {
        if ($(form).valid()) {
            var submitButton = $(form).find('[type=submit]');

            submitButton.html('<i class="fa fa-spinner fa-spin"></i>');
            submitButton.attr('disabled', 'disabled');
        }
    });
});

$('.confirmable').click(function() {
    if (!confirm('Tem certeza que deseja executar essa ação?')) {
        return false;
    }
});

$('[data-toggle="tooltip"]').tooltip();