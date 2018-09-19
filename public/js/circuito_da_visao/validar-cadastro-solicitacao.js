
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
const stringDivSelecionarLiderIgreja = '#divSelecionarLiderIgreja';
const stringDivSelecionarNumeracao = '#divSelecionarNumeracao';
const stringDivSelecionarEquipe = '#divSelecionarEquipe';
const stringDivSelecionarHomem = '#divSelecionarHomem';
const stringDivSelecionarMulher = '#divSelecionarMulher';
const stringDivSelecionarCasal = '#divSelecionarCasal';
const stringDivSelecionarAluno = '#divSelecionarAluno';
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
const TRANSFERIR_ALUNO = 8;

function selecionarTipo() {
	$(stringDivSolicitacaoTipo).addClass(hidden);
	$(stringDivObjetos).removeClass(hidden);
	$('#divProgress').removeClass(hidden);
	$('#tituloDaPagina').html($('#tituloDaPagina').html() + ' - ' + $('#solicitacaoTipo option:selected').text());
	$('#solicitacaoTipoId').val($('#solicitacaoTipo').val());

	$('.grupoLogado').attr('disabled','disabled');

	if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
		parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
		parseInt($('#solicitacaoTipo').val()) === SEPARAR ||
		parseInt($('#solicitacaoTipo').val()) === TROCAR_RESPONSABILIDADES ||
		parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
		parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_ALUNO ||
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
		if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_ALUNO) {
			$('#spanSelecioneObjeto1').html('Selecione o aluno');
			$('#spanMensagemDeConfirmacao').html('Confirma a transferência desse aluno? Somente no próximo período será feita a mudança')
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
			parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
			parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {

			if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER) {
				$('#spanSelecioneObjeto1').html('Selecione o líder para remover');
				$('#spanMensagemDeConfirmacao').html('Confirma a remoção desse líder? Somente após autorização do líder da igreja no próximo período será feita a mudança')
			}
			if (parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {
				$('.grupoLogado').removeAttr('disabled');
				$('#spanSelecioneObjeto1').html('Selecione o líder para remover a célula');
				$('#spanMensagemDeConfirmacao').html('Confirma a remoção dessa célula? Somente após autorização do líder da igreja no próximo período será feita a mudança')
			}
			if (parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL) {
				$('#spanMensagemDeConfirmacao').html('Confirma a união desse casal? Eles serão inativados e será criado um novo time, somente no próximo período será feita a mudança')
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
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == TRANSFERIR_ALUNO) {
			$(stringDivSelecionarAluno).removeClass(hidden);
		}
		if (qualObjeto == 2 && $('#solicitacaoTipoId').val() == TRANSFERIR_ALUNO) {
			$(stringDivSelecionarLiderIgreja).removeClass(hidden);
		}
	} else {
		if ($('#solicitacaoTipoId').val() == SEPARAR) {
			$(stringDivSelecionarQuemSaira).removeClass(hidden);
		} 
		if($('#solicitacaoTipoId').val() == REMOVER_LIDER){
			$('#divSelecionarMotivo').removeClass(hidden)
		}
		if($('#solicitacaoTipoId').val() == REMOVER_CELULA){
			$('#divSelecionarCelula').removeClass(hidden)
		}
		if($('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE){
			$(stringDivSelecionarNumeracao).removeClass(hidden);
		}
		if($('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE){
			$('#divSelecionarEquipe').removeClass(hidden);
		}
		if($('#idSolicitacaoTipo').val() >0){
			$(stringDivSelecionarNumeracao).removeClass(hidden);
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

function mostrarBotaoSelecionarLiderIgreja() {
	let divBotaoSelecionarLiderIgreja = $('#divBotaoSelecionarLiderIgreja');
	if (parseInt($('#idLiderIgreja').val()) === 0) {
		divBotaoSelecionarLiderIgreja.addClass(hidden);
	} else {
		divBotaoSelecionarLiderIgreja.removeClass(hidden);
	}
}


function mostrarBotaoSelecionarEquipe() {
	let divBotaoSelecionarEquipe = $('#divBotaoSelecionarEquipe');
	if (parseInt($('#idEquipe').val()) === 0) {
		divBotaoSelecionarEquipe.addClass(hidden);
	} else {
		divBotaoSelecionarEquipe.removeClass(hidden);
	}
}

function mostrarBotaoSelecionarHomem() {
	let divBotaoSelecionarHomem = $('#divBotaoSelecionarHomem');
	if (parseInt($('#idHomem').val()) === 0) {
		$('#divBotaoSelecionarHomem').addClass(hidden);
	} else {
		$('#divBotaoSelecionarHomem').removeClass(hidden);
	}
}

function mostrarBotaoSelecionarMulher() {
	let divBotaoSelecionarMulher = $('#divBotaoSelecionarMulher');
	if (parseInt($('#idMulher').val()) === 0) {
		$('#divBotaoSelecionarMulher').addClass(hidden);
	} else {
		$('#divBotaoSelecionarMulher').removeClass(hidden);
	}
}

function mostrarBotaoSelecionarAluno() {
	let divBotaoSelecionarAluno = $('#divBotaoSelecionarAluno');
	if (parseInt($('#idAluno').val()) === 0) {
		$('#divBotaoSelecionarAluno').addClass(hidden);
	} else {
		$('#divBotaoSelecionarAluno').removeClass(hidden);
	}
}

function selecionarLider() {
	let idLider = $('#idLider')
	selecionarObjeto(idLider.val(), $('#idLider>option:selected').text())
}

function selecionarLiderIgreja() {
	let idLiderIgreja = $('#idLiderIgreja')
	selecionarObjeto(idLiderIgreja.val(), $('#idLiderIgreja>option:selected').text())
}

function selecionarEquipe() {
	let idEquipe = $('#idEquipe')
	selecionarObjeto(idEquipe.val(), $('#idEquipe>option:selected').text())
}

function selecionarHomem() {
	let idHomem = $('#idHomem')
	selecionarObjeto(idHomem.val(), $('#idHomem>option:selected').text())
}

function selecionarMulher() {
	let idMulher = $('#idMulher')
	selecionarObjeto(idMulher.val(), $('#idMulher>option:selected').text())
}

function selecionarAluno() {
	let idAluno = $('#idAluno')
	selecionarObjeto(idAluno.val(), $('#idAluno>option:selected').text())
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
		$('#divSelecionarEquipe').addClass(hidden);
	}
	if (parseInt(objetoSelecionado) === 2 && parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
		$(stringDivSelecionarEquipe).addClass(hidden);
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
		$('#spanObjeto3').removeClass(hidden)
	}else{
		/* buscar dados do grupo */
		let idGrupo = id;
		let splitId = id.split('_')
		if (splitId[1]) {
			idGrupo = splitId[1];
		}
		if (parseInt($('#solicitacaoTipo').val()) !== TRANSFERIR_ALUNO) {
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
							$('#spanObjeto3').removeClass(hidden)
							if($('#idSolicitacaoTipo').val() > 0){

							}
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
									$('#blocoObjeto2').addClass(hidden)
									$('#blocoObjeto3').removeClass(hidden)
								}
								if ($('#solicitacaoTipoId').val() == REMOVER_CELULA) {
									objeto.html('Líder que terá a célula removida');
									console.log('Quantas celulas: ', data.celulas)
									if(data.celulas['1']){
										if(data.celulas['1']){
											$('#idGrupoEvento').append('<option value="'+data.celulas['1']['idGrupoEvento']+'">'+data.celulas['1']['nomeHospedeiro']+'</option>')
										}
										if(data.celulas['2']){
											$('#idGrupoEvento').append('<option value="'+data.celulas['2']['idGrupoEvento']+'">'+data.celulas['2']['nomeHospedeiro']+'</option>')
										}
										$('#blocoObjeto3').removeClass(hidden)
									}
								}
							}
							if (parseInt(objetoSelecionado) === 2) {
								objeto.html('Líderes que receberão');
								if ($('#solicitacaoTipoId').val() == UNIR_CASAL) {
									objeto.html('Mulher que será unida');
								}
								if($('#idSolicitacaoTipo').val() > 0){
									$('#blocoObjeto3').removeClass(hidden)
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
							if ($('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
								$('#objeto2').val(id);
							}else{
								$('#numeracao').val(id);
							}
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
							$('.grupo' + id).attr('disabled','disabled')
							$('#idLider').val(0)
							$('#divBotaoSelecionarLider').addClass(hidden)
						}

						/* Nao posso deixar selecionar alguem que esta com solicitacao pendente */
						if ((
							parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
							parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
							parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA ||
							parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
							parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE) && objetoSelecionado == 1) {
							if(data.temSolicitacaoPendente){
								$(stringSpanSelecioneObjeto + 3).addClass(hidden)
								$(stringSpanObjeto + 3).html('<div class="alert alert-danger">Não é possível selecionar esse time, pois tem alguma solicitação não encerrada</div>')
								$(stringSpanObjeto + 3).removeClass(hidden)
								$('#blocoObjeto2').addClass(hidden)
								$('#blocoObjeto3').removeClass(hidden)
								$('#divBotaoSelecionar3').addClass(hidden)
							}else{
								if (
									(
										parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE ||
										parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
										parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA ||
										parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
										parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER 
									) && objetoSelecionado == 1) {
									$(stringSpanObjeto + 3).addClass(hidden)
									$(stringSpanSelecioneObjeto + 3).removeClass(hidden)
									if(parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE){
										$('#blocoObjeto2').removeClass(hidden)
									}
									$('#blocoObjeto3').removeClass(hidden)
									$('#spanObjeto3').addClass(hidden)
									$('#spanSelecioneObjeto3').removeClass(hidden)
									$('#spanObjeto3').addClass(hidden)

									if ($('#solicitacaoTipoId').val() == REMOVER_CELULA) {
										if(data.celula['1']){
											$('#blocoObjeto3').removeClass(hidden)
											$('#spanObjeto3').addClass(hidden)
											$('#spanSelecioneObjeto3').removeClass(hidden)
											$('#spanObjeto3').addClass(hidden)
										}else{
											$(stringSpanSelecioneObjeto + 3).addClass(hidden)
											$(stringSpanObjeto + 3).html('<div class="alert alert-danger">Time não possui células</div>')
											$(stringDivBotaoSelecionar + 3).addClass(hidden)
											$('#spanSelecioneObjeto3').addClass(hidden)
											$('#spanObjeto3').removeClass(hidden)
										}
									}
									if ($('#solicitacaoTipoId').val() == UNIR_CASAL) {
										$('#blocoObjeto2').removeClass(hidden)
										$('#blocoObjeto3').addClass(hidden)
									}
								}
							}
						}
						if ($('#solicitacaoTipoId').val() == TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
							$('#blocoObjeto2').addClass(hidden)
						}
						if (parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL && objetoSelecionado == 1) {
							$('.mulheres').attr('disabled','disabled')
							let grupoParaMostrar = $('#homem'+id).attr('class');
							$('.'+grupoParaMostrar).removeAttr('disabled')
						}
					}
				}, 'json')
		}else{
			spanLoader.addClass(hidden);
			$(stringDivSelecionarAluno).addClass(hidden)
			spanNomeLideres.html('Aluno: ' + informacao)
			botaoLimpar.removeClass(hidden)
			check.removeClass(hidden)
			valorParaAdicionar = 50
			atualizarBarraDeProgresso(valorParaAdicionar);
			verificarSeMostraOBotaoDeContinuar();
			if(objetoSelecionado == 1){
				$(stringDivBotaoSelecionar+2).removeClass(hidden)
				$('#blocoObjeto2').removeClass(hidden)
				$('#objeto1').val(id)
			}
			if(objetoSelecionado == 2){
				$(stringDivSelecionarLiderIgreja).addClass(hidden)
				$(stringDivBotaoSelecionar+2).addClass(hidden)
				$('#objeto2').val(id)
			}
		}
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
		$('#idEquipe').val(0)
	}

	let valorParaRemover;
	if (qualObjeto === 1 || qualObjeto === 2) {
		/* Reaparecer lideres */
		$('.grupo' + $('#objeto' + qualObjeto).val()).removeAttr('disabled')
		if(qualObjeto === 1){
			$('.lider').removeAttr('disabled')
		}
		if (qualObjeto === 1) {
			$('#idLider').val(0);
			$('#divBotaoSelecionarLider').addClass(hidden);
		}
		valorParaRemover = -35;
		if ($('#formSolicitacaoReceber').val()) {
			valorParaRemover = -50;
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
			parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA ||
			parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE) {
			valorParaRemover = -50;
			$('#idGrupoEvento').html('<option value="0">SELECIONE</option>')
			$('#blocoObjeto3').addClass(hidden)
		}
		if (parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL){
			valorParaRemover = -50;
		}
		if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE) {
			$('#blocoObjeto3').addClass(hidden)
		}
		if(qualObjeto === 2 && $('#idSolicitacaoTipo').val() > 0){
			$('#idLider').val(0)
			valorParaRemover = -50
		}
		$('#numero').val(0);

		$('#spanFotos'+qualObjeto).html('')
		$('#spanNomeLideres'+qualObjeto).html('')
		$('#spanCelulaQuantidade'+qualObjeto).html('')
		$('#spanQuantidadeLideres'+qualObjeto).html('')
		if (objetoSelecionado == 2 && parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_ALUNO){
			valorParaRemover = -50;
		}

		atualizarBarraDeProgresso(valorParaRemover);
		$(stringDivSelecionarLiderIgreja).addClass(hidden)
	}
	if (qualObjeto === 1) {
		$('#divBotaoSelecionar2').addClass(hidden)
		$('#spanFotos2').html('')
		$('#spanNomeLideres2').html('')
		$('#spanCelulaQuantidade2').html('')
		$('#spanQuantidadeLideres2').html('')
		$('#spanObjeto3').html('')
		$('#blocoObjeto2').addClass(hidden)
		$('#blocoObjeto3').addClass(hidden)
		$('#divCheckDadosInseridos2').addClass(hidden)
		$('#divCheckDadosInseridos3').addClass(hidden)
		$('#divBotaoLimpar2').addClass(hidden)
		$('#divBotaoLimpar3').addClass(hidden)
		$('#idEquipe').val(0)
		$('#idHomem').val(0)
		$('#idMulher').val(0)
		$('#idAluno').val(0)
		$('#divBotaoSelecionarHomem').addClass(hidden)
		$('#divBotaoSelecionarMulher').addClass(hidden)
		$(stringDivSelecionarLiderIgreja).addClass(hidden)
		let valorDaBarra = pegaValorBarraDeProgresso()
		atualizarBarraDeProgresso(parseInt(valorDaBarra) * -1)
	}
	if (qualObjeto === 3) {
		valorParaRemover = -30;
		if ($('#formSolicitacaoReceber').val()) {
			valorParaRemover = -50;
		}
		if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE || 
			parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER || 
			parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA) {
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
