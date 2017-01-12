/**
 * Nome: mudar-atendimento.js
 * @author Lucas Filipe de Carvalho cunha <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarAtendimento(idGrupo, tipo) {
    var botaoLancar = $('#bl_'+idGrupo);
    var botaoRemover = $('#br_'+idGrupo);
    var progressBar = $('#progressBarAtendimento'+idGrupo);
    var corBarraVermelha = 'progress-bar-danger';
    var corBarraAmarela = 'progress-bar-warning';
    var corBarraVerde = 'progress-bar-success';
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var botao;
    
    if(tipo == 1){
        botao = botaoLancar;
    }else{
        botao = botaoRemover;
    }
    

    botao.html(loader);

    /* Desabilitar botão ate terminar o processamento */
    botao.addClass('disabled');
    $.post(
            "/lancamentoMudarAtendimento",
            {
                idGrupo: idGrupo,
                tipo: tipo,
                
            },
            function (data) {
                if (data.response) {
                    var numeroAtendimentos = parseInt(data.numeroAtendimentos);
                    var textoProgressBar = data.numeroAtendimentos+' Atendimentos'; 
                    progressBar.removeClass()
                    
                    if(numeroAtendimentos == 1){
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 50)
                                .addClass(corBarraAmarela)
                                .html(textoProgressBar)
                                .css('width', 50);
                    }else if(numeroAtendimentos == 2){
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 100)
                                .addClass(corBarraVerde)
                                .html(textoProgressBar)
                                .css('width', 100);
                    }else if(numeroAtendimentos > 2) {
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 10)
                                .addClass(corBarraVerde)
                                .html(textoProgressBar)
                                .css('width', 10);
                    }else {
                        progressBar.removeClass(corBarraVermelha);
                        progressBar.removeClass(corBarraAmarela);
                        progressBar.removeClass(corBarraVerde);
                        progressBar.attr('aria-valuenow', 10)                                
                                .addClass(corBarraVermelha)
                                .html(textoProgressBar)
                                .css('width', 10);
                    }
                    botao.removeClass('disabled');
                }
            }, 'json');
}