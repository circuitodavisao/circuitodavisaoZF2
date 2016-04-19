/**
 * Nome: botao-loader.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para por loader nos botoes
 */

$(document).ready(function () {
    var colorAux;
    var backgroundColorAux;
    $(".button").focus(function () {
        colorAux = $(this).css('color');
        backgroundColorAux = $(this).css('background-color');
        $(this).css('background', '50% 50% no-repeat url(/img/loader.gif)');
        $(this).css('color', 'transparent');
    });
    $(".button").blur(function () {
        $(this).css('background-image', 'none');
        $(this).css('color', colorAux);
        $(this).css('background-color', backgroundColorAux);
    });

    $(".btn").focus(function () {
        colorAux = $(this).css('color');
        backgroundColorAux = $(this).css('background-color');
        $(this).css('background', '50% 50% no-repeat url(/img/loader.gif)');
        $(this).css('color', 'transparent');
    });
    $(".btn").blur(function () {
        $(this).css('background-image', 'none');
        $(this).css('color', colorAux);
        $(this).css('background-color', backgroundColorAux);
    });
});