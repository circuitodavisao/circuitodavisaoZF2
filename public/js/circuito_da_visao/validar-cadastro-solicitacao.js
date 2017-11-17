
/* Dados */
var arrayObjetos = [0, 0, 0];
var iconeTime = '<i class="fa fa-times" aria-hidden="true"></i>';
var iconeCheck = '<i class="fa fa-check" aria-hidden="true"></i>';
var hidden = 'hidden';
var textDanger = 'text-danger';
var textSuccess = 'text-success';
var stringNBSP = '&nbsp;';
var alertDanger = 'alert-danger';
var alertSuccess = 'alert-success';
var btnDefault = 'btn-default';
var btnPrimary = 'btn-primary';
var objetoSelecionado = 0;
var stringSpanObjeto = '#spanObjeto';
var stringSpanSelecioneObjeto = '#spanSelecioneObjeto';
var stringDivBotaoSelecionar = '#divBotaoSelecionar';
var stringDivBotaoLimpar = '#divBotaoLimpar';
var stringDivCheckDadosInseridos = '#divCheckDadosInseridos';
var stringBlocoObjeto = '#blocoObjeto';
var stringDivSolicitacaoTipo = '#divSolicitacaoTipo';
var stringDivObjetos = '#divObjetos';
var stringDivSelecionarLider = '#divSelecionarLider';
var stringDivSelecionarNumeracao = '#divSelecionarNumeracao';
var objeto;
var spanSelecioneOObjeto;
var botaoSelecionar;
var botaoLimpar;
var check;
var blocoObjeto;

function mostrarBotaoContinuar() {
    var divBotaoContinuarSelecionarTipo = $('#divBotaoContinuarSelecionarTipo');
    if (parseInt($('#solicitacaoTipo').val()) === 0) {
        divBotaoContinuarSelecionarTipo.addClass(hidden);
    } else {
        divBotaoContinuarSelecionarTipo.removeClass(hidden);
    }
}

function selecionarTipo() {
    if (parseInt($('#solicitacaoTipo').val()) === 0) {

    } else {
        $(stringDivSolicitacaoTipo).addClass(hidden);
        $(stringDivObjetos).removeClass(hidden);
        $('#divProgress').removeClass(hidden);
        $('#tituloDaPagina').html($('#tituloDaPagina').html() + ' - ' + $('#solicitacaoTipo option:selected').text());
        $('#solicitacaoTipoId').val($('#solicitacaoTipo').val());
    }
}

function abrirSelecionarObjeto(qualObjeto) {
    $(stringDivObjetos).addClass(hidden);
    if (qualObjeto != 3) {
        $(stringDivSelecionarLider).removeClass(hidden);
    } else {
        $(stringDivSelecionarNumeracao).removeClass(hidden);
    }
    objetoSelecionado = qualObjeto;
}

function selecionarObjeto(id, informacao) {
    $(stringDivObjetos).removeClass(hidden);
    if (parseInt(objetoSelecionado) !== 3) {
        $(stringDivSelecionarLider).addClass(hidden);
        $('#tr_' + id).addClass(hidden);
    } else {
        $(stringDivSelecionarNumeracao).addClass(hidden);
    }

    objeto = $(stringSpanObjeto + objetoSelecionado);
    spanSelecioneOObjeto = $(stringSpanSelecioneObjeto + objetoSelecionado);
    botaoSelecionar = $(stringDivBotaoSelecionar + objetoSelecionado);
    botaoLimpar = $(stringDivBotaoLimpar + objetoSelecionado);
    check = $(stringDivCheckDadosInseridos + objetoSelecionado);
    blocoObjeto = $(stringBlocoObjeto + objetoSelecionado);

    if (parseInt(objetoSelecionado) === 3) {
        informacao = 'Nova numeração: ' + informacao;
    }
    objeto.html(informacao);
    spanSelecioneOObjeto.addClass(hidden);
    botaoSelecionar.addClass(hidden);
    botaoLimpar.removeClass(hidden);
    check.removeClass(hidden);
    blocoObjeto.removeClass('btn').removeClass('btn-default').prop('onclick', null).off('click');

    arrayObjetos[objetoSelecionado] = id;
    var valorParaAdicionar = 35;
    if (parseInt(objetoSelecionado) === 3) {
        valorParaAdicionar = 30;
    }
    atualizarBarraDeProgresso(valorParaAdicionar);
    verificarSeMostraOBotaoDeContinuar();

    if (objetoSelecionado != 3) {
        $('#objeto' + objetoSelecionado).val(id);
    } else {
        $('#numeracao').val(id);
    }
}

