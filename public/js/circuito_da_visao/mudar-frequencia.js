/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(idEventoFrequencia, ciclo, aba, idGrupo) {
    var faThumbsDown = 'fa-thumbs-down';
    var faThumbsUp = 'fa-thumbs-up';
    var iconefaThumbsDown = '<i class="fa ' + faThumbsDown + '"></i>';
    var iconefaThumbsUp = '<i class="fa ' + faThumbsUp + '"></i>';
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var btnDefault = 'btn-default';
    var btnSuccess = 'btn-success';
    var btnTransicao = 'btn-default';
    var botao = $('#b_' + idEventoFrequencia);
    var mensagem1 = $('#statusEnvio1');
    var mensagem2 = $('#statusEnvio2');

    var valor = 'N';
    if (botao.hasClass(btnDefault)) {
        valor = "S";
    }
    botao.html(loader);
    botao.removeClass(btnDefault);
    botao.removeClass(btnSuccess);
    botao.addClass(btnTransicao);

    /* Desabilitar botão ate terminar o processamento */
    botao.addClass('disabled');
    $.post(
            "/lancamentoMudarFrequencia",
            {
                valor: valor,
                idEventoFrequencia: idEventoFrequencia,
                ciclo: ciclo,
                aba: aba,
                idGrupo: idGrupo,
            },
            function (data) {
                if (data.response) {
                    var total = $('#total_' + data.idEvento);

                    botao.removeClass(btnTransicao);
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
                    botao.removeClass('disabled');
                    total.html(totalSoma);
                    mensagem1.addClass('hidden');
                    mensagem2.removeClass('hidden');
                }
            }, 'json');
}