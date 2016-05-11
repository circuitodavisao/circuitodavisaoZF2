/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(checkbox, ciclo) {
    var valor = 'N';
    if (checkbox.checked) {
        valor = "S";
    }
    $.post(
            "/lancamentoMudarFrequencia",
            {
                valor: valor,
                checkbox: checkbox.id,
                ciclo: ciclo,
            },
            function (data) {
                if (data.response) {
                    $('#div_' + checkbox.id).addClass('btn-success');
                } else {
//                    alert(5);
                }
            }, 'json');
}