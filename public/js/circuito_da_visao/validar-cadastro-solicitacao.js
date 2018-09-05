
/* Dados */
let arrayObjetos = [0, 0, 0];
const iconeTime = '<i class="fa fa-times" aria-hidden="true"></i>';
const iconeCheck = '<i class="fa fa-check" aria-hidden="true"></i>';
const hidden = 'hidden';
const textDanger = 'text-danger';
const textSuccess = 'text-success';
const stringNBSP = '&nbsp;';
const alertDanger = 'alert-danger';
const alertSuccess = 'alert-success';
const btnDefault = 'btn-default';
const btnPrimary = 'btn-primary';
let objetoSelecionado = 0;
const stringSpanObjeto = '#spanObjeto';
const stringSpanSelecioneObjeto = '#spanSelecioneObjeto';
const stringDivBotaoSelecionar = '#divBotaoSelecionar';
const stringDivBotaoLimpar = '#divBotaoLimpar';
const stringDivCheckDadosInseridos = '#divCheckDadosInseridos';
const stringBlocoObjeto = '#blocoObjeto';
const stringDivSolicitacaoTipo = '#divSolicitacaoTipo';
const stringDivObjetos = '#divObjetos';
const stringDivSelecionarLider = '#divSelecionarLider';
const stringDivSelecionarNumeracao = '#divSelecionarNumeracao';
const stringDivSelecionarEquipe = '#divSelecionarEquipe';
const stringDivSelecionarHomem = '#divSelecionarHomem';
const stringDivSelecionarMulher = '#divSelecionarMulher';
const stringDivSelecionarCasal = '#divSelecionarCasal';
const stringDivSelecionarQuemSaira = '#divSelecionarQuemSaira';
const stringSpanNomeLideres = '#spanNomeLideres';
const stringSpanCelulaQuantidade = '#spanCelulaQuantidade';
const stringSpanQuantidadeLideres = '#spanQuantidadeLideres';
const stringSpanFotos = '#spanFotos';
const stringSpanLoader = '#spanLoader';
let objeto;
let spanSelecioneOObjeto;
let botaoSelecionar;
let botaoLimpar;
let check;
let blocoObjeto;

function mostrarBotaoContinuar() {
	let divBotaoContinuarSelecionarTipo = $('#divBotaoContinuarSelecionarTipo');
	if (parseInt($('#solicitacaoTipo').val()) === 0) {
		divBotaoContinuarSelecionarTipo.addClass(hidden);
	} else {
		divBotaoContinuarSelecionarTipo.removeClass(hidden);
	}
}

const TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE = 1;
const TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE = 2;
const UNIR_CASAL = 3;
const SEPARAR = 4;
const TROCAR_RESPONSABILIDADES = 5;
const REMOVER_LIDER = 6;
const REMOVER_CELULA = 7;

function selecionarTipo() {
	$(stringDivSolicitacaoTipo).addClass(hidden);
	$(stringDivObjetos).removeClass(hidden);
	$('#divProgress').removeClass(hidden);
	$('#tituloDaPagina').html($('#tituloDaPagina').html() + ' - ' + $('#solicitacaoTipo option:selected').text());
	$('#solicitacaoTipoId').val($('#solicitacaoTipo').val());

	$('.grupoLogado').addClass(hidden);

	if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
		parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
		parseInt($('#solicitacaoTipo').val()) === SEPARAR ||
		parseInt($('#solicitacaoTipo').val()) === TROCAR_RESPONSABILIDADES ||
		parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
		parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {

		$('#blocoObjeto3').addClass(hidden);
		if (parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL) {
			$('#spanSelecioneObjeto1').html('Selecione o homem');
			$('#spanSelecioneObjeto2').html('Selecione a mulher');
		}
		if (parseInt($('#solicitacaoTipo').val()) === SEPARAR) {
			$('#spanSelecioneObjeto1').html('Selecione o casal');
			$('#spanSelecioneObjeto3').html('Selecione quem do casal irá sair');
			$('#blocoObjeto2').addClass(hidden);
			$('#blocoObjeto3').removeClass(hidden);
		}
		if (parseInt($('#solicitacaoTipo').val()) === TROCAR_RESPONSABILIDADES) {
			$('#spanSelecioneObjeto1').html('Selecione o lider(es) que vão trocar responsabilidades');
			$('#spanSelecioneObjeto2').html('Selecione o lider(es) que vão trocar responsabilidades');
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
			parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {

			if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER) {
				$('#spanSelecioneObjeto1').html('Selecione o líder para remover');
				$('.comDiscipulos').addClass(hidden);
				$('#spanMensagemDeConfirmacao').html('Confirma a remoção desse líder? Somente após autorização do líder da igreja no próximo período será feita a mudança')
			}
			if (parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {
				$('#spanSelecioneObjeto1').html('Selecione o líder para remover a célula');
				$('#spanMensagemDeConfirmacao').html('Confirma a remoção dessa célula? Somente após autorização do líder da igreja no próximo período será feita a mudança')
			}
			$('#blocoObjeto2').addClass(hidden);
		}
	}
}

function abrirSelecionarObjeto(qualObjeto, idLider) {
	$(stringDivObjetos).addClass(hidden);
	if (qualObjeto != 3) {
		if ($('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE ||
			$('#solicitacaoTipoId').val() == TROCAR_RESPONSABILIDADES ||
			$('#solicitacaoTipoId').val() == REMOVER_LIDER ||
			$('#solicitacaoTipoId').val() == REMOVER_CELULA ||
			$("#formSolicitacaoReceber").length) {
			$(stringDivSelecionarLider).removeClass(hidden);
		}

		if (qualObjeto === 1 && $('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE) {
			$('#idLider').val(0);
			$('#divBotaoSelecionarLider').addClass(hidden);
		}

		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
			$(stringDivSelecionarLider).removeClass(hidden);
		}
		if (qualObjeto == 2 && $('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
			$(stringDivSelecionarEquipe).removeClass(hidden);
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == UNIR_CASAL) {
			$(stringDivSelecionarHomem).removeClass(hidden);
		}
		if (qualObjeto == 2 && $('#solicitacaoTipoId').val() == UNIR_CASAL) {
			$(stringDivSelecionarMulher).removeClass(hidden);
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == SEPARAR) {
			$(stringDivSelecionarCasal).removeClass(hidden);
		}
	} else {
		if ($('#solicitacaoTipoId').val() == SEPARAR) {
			$(stringDivSelecionarQuemSaira).removeClass(hidden);
		} else {
			if($('#solicitacaoTipoId').val() == REMOVER_LIDER){
				$('#divSelecionarMotivo').removeClass(hidden)
			}else{
				if($('#solicitacaoTipoId').val() == REMOVER_CELULA){
					$('#divSelecionarCelula').removeClass(hidden)
				}else{
					$(stringDivSelecionarNumeracao).removeClass(hidden);
				}
			}
		}
	}
	objetoSelecionado = qualObjeto;
}

function mostrarBotaoSelecionarLider() {
	let divBotaoSelecionarLider = $('#divBotaoSelecionarLider');
	if (parseInt($('#idLider').val()) === 0) {
		divBotaoSelecionarLider.addClass(hidden);
	} else {
		divBotaoSelecionarLider.removeClass(hidden);
	}
}

function selecionarLider() {
	let idLider = $('#idLider')
	selecionarObjeto(idLider.val(), $('#idLider>option:selected').text())
}

function selecionarMotivo(){
	let motivo = $('#motivo')
	let textareaMotivo = $('#textareaMotivo')
	let motivoFinal = 'Saiu da igreja';
	if(motivo.val() != 0){
		motivoFinal = textareaMotivo.val()
	}
	selecionarObjeto(motivoFinal, 'Motivo: ' + motivoFinal)
}
function selecionarCelula() {
	let idGrupoEvento = $('#idGrupoEvento')
	selecionarObjeto(idGrupoEvento.val(), $('#idGrupoEvento>option:selected').text())
}

function selecionarObjeto(id, informacao) {
	$(stringDivObjetos).removeClass(hidden);
	if (parseInt(objetoSelecionado) !== 3) {
		$(stringDivSelecionarLider).addClass(hidden);
	} else {
		$(stringDivSelecionarNumeracao).addClass(hidden);
	}
	if (parseInt($('#solicitacaoTipo').val()) === SEPARAR) {
		$(stringDivSelecionarCasal).addClass(hidden);
	}
	if (parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL) {
		$(stringDivSelecionarHomem).addClass(hidden);
		$(stringDivSelecionarMulher).addClass(hidden);
	}
	if (parseInt($('#solicitacaoTipo').val()) === SEPARAR) {
		$(stringDivSelecionarQuemSaira).addClass(hidden);
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

	if (objetoSelecionado === 3 && (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER || parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA)){
		objeto.html(informacao);
		spanLoader.addClass(hidden);
		if(parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER){
			$('#divSelecionarMotivo').addClass(hidden);
		}
		if(parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA){
			$('#divSelecionarCelula').addClass(hidden);
		}
		valorParaAdicionar = 50;
		atualizarBarraDeProgresso(valorParaAdicionar);
		verificarSeMostraOBotaoDeContinuar();
		botaoLimpar.removeClass(hidden);
		check.removeClass(hidden);
	}else{

		/* buscar dados do grupo */
		let idGrupo = id;
		let splitId = id.split('_')
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
						if (parseInt($('#solicitacaoTipo').val()) === SEPARAR) {
							informacao = 'Quem do casal vai sair: ' + informacao;
						} else {
							informacao = 'Nova numeração: ' + informacao;
						}
						objeto.html(informacao);
					} else {
						if (parseInt(objetoSelecionado) === 1) {
							objeto.html('Líderes que serão transferidos');
							if ($('#solicitacaoTipoId').val() == UNIR_CASAL) {
								objeto.html('Homem que será unido');
							}
							if ($('#solicitacaoTipoId').val() == SEPARAR) {
								objeto.html('Casal que será separado');
							}
							if ($('#solicitacaoTipoId').val() == REMOVER_LIDER) {
								objeto.html('Líder que será removido');
								$('#blocoObjeto3').removeClass(hidden)
							}
							if ($('#solicitacaoTipoId').val() == REMOVER_CELULA) {
								objeto.html('Líder que terá a célula removida');
								if(data.celulas['1']){
									$('#idGrupoEvento').append('<option value="'+data.celulas['1']['idGrupoEvento']+'">'+data.celulas['1']['nomeHospedeiro']+'</option>')
								}
								if(data.celulas['2']){
									$('#idGrupoEvento').append('<option value="'+data.celulas['2']['idGrupoEvento']+'">'+data.celulas['2']['nomeHospedeiro']+'</option>')
								}
								$('#blocoObjeto3').removeClass(hidden)
							}
						}
						if (parseInt(objetoSelecionado) === 2) {
							objeto.html('Líderes que receberão');
							if ($('#solicitacaoTipoId').val() == UNIR_CASAL) {
								objeto.html('Mulher que será unida');
							}
						}
						spanNomeLideres.html('Nome dos Líderes: ' + data.nomeLideres);
						spanCelulaQuantidade.html('Quantidade de Células: ' + data.celulaQuantidade);
						spanQuantidadeLideres.html('Quantidade de Líderes: ' + data.quantidadeLideres);
						spanFotos.html(data.fotos);
					}

					botaoLimpar.removeClass(hidden);
					check.removeClass(hidden);

					arrayObjetos[objetoSelecionado] = id;
					let valorParaAdicionar = 35;
					if (parseInt(objetoSelecionado) === 3) {
						valorParaAdicionar = 30;
					}
					if ($("#formSolicitacaoReceber").length) {
						valorParaAdicionar = 50;
					}
					if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
						parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
						parseInt($('#solicitacaoTipo').val()) === SEPARAR ||
						parseInt($('#solicitacaoTipo').val()) === TROCAR_RESPONSABILIDADES ||
						parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA ||
						parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER) {
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

					const objetoQueVaiReceber = 2;
					if (parseInt(objetoSelecionado) === objetoQueVaiReceber &&
						parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE) {
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
									for (let i = 1, max = 36; i < max; i++) {
										let mostrarOption = true;
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

					/* Escondendo lideres abaixo quando selecionar um lider para transferir */
					if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
						parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE) {
						$('.grupo' + id).addClass(hidden)
						$('#idLider').val(0)
						$('#divBotaoSelecionarLider').addClass(hidden)
					}
				}
			}, 'json')
	}
}

function selecionarNumeracao() {
	let numero = $('#numero').val();
	if (parseInt(numero) === 0) {
		alert('Selecione uma numeração');
	} else {
		selecionarObjeto(numero, numero);
	}
}

function selecionarQuemSaira() {
	let valor = $('#quemVaiSair').val();
	if (parseInt(valor) === 0) {
		alert('Selecion quem do casal vai sair');
	} else {
		selecionarObjeto(valor, valor);
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

	let valorParaRemover;
	if (qualObjeto === 1 || qualObjeto === 2) {
		/* Reaparecer lideres */
		$('.grupo' + $('#objeto' + qualObjeto).val()).removeClass(hidden)
		if (qualObjeto === 1) {
			$('#idLider').val(0);
			$('#divBotaoSelecionarLider').addClass(hidden);
		}
		valorParaRemover = -35;
		if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
			valorParaRemover = -50;
		}
		if ($('#formSolicitacaoReceber').val()) {
			valorParaRemover = -50;
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER||parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {
			valorParaRemover = -50;
			$('#idGrupoEvento').html('<option value="0">SELECIONE</option>')
			limparObjeto(3)
		}
		atualizarBarraDeProgresso(valorParaRemover);
		$('#numero').val(0);
	}
	if (qualObjeto === 3) {
		valorParaRemover = -30;
		if ($('#formSolicitacaoReceber').val()) {
			valorParaRemover = -50;
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {
			valorParaRemover = -50;
		}
		atualizarBarraDeProgresso(valorParaRemover);
	}
	verificarSeMostraOBotaoDeContinuar();
}

function verificarSeMostraOBotaoDeContinuar() {
	let valorAtualDaBarraDeProgresso = pegaValorBarraDeProgresso();
	let divBotaoContinuar = $('#divBotaoContinuar');
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
	let valorAtualDaBarraDeProgresso = pegaValorBarraDeProgresso();
	let valorAtualizadoDaBarraDeProgresso = parseInt(valorAtualDaBarraDeProgresso) + valorParaSomar;
	let stringPercentual = '%';
	$('#divProgressBar')
		.attr("aria-valuenow", valorAtualizadoDaBarraDeProgresso)
		.html(valorAtualizadoDaBarraDeProgresso + stringPercentual)
		.css('width', valorAtualizadoDaBarraDeProgresso + stringPercentual);
	let divBotaoSubmit = $('#divBotaoSubmit');
	if (valorAtualizadoDaBarraDeProgresso == 100) {
		divBotaoSubmit.removeClass(hidden);
	} else {
		divBotaoSubmit.addClass(hidden);
	}
}

function pegaValorBarraDeProgresso() {
	return $('#divProgressBar').attr("aria-valuenow");
}
