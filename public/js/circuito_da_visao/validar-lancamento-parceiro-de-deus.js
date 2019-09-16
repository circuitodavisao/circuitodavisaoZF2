const fatoFinanceiroTipoParceiroDeDeusIndividual = 1;
const fatoFinanceiroTipoParceiroDeDeusCelula = 2;
function validar(formulario){
	let mensagemDeErro = '';
	let temErros = false;
	let idFatoFinanceiroTipo = $('#idFatoFinanceiroTipo').val()

	if(formulario.idGrupo.value == 0){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Líder';
		}else{
			mensagemDeErro += ', Líder';
		}
	}

	if(idFatoFinanceiroTipo == fatoFinanceiroTipoParceiroDeDeusIndividual){
		if(formulario.Dia.value == 0){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Dia';
			}else{
				mensagemDeErro += ', Dia';
			}
		}
		if(formulario.Mes.value == 0){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Mês';
			}else{
				mensagemDeErro += ', Mês';
			}
		}
		if(formulario.Ano.value == 0){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Ano';
			}else{
				mensagemDeErro += ', Ano';
			}
		}

		if(formulario.individual.value == '' || isNaN(formulario.individual.value.replace(',','.'))){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Valor do individual';
			}else{
				mensagemDeErro += ', Valor do Individual';
			}
		}
	}

	if(idFatoFinanceiroTipo == fatoFinanceiroTipoParceiroDeDeusCelula){
		if(formulario.idGrupoEvento.value == 0){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Célula';
			}else{
				mensagemDeErro += ', Célula';
			}
		}
		if(formulario.celula.value == '' || isNaN(formulario.celula.value.replace(',','.'))){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Valor de célula';
			}else{
				mensagemDeErro += ', Valor de célula';
			}
		}
		if(formulario.data.value == 0){
			temErros = true
			if(mensagemDeErro == ''){
				mensagemDeErro = 'Data';
			}else{
				mensagemDeErro += ', Data';
			}
		}
	}
	
	if(!temErros){
		$('#divMEnsagens')
			.addClass('hidden');
		mostrarSplash();
		formulario.submit();
	}else{
		$('#divMensagens')
			.html('Preencha os seguintes campos: '+mensagemDeErro)
			.removeClass('hidden')
	}
}

function selecionarLider(){
	const idGrupo = $('#idGrupo').val()
	$('#loaderCelula').removeClass('hidden')
	if(idGrupo != 0){
		$.post(
			"/relatorioBuscarDadosGrupo",
			{
				idGrupo: idGrupo
			},
			function (data) {
				$('#loaderCelula').addClass('hidden')
				if (data.resposta) {
					$('#idGrupoEvento').html('<option value="0">Selecione</option>')
					if(data.celulas['1']){
						$('#idGrupoEvento').append('<option value="'+data.celulas['1']['idGrupoEvento']+'_'+data.celulas['1']['diaDaSemana']+'">'+data.celulas['1']['nomeHospedeiro']+'</option>')
					}
					if(data.celulas['2']){
						$('#idGrupoEvento').append('<option value="'+data.celulas['2']['idGrupoEvento']+'_'+data.celulas['2']['diaDaSemana']+'">'+data.celulas['2']['nomeHospedeiro']+'</option>')
					}
				}
			}, 'json')
	}else{
		$('#loaderCelula').addClass('hidden')
	}
}

function selecionarEquipe(){
	$('#loaderSub').removeClass('hidden')
	let idEquipe = $('#idEquipe').val()
	if(idEquipe != 0){
		$.post(
			"/cursoBuscarSubsCompleto",
			{ id: idEquipe, },
			function (data) {
				$('#loaderSub').addClass('hidden')
				$('#idGrupo').html('<option value="0">SELECIONE</option>')
				data.filhos.map((filho) => $('#idGrupo').append('<option value="'+filho.id+'">'+filho.informacao+'</option>'))
			}, 'json');
	}else{
		$('#loaderSub').addClass('hidden')
	}
}

function mostrarBotaoSelecionarFatoFinanceiroTipo(){
	let selecionarFatoFinanceiroTipo = $('#idFatoFinanceiroTipo')
	let divBotaoSelecionarFatoFinanceiroTipo = $('#divBotaoSelecionarFatoFinanceiroTipo')
	let hidden = 'hidden'
	let fatoFinanceiroTipoParceiroDeDeusCelula = 2;
	if(selecionarFatoFinanceiroTipo.val() != 0){
		divBotaoSelecionarFatoFinanceiroTipo.removeClass(hidden)
		if(selecionarFatoFinanceiroTipo.val() == fatoFinanceiroTipoParceiroDeDeusCelula){
			$('#divTipoCelula').removeClass(hidden)
			$('#divTipoIndividual').addClass(hidden)
		}
	}else{
		divBotaoSelecionarFatoFinanceiroTipo.addClass(hidden)
	}
}

function selecionarFatoFinanceiroTipo(){
	$('#divSelecionarFatoFinanceiroTipo').addClass('hidden')
	$('#divLancamento').removeClass('hidden')
}

function selecionarCelula(){
	let idGrupoEvento = $('#idGrupoEvento').val()
	let arrayId = idGrupoEvento.split('_')
	let diaDaSemana= arrayId[1]
	if(diaDaSemana == 1){
		diaDaSemana = 7;
	}else{
		diaDaSemana--;
	}
	let data = new Date()
	let diferencaDeDiaDaSemana = diaDaSemana - data.getDay()
	$('#data').html('<option value="0">SELECIONE</option>')
	for(let i = 0; i <= 10; i++){
		let dataTemporaria = new Date()
		dataTemporaria.setDate(dataTemporaria.getDate()-(diferencaDeDiaDaSemana+(i*7)))
		let ano = dataTemporaria.getFullYear()
		let mes = dataTemporaria.getMonth()
		let dia = dataTemporaria.getDate()
		mes++
		let zero2 = new Padder(2)
		dia = zero2.pad(dia)
		mes = zero2.pad(mes)
		let dataFormatadaBancoDeDados = ano+'-'+mes+'-'+dia
		let dataFormatadaBrasil = dia+'/'+mes+'/'+ano
		$('#data').append('<option value="'+dataFormatadaBancoDeDados+'">'+dataFormatadaBrasil+'</option>')
	}
}

function Padder(len, pad) {
	if (len === undefined) {
		len = 1;
	} else if (pad === undefined) {
		pad = '0';
	}

	var pads = '';
	while (pads.length < len) {
		pads += pad;
	}

	this.pad = function (what) {
		var s = what.toString();
		return pads.substring(0, pads.length - s.length) + s;
	};
}
