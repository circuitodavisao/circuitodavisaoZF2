/**
 * Nome: mudar-atendimento.js
 * @author Lucas Filipe de Carvalho cunha <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */

function mudarAtendimento(idGrupo, tipo, mes, ano) {
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

    if (tipo == 1) {
        botao = botaoLancar;
        icone = iconePlus;
    }
    if (tipo == 2) {
        botao = botaoRemover;
        icone = iconeMinus;
    }

    /* Desabilitar botão ate terminar o processamento */
    botao.addClass('disabled');

    $.post(
            "/lancamentoMudarAtendimento",
            {
                idGrupo: idGrupo,
                tipo: tipo,
                mes: mes,
                ano: ano
            },
            function (data) {
                if (data.response) {
                    var numeroAtendimentos = parseInt(data.numeroAtendimentos);
                    var progresso = parseFloat(data.progresso);

                    progressBar.removeClass(corBarraVermelha);
                    progressBar.removeClass(corBarraAmarela);
                    progressBar.removeClass(corBarraVerde);

                    botao.removeClass('disabled');

                    if (numeroAtendimentos == 1) {
                        botaoRemover.removeClass('disabled');
                        botaoRemover.removeAttr('disabled');

                        progressBar.attr('aria-valuenow', 50)
                                .addClass(corBarraAmarela)
                                .html(numeroAtendimentos)
                                .css('width', '50%');

                        if (parseInt(tipo) === 1) {
                            progressBarCabecalho.removeClass(corBarraVermelha);
                            progressBarCabecalho.removeClass(corBarraAmarela);
                            progressBarCabecalho.removeClass(corBarraVerde);
                            progressBarCabecalho.attr('aria-valuenow', progresso)
                                    .addClass(data.corBarraTotal)
                                    .html(progresso + '%')
                                    .css('width', progresso + '%');
                        }

                        $('#totalGruposAtendidos').text(data.totalGruposAtendidos);
                    }
                    if (numeroAtendimentos >= 2) {
                        botaoRemover.removeClass('disabled');
                        progressBar.attr('aria-valuenow', 100)
                                .addClass(corBarraVerde)
                                .html(numeroAtendimentos)
                                .css('width', '100%');
                    }
                    if (numeroAtendimentos == 0) {
                        botaoRemover.addClass('disabled');
                        progressBarCabecalho.removeClass(corBarraVermelha);
                        progressBarCabecalho.removeClass(corBarraAmarela);
                        progressBarCabecalho.removeClass(corBarraVerde);

                        progressBar.attr('aria-valuenow', 10)
                                .addClass(corBarraVermelha)
                                .html(numeroAtendimentos)
                                .css('width', '10%');
                        progressBarCabecalho.attr('aria-valuenow', progresso)
                                .addClass(data.corBarraTotal)
                                .html(progresso + '%')
                                .css('width', progresso + '%');
                        $('#totalGruposAtendidos').text(data.totalGruposAtendidos);
                    }

                    botao.html('');
                    botao.html(icone);
                }
            }, 'json');
}

function validarExclusaoComentario(idGrupoAtendimentoComentario) {
    let resposta = confirm('Tem certeza que quer remover esse comentario');
    if (resposta) {
        $.post(
                "/lancamentoAtendimentoComentarioRemover",
                {
                    id: idGrupoAtendimentoComentario,
                },
                function (data) {
                    if (data.response) {
                        mostrarSplash();
                        location.href = '/lancamentoAtendimento';
                    }
                }, 'json');

    } else {
        return false;
    }
}