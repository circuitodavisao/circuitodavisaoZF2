/**
 * Nome: consultar-ficha.js
 * @author Lucas Filipe de Carvalho cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function consultarFicha() {
    var codigo = $('#codigo');
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var botao = $('#botaoBuscarFicha');
    var botaoLimparCampos = $('#botaoLimparCampos');
    var divMensagens = $('#divMensagens');
    var divInputCodigo = $('#inputCodigo');
    var divBotoesCodigo = $('#botoesCodigo');

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
                    var status = parseInt(data.status);

                    if(status == 1){
                        var nomeRevisionista = data.nomeRevisionista;
                        var nomeEntidadeLider = data.nomeEntidadeLider;
                        var idEventoFrequencia = data.idEventoFrequencia;
                        
                        divMensagens.html("ID Ficha: "+idEventoFrequencia+" <br />"+
                                                 "Nome: "+nomeRevisionista+" <br/> "+
                                                 "Equipe: "+nomeEntidadeLider+" <br/>");
                        divMensagens.removeClass('alert-danger');
                        divMensagens.addClass('alert-success');
                        divMensagens.removeClass('hidden');
                        divInputCodigo.addClass('hidden');
                        
                        botao.removeAttr('disabled');
                        botao.removeClass('disabled');
                        botaoLimparCampos.removeClass('hidden');                         
                    }
                    if(status == 0){
                        divMensagens.html("Ficha nao Encontrada!!!");
                        divMensagens.removeClass('hidden');
                        divMensagens.removeClass('alert-success');
                        divMensagens.addClass('alert-danger');
                    }
                    
                    botao.html('');
                    botao.html('Enable');
                    
                }
            }, 'json');
}

$('#AtivarFichaForm').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});

function limparCampos(){
    var codigo = $('#codigo');
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var botaoBuscarFicha = $('#botaoBuscarFicha');
    var botaoLimparCampos = $('#botaoLimparCampos');
    var divMensagens = $('#divMensagens');
    var divInputCodigo = $('#inputCodigo');
    
    botaoLimparCampos.addClass('hidden');
    botaoBuscarFicha.addClass('disabled');
    divMensagens.addClass('hidden');
    codigo.val('');
    divInputCodigo.removeClass('hidden');
}

function confirmar(form){
    var divMensagens = $('#divMensagens');
    var loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
    var botao = $('#botaoBuscarFicha');
    botao.html(loader);
    botao.addClass('disabled');
    form.submit();
}