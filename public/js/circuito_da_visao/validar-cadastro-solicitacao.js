
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
const stringDivSelecionarIgreja = '#divSelecionarIgreja';
const stringDivSelecionarParaOndeTransferir = '#divSelecionarParaOndeTransferir';
const stringDivSelecionarResponsabilidade = '#divSelecionarResponsabilidade';
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
const SUBIR_LIDER = 9;
const REMOVER_IGREJA = 10;
const TRANSFERIR_IGREJA = 11;
const ADICIONAR_RESPONSABILIDADE = 12;
const REMOVER_RESPONSABILIDADE = 13;
const ABRIR_IGREJA_COM_EQUIPE_COMPLETA = 14;
const ABRIR_EQUIPE_COM_LIDER_DA_IGREJA = 15;

function selecionarTipo() {
	$(stringDivSolicitacaoTipo).addClass(hidden);
	$(stringDivObjetos).removeClass(hidden);
	$('#divProgress').removeClass(hidden);
	$('#tituloDaPagina').html($('#tituloDaPagina').html() + ' - ' + $('#solicitacaoTipo option:selected').text());
	$('#solicitacaoTipoId').val($('#solicitacaoTipo').val());	

	if(parseInt($('#solicitacaoTipo').val()) !== TRANSFERIR_ALUNO){
		$('.grupoLogado').attr('disabled','disabled');
	}	

	// coordenacao
	if(document.getElementById('idEntidadeTipo').value == 3 || 
		document.getElementById('idEntidadeTipo').value == 4){
		$('#blocoObjeto3').addClass(hidden);
	}

	if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
		parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL ||
		parseInt($('#solicitacaoTipo').val()) === SEPARAR ||
		parseInt($('#solicitacaoTipo').val()) === TROCAR_RESPONSABILIDADES ||
		parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER ||
		parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_ALUNO ||
		parseInt($('#solicitacaoTipo').val()) === SUBIR_LIDER ||
		parseInt($('#solicitacaoTipo').val()) === REMOVER_IGREJA ||
		parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_IGREJA ||
		parseInt($('#solicitacaoTipo').val()) === ADICIONAR_RESPONSABILIDADE ||
		parseInt($('#solicitacaoTipo').val()) === REMOVER_RESPONSABILIDADE ||
		parseInt($('#solicitacaoTipo').val()) === ABRIR_IGREJA_COM_EQUIPE_COMPLETA ||
		parseInt($('#solicitacaoTipo').val()) === ABRIR_EQUIPE_COM_LIDER_DA_IGREJA ||
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
				$('#spanMensagemDeConfirmacao').html('Confirma a união desse casal? Eles serão inativados e será criado um novo time, <b class="text-danger">somente no próximo mês</b> será feita a mudança')
			}
			$('#blocoObjeto2').addClass(hidden);
		}
		if (parseInt($('#solicitacaoTipo').val()) === SUBIR_LIDER) {
			$('#blocoObjeto2').addClass(hidden);
			$('#blocoObjeto3').removeClass(hidden);
			$('.grupoEquipe').attr('disabled','disabled');
			$('.grupoLogado').attr('disabled','disabled');
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_IGREJA) {
			$('#spanSelecioneObjeto1').html('Selecione a igreja');
			$('#blocoObjeto2').addClass(hidden);
			$('#blocoObjeto3').addClass(hidden);
		}
		if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_IGREJA) {
			$('#spanSelecioneObjeto1').html('Selecione a igreja');
			$('#blocoObjeto2').addClass(hidden);
		}		
		if (parseInt($('#solicitacaoTipo').val()) === ADICIONAR_RESPONSABILIDADE) {			
			$('#spanSelecioneObjeto1').html('Selecione a pessoa que será seu secretário(a).');
			$('#blocoObjeto2').addClass(hidden);
		}
		if (parseInt($('#solicitacaoTipo').val()) === REMOVER_RESPONSABILIDADE) {			
			$('#spanSelecioneObjeto1').html('Selecione a pessoa que deixará de ser secretário(a).');
			$('#blocoObjeto2').addClass(hidden);
		}
		if (parseInt($('#solicitacaoTipo').val()) === ABRIR_IGREJA_COM_EQUIPE_COMPLETA) {			
			$('#spanSelecioneObjeto1').html('Selecione a equipe para abrir a nova igreja.');
			$('#blocoObjeto2').addClass(hidden);
		}
		if (parseInt($('#solicitacaoTipo').val()) === ABRIR_EQUIPE_COM_LIDER_DA_IGREJA) {			
			$('#blocoObjeto1').addClass(hidden);
			$('#blocoObjeto2').addClass(hidden);
			$('#blocoObjeto3').removeClass(hidden);
			$('#spanSelecioneObjeto3').html('Informe o nome da nova equipe');
			$('#divBotaoSelecionar3').removeClass(hidden);
		}
	}

	if ( parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_ALUNO){
		buscarAlunos();
		buscarDiscipulosIgreja();
	}else{

		if (parseInt($('#solicitacaoTipo').val()) !== ABRIR_EQUIPE_COM_LIDER_DA_IGREJA) {			
			buscarDiscipulos(parseInt($('#solicitacaoTipo').val()));
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
			parseInt($("#receber").val()) === 1) {
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
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == SUBIR_LIDER) {
			$(stringDivSelecionarLider).removeClass(hidden);
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == REMOVER_IGREJA) {
			$(stringDivSelecionarIgreja).removeClass(hidden);
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == TRANSFERIR_IGREJA) {
			$(stringDivSelecionarIgreja).removeClass(hidden);
		}
		if (qualObjeto == 2 && $('#solicitacaoTipoId').val() == TRANSFERIR_IGREJA) {
			$(stringDivSelecionarParaOndeTransferir).removeClass(hidden);
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == ADICIONAR_RESPONSABILIDADE) {
			$('#divSelecionarPessoa').removeClass(hidden);			
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == REMOVER_RESPONSABILIDADE) {
			$('#divSelecionarPessoa').removeClass(hidden);			
		}
		if (qualObjeto == 1 && $('#solicitacaoTipoId').val() == ABRIR_IGREJA_COM_EQUIPE_COMPLETA) {
			$(stringDivSelecionarEquipe).removeClass(hidden);			
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
		if($('#solicitacaoTipoId').val() == SUBIR_LIDER){
			$('#divNomeEquipe').removeClass(hidden);
		}
		if(parseInt($("#receber").val()) === 1) {
			$(stringDivSelecionarNumeracao).removeClass(hidden);
		}
		if($('#solicitacaoTipoId').val() == ABRIR_IGREJA_COM_EQUIPE_COMPLETA) {
			$('#divNomeEquipe').removeClass(hidden);
		} 
		if($('#solicitacaoTipoId').val() == ABRIR_EQUIPE_COM_LIDER_DA_IGREJA) {
			$('#divNomeEquipe').removeClass(hidden);
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

function mostrarBotaoSelecionarResponsabilidade() {
	let divBotaoSelecionarResponsabilidade = $('#divBotaoSelecionarResponsabilidade');
	if (parseInt($('#idResponsabilidade').val()) === 0) {
		divBotaoSelecionarResponsabilidade.addClass(hidden);
	} else {
		divBotaoSelecionarResponsabilidade.removeClass(hidden);
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

function mostrarBotaoSelecionarCasal() {
	let divBotaoSelecionarCasal = $('#divBotaoSelecionarCasal');
	if (parseInt($('#idCasal').val()) === 0) {
		$('#divBotaoSelecionarCasal').addClass(hidden);
	} else {
		$('#divBotaoSelecionarCasal').removeClass(hidden);
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

function mostrarBotaoSelecionarIgreja() {
	let divBotaoSelecionarAluno = $('#divBotaoSelecionarIgreja');
	if (parseInt($('#idIgreja').val()) === 0) {
		$('#divBotaoSelecionarIgreja').addClass(hidden);
	} else {
		$('#divBotaoSelecionarIgreja').removeClass(hidden);
	}
}

function mostrarBotaoSelecionarParaOndeTransferir() {
	let divBotaoSelecionarAluno = $('#divBotaoSelecionarParaOndeTransferir');
	if (parseInt($('#idParaOndeTransferir').val()) === 0) {
		$('#divBotaoSelecionarParaOndeTransferir').addClass(hidden);
	} else {
		$('#divBotaoSelecionarParaOndeTransferir').removeClass(hidden);
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

function selecionarCasal() {
	let idCasal = $('#idCasal')
	selecionarObjeto(idCasal.val(), $('#idCasal>option:selected').text())
}

function selecionarAluno() {
	let idAluno = $('#idAluno')
	selecionarObjeto(idAluno.val(), $('#idAluno>option:selected').text())
}

function selecionarIgreja() {
	let idIgreja = $('#idIgreja')
	$('#divSelecionarIgreja').addClass(hidden)
	selecionarObjeto(idIgreja.val(), $('#idIgreja>option:selected').text())
}

function prosseguirSecretarario() {
	var solicitacaoTipo = $('#solicitacaoTipo');
	var divSelecionarPessoa = $('#divSelecionarPessoa');
	var divSelecionarEntidadeSecretarioParaInativar = $('#divSelecionarEntidadeSecretarioParaInativar');
	if(parseInt(solicitacaoTipo.val()) == 12){		
		var divSelecionarPessoaParaSerSecretarioProsseguir = $('#divSelecionarPessoaParaSerSecretarioProsseguir');					
		divSelecionarPessoa.addClass(hidden);				
		divSelecionarPessoaParaSerSecretarioProsseguir.addClass(hidden);	
		$('#spanMensagemDeConfirmacao').html('Confirma delegar a função de secretário? <b class="text-danger">A pessoa receberá poder para alterar e visualizar dados do seu perfil</b>')	
		continuarParaConfimacao();
	}
	if(parseInt(solicitacaoTipo.val()) == 13){		
		var divSelecionarPessoaParaSerSecretarioProsseguir = $('#divSelecionarPessoaParaSerSecretarioProsseguir');				
		divSelecionarPessoa.addClass(hidden);				
		divSelecionarPessoaParaSerSecretarioProsseguir.addClass(hidden);	
		$('#spanMensagemDeConfirmacao').html('Confirma remover a função de secretário? <b class="text-danger">A pessoa perderá o poder para alterar e visualizar dados do seu perfil</b>')	
		divSelecionarEntidadeSecretarioParaInativar.removeClass(hidden);	
	}	
	
}

function selecionarParaOndeTransferir() {
	let idParaOndeTransferir = $('#idParaOndeTransferir')
	$('#divSelecionarParaOndeTransferir').addClass(hidden)
	selecionarObjeto(idParaOndeTransferir.val(), $('#idParaOndeTransferir>option:selected').text())
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

function selecionarNomeEquipe() {
	let nomeEquipe = $('#nomeEquipe')
	selecionarObjeto(nomeEquipe.val(), 'Nome Equipe: ' + nomeEquipe.val())
}

function selecionarObjeto(id, informacao) {
	const splitId = id.split('_')
	if(splitId.length > 1){
		id = splitId[0]
		if(parseInt(splitId[1]) === 1){
			/* limpar lideres coordenacao e regiao */
			buscarIgrejas()
		}
	}
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
		$(stringDivSelecionarQuemSaira).addClass(hidden);
		$('#blocoObjeto3').removeClass(hidden)
	}
	if (parseInt($('#solicitacaoTipo').val()) === UNIR_CASAL) {
		$(stringDivSelecionarHomem).addClass(hidden);
		$(stringDivSelecionarMulher).addClass(hidden);
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

	if (objetoSelecionado === 3 
		&& 
		(parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER 
			|| parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA
			|| parseInt($('#solicitacaoTipo').val()) === ABRIR_IGREJA_COM_EQUIPE_COMPLETA
			|| parseInt($('#solicitacaoTipo').val()) === ABRIR_EQUIPE_COM_LIDER_DA_IGREJA
			|| parseInt($('#solicitacaoTipo').val()) === SUBIR_LIDER)
	){
		objeto.html(informacao);
		spanLoader.addClass(hidden);
		if(parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER){
			$('#divSelecionarMotivo').addClass(hidden);
		}
		if(parseInt($('#solicitacaoTipo').val()) === REMOVER_CELULA){
			$('#divSelecionarCelula').addClass(hidden);
		}
		if(parseInt($('#solicitacaoTipo').val()) === SUBIR_LIDER ||
			parseInt($('#solicitacaoTipo').val()) === ABRIR_IGREJA_COM_EQUIPE_COMPLETA ||
			parseInt($('#solicitacaoTipo').val()) === ABRIR_EQUIPE_COM_LIDER_DA_IGREJA){
			$('#divNomeEquipe').addClass(hidden);
		}
		valorParaAdicionar = 50;
		if(parseInt($('#solicitacaoTipo').val()) === ABRIR_EQUIPE_COM_LIDER_DA_IGREJA){
			valorParaAdicionar = 100;
		}
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
								if ($('#solicitacaoTipoId').val() == REMOVER_IGREJA) {
									objeto.html('Igreja que será removida');
									$('#divSelecionarIgreja').addClass(hidden)
								}
								if ($('#solicitacaoTipoId').val() == TRANSFERIR_IGREJA) {
									objeto.html('Igreja que será transferida');
									$('#divSelecionarIgreja').addClass(hidden)
									$('#blocoObjeto2').removeClass(hidden)
								}
								if ($('#solicitacaoTipoId').val() == ABRIR_IGREJA_COM_EQUIPE_COMPLETA) {
									objeto.html('Equipe que se tornara uma igreja');
									$('#divSelecionarEquipe').addClass(hidden)
									$('#blocoObjeto3').removeClass(hidden)
								}
								if ($('#solicitacaoTipoId').val() == REMOVER_CELULA) {
									objeto.html('Líder que terá a célula removida');
									console.log('Quantas celulas: ', data.celulas)
									if(data.celulas){
										if(data.celulas['1']){
											for(let indice = 1 ; indice <= 6 ; indice++){
												let indiceString = indice + '';
												if(data.celulas[indiceString]){
													$('#idGrupoEvento').append('<option value="'+data.celulas[indiceString]['idGrupoEvento']+'">'+data.celulas[indiceString]['nomeHospedeiro']+'</option>')
												}
											}										
											$('#blocoObjeto3').removeClass(hidden)
										}
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
								if ($('#solicitacaoTipoId').val() == TRANSFERIR_IGREJA) {
									$('#divSelecionarParaOndeTransferir').addClass(hidden)
								}

							}
							spanNomeLideres.html('Nome dos Líderes: ' + data.nomeLideres);
							spanCelulaQuantidade.html('Quantidade de Células: ' + data.celulaQuantidadePessoal);
							spanQuantidadeLideres.html('Quantidade de Líderes: ' + data.quantidadeLideresPessoal);
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
							parseInt($('#solicitacaoTipo').val()) === SUBIR_LIDER ||
							parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_IGREJA ||
							parseInt($('#solicitacaoTipo').val()) === ABRIR_IGREJA_COM_EQUIPE_COMPLETA ||
							parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER) {
							valorParaAdicionar = 50;
						}
						if(document.getElementById('idEntidadeTipo').value == 3 || 
							document.getElementById('idEntidadeTipo').value == 4){
							valorParaAdicionar = 50;
							$('#blocoObjeto3').addClass(hidden)
						}
						if (parseInt($('#solicitacaoTipo').val()) === REMOVER_IGREJA){
							valorParaAdicionar = 100;
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
										if(data.celulas){
											if(data.celulas['1']){
												$('#blocoObjeto3').removeClass(hidden)
												$('#spanObjeto3').addClass(hidden)
												$('#spanSelecioneObjeto3').removeClass(hidden)
												$('#spanObjeto3').addClass(hidden)
											}
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

						if(document.getElementById('idEntidadeTipo').value == 3 || 
						document.getElementById('idEntidadeTipo').value == 4){
							$('#blocoObjeto3').addClass(hidden)
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
		alert('Selecione quem do casal vai sair');
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
		$('#nomeEquipe').val('')
	}
	$('.grupoLogado').attr('disabled','disabled');

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
		$('#idCasal').val(0)
		$('#quemVaiSair').val(0)
		$('#idAluno').val(0)
		$('#idIgreja').val(0)
		$('#divBotaoSelecionarHomem').addClass(hidden)
		$('#divBotaoSelecionarMulher').addClass(hidden)
		$(stringDivSelecionarLiderIgreja).addClass(hidden)
		let valorDaBarra = pegaValorBarraDeProgresso()
		atualizarBarraDeProgresso(parseInt(valorDaBarra) * -1)
		if ($('#solicitacaoTipoId').val() == SUBIR_LIDER) {
			$('#blocoObjeto3').removeClass(hidden)
			$('#spanSelecioneObjeto3').html('Selecione os dados complementares')
		}
	}
	if (qualObjeto === 3) {
		valorParaRemover = -30;
		if ($('#formSolicitacaoReceber').val()) {
			valorParaRemover = -50;
		}
		if (parseInt($('#solicitacaoTipo').val()) === TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE || 
			parseInt($('#solicitacaoTipo').val()) === REMOVER_LIDER || 
			parseInt($('#solicitacaoTipo').val()) === SEPARAR || 
			parseInt($('#solicitacaoTipo').val()) === SUBIR_LIDER || 
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
	var solicitacaoTipo = parseInt($('#solicitacaoTipo').val());
	// if(solicitacaoTipo == 12){
	// 	valorParaAdicionar = -100;
	// }
	// if(solicitacaoTipo == 13){
	// 	valorParaAdicionar = -50;
	// }
	if(solicitacaoTipo == 12 || solicitacaoTipo == 13){	
		valorParaAdicionar = -100;	
		atualizarBarraDeProgresso(valorParaAdicionar);
		var divSelecionarPessoaParaSerSecretarioProsseguir = $('#divSelecionarPessoaParaSerSecretarioProsseguir');
		var divSelecionarPessoa = $('#divSelecionarPessoa');		
		var idPessoa = $('#idPessoa');     	
		var spanStatus = $('#spanStatus'); 

		idPessoa.val(null);
		spanStatus.addClass(hidden);
		divSelecionarPessoa.removeClass(hidden);				
		divSelecionarPessoaParaSerSecretarioProsseguir.addClass(hidden);
		$('#divTelaConfirmacao').addClass(hidden);
	} else {
		$('#divTelaConfirmacao').addClass(hidden);
		verificarSeMostraOBotaoDeContinuar();
		$(stringDivObjetos).removeClass(hidden);		
	}
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

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function selecionarPessoaParaSerSecretario() {
	var temErro = false;
	var hidden = 'hidden';	
	var solicitacaoTipo = parseInt($('#solicitacaoTipo').val());
	var idPessoa = $('#idPessoa');				
	var divSelecionarPessoaParaSerSecretarioProsseguir = $('#divSelecionarPessoaParaSerSecretarioProsseguir');
	var divSelecionarPessoa = $('#divSelecionarPessoa');		
	var spanPessoa = $('#spanPessoa');
	var spanStatus = $('#spanStatus');
	var inputCPF = $("#cpf");        	
	var cpf = inputCPF.val();
	if (cpf.length === 0 || !isNumber(cpf) || cpf.length !== 11) {
		spanStatus.removeClass(hidden);
		spanPessoa.addClass(hidden);		
		spanStatus.text('CPF INVÁLIDO');            
		temErro = true;
	}
	if(!temErro){
		spanStatus.addClass(hidden);
		
		const url = '/cadastroVerificarCPF'
		fetch(
			url,
			{
				method: 'POST',	
				body: JSON.stringify({
					"cpf": cpf,					
				}),
			},
		)
		.then(retorno => {					
			if(!retorno.ok){
				alert('erro ao buscar dados principais instituto')
			}			
			return retorno.json()		
		})
		.then(json => {	
			if(json.nome && !json.status){
				idPessoa.val(json.idPessoa);
				if(solicitacaoTipo == 12){
					valorParaAdicionar = 100;	
					atualizarBarraDeProgresso(valorParaAdicionar);									
					divSelecionarPessoa.addClass(hidden);				
					divSelecionarPessoaParaSerSecretarioProsseguir.removeClass(hidden);						  
					inputCPF.val(null);
				} 
				if(solicitacaoTipo == 13){
					if(!json.temSecretario){
						spanStatus.removeClass(hidden);
						spanPessoa.addClass(hidden);				
						spanStatus.text('PESSOA NÃO POSSUI RESPONSABILIDADE DE SECRETÁRIO');
					}	
					if(json.temSecretario){
						inputCPF.val(null);
						valorParaAdicionar = 50;
						atualizarBarraDeProgresso(valorParaAdicionar);
						spanStatus.addClass(hidden);
						spanPessoa.addClass(hidden);	
						divSelecionarPessoaParaSerSecretarioProsseguir.removeClass(hidden);	
						divSelecionarPessoa.addClass(hidden);	
						$('#selecionarEntidadeSecretarioParaInativar').children("option").remove();
						$('#selecionarEntidadeSecretarioParaInativar').append('<option value="0">SELECIONE</option>')						
						for (let index = 1; index <= json.temSecretario; index++) {							
							const infoEntidade = json.entidadeSecretario[index].infoEntidade;
							const tipoEntidade = json.entidadeSecretario[index].tipoEntidade;
							const entidadeId = json.entidadeSecretario[index].id;
							$('#selecionarEntidadeSecretarioParaInativar').append('<option value="'+entidadeId+'">SECRETÁRIO '+tipoEntidade+': ' + infoEntidade +'</option>')
						}																																							
					}	
				}					
				spanPessoa.removeClass(hidden);				
				spanPessoa.text(json.nome);								
			}	
			if(json.nome && json.status){
				spanStatus.removeClass(hidden);
				spanPessoa.addClass(hidden);				
				spanStatus.text(`${json.nome} ${json.status}`);
			}
			if(!json.nome && json.status){
				spanStatus.removeClass(hidden);
				spanPessoa.addClass(hidden);				
				spanStatus.text(`${json.status}`);
			}	
			
			console.log(json)			
		})
		.catch(error => console.log(error))	
	}
}

function voltarSelecionarSecretario(){		   	
	var divSelecionarEntidadeSecretarioParaInativar = $('#divSelecionarEntidadeSecretarioParaInativar');
	selecionarEntidadeSecretarioParaInativar = parseInt($('#selecionarEntidadeSecretarioParaInativar').val());
	var solicitacaoTipo = parseInt($('#solicitacaoTipo').val());
	var idPessoa = $('#idPessoa');  		
	var inputCPF = $("#cpf");   					
	idPessoa.val(null);
	inputCPF.val(null);
	if(solicitacaoTipo == 12){
		valorParaAdicionar = -100;
	}
	if(solicitacaoTipo == 13){	
		valorParaAdicionar = -50;
	}	
	if(selecionarEntidadeSecretarioParaInativar && selecionarEntidadeSecretarioParaInativar != 0){
		valorParaAdicionar -= 50;		
	}	
	atualizarBarraDeProgresso(valorParaAdicionar);
	var divSelecionarPessoaParaSerSecretarioProsseguir = $('#divSelecionarPessoaParaSerSecretarioProsseguir');	
	var divSelecionarPessoa = $('#divSelecionarPessoa');			   	
	var spanStatus = $('#spanStatus'); 
	
	spanStatus.addClass(hidden);
	divSelecionarPessoa.removeClass(hidden);
	divSelecionarEntidadeSecretarioParaInativar.addClass(hidden);				
	divSelecionarPessoaParaSerSecretarioProsseguir.addClass(hidden);	
}

function mostrarBotaoInativarSecretario(){
	var botaoProsseguirSecretarioParaInativar = $('#botaoProsseguirSecretarioParaInativar');    
	var selecionarEntidadeSecretarioParaInativar = parseInt($('#selecionarEntidadeSecretarioParaInativar').val());
	if(selecionarEntidadeSecretarioParaInativar == 0){
		valorParaAdicionar = -50;
		atualizarBarraDeProgresso(valorParaAdicionar);
		botaoProsseguirSecretarioParaInativar.addClass(hidden);
	} else {
		valorParaAdicionar = 50;
		atualizarBarraDeProgresso(valorParaAdicionar);
		botaoProsseguirSecretarioParaInativar.removeClass(hidden);
	}
}

function prosseguirInativarSecretarario(){
	var divSelecionarEntidadeSecretarioParaInativar = $('#divSelecionarEntidadeSecretarioParaInativar');
	divSelecionarEntidadeSecretarioParaInativar.addClass(hidden);
	continuarParaConfimacao();
}

function buscarDiscipulos(idSolicitacaoTipo){
	const url = '/principalBuscarLideresSolicitacao'
	const idGrupo = document.getElementById('idGrupo').value
	fetch(
		url,
		{
			method: 'POST',	
			body: JSON.stringify({
				"token": idGrupo,
				"idSolicitacaoTipo": idSolicitacaoTipo,
			}),
		},
	)
		.then(resultado => {
			if(!resultado.ok){
				console.log('erro ao buscar discipulos para solicitacao')
			}
			return resultado.json()
		})
		.then(json => {
			if(json.resultado.discipulos){
				document.getElementById('idLider').innerHTML = json.resultado.discipulos
			}
			if(json.resultado.equipes){
				document.getElementById('idEquipe').innerHTML = json.resultado.equipes
			}
			if(json.resultado.homens){
				document.getElementById('idHomem').innerHTML = json.resultado.homens
			}
			if(json.resultado.mulheres){
				document.getElementById('idMulher').innerHTML = json.resultado.mulheres
			}
			if(json.resultado.casais){
				document.getElementById('idCasal').innerHTML = json.resultado.casais
			}
			if (idSolicitacaoTipo === SUBIR_LIDER) {
				$('.grupoEquipe').attr('disabled','disabled');
				$('.grupoLogado').attr('disabled','disabled');
			}
		})
}

function buscarIgrejas(){
	const url = '/principalBuscarLideresSolicitacaoIgreja'
	const idGrupo = document.getElementById('idGrupo').value
	fetch(
		url,
		{
			method: 'POST',	
			body: JSON.stringify({
				"token": idGrupo,
			}),
		},
	)
		.then(resultado => {
			if(!resultado.ok){
				console.log('erro ao buscar discipulos para solicitacao')
			}
			return resultado.json()
		})
		.then(json => {
			if(json.resultado.discipulos){
				document.getElementById('idLider').innerHTML = json.resultado.discipulos
			}
		})
}

function buscarAlunos(){
	const url = '/principalBuscarAlunosSolicitacao'
	const idGrupo = document.getElementById('idGrupo').value
	fetch(
		url,
		{
			method: 'POST',	
			body: JSON.stringify({
				"token": idGrupo,
			}),
		},
	)
		.then(resultado => {
			if(!resultado.ok){
				console.log('erro ao buscar alunos para solicitacao')
			}
			return resultado.json()
		})
		.then(json => {
			document.getElementById('idAluno').innerHTML = json.resultado.alunos
		})
}

function buscarDiscipulosIgreja(){
	const url = '/principalBuscarDiscipulosIgrejaSolicitacao'
	const idGrupo = document.getElementById('idGrupo').value
	fetch(
		url,
		{
			method: 'POST',	
			body: JSON.stringify({
				"token": idGrupo,
			}),
		},
	)
		.then(resultado => {
			if(!resultado.ok){
				console.log('erro ao buscar discipulos igreja para solicitacao')
			}
			return resultado.json()
		})
		.then(json => {
			document.getElementById('idLiderIgreja').innerHTML = json.resultado.discipulos
		})
}
