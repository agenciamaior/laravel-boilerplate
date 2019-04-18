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
require('summernote');
require('summernote/dist/summernote-bs4');

window.moment = require('moment');
window.numeral = require('numeral');
window.Chart = require('chart.js');

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
$('.select-2').select2({
    theme: "bootstrap4"
});

$('.money-mask').maskMoney({
    prefix: 'R$ ',
    allowNegative: false,
    thousands: '.',
    decimal: ',',
});

$('.number-mask').maskMoney({
    prefix: '',
    allowNegative: false,
    thousands: '.',
    decimal: ',',
});

$('.percent-mask').maskMoney({
    prefix: '',
    suffix: '%',
    allowNegative: false,
    thousands: '.',
    decimal: ',',
});

$('.editor').summernote({
    lang: 'pt-BR',
    height: 150,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph', 'link']],
        ['undoredo', ['undo', 'redo']],
    ]
});

$.validator.addMethod("dateBR", function (value, element) {
    return this.optional(element) || moment(value, $.moment.dateFormat).isValid();
}, $.validator.messages.date);

$.validator.addMethod('period', function (value, element, params) {
    var startDate = moment($('input[name="' + params[0] + '"]').val(), $.moment.dateFormat);
    var endDate = moment(value, $.moment.dateFormat);

    return this.optional(element) || startDate <= endDate;
}, $.validator.messages.period);

$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param * 1024 * 1024)
}, $.validator.messages.filesize);

$.validator.addMethod('cpfCnpj', function (value, element) {
    return this.optional(element) || value.length === 14 || value.length === 18;
}, $.validator.messages.pattern);

$.validator.addMethod('editorRequired', function (value, element) {
    return !$(element).summernote('isEmpty');
}, $.validator.messages.required);