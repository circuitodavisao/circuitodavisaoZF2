/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(checkbox, ciclo, aba) {
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
                aba: aba,
            },
            function (data) {
                if (data.response) {
                    if (valor == "S") {
                        $('#div_' + checkbox.id).removeClass('btn-warning');
                        $('#div_' + checkbox.id).addClass('btn-success');
                    } else {
                        $('#div_' + checkbox.id).removeClass('btn-success');
                        $('#div_' + checkbox.id).addClass('btn-warning');
                    }
                } else {
//                    alert(5);
                }
            }, 'json');
}