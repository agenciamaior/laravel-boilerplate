require('bootstrap');
require('admin-lte');
require('jquery-validation');
require('jquery-validation/dist/additional-methods');
require('datatables.net-bs4');
require('select2');
require('jquery-maskmoney/dist/jquery.maskMoney');
require('jquery-mask-plugin');
require('webpack-jquery-ui/css');
require('webpack-jquery-ui/datepicker');

window.moment = require('moment');
window.numeral = require('numeral');

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

$('.cpf-mask').mask('000.000.000-00');
$('.cnpj-mask').mask('00.000.000/0000-00');
$('.tel-ddd-mask').mask('(00) 000000009');
$('.cep-mask').mask('00000-000');
$('.time-mask').mask('00:00');

var CpfCnpjMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
},
cpfCnpjOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
    }
};

$('.cpf-cnpj-mask').mask(CpfCnpjMaskBehavior, cpfCnpjOptions);

$('.datepicker').datepicker();

$('.br-money-mask').maskMoney({
    prefix: 'R$ ',
    allowNegative: false,
    thousands: '.',
    decimal: ',',
});

jQuery.validator.addMethod("dateBR", function (value, element) {
    return this.optional(element) || moment(value, $.moment.dateFormat).isValid();
}, $.validator.messages.date);

$.validator.addMethod('period', function (value, element, params) {
    var startDate = moment($('input[name="' + params[0] + '"]').val(), $.moment.dateFormat);
    var endDate = moment(value, $.moment.dateFormat);

    return this.optional(element) || startDate <= endDate;
}, $.validator.messages.period);
