
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
var stringDivSelecionarEquipe = '#divSelecionarEquipe';
var stringSpanNomeLideres = '#spanNomeLideres';
var stringSpanCelulaQuantidade = '#spanCelulaQuantidade';
var stringSpanQuantidadeLideres = '#spanQuantidadeLideres';
var stringSpanFotos = '#spanFotos';
var stringSpanLoader = '#spanLoader';
var objeto;
var spanSelecioneOObjeto;
var botaoSelecionar;
var botaoLimpar;
var check;
var blocoObjeto;

$("#treeLideres").fancytree({
    checkbox: "radio",
    selectMode: 1,
    clickFolderMode: 2,
    extensions: ["childcounter"],
    childcounter: {
        deep: true,
        hideZeros: true,
        hideExpanded: true
    },
    select: function (event, data) {
        var node = data.tree.getNodeByKey(data.node.key);
        $(node.span).closest('li').addClass('hide');
        if (data.node.isSelected()) {
            selecionarObjeto(node.key, node.title);
        }
        data.node.setSelected(false);
    }
});
$("#treeEquipes").fancytree({
    checkbox: "radio",
    selectMode: 1,
    clickFolderMode: 2,
    extensions: ["childcounter"],
    childcounter: {
        deep: true,
        hideZeros: true,
        hideExpanded: true
    },
    select: function (event, data) {
        var node = data.tree.getNodeByKey(data.node.key);
        $(node.span).closest('li').addClass('hide');
        if (data.node.isSelected()) {
            selecionarObjeto(node.key, node.title);
        }
        data.node.setSelected(false);
    }
});

function mostrarBotaoContinuar() {
    var divBotaoContinuarSelecionarTipo = $('#divBotaoContinuarSelecionarTipo');
    if (parseInt($('#solicitacaoTipo').val()) === 0) {
        divBotaoContinuarSelecionarTipo.addClass(hidden);
    } else {
        divBotaoContinuarSelecionarTipo.removeClass(hidden);
    }
}

function selecionarTipo() {
    $(stringDivSolicitacaoTipo).addClass(hidden);
    $(stringDivObjetos).removeClass(hidden);
    $('#divProgress').removeClass(hidden);
    $('#tituloDaPagina').html($('#tituloDaPagina').html() + ' - ' + $('#solicitacaoTipo option:selected').text());
    $('#solicitacaoTipoId').val($('#solicitacaoTipo').val());
    if (parseInt($('#solicitacaoTipo').val()) === 2) {
        $('#blocoObjeto3').addClass(hidden);
    }
}

function abrirSelecionarObjeto(qualObjeto, idLider) {
    $(stringDivObjetos).addClass(hidden);
    if (qualObjeto != 3) {
        if (qualObjeto == 2 && $('#solicitacaoTipoId').val() == 2) {
            $(stringDivSelecionarEquipe).removeClass(hidden);
        } else {
            $(stringDivSelecionarLider).removeClass(hidden);
        }
    } else {
        $(stringDivSelecionarNumeracao).removeClass(hidden);
    }
    objetoSelecionado = qualObjeto;

    var tree = $("#treeLideres").fancytree("getTree");
    var node = tree.getNodeByKey('lider_' + idLider);
    if (objetoSelecionado === 1) {
        /* Esconder lider */
        $(node.span).closest('li').addClass('hide');
    }
    if (objetoSelecionado === 2) {
        /* mostrar lider */
        $(node.span).closest('li').removeClass('hide');
    }
}

