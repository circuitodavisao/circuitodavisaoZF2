function validar(formulario){
	let mensagemDeErro = '';
	let temErros = false;

	if(formulario.idGrupoEvento.value == 0){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Discipulado';
		}else{
			mensagemDeErro += ', Discipulado';
		}
	}
	if(formulario.lanche.value == 'selecione'){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Lanche';
		}else{
			mensagemDeErro += ', Lanche';
		}
	}
	if(formulario.avisos.value == 'selecione'){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Avisos';
		}else{
			mensagemDeErro += ', Avisos';
		}
	}
	if(formulario.administrativo.value == 'selecione'){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Administrativo';
		}else{
			mensagemDeErro += ', Administrativo';
		}
	}
	if(formulario.oracao.value == 'selecione'){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Oração';
		}else{
			mensagemDeErro += ', Oração';
		}
	}
	if(formulario.administrativo.value == 'selecione'){
		temErros = true
		if(mensagemDeErro == ''){
			mensagemDeErro = 'Palavra';
		}else{
			mensagemDeErro += ', Palavra';
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
