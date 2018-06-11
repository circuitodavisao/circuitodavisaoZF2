/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(idPessoa, idEvento, diaRealDoEvento, idGrupo, periodo) {
    var faThumbsDown = 'fa-thumbs-down';
    var faThumbsUp = 'fa-thumbs-up';
    var disabled = 'disabled';
    var iconefaThumbsDown = '<i class="fa ' + faThumbsDown + '"></i>';
    var iconefaThumbsUp = '<i class="fa ' + faThumbsUp + '"></i>';
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var btnDefault = 'btn-default';
    var btnSuccess = 'btn-primary';
    var btnTransicao = 'btn-default';
    var botao = $('#botao_' + idPessoa + '_' + idEvento);

    var valor = 'N';
    if (botao.hasClass(btnDefault)) {
        valor = "S";
    }
    botao.html(loader);
    botao.removeClass(btnDefault);
    botao.removeClass(btnSuccess);
    botao.addClass(btnTransicao);

    /* Desabilitar botão ate terminar o processamento */
    botao.addClass(disabled);
    $.post(
            "/lancamentoMudarFrequencia",
            {
                valor: valor,
                idPessoa: idPessoa,
                idEvento: idEvento,
                diaRealDoEvento: diaRealDoEvento,
                idGrupo: idGrupo,
                periodo: periodo,
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
                    botao.removeClass(disabled);
                    total.html(totalSoma);
                } else {
                    alert('Problemas com sua conexão de internet!');
                    location.href = '/';
                    exit;
                }
            }, 'json');
}