function validar(formulario){
	let mensagemDeErro = '';
	let temErros = false;
	if(formulario.idPessoa.value == 0){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Líder';
		}else{
			mensagemDeErro += ', Líder';
		}
	}
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
	if(formulario.celula.value == '' || isNaN(formulario.celula.value.replace(',','.'))){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Valor de célula';
		}else{
			mensagemDeErro += ', Valor de célula';
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
	const idGrupo = $('#idGrupo').val();
	$.post(
		"/relatorioBuscarDadosGrupo",
		{
			idGrupo: idGrupo
		},
		function (data) {
			if (data.resposta) {
				$('#idGrupoEvento').html('<option value="0">Selecione</option>')
				if(data.celulas['1']){
					$('#idGrupoEvento').append('<option value="'+data.celulas['1']['idGrupoEvento']+'">'+data.celulas['1']['nomeHospedeiro']+'</option>')
				}
				if(data.celulas['2']){
					$('#idGrupoEvento').append('<option value="'+data.celulas['2']['idGrupoEvento']+'">'+data.celulas['2']['nomeHospedeiro']+'</option>')
				}
			}
		}, 'json')
}

function atualizarTotal(){
	let valorIndividual = $('#individual').val()
	let valorCelula = $('#celula').val()
	if(valorIndividual == ''){
		valorIndividual = '0'
	}
	if(valorCelula == ''){
		valorCelula = '0'
	}

	valorIndividual = valorIndividual.replace(',','.')
	valorIndividual = parseFloat(valorIndividual)

	valorCelula = valorCelula.replace(',','.')
	valorCelula = parseFloat(valorCelula)

	let soma = valorIndividual + valorCelula
	console.log('Individual', valorIndividual)
	console.log('Celula', valorCelula)
	$('#total').html(soma.toFixed(2))
}

function selecionarEquipe(){
	$('#loaderSub').removeClass('hidden')
	let idEquipe = $('#selecionaEquipe').val()
	if(idEquipe != 0){
		$.post(
			"/cursoBuscarSubsCompleto",
			{ id: idEquipe, },
			function (data) {
				$('#loaderSub').addClass('hidden')
				$('#selecionarSub').html('<option value="0">SELECIONE</option>')
				data.filhos.map((filho) => $('#selecionarSub').append('<option value="'+filho.id+'">'+filho.informacao+'</option>'))
			}, 'json');
	}else{
		$('#loaderSub').addClass('hidden')
	}
}
