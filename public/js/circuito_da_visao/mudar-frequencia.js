/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(idEventoFrequencia, ciclo, aba) {
    var link = $('#a_' + idEventoFrequencia);
    var icone = $('#i_' + idEventoFrequencia);

    var valor = 'N';
    if (icone.hasClass('fa-user')) {
        valor = "S";
    }
    icone.removeClass('fa-user');
    icone.removeClass('fa-bolt');
    icone.addClass('fa-wheelchair');

    link.removeClass('btn-danger');
    link.removeClass('btn-success');
    link.addClass('btn-warning');
    $.post(
            "/lancamentoMudarFrequencia",
            {
                valor: valor,
                idEventoFrequencia: idEventoFrequencia,
                ciclo: ciclo,
                aba: aba,
            },
            function (data) {
                if (data.response) {
                    link.removeClass('btn-warning');
                    icone.removeClass('fa-wheelchair');
                    if (valor == "S") {
                        link.addClass('btn-success');
                        icone.addClass('fa-bolt');
                    } else {
                        link.addClass('btn-danger');
                        icone.addClass('fa-user');
                    }
                }
            }, 'json');
}