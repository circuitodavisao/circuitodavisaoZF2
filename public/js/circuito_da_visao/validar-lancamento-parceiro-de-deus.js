function validar(formulario){
	let mensagemDeErro = '';
	let temErros = false;
	if(formulario.idGrupo.value == 0){
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

	if(formulario.individual.value == ''){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Valor do individual';
		}else{
			mensagemDeErro += ', Valor do Individual';
		}
	}
	if(formulario.idGrupoEvento.value == 0){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Qual célula';
		}else{
			mensagemDeErro += ', Qual Célula';
		}
	}
	if(formulario.celula.value == ''){
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

