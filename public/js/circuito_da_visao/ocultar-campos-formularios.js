/**
 * Nome: ocultar-campos-formularios.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> & Sergio Bezerra da Silva <sergio.silva.unb@gmail.com>
 * Descricao: Função para mostrar ou olcultar campos nos formulários
 */

$(document).ready(function () {
    $("#tipo").change(function () {

        var opSelected = $("#tipo option:selected").val();

        if (opSelected == 0 || opSelected == 1 || opSelected == 2) {
            $("#ocultar_checkbox").hide();
        } else {
            $("#ocultar_checkbox").show();
        }
    });
});