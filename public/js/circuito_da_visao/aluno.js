function mudarSituacao(select, idTurmaPessoa) {
	const classHidden = 'hidden';
	let idSituacao = select.value;
	$.post(
		"/cursoMudarSituacao",
		{idTurmaPessoa, idSituacao},
		(data) => {
			if (data.response) {
				$("#selectMudarSituacao").addClass(classHidden);
				$("#botaoSituacao")
					.text($('#selectMudarSituacao option:selected').text())
					.removeClass(classHidden);
			}
		},
		'json');
}

function removerDaTurma(idTurmaPessoa) {
	const classHidden = 'hidden';
	let resposta = confirm('Realmente quer remover essa pessoa da turma?');
	if (resposta) {
		$.post(
			"/cursoRemoverDaTurma",
			{idTurmaPessoa},
			(data) => {
				if (data.response) {
					$('#dadosDoCurso').addClass(classHidden);
				}
			},
			'json');
	}
}

function mudarPresencaOuVistoOuFinanceiro(idTurmaPessoa, idAulaOuDisciplina, tipoDeLancamento = 1, qualAvaliacao = 0) {
	const classHidden = 'hidden';
	const disabled = 'disabled';
	const iconeFaTimes = '<i class="fa fa-times"></i>';
	const iconeFaCheck = '<i class="fa fa-check"></i>';
	const loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
	const btnDanger = 'btn-danger';
	const btnSuccess = 'btn-success';
	const btnTransicao = 'btn-default';
	let botao = $('#botao_' + idTurmaPessoa + '_' + idAulaOuDisciplina + '_' + tipoDeLancamento);
	if (parseInt(tipoDeLancamento) === 4 || parseInt(tipoDeLancamento) === 3) {
		botao = $('#botao_' + idTurmaPessoa + '_' + idAulaOuDisciplina + '_' + tipoDeLancamento + '_' + qualAvaliacao);
	}
	let valor = 'N';
	if (botao.hasClass(btnDanger)) {
		valor = "S";
	}	
	botao.html(loader);
	botao.removeClass(btnDanger);
	botao.removeClass(btnSuccess);
	botao.addClass(btnTransicao);

	/* Desabilitar botão ate terminar o processamento */
	botao.addClass(disabled);

	$.post(
		"/cursoMudarPresencaOuVistoOuFinanceiro",
		{valor, idTurmaPessoa, idAulaOuDisciplina, tipoDeLancamento, qualAvaliacao},
		function (data) {
			if (data.response) {
				botao.removeClass(btnTransicao);
				botao.html('');

				if (valor === "S") {
					botao.addClass(btnSuccess);
					botao.html(iconeFaCheck);
					if(tipoDeLancamento === 3){
						document.getElementById('recibo'+botao.attr('id')).classList.remove('hidden')
					}
				} else {
					botao.addClass(btnDanger);
					botao.html(iconeFaTimes);
					if(tipoDeLancamento === 3){
						document.getElementById('recibo'+botao.attr('id')).classList.add('hidden')
					}
				}
				botao.removeClass(disabled);
			}
		}, 'json');
}
function acessarMatricula() {
	var mensagem = $('#mensagem');
	var botaoLancar = $('#botaoLancar');
	mensagem.addClass('hidden');
	botaoLancar.addClass('hidden');
	var idTurmaPessoa = $('#matricula');
	if (idTurmaPessoa.val().length != 0) {
		$.post(
			"/cursoConsultarMatricula",
			{
				id: idTurmaPessoa.val(),
			},
			function (data) {
				if (data.response) {
					var informacaoAluno = '<p>Turma: ' + data.turma +
						'</p><p>Aluno: ' + data.pessoa +
						'</p><p>Matrícula: ' + data.idTurmaPessoa + '</p>';
					mensagem
						.addClass('alert-info')
						.removeClass('alert-danger')
						.removeClass('hidden')
						.html(informacaoAluno);
					location.href="/cursoAluno/"+data.idTurmaPessoa
				} else {
					mensagem
						.addClass('alert-danger')
						.removeClass('alert-info')
						.removeClass('hidden')
						.html('Matrícula NÃO encontrada!');
				}
			}, 'json');
	} else {
		mensagem
			.addClass('alert-danger')
			.removeClass('alert-info')
			.removeClass('hidden')
			.html('Informe a Mátricula!');
	}
}

document.getElementById('matricula').focus()

function verificarMatricula(reposicao = false) {
	let mensagem = $('#mensagem');
	let botaoLancar = $('#botaoLancar');
	mensagem.addClass('hidden');
	botaoLancar.addClass('hidden');
	let idTurmaPessoa = $('#matricula');
	if (idTurmaPessoa.val().length != 0) {
		let url = '/cursoConsultarMatricula'
		if(reposicao){
			url = '/cursoConsultarReposicao'
		}
		$.post(
			url,
			{
				id: idTurmaPessoa.val(),
			},
			function (data) {
				if (data.response) {
					let informacaoAluno = '<p>Turma: ' + data.turma +
						'</p><p>Aluno: ' + data.pessoa +
						'</p><p>Matrícula: ' + idTurmaPessoa.val() + '</p>';
					if(data.temAulaAtiva || reposicao){
						botaoLancar.removeClass('hidden');
						informacaoAluno += '<p>Aula: ' + data.nomeAula+ '</p>';
					}else{
						informacaoAluno += '<p class="alert alert-danger">SEM AULA ABERTA</p>';
					}
					mensagem
						.addClass('alert-info')
						.removeClass('alert-danger')
						.removeClass('hidden')
						.html(informacaoAluno);

				} else {
					mensagem
						.addClass('alert-danger')
						.removeClass('alert-info')
						.removeClass('hidden')
						.html('Matrícula NÃO encontrada!');
				}
			}, 'json');
	} else {
		mensagem
			.addClass('alert-danger')
			.removeClass('alert-info')
			.removeClass('hidden')
			.html('Informe a Mátricula!');
	}
}

function lancarPresenca() {
	let mensagem = $('#mensagem');
	mensagem.addClass('hidden');
	let matricula = $('#matricula');
	let botaoLancar = $('#botaoLancar');
	if (matricula.val().length != 0) {
		mostrarSplash();
		$.post(
			"/cursoLancarPresencaFinalizar",
			{id: matricula.val(),},
			function (data) {
				esconderSplash()
				if (data.response) {
					mensagem
						.addClass('alert-success')
						.removeClass('alert-danger')
						.removeClass('hidden')
						.html('Frequência registrada com sucesso!');
					matricula.val('');
					matricula.focus()
				} else {
					mensagem
						.addClass('alert-danger')
						.removeClass('alert-info')
						.removeClass('hidden')
						.html('Frequência NÃO registrada!');
				}
				botaoLancar.addClass('hidden');
			}, 'json');
	
	} else {
		mensagem
			.addClass('alert-danger')
			.removeClass('alert-info')
			.removeClass('hidden')
			.html('Informe a Mátricula!');
	}
}

function lancarReposicao() {
	let mensagem = $('#mensagem');
	mensagem.addClass('hidden');
	let matricula = $('#matricula');
	let botaoLancar = $('#botaoLancar');
	if (matricula.val().length != 0) {
		mostrarSplash();
		$.post(
			"/cursoLancarReposicaoFinalizar",
			{id: matricula.val(),},
			function (data) {
				esconderSplash()
				if (data.response) {
					mensagem
						.addClass('alert-success')
						.removeClass('alert-danger')
						.removeClass('hidden')
						.html('Reposição registrada com sucesso!');
					matricula.val('');
					matricula.focus()
				} else {
					mensagem
						.addClass('alert-danger')
						.removeClass('alert-info')
						.removeClass('hidden')
						.html('Frequência NÃO registrada!');
				}
				botaoLancar.addClass('hidden');
			}, 'json');
	
	} else {
		mensagem
			.addClass('alert-danger')
			.removeClass('alert-info')
			.removeClass('hidden')
			.html('Informe a Mátricula!');
	}
}


function mudarStatusAprovado() {
	let validacaoGeralDoAluno = document.getElementById("validacaoGeralDoAluno");	
	 let validacaoDoFinanceiro = document.getElementById("validacaoDoFinanceiro");		 	 
	if(validacaoGeralDoAluno.value == 1){		
		let alertSituacao = $('#alertSituacao');	
		let h3Situacao = $('#h3Situacao');
		alertSituacao.removeClass('alert-danger')
		alertSituacao.addClass('alert-success');
		h3Situacao.html('Aluno Aprovado');
	}
	
	if(validacaoDoFinanceiro.value == 1){			
		let alertFinanceiroSituacao = $('#alertFinanceiroSituacao');	
		let h3financeiroSituacao = $('#h3financeiroSituacao');
		alertFinanceiroSituacao.removeClass('alert-danger')
		alertFinanceiroSituacao.addClass('alert-success');
		h3financeiroSituacao.html('Financeiro Adimplente');
	}
	
}


mudarStatusAprovado();
	
