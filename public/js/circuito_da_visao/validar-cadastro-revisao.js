function validar(formulario){
	let mensagemDeErro = '';
	let temErros = false;
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