function selecionarNumeracao() {
    var numero = $('#numero').val();
    if (parseInt(numero) === 0) {
        alert('Selecion uma numeracao');
    } else {
        selecionarObjeto(numero, numero);
    }
}

function limparObjeto(qualObjeto) {
    objeto = $(stringSpanObjeto + qualObjeto);
    spanSelecioneOObjeto = $(stringSpanSelecioneObjeto + qualObjeto);
    botaoSelecionar = $(stringDivBotaoSelecionar + qualObjeto);
    botaoLimpar = $(stringDivBotaoLimpar + qualObjeto);
    check = $(stringDivCheckDadosInseridos + qualObjeto);

    objeto.html('');
    spanSelecioneOObjeto.removeClass(hidden);
    botaoSelecionar.removeClass(hidden);
    botaoLimpar.addClass(hidden);
    check.addClass(hidden);
    if (qualObjeto != 3) {
        $('#tr_' + arrayObjetos[qualObjeto]).removeClass(hidden);
    } else {
        $('#numeracao').val(0);
    }
    var valorParaRemover = -35;
    if (parseInt(qualObjeto) === 3) {
        valorParaRemover = -30;
    }
    atualizarBarraDeProgresso(valorParaRemover);
    verificarSeMostraOBotaoDeContinuar();
}

function verificarSeMostraOBotaoDeContinuar() {
    var valorAtualDaBarraDeProgresso = pegaValorBarraDeProgresso();
    var divBotaoContinuar = $('#divBotaoContinuar');
    if (parseInt(valorAtualDaBarraDeProgresso) === 100) {
        divBotaoContinuar.removeClass(hidden);
    } else {
        divBotaoContinuar.addClass(hidden);
    }
}

function continuarParaConfimacao() {
    $('#divProgress').addClass(hidden);
    $('#divObjetos').addClass(hidden);
    $('#divBotaoContinuar').addClass(hidden);
    $('#divTelaConfirmacao').removeClass(hidden);
    $('#divSenha').addClass(hidden);
}

function pedirSenha() {
    $('#divTelaConfirmacao').addClass(hidden);
    $('#divSenha').removeClass(hidden);
}

function voltaAosObjetos() {
    $('#divTelaConfirmacao').addClass(hidden);
    verificarSeMostraOBotaoDeContinuar();
    $(stringDivObjetos).removeClass(hidden);
    $('#divProgress').removeClass(hidden);
}

/* por em funcoes */
/* atualizar grupo-validacao tbm */
function atualizarBarraDeProgresso(valorParaSomar) {
    valorParaSomar = parseInt(valorParaSomar);
    var valorAtualDaBarraDeProgresso = pegaValorBarraDeProgresso();
    var valorAtualizadoDaBarraDeProgresso = parseInt(valorAtualDaBarraDeProgresso) + valorParaSomar;
    var stringPercentual = '%';
    $('#divProgressBar')
            .attr("aria-valuenow", valorAtualizadoDaBarraDeProgresso)
            .html(valorAtualizadoDaBarraDeProgresso + stringPercentual)
            .css('width', valorAtualizadoDaBarraDeProgresso + stringPercentual);
    var divBotaoSubmit = $('#divBotaoSubmit');
    if (valorAtualizadoDaBarraDeProgresso == 100) {
        divBotaoSubmit.removeClass(hidden);
    } else {
        divBotaoSubmit.addClass(hidden);
    }
}

function pegaValorBarraDeProgresso() {
    return $('#divProgressBar').attr("aria-valuenow");
}