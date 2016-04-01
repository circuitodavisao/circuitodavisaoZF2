/**
 * Nome: botao-loader.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para por loader nos botoes
 */

$(document).ready(function () {
    $(".button").focus(function () {
        $(this).css('color', 'transparent');
        $(this).css('background', 'url(/img/loader.gif) 50% 50% no-repeat');
    });
    $(".button").blur(function () {
        $(this).css('background-image', 'none');
        $(this).css('color', '#FFFFFF');
        $(this).css('background-color', '#000000');
    });
});