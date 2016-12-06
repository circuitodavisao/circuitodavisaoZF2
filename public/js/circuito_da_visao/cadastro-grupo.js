/**
 * Nome: cadastro-grupo.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Funções para cadastro de um novo grupo
 */

/* Dados */
var estadoCivil = 0;
var iconeTime = '<i class="fa fa-times" aria-hidden="true"></i>';
var iconeCheck = '<i class="fa fa-check" aria-hidden="true"></i>';
var hidden = 'hidden';
var textDanger = 'text-danger';
var textSuccess = 'text-success';
var stringNBSP = '&nbsp;';
var alertDanger = 'alert-danger';
var alertSuccess = 'alert-success';
var btnDefault = 'btn-default';
var btnPrimary = 'btn-primary';

function validarEstadoCivil() {
    var valorSelecionado = $('input[name=inputEstadoCivil]:checked').val();

    estadoCivil = parseInt(valorSelecionado);
    switch (estadoCivil) {
        case 1:
            $('#blocoHomem').addClass('hidden');
            $('#blocoMulher').addClass('hidden');
            break;
        case 2:
            $('#blocoResponsavel').addClass('hidden');
            break;
        default:
            break;
    }
    $('#divEstadoCivil').addClass('hidden');
    $('#divConfirmacao').removeClass('hidden');

}

function abrirTelaDeAlunos(tipo) {
    $('#botaoInserirResponsavel0').addClass(hidden);
    $('#botaoInserirResponsavel1').addClass(hidden);
    $('#botaoInserirResponsavel2').addClass(hidden);
    $('.alunoM').addClass(hidden);
    $('.alunoF').addClass(hidden);
    switch (tipo) {
        case 0:
            $('.alunoM').removeClass(hidden);
            $('.alunoF').removeClass(hidden);
            $('#botaoInserirResponsavel0').removeClass(hidden);
            break;
        case 1:
            $('.alunoM').removeClass(hidden);
            $('#botaoInserirResponsavel1').removeClass(hidden);
            break;
        case 2:
            $('.alunoF').removeClass(hidden);
            $('#botaoInserirResponsavel2').removeClass(hidden);
            break;
    }
    $('#divConfirmacao').addClass(hidden);
    $('#divSelecionarAluno').removeClass(hidden);
    $('#divIncluirResponsavel').removeClass(hidden);
    $('#divPassoAPasso').removeClass(hidden);
    $('#divDadosSelecionados').addClass(hidden);
    $('#divBotaoDeSelecionarAluno').addClass(hidden);
    $('#divSpanResponsavelCPF').addClass(hidden);
    $('#divSpanResponsavelEmail').addClass(hidden);
    $('#botaoHierarquiaSelecionada').addClass(hidden);
    $('#divHierarquia').addClass(hidden);
    limparPassoAPasso(tipo);
}

function selecionarAluno() {
    var alunoSelecionado = $('input[name=radioAlunoSelecionado]:checked').val();
    /* Dados do aluno selecionado */
    var splitAlunoSelecionado = alunoSelecionado.split("#");
    var matricula = splitAlunoSelecionado[0];
    var nome = splitAlunoSelecionado[1];
    var dataNascimento = splitAlunoSelecionado[2];
    var tipo = splitAlunoSelecionado[3];
    /* Coloca dados do aluno selecionado na tela */
    $('#spanResponsavelMatricula').html(matricula);
    $('#spanResponsavelNome').html(nome);
    $('#spanResponsavelDataNascimento').html(dataNascimento);
    /* Por matricula do aluno no campo */
    var inputIdAlunoSelecionado;
    switch (tipo) {
        case 1:
            inputIdAlunoSelecionado = $('#idAlunoSelecionado1');
            break;
        case 2:
            inputIdAlunoSelecionado = $('#idAlunoSelecionado2');
            break;
        default:
            inputIdAlunoSelecionado = $('#idAlunoSelecionado0');
            break;
    }
    inputIdAlunoSelecionado.val(matricula);

    /* Limpar campos */
    $('#cpf').val('');
    $('#dataNascimento').val('');

    /* Abrir div para cpf e data de nascimento */
    $('#botaoPasso2')
            .removeClass(btnDefault)
            .addClass(btnPrimary);
    $('#divSelecionarAluno').addClass(hidden);
    $('#divCPFDataNascimento').removeClass(hidden);
    $('#divDadosSelecionados').removeClass(hidden);
    $('#nomeAluno').val(nome);
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function buscarCPF() {
    $('#botaoCPFLiberado').addClass(hidden);
    var cpf = $("#cpf").val();
    var stringBarra = '/';
    var DiaDataNascimento = $("#Dia").val();
    var MesDataNascimento = $("#Mes").val();
    var AnoDataNascimento = $("#Ano").val();

    DiaDataNascimento = parseInt(DiaDataNascimento);
    MesDataNascimento = parseInt(MesDataNascimento);
    AnoDataNascimento = parseInt(AnoDataNascimento);

    var loader = $('#loadercpf');
    var spanMensagens = $('#spanMensagens');
    spanMensagens.removeClass(alertDanger)
            .removeClass(alertSuccess)
            .addClass(hidden);
    loader.removeClass(hidden);

    var temErro = false;
    if (cpf.length === 0 || DiaDataNascimento === 0 || MesDataNascimento === 0 || AnoDataNascimento === 0) {
        temErro = true;
        spanMensagens.text('Preenchar a Data de Nascimento e o CPF corretamente!');
    } else {
        if (DiaDataNascimento < 10) {
            DiaDataNascimento = '0' + DiaDataNascimento;
        }
        if (MesDataNascimento < 10) {
            MesDataNascimento = '0' + MesDataNascimento;
        }
        var dataNascimento = DiaDataNascimento + stringBarra
                + MesDataNascimento + stringBarra
                + AnoDataNascimento;
        if (!isNumber(cpf) || cpf.length !== 11) {
            spanMensagens.text('CPF é invalido!');
            temErro = true;
        }
    }

    if (!temErro) {
        $.post(
                "/cadastroBuscarCPF",
                {
                    cpf: cpf,
                    dataNascimento: dataNascimento
                },
        function (data) {
            var resposta = parseInt(data.resposta);
            if (resposta === 1) {
                var nomeDoAlunoSelecionado = $('#nomeAluno').val();
                var splitNomeDoAluno = nomeDoAlunoSelecionado.split(" ");
                var splitNomeEncontradoNaBusca = data.nome.split(" ");

                var dadosValidados = true;

                if (splitNomeDoAluno[0] !== splitNomeEncontradoNaBusca[0]) {
                    dadosValidados = false;
                    spanMensagens
                            .addClass(alertDanger)
                            .text('Nome do CPF não confere com o do aluno')
                            .removeClass(hidden);
                }

                if (dataNascimento !== data.dataNascimento) {
                    dadosValidados = false;
                    spanMensagens
                            .addClass(alertDanger)
                            .text('Data de Nascimento não confere com o encontrado')
                            .removeClass(hidden);
                }
                if (dadosValidados) {
                    /* Mensagem de sucesso */
                    spanMensagens
                            .text('Dados Liberados!')
                            .addClass(alertSuccess)
                            .removeClass(hidden);
                    $('#botaoCPFLiberado').removeClass(hidden);

                    /* Pondo valores encontrados na tela */
                    $('#spanResponsavelNome').html(data.nome);
                    $('#spanResponsavelCPF').html(data.cpf);
                    $('#divSpanResponsavelCPF').removeClass(hidden);
                    $('#spanResponsavelDataNascimento').html(data.dataNascimento);
                }
            }
            if (resposta === 2) {
                /* Não encontrou no PROCOB */
                spanMensagens.text('CPF e Data de Nascimento não encontrado na base de dados');
            }

            if (resposta === 3) {
                /* Já cadastrado */
                spanMensagens.text('CPF já cadastrado!');
            }

            if (resposta === 2 || resposta === 3) {
                spanMensagens
                        .addClass(alertDanger)
                        .removeClass(hidden);
            }
            loader.addClass(hidden);
        }
        , 'json');
    } else {
        spanMensagens
                .addClass(alertDanger)
                .removeClass(hidden);
        loader.addClass(hidden);
    }
}

function mostrarDivEmail() {
    $('#divCPFDataNascimento').addClass(hidden);
    $('#botaoCPFLiberado').addClass(hidden);
    $('#divEmail').removeClass(hidden);
    $('#botaoPasso3')
            .removeClass('btn-default')
            .addClass('btn-primary');
    escondeMensagem();
}

function buscarEmail() {
    var temErro = false;
    var email = $('#email').val();
    var repetirEmail = $('#repetirEmail').val();
    var loader = $('#loaderrepetirEmail');
    var spanMensagens = $('#spanMensagens');
    spanMensagens
            .addClass(hidden)
            .removeClass(alertDanger)
            .removeClass(alertSuccess);
    loader.removeClass(hidden);
    if (repetirEmail != email) {
        temErro = true;
        spanMensagens.html('Emails não conferem');
    }
    if (email.length === 0) {
        temErro = true;
        spanMensagens.html('Preeencha o email');
    } else {
        if (!validaEmail(email)) {
            temErro = true;
            spanMensagens.html('Email invalido!');
        }
        if (email === email0 || email === email1 || email === email2) {
            temErro = true;
            spanMensagens.html('Email já usado pelo conjungê');
        }
    }
    if (repetirEmail.length === 0) {
        temErro = true;
        spanMensagens.html('Repetir o email');
    }
    if (email.length === 0 && repetirEmail.length === 0) {
        temErro = true;
        spanMensagens.html('Preeencha o email e repita');
    }
    /* Não deixar repetir email */

    var email0 = $('#email0').val();
    var email1 = $('#email1').val();
    var email2 = $('#email2').val();


    if (!temErro) {
        $.post(
                "/cadastroBuscarEmail",
                {email: email},
        function (data) {
// liberado
            if (!data.resposta) {
                spanMensagens
                        .html('Email liberado!')
                        .removeClass(hidden)
                        .addClass(alertSuccess);
                $('#divSpanResponsavelEmail').removeClass(hidden);
                $('#spanResponsavelEmail').html(email);
                $('#botaoEmailLiberado').removeClass(hidden);
            }
// não liberado
            if (data.resposta) {
                spanMensagens
                        .html('Email já utilizado por outra pessoa!')
                        .addClass(alertDanger)
                        .removeClass(hidden);
            }
            loader.addClass(hidden);
        }, 'json');
    } else {
        spanMensagens
                .removeClass(hidden)
                .addClass(alertDanger);
        loader.addClass(hidden);
    }
}

function mostrarDivHierarquia() {
    $('#divEmail').addClass(hidden);
    $('#botaoPasso4')
            .removeClass('btn-default')
            .addClass('btn-primary');
    $('#divHierarquia').removeClass(hidden);
    escondeMensagem();
}

function escondeMensagem() {
    $('#spanMensagens').addClass(hidden);
}

function validaEmail(email) {
    var er = RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);
    if (er.test(email) == false) {
        return false;
    }
    return true;
}

function mostrarBotaoDeProsseguirDoEstadoCivil() {
    $('#divBotaoDeProsseguirDoEstadoCivil').removeClass(hidden);
}

function mostrarBotaoDeSelecionarAluno() {
    $('#divBotaoDeSelecionarAluno').removeClass(hidden);
}

function mostrarBotaoDeInserirResponsavel(valorDoSelectHierarquia) {
    valorDoSelectHierarquia = parseInt(valorDoSelectHierarquia);
    var botaoHierarquiaSelecionada = $('#botaoHierarquiaSelecionada');
    if (valorDoSelectHierarquia !== 0) {
        botaoHierarquiaSelecionada.removeClass(hidden);
    }
    if (valorDoSelectHierarquia === 0) {
        botaoHierarquiaSelecionada.addClass(hidden);
    }
}

function mostrarBotaoDeInserirDadosComplementares(valorDoSelectNumeracao) {
    valorDoSelectNumeracao = parseInt(valorDoSelectNumeracao);
    var divInserirAlterarDadosComplementares = $('#divInserirAlterarDadosComplementares');
    if (valorDoSelectNumeracao !== 0) {
        divInserirAlterarDadosComplementares.removeClass(hidden);
    }
    if (valorDoSelectNumeracao === 0) {
        divInserirAlterarDadosComplementares.addClass(hidden);
    }
}

function insereResponsavelNaTelaDeConfimacao(tipoResponsavel, mudarBarraDeProgresso) {
    var spanMatricula = '';
    var spanNome = '';
    var spanNomexs = '';
    var spanEmail = '';
    var spanEmailxs = '';
    var spanHierarquia = '';
    var spanCPF = '';
    var iconeResponavelMatricula = '<i class="fa fa-folder" aria-hidden="true"></i>' + stringNBSP;
    var iconeResponavelNomeInicial = '<i class="fa fa-user" aria-hidden="true"></i>' + stringNBSP + '<strong>';
    var iconeResponavelNomeFinal = '</strong>';
    var iconeResponavelCPF = '<i class="fa fa-info-circle" aria-hidden="true"></i>' + stringNBSP;
    var iconeResponavelEmail = '<i class="fa fa-envelope" aria-hidden="true"></i>' + stringNBSP;
    var iconeResponavelHierarquia = '<i class="fa fa-sitemap" aria-hidden="true"></i>' + stringNBSP;
    var inputHiddenEmail;
    var inputHiddenHierarquia;
    var valorBarraDeProgresso;
    var divCheckDadosResponsavelInseridos;
    var divBotaoInserirResponsavel;
    var divBotaoLimparResponsavel;
    var inputHiddenNome;
    var inputHiddenCPF;
    var inputHiddenDataNascimento;

    switch (tipoResponsavel) {
        case 0:
            spanMatricula = $('#spanMatricula0');
            spanNome = $('#spanNome0');
            spanNomexs = $('#spanNome0xs');
            spanEmail = $('#spanEmail0');
            spanEmailxs = $('#spanEmail0xs');
            spanHierarquia = $('#spanHierarquia0');
            spanCPF = $('#spanCPF0');
            inputHiddenEmail = $('#email0');
            inputHiddenHierarquia = $('#hierarquia0');
            $('#spanInsiraOsDadosDoResponsavel').html('Dados do Respons&aacute;vel');
            valorBarraDeProgresso = 50;
            divCheckDadosResponsavelInseridos = $('#divCheckDadosResponsavelInseridos');
            divBotaoInserirResponsavel = $('#divBotaoInserirResponsavel');
            divBotaoLimparResponsavel = $('#divBotaoLimparResponsavel');
            inputHiddenNome = $('#nome0');
            inputHiddenCPF = $('#cpf0');
            inputHiddenDataNascimento = $('#dataNascimento0');
            break;
        case 1:
            spanMatricula = $('#spanMatricula1');
            spanNome = $('#spanNome1');
            spanNomexs = $('#spanNome1xs');
            spanEmail = $('#spanEmail1');
            spanEmailxs = $('#spanEmail1xs');
            spanHierarquia = $('#spanHierarquia1');
            spanCPF = $('#spanCPF1');
            inputHiddenEmail = $('#email1');
            inputHiddenHierarquia = $('#hierarquia1');
            $('#spanInsiraOsDadosDoResponsavel1').html('Dados do Homem');
            valorBarraDeProgresso = 25;
            divCheckDadosResponsavelInseridos = $('#divCheckDadosResponsavelInseridos1');
            divBotaoInserirResponsavel = $('#divBotaoInserirResponsavel1');
            divBotaoLimparResponsavel = $('#divBotaoLimparResponsavel1');
            inputHiddenNome = $('#nome1');
            inputHiddenCPF = $('#cpf1');
            inputHiddenDataNascimento = $('#dataNascimento1');
            break;
        case 2:
            spanMatricula = $('#spanMatricula2');
            spanNome = $('#spanNome2');
            spanNomexs = $('#spanNome2xs');
            spanEmail = $('#spanEmail2');
            spanEmailxs = $('#spanEmail2xs');
            spanHierarquia = $('#spanHierarquia2');
            spanCPF = $('#spanCPF2');
            inputHiddenEmail = $('#email2');
            inputHiddenHierarquia = $('#hierarquia2');
            $('#spanInsiraOsDadosDoResponsavel2').html('Dados da Mulher');
            valorBarraDeProgresso = 25;
            divCheckDadosResponsavelInseridos = $('#divCheckDadosResponsavelInseridos2');
            divBotaoInserirResponsavel = $('#divBotaoInserirResponsavel2');
            divBotaoLimparResponsavel = $('#divBotaoLimparResponsavel2');
            inputHiddenNome = $('#nome2');
            inputHiddenCPF = $('#cpf2');
            inputHiddenDataNascimento = $('#dataNascimento2');
            break;
        default:
            return false;
    }

    var spanResponsavelMatricula = $('#spanResponsavelMatricula');
    var spanResponsavelNome = $('#spanResponsavelNome');
    var spanResponsavelDataNascimento = $('#spanResponsavelDataNascimento');
    var spanResponsavelCPF = $('#spanResponsavelCPF');
    var spanResponsavelEmail = $('#spanResponsavelEmail');
    var hierarquiaSelecioanada = $('#hierarquia option:selected');

    inputHiddenNome.val(spanResponsavelNome.html());
    inputHiddenCPF.val(spanResponsavelCPF.html());
    inputHiddenDataNascimento.val(spanResponsavelDataNascimento.html());
    spanMatricula.html(iconeResponavelMatricula + spanResponsavelMatricula.html());
    spanCPF.html(iconeResponavelCPF + spanResponsavelCPF.html());
    spanNomexs.html(iconeResponavelNomeInicial + ajustaStringTelaPequena(spanResponsavelNome.html()) + iconeResponavelNomeFinal);
    spanEmailxs.html(iconeResponavelEmail + ajustaStringTelaPequena(spanResponsavelEmail.html()));
    spanNome.html(iconeResponavelNomeInicial + spanResponsavelNome.html() + iconeResponavelNomeFinal);
    spanEmail.html(iconeResponavelEmail + spanResponsavelEmail.html());
    spanHierarquia.html(iconeResponavelHierarquia + hierarquiaSelecioanada.text());
    divCheckDadosResponsavelInseridos.removeClass(hidden);
    divBotaoInserirResponsavel.addClass(hidden);
    divBotaoLimparResponsavel.removeClass(hidden);
    inputHiddenEmail.val(spanResponsavelEmail.html());
    inputHiddenHierarquia.val(hierarquiaSelecioanada.val());

    if (mudarBarraDeProgresso === 0) {
        atualizarBarraDeProgresso(valorBarraDeProgresso);
    }
    $('#divIncluirResponsavel').addClass(hidden);
}

function limparDadosPessoaSelecionada(tipoResponsavel) {
    var spanMatricula = '';
    var spanNome = '';
    var spanNomexs = '';
    var spanEmail = '';
    var spanEmailxs = '';
    var spanHierarquia = '';
    var spanCPF = '';
    var divBotaoLimpar = '';
    var divBotaoInserir = '';
    var divCheckDadosResponsavelInseridos = '';
    switch (tipoResponsavel) {
        case 1:
            spanMatricula = $('#spanMatricula1');
            spanNome = $('#spanNome1');
            spanNomexs = $('#spanNome1xs');
            spanEmail = $('#spanEmail1');
            spanEmailxs = $('#spanEmail1xs');
            spanHierarquia = $('#spanHierarquia1');
            spanCPF = $('#spanCPF1');
            divBotaoLimpar = $('#divBotaoLimparResponsavel1');
            divBotaoInserir = $('#divBotaoInserirResponsavel1');
            divCheckDadosResponsavelInseridos = $('#divCheckDadosResponsavelInseridos1');
            break;
        case 2:
            spanMatricula = $('#spanMatricula2');
            spanNome = $('#spanNome2');
            spanNomexs = $('#spanNome2xs');
            spanEmail = $('#spanEmail2');
            spanEmailxs = $('#spanEmail2xs');
            spanHierarquia = $('#spanHierarquia2');
            spanCPF = $('#spanCPF2');
            divBotaoLimpar = $('#divBotaoLimparResponsavel2');
            divBotaoInserir = $('#divBotaoInserirResponsavel2');
            divCheckDadosResponsavelInseridos = $('#divCheckDadosResponsavelInseridos2');
            break;
        default:
            spanMatricula = $('#spanMatricula0');
            spanNome = $('#spanNome0');
            spanNomexs = $('#spanNome0xs');
            spanEmail = $('#spanEmail0');
            spanEmailxs = $('#spanEmail0xs');
            spanHierarquia = $('#spanHierarquia0');
            spanCPF = $('#spanCPF0');
            divBotaoLimpar = $('#divBotaoLimparResponsavel');
            divBotaoInserir = $('#divBotaoInserirResponsavel');
            divCheckDadosResponsavelInseridos = $('#divCheckDadosResponsavelInseridos');
            break;
    }
    spanMatricula.html('');
    spanCPF.html('');
    spanNomexs.html('');
    spanEmailxs.html('');
    spanNome.html('');
    spanEmail.html('');
    spanHierarquia.html('');

    $('#spanInsiraOsDadosDoResponsavel').html('Insira os dados do Responsavel');
    $('#spanInsiraOsDadosDoResponsavel1').html('Insira os dados do Homem');
    $('#spanInsiraOsDadosDoResponsavel2').html('Insira os dados do Mulher');

    divBotaoLimpar.addClass(hidden);
    divBotaoInserir.removeClass(hidden);
    divCheckDadosResponsavelInseridos.addClass(hidden);
    limparPassoAPasso(tipoResponsavel);
    var valorDeReducaoBarraDeProgresso = 0;
    if (tipoResponsavel === 0) {
        valorDeReducaoBarraDeProgresso = -50;
    }
    if (tipoResponsavel === 1 || tipoResponsavel === 1) {
        valorDeReducaoBarraDeProgresso = -25;
    }
    atualizarBarraDeProgresso(valorDeReducaoBarraDeProgresso);
}

function limparPassoAPasso(tipo) {
    $('#botaoPasso2').removeClass(btnPrimary).addClass(btnDefault);
    $('#botaoPasso3').removeClass(btnPrimary).addClass(btnDefault);
    $('#botaoPasso4').removeClass(btnPrimary).addClass(btnDefault);
    $('#divHierarquia').addClass(hidden);
    $('#Dia').val(0);
    $('#Mes').val(0);
    $('#Ano').val(0);
    $('#email').val('');
    $('#repetirEmail').val('');
    $('#hierarquia').val(0);
    $("input:radio[name='radioAlunoSelecionado']").each(function (i) {
        this.checked = false;
    });
    $('#divSpanResponsavelEmail').addClass(hidden);
    $('#divSpanResponsavelCPF').addClass(hidden);
    $('#divDadosSelecionados').addClass(hidden);
    $('#botaoHierarquiaSelecionada').addClass(hidden);
    $('#botaoEmailLiberado').addClass(hidden);
    $('#divBotaoDeSelecionarAluno').addClass(hidden);

    if (tipo === 0) {
        $('#idAlunoSelecionado0').val('');
        $('#nome0').val('');
        $('#email0').val('');
        $('#cpf0').val('');
        $('#hierarquia0').val('');
        $('#dataNascimento0').val('');
    }
    if (tipo === 1) {
        $('#idAlunoSelecionado1').val('');
        $('#nome1').val('');
        $('#email1').val('');
        $('#cpf1').val('');
        $('#hierarquia1').val('');
        $('#dataNascimento1').val('');
    }
    if (tipo === 2) {
        $('#idAlunoSelecionado2').val('');
        $('#nome2').val('');
        $('#email2').val('');
        $('#cpf2').val('');
        $('#hierarquia2').val('');
        $('#dataNascimento2').val('');
    }
    $('#nomeAluno').val('');
}

function insereNumeracaoNaTelaDeConfimacao(mudarBarraDeProgresso) {
    var spanDadosComplementares = $('#spanDadosComplementares');
    var iconeNumeracao = '<i class="fa fa-info-circle" aria-hidden="true"></i>' + stringNBSP;
    spanDadosComplementares.html(iconeNumeracao + stringNBSP + 'Numeração:' + stringNBSP + $('#numeracao option:selected').text());
    $('#spanInsiraOsDadosComplementares').html('Dados Complementares');
    $('#divCheckDadosComplementaresInseridos').removeClass(hidden);
    $('#divBotaoInserirDadosComplementares').addClass(hidden);
    $('#divBotaoAlterarDadosComplementares').removeClass(hidden);
    if (mudarBarraDeProgresso === 0) {
        atualizarBarraDeProgresso(50);
    }
}

function pegaValorBarraDeProgresso() {
    return $('#divProgressBar').attr("aria-valuenow");
}

function atualizarBarraDeProgresso(valorParaSomar) {
    valorParaSomar = parseInt(valorParaSomar);
    var valorAtualDaBarraDeProgresso = pegaValorBarraDeProgresso();
    var valorAtualizadoDaBarraDeProgresso = parseInt(valorAtualDaBarraDeProgresso) + valorParaSomar;
    var stringPercentual = '%';
    $('#divProgressBar')
            .attr("aria-valuenow", valorAtualizadoDaBarraDeProgresso)
            .html(valorAtualizadoDaBarraDeProgresso + stringPercentual)
            .css('width', valorAtualizadoDaBarraDeProgresso + stringPercentual);

    var divBotaoSubmit = $('#divBotaoSubmit');
    if (valorAtualizadoDaBarraDeProgresso == 100) {
        divBotaoSubmit.removeClass(hidden);
    } else {
        divBotaoSubmit.addClass(hidden);
    }
}

function ajustaStringTelaPequena(string) {
    var stringAjustada = '';
    stringAjustada = string.substr(0, 20) + '...';
    return stringAjustada;
}

function botaoVoltarSelecionarAluno() {
    $('#divIncluirResponsavel').addClass(hidden);
    $('#divPassoAPasso').addClass(hidden);
    $('#divConfirmacao').removeClass(hidden);
}

function botaoVoltarDataNascimentoCPF() {
    $('#divCPFDataNascimento').addClass(hidden);
    $('#divSelecionarAluno').removeClass(hidden);
    $('#divDadosSelecionados').addClass(hidden);
    $('#spanMensagens').addClass(hidden);
    $('#divSpanResponsavelCPF').addClass(hidden);
    $('#Dia').val(0);
    $('#Mes').val(0);
    $('#Ano').val(0);
    $('#botaoCPFLiberado').addClass(hidden);
    $('#botaoPasso2').removeClass(btnPrimary).addClass(btnDefault);
}

function botaoVoltarEmail() {
    $('#divCPFDataNascimento').removeClass(hidden);
    $('#divEmail').addClass(hidden);
    $('#botaoCPFLiberado').addClass(hidden);
    $('#spanMensagens').addClass(hidden);
    $('#divSpanResponsavelEmail').addClass(hidden);
    $('#email').val('');
    $('#repetirEmail').val('');
    $('#botaoEmailLiberado').addClass(hidden);
    $('#divSpanResponsavelCPF').addClass(hidden);
    $('#botaoPasso3').removeClass(btnPrimary).addClass(btnDefault);
}

function botaoVoltarHierarquia() {
    $('#divEmail').removeClass(hidden);
    $('#divHierarquia').addClass(hidden);
    $('#botaoEmailLiberado').addClass(hidden);
    $('#spanMensagens').addClass(hidden);
    $('#hierarquia').val(0);
    $('#botaoHierarquiaSelecionada').addClass(hidden);
    $('#divSpanResponsavelEmail').addClass(hidden);
    $('#botaoPasso4').removeClass(btnPrimary).addClass(btnDefault);
}
