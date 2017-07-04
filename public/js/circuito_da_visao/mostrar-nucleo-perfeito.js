/**
 * Nome: mostrar-nucleo-perfeito.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> & Sergio Bezerra da Silva <sergio.silva.unb@gmail.com>
 * Descricao: Função para mostrar ou olcultar campos nos formulários
 */

function mostrarNucleoPerfeito() {
    var select = parseInt($("#tipo option:selected").val());
    var divNucleoPerfeito = $("#ocultar_checkbox");
    if (select === 0
            || select === 1
            || select === 2) {
        divNucleoPerfeito.addClass('hidden');
    } else {
        divNucleoPerfeito.removeClass('hidden');
    }
}

      