function selecionarObjeto(id, informacao) {
    $(stringDivObjetos).removeClass(hidden);
    if (parseInt(objetoSelecionado) !== 3) {
        $(stringDivSelecionarLider).addClass(hidden);
    } else {
        $(stringDivSelecionarNumeracao).addClass(hidden);
    }
    objeto = $(stringSpanObjeto + objetoSelecionado);
    spanNomeLideres = $(stringSpanNomeLideres + objetoSelecionado);
    spanCelulaQuantidade = $(stringSpanCelulaQuantidade + objetoSelecionado);
    spanQuantidadeLideres = $(stringSpanQuantidadeLideres + objetoSelecionado);
    spanFotos = $(stringSpanFotos + objetoSelecionado);
    spanSelecioneOObjeto = $(stringSpanSelecioneObjeto + objetoSelecionado);
    botaoSelecionar = $(stringDivBotaoSelecionar + objetoSelecionado);
    botaoLimpar = $(stringDivBotaoLimpar + objetoSelecionado);
    check = $(stringDivCheckDadosInseridos + objetoSelecionado);
    blocoObjeto = $(stringBlocoObjeto + objetoSelecionado);
    spanLoader = $(stringSpanLoader + objetoSelecionado);
    spanLoader.removeClass(hidden);
    spanSelecioneOObjeto.addClass(hidden);
    botaoSelecionar.addClass(hidden);
    /* buscar dados do grupo */
    var idGrupo = id;
    var splitId = id.split('_')
    if (splitId[1]) {
        idGrupo = splitId[1];
    }
    $.post(
            "/relatorioBuscarDadosGrupo",
            {
                idGrupo: idGrupo
            },
            function (data) {
                if (data.resposta) {
                    spanLoader.addClass(hidden);
                    if (parseInt(objetoSelecionado) === 3) {
                        informacao = 'Nova numeração: ' + informacao;
                        objeto.html(informacao);
                    } else {
                        if (parseInt(objetoSelecionado) === 1) {
                            objeto.html('Líderes que serão transferidos');
                        }
                        if (parseInt(objetoSelecionado) === 2) {
                            objeto.html('Líderes que receberão');
                        }
                        spanNomeLideres.html('Nome dos Líderes: ' + data.nomeLideres);
                        spanCelulaQuantidade.html('Quantidade de Células: ' + data.celulaQuantidade);
                        spanQuantidadeLideres.html('Quantidade de Líderes: ' + data.quantidadeLideres);
                        spanFotos.html(data.fotos);
                    }

                    botaoLimpar.removeClass(hidden);
                    check.removeClass(hidden);

                    arrayObjetos[objetoSelecionado] = id;
                    var valorParaAdicionar = 35;
                    if (parseInt(objetoSelecionado) === 3) {
                        valorParaAdicionar = 30;
                    }
                    if (parseInt($('#solicitacaoTipo').val()) === 2) {
                        valorParaAdicionar = 50;
                    }
                    atualizarBarraDeProgresso(valorParaAdicionar);
                    verificarSeMostraOBotaoDeContinuar();

                    if (objetoSelecionado != 3) {
                        $('#objeto' + objetoSelecionado).val(id);
                    } else {
                        $('#numeracao').val(id);
                    }

                    if (parseInt(objetoSelecionado) === 1) {
                        $(stringDivBotaoSelecionar + 2).removeClass(hidden);
                        $(stringDivBotaoSelecionar + 3).removeClass(hidden);
                    }

                    var objetoQueVaiReceber = 2;
                    var tipoTransferenciaDeLiderNaEquipe = 1;
                    if (parseInt(objetoSelecionado) === objetoQueVaiReceber &&
                            parseInt($('#solicitacaoTipo').val()) === tipoTransferenciaDeLiderNaEquipe) {
                        $('#numero')
                                .find('option')
                                .remove()
                                .end();
                        /* Buscar numeracao das subs liberadas */
                        $('#numero').append($('<option>', {
                            value: 0,
                            text: 'SELECIONE'
                        }));
                        $.post(
                                "/relatorioBuscarNumeracoesDisponivel",
                                {
                                    idGrupo: idGrupo
                                },
                                function (data) {
                                    if (data.resposta) {
                                        for (var i = 1, max = 36; i < max; i++) {
                                            var mostrarOption = true;
                                            $.each(data.numerosUsados, function (index, value) {
                                                if (i === parseInt(value)) {
                                                    mostrarOption = false;
                                                }
                                            });
                                            if (mostrarOption) {
                                                $('#numero').append($('<option>', {
                                                    value: i,
                                                    text: i
                                                }));
                                            }
                                        }
                                    }
                                }, 'json');
                    }
                }
            }, 'json');
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
    spanNomeLideres = $(stringSpanNomeLideres + objetoSelecionado);
    spanCelulaQuantidade = $(stringSpanCelulaQuantidade + objetoSelecionado);
    spanQuantidadeLideres = $(stringSpanQuantidadeLideres + objetoSelecionado);
    spanFotos = $(stringSpanFotos + objetoSelecionado);
    spanSelecioneOObjeto = $(stringSpanSelecioneObjeto + qualObjeto);
    botaoSelecionar = $(stringDivBotaoSelecionar + qualObjeto);
    botaoLimpar = $(stringDivBotaoLimpar + qualObjeto);
    check = $(stringDivCheckDadosInseridos + qualObjeto);

    objeto.html('');
    spanNomeLideres.html('');
    spanCelulaQuantidade.html('');
    spanQuantidadeLideres.html('');
    spanFotos.html('');
    spanSelecioneOObjeto.removeClass(hidden);
    botaoSelecionar.removeClass(hidden);
    botaoLimpar.addClass(hidden);
    check.addClass(hidden);
    if (qualObjeto === 3) {
        $('#numero').val(0);
    }

    var valorParaRemover;
    if (parseInt(qualObjeto) === 1 || parseInt(qualObjeto) === 2) {
        for (var i = qualObjeto, max = 3; i <= max; i++) {
            $(stringSpanObjeto + i).html('');
            $(stringSpanNomeLideres + i).html('');
            $(stringSpanCelulaQuantidade + i).html('');
            $(stringSpanQuantidadeLideres + i).html('');
            $(stringSpanFotos + i).html('');
            $(stringSpanSelecioneObjeto + i).removeClass(hidden);
            $(stringDivBotaoSelecionar + i).removeClass(hidden);
            $(stringDivBotaoLimpar + i).addClass(hidden);
            $(stringDivCheckDadosInseridos + i).addClass(hidden);
            if (qualObjeto === 1 && i !== 1) {
                $(stringDivBotaoSelecionar + i).addClass(hidden);
            }
            valorParaRemover = -35;
            if (i === 3) {
                valorParaRemover = -30;
            }
            atualizarBarraDeProgresso(valorParaRemover);
        }
        $('#numero').val(0);
    }
    if (parseInt(qualObjeto) === 3) {
        valorParaRemover = -30;
        atualizarBarraDeProgresso(valorParaRemover);


    }

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