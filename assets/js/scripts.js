$(document).ready(function () {
    $("#placa").inputmask({ mask: ['AAA-9*9[9]'], placeholder: ""});
    $("#preco").inputmask({
        alias: "currency", prefix: 'R$ ', removeMaskOnSubmit: true
    });
});