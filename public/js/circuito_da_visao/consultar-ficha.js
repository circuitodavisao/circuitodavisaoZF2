/**
 * Nome: consultar-ficha.js
 * @author Lucas Filipe de Carvalho cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function consultarFicha() {
    var codigo = $('#codigo');
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var botao = $('#botaoBuscarFicha')
    alert(codigo.val());

    botao.html(loader);

    /* Desabilitar botão ate terminar o processamento */
    botao.addClass('disabled');
    $.post(
            "/cadastroConsultarFicha",
            {
                idEventoFrequencia: codigo.val(),
            },
            function (data) {
                if (data.response) {
                    var status = parseBoolean(data.status);
                    if(status){
                        var nomeRevisionista = data.nomeRevisionista;
                        var nomeEntidadeLider = data.nomeEntidadeLider;
                        var idEventoFrequencia = data.idEventoFrequencia;
                    }else{
                        
                    }
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
                                .html(progresso+'%')
                                .css('width', progresso+'%');
                        
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
                                .html(progresso+'%')
                                .css('width', progresso+'%');
                        $('#totalGruposAtendidos').text(data.totalGruposAtendidos);
                    }

                    botao.html('');
                    botao.html(icone);
                    fecharModalCarregando();
                }
            }, 'json');
}