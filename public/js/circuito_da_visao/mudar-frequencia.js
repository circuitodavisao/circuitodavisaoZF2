/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(idEventoFrequencia, ciclo, aba) {
    var faThumbsDown = 'fa-thumbs-down';
    var faThumbsUp = 'fa-thumbs-up';
    var iconefaThumbsDown = '<i class="fa ' + faThumbsDown + '"></i>';
    var iconefaThumbsUp = '<i class="fa ' + faThumbsUp + '"></i>';
    var loader = '<img src="/img/loader.gif"></i>';
    var btnDefault = 'btn-default';
    var btnSuccess = 'btn-success';
    var btnWarning = 'btn-warning';
    var botao = $('#b_' + idEventoFrequencia);
    var icone = $('#i_' + idEventoFrequencia);

    var valor = 'N';
    if (botao.hasClass(btnDefault)) {
        valor = "S";
    }
    botao.html(loader);
    botao.removeClass(btnDefault);
    botao.removeClass(btnSuccess);
    botao.addClass(btnWarning);
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
                    var total = $('#total_' + data.idEvento);

                    botao.removeClass(btnWarning);
                    botao.html('');
                    var totalSoma = parseInt(total.html());
                    if (valor == "S") {
                        botao.addClass(btnSuccess);
                        botao.html(iconefaThumbsUp);
                        totalSoma++;
                    } else {
                        botao.addClass(btnDefault);
                        botao.html(iconefaThumbsDown);
                        totalSoma--;
                    }

                    total.html(totalSoma);
                }
            }, 'json');
}