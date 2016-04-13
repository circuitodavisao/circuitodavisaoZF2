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
        $(this).css('color', 'transparent');
        $(this).css('background', 'url(/img/loader.gif) 50% 50% no-repeat');
    });
    $(".button").blur(function () {
        $(this).css('background-image', 'none');
        $(this).css('color', colorAux);
        $(this).css('background-color', backgroundColorAux);
    });
});