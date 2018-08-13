/**
 * Nome: mudar-situacao.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 */
const classHidden = 'hidden';
const disabled = 'disabled';
const iconeFaTimes = '<i class="fa fa-times"></i>';
const iconeFaCheck = '<i class="fa fa-check"></i>';
const loader = '<img width="11" hegth="11" src="/img/loader.gif"></i>';
const btnDanger = 'btn-danger';
const btnSuccess = 'btn-success';
const btnTransicao = 'btn-default';

function mudarSituacao(select, idTurmaPessoa) {
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

const tipoDeLancamentoPresenca = 1;
function mudarPresencaOuVistoOuFinanceiro(idTurmaPessoa, idAulaOuDisciplina, tipoDeLancamento = tipoDeLancamentoPresenca, qualAvaliacao = 0) {
    let botao = $('#botao_' + idTurmaPessoa + '_' + idAulaOuDisciplina + '_' + tipoDeLancamento);
    if (parseInt(tipoDeLancamento) === 4) {
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
                    } else {
                        botao.addClass(btnDanger);
                        botao.html(iconeFaTimes);
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
							var informacaoAluno = '<p>Curso: ' + data.curso +
								'</p><p>Turma: ' + data.turma +
								'</p><p>Equipe: ' + data.equipe +
								'</p><p>Aluno: ' + data.pessoa +
								'</p><p>Matrícula: ' + data.idTurmaPessoa + '</p>';
							mensagem
								.addClass('alert-info')
								.removeClass('alert-danger')
								.removeClass('hidden')
								.html(informacaoAluno);
							location.href="/cursoAluno/"+idTurmaPessoa.val()
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

