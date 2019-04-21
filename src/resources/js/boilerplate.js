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
    if (!$(form).hasClass('without-spinner')) {
        $(form).submit(() => {
            if ($(form).valid()) {
                var submitButton = $(form).find('[type=submit]');
    
                submitButton.html('<i class="fa fa-circle-notch fa-spin"></i>');            
                submitButton.attr('disabled', 'disabled');
            }
        });
    }
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
$('.date-mask').mask('99/99/9999');

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

$.validator.addMethod('cpf', function (value, element) {
    return this.optional(element) || validateCpf(value);
}, $.validator.messages.cpf);

$.validator.addMethod('cnpj', function (value, element) {
    return this.optional(element) || validateCnpj(value);
}, $.validator.messages.cnpj);

$.validator.addMethod('cpfCnpj', function (value, element) {
    if (value.length === 14) {
        return this.optional(element) || validateCpf(value);
    }

    if (value.length === 18) {
        return this.optional(element) || validateCnpj(value);
    }

    return false;
}, $.validator.messages.cpfCnpj);

$.validator.addMethod('editorRequired', function (value, element) {
    return !$(element).summernote('isEmpty');
}, $.validator.messages.required);

$.validator.setDefaults({
    highlight: function (element, errorClass, validClass) {
        if ($(element).hasClass("select-2")) {
            $(element).next('.select2').addClass(errorClass);
        } else if ($(element).hasClass("editor")) {
            $(element).next('.note-frame').addClass(errorClass);
        } else {
            $(element).addClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).hasClass("select-2")) {
            $(element).next('.select2').removeClass(errorClass);
        } else if ($(element).hasClass("editor")) {
            $(element).siblings('.note-frame').removeClass(errorClass);
        } else {
            $(element).removeClass(errorClass);
        }
    },
    errorPlacement: function(error, element) {
        if (element.hasClass('select-2')) {
            error.insertAfter(element.next('.select2'));
        } else if (element.hasClass('editor')) {
            error.insertBefore(element.next('.note-frame'));
        } else {
            error.insertAfter(element);
        }
    },
});

function validateCpf(value) {
    var Soma;
    var Resto;
    Soma = 0;

    if (value) {
        value = value.replace(/[^\d]+/g,'');
    
        if (value == "00000000000") return false;
         
        for (i=1; i<=9; i++) Soma = Soma + parseInt(value.substring(i-1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;
       
        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(value.substring(9, 10)) ) return false;
       
        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(value.substring(i-1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;
       
        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(value.substring(10, 11) ) ) return false;
    
        return true;
    }
}

function validateCnpj(cnpj) {
    if (cnpj) {
        cnpj = cnpj.replace(/[^\d]+/g,'');
     
        if(cnpj == '') return false;
         
        if (cnpj.length != 14)
            return false;
     
        if (cnpj == "00000000000000" || 
            cnpj == "11111111111111" || 
            cnpj == "22222222222222" || 
            cnpj == "33333333333333" || 
            cnpj == "44444444444444" || 
            cnpj == "55555555555555" || 
            cnpj == "66666666666666" || 
            cnpj == "77777777777777" || 
            cnpj == "88888888888888" || 
            cnpj == "99999999999999")
            return false;
             
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
             
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
              return false;
               
        return true;
    }
}