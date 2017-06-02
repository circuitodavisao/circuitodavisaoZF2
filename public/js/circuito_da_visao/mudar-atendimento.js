/**
 * Nome: mudar-atendimento.js
 * @author Lucas Filipe de Carvalho cunha <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarAtendimento(idGrupo, tipo) {
    var botaoLancar = $('#botao1_' + idGrupo);
    var botaoRemover = $('#botao2_' + idGrupo);
    var progressBar = $('#progressBarAtendimento' + idGrupo);
    var progressBarCabecalho = $('#divProgressBar');
    var iconePlus = '<i class="fa fa-plus" aria-hidden="true"></i>';
    var iconeMinus = '<i class="fa fa-minus" aria-hidden="true"></i>';
    var corBarraVermelha = 'progress-bar-danger';
    var corBarraAmarela = 'progress-bar-warning';
    var corBarraVerde = 'progress-bar-success';
    var botao;
    var icone;

    if (tipo === 1) {
        botao = botaoLancar;
        icone = iconePlus;
    } else {
        botao = botaoRemover;
        icone = iconeMinus;
    }

    /* Desabilitar botão ate terminar o processamento */
    botao.addClass('disabled');

    $.post(
            "/lancamentoMudarAtendimento",
            {
                idGrupo: idGrupo,
                tipo: tipo
            },
            function (data) {
                if (data.response) {
                    var numeroAtendimentos = parseInt(data.numeroAtendimentos);
                    var progresso = parseFloat(data.progresso);
                    if (numeroAtendimentos === 0) {
                        var textoProgressBar = numeroAtendimentos + ' Atd.';
                    } else {
                        var textoProgressBar = numeroAtendimentos + ' Atendimentos';
                    }

                    botao.removeClass('disabled');
                    if (numeroAtendimentos == 1) {
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBarCabecalho.removeClass(corBarraVermelha);
                        progressBarCabecalho.removeClass(corBarraAmarela);
                        progressBarCabecalho.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 50)
                                .addClass(corBarraAmarela)
                                .html(textoProgressBar)
                                .css('width', '50%');
                        botaoRemover.removeClass('disabled');
                        progressBarCabecalho.attr('aria-valuenow', progresso)
                                .addClass(data.corBarraTotal)
                                .html(progresso + '%')
                                .css('width', progresso + '%');

                        $('#totalGruposAtendidos').text(data.totalGruposAtendidos);
                    }
                    if (numeroAtendimentos == 2) {
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 100)
                                .addClass(corBarraVerde)
                                .html(textoProgressBar)
                                .css('width', '100%');
                    }
                    if (numeroAtendimentos > 2) {
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 100)
                                .addClass(corBarraVerde)
                                .html(textoProgressBar)
                                .css('width', '100%');
                    }
                    if (numeroAtendimentos == 0) {
                        botaoRemover.addClass('disabled');
                        progressBarCabecalho.removeClass(corBarraVermelha);
                        progressBarCabecalho.removeClass(corBarraAmarela);
                        progressBarCabecalho.removeClass(corBarraVerde);
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 10)
                                .addClass(corBarraVermelha)
                                .html(textoProgressBar)
                                .css('width', '10%');
                        progressBarCabecalho.attr('aria-valuenow', progresso)
                                .addClass(data.corBarraTotal)
                                .html(progresso + '%')
                                .css('width', progresso + '%');
                        $('#totalGruposAtendidos').text(data.totalGruposAtendidos);
                    }

                    botao.html('');
                    botao.html(icone);
                    botao.removeClass('disabled');
                }
            }, 'json');
}