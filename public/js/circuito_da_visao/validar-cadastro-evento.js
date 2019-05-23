
var btnDefault = 'btn-default';
var btnPrimary = 'btn-primary';

function voltarListagemCultos() {
    location.href = "/cadastroCultos";
}
function voltarListagemCelulas() {
    location.href = "/cadastroCelulas";
}
function voltarListagemDiscipulados() {
    location.href = "/cadastroDiscipulados";
}

function validarDiaHoraEMinuto() {
    var dia_da_semana = $('#dia_da_semana').val();
    var hora = $('#hora').val();
    var minutos = $('#minutos').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';

    if (minutos.length === 0) {
        temErro = true;
        mensagem = 'Selecione os minutos';
    }
    if (hora.length === 0) {
        temErro = true;
        mensagem = 'Selecione as horas';
    }

    if (dia_da_semana.length === 0) {
        temErro = true;
        mensagem = 'Selecione o dia da semana';
    }
    if (temErro) {
        divMensagens
                .html(mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        divMensagens
                .addClass('hidden');
        $('#divDiaDaSemanaHoraMinuto').addClass('hidden');
        $('#divExtras').removeClass('hidden');
        $('#botaoPasso2')
                .removeClass(btnDefault)
                .addClass(btnPrimary);
    }
}

function voltarSelecionarDiaHoraEMinuto() {
    $('#divMensagens')
            .addClass('hidden');
    $('#divDiaDaSemanaHoraMinuto').removeClass('hidden');
    $('#divExtras').addClass('hidden');
    $('#botaoPasso2')
            .addClass(btnDefault)
            .removeClass(btnPrimary);
}
function voltarEndereco() {
    $('#divMensagens')
            .addClass('hidden');
    $('#divEndereco').removeClass('hidden');
    $('#divDadosHospedeiro').addClass('hidden');
    $('#botaoPasso3')
            .addClass(btnDefault)
            .removeClass(btnPrimary);
}

function validarNome() {
    var nome = $('#nome').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';

    if (nome.length < 3) {
        temErro = true;
        mensagem = 'Nome tem que ter pelo menos 3 caracteres';
    }
    if (nome.length === 0) {
        temErro = true;
        mensagem = 'Preencha o nome do culto';
    }
    if (temErro) {
        divMensagens
                .html(mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        // Preenchendo os campos
        var valorDiaDaSemana = $('#dia_da_semana').val();
        var diaDaSemanaPorExtenso = retornaDiaDaSemanaPorExtenso(valorDiaDaSemana);
        var spanDiaHora = diaDaSemanaPorExtenso + '/' + $('#hora').val() + ':' + $('#minutos').val();
        var spanNome = $('#nome').val();
        spanNome = spanNome.toUpperCase();

        $('#spanDiaHora').html(spanDiaHora);
        $('#spanNome').html(spanNome);

        var nomeEquipesSelecionadas = '';
        var todosElementos = document.Form.elements;
        for (i = 0; i < todosElementos.length; i++) {
            if (todosElementos[i].type == "checkbox" && todosElementos[i].checked) {
                nomeEquipesSelecionadas += '<br />' + todosElementos[i].value;
            }
        }
        if (nomeEquipesSelecionadas === '') {
            nomeEquipesSelecionadas = 'Nenhuma equipe selecionada';
        }
        $('#spanEquipes').html(nomeEquipesSelecionadas);

        divMensagens
                .addClass('hidden');
        $('#divExtras').addClass('hidden');
        $('#divConfirmacao').removeClass('hidden');
        $('#botaoPasso3')
                .removeClass(btnDefault)
                .addClass(btnPrimary);
    }
}

function voltarPreencherNome() {
    $('#divMensagens')
            .addClass('hidden');
    $('#divExtras').removeClass('hidden');
    $('#divConfirmacao').addClass('hidden');
    $('#botaoPasso4')
            .addClass(btnDefault)
            .removeClass(btnPrimary);
}

function retornaDiaDaSemanaPorExtenso(diaDaSemana) {
    var diaDaSemanaPorExtenso;
    var valorInteiro = parseInt(diaDaSemana);
    switch (valorInteiro) {
        case 1:
            diaDaSemanaPorExtenso = 'DOMINGO';
            break;
        case 2:
            diaDaSemanaPorExtenso = 'SEGUNDA-FEIRA';
            break;
        case 3:
            diaDaSemanaPorExtenso = 'TERÇA-FEIRA';
            break;
        case 4:
            diaDaSemanaPorExtenso = 'QUARTA-FEIRA';
            break;
        case 5:
            diaDaSemanaPorExtenso = 'QUINTA-FEIRA';
            break;
        case 6:
            diaDaSemanaPorExtenso = 'SEXTA-FEIRA';
            break;
        case 7:
            diaDaSemanaPorExtenso = 'SÁBADO';
            break;
    }
    return diaDaSemanaPorExtenso;
}

function submeter(form) {
    mostrarSplash();
    form.submit();
}

function abrirDadosHospedeiro() {
    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';

    if ($('#cep_logradouro').val().length < 8) {
        temErro = true;
        mensagem = 'Preencha o CEP corretamente';
    }

    if (temErro) {
        divMensagens
                .html(mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        divMensagens.addClass('hidden');
        $('#divEndereco').addClass('hidden');
        $('#divDadosHospedeiro').removeClass('hidden');
        $('#botaoPasso3')
                .removeClass(btnDefault)
                .addClass(btnPrimary);
    }
}

function validarDadosHospedeiro() {
    var nome_hospedeiro = $('#nome_hospedeiro').val();
    var ddd_hospedeiro = $('#ddd_hospedeiro').val();
    var telefone_hospedeiro = $('#telefone_hospedeiro').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';

    if (telefone_hospedeiro.length < 8 || telefone_hospedeiro.length > 9) {
        temErro = true;
        mensagem = 'Telefone tem que ter de 8 a 9 caracteres';
    }
    if (telefone_hospedeiro.length === 0) {
        temErro = true;
        mensagem = 'Preencha o telefone do hospedeiro';
    }
    if (ddd_hospedeiro.length !== 2) {
        temErro = true;
        mensagem = 'DDD tem que ter pelo menos 2 caracteres';
    }
    if (ddd_hospedeiro.length === 0) {
        temErro = true;
        mensagem = 'Preencha o DDD do hospedeiro';
    }
    if (nome_hospedeiro.length > 80) {
        temErro = true;
        mensagem = 'Nome do hospedeiro pode ter no máximo 80 caracteres';
    }
    if (nome_hospedeiro.length < 3) {
        temErro = true;
        mensagem = 'Nome do hospedeiro tem que ter pelo menos 3 caracteres';
    }
    if (nome_hospedeiro.length === 0) {
        temErro = true;
        mensagem = 'Preencha o nome do hospedeiro';
    }
    if (temErro) {
        divMensagens
                .html(mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        // Preenchendo os campos
        var valorDiaDaSemana = $('#dia_da_semana').val();
        var diaDaSemanaPorExtenso = retornaDiaDaSemanaPorExtenso(valorDiaDaSemana);
        var spanDiaHora = diaDaSemanaPorExtenso + '/' + $('#hora').val() + ':' + $('#minutos').val();
        var spanNome = nome_hospedeiro;
        spanNome = spanNome.toUpperCase();
        var spanTelefone =
                '(' + $('#ddd_hospedeiro').val() + ') '
                + $('#telefone_hospedeiro').val();
        var spanLogradouroComplemento = $('#logradouro').val() + ', ' +
                $('#complemento').val();
        var spanEndereco = $('#cidade').val() + '&nbsp;-&nbsp;' +
                $('#bairro').val() + ',&nbsp;' +
                $('#uf').val() + '&nbsp; CEP:' +
                $('#cep_logradouro').val();
        $('#spanDiaHora').html(spanDiaHora);
        $('#spanNome').html(spanNome);
        $('#spanTelefone').html(spanTelefone);
        $('#spanLogradouroComplemento').html(spanLogradouroComplemento);
        $('#spanEndereco').html(spanEndereco);

        divMensagens
                .addClass('hidden');
        $('#divExtras').addClass('hidden');
        $('#divConfirmacao').removeClass('hidden');
        $('#botaoPasso4')
                .removeClass(btnDefault)
                .addClass(btnPrimary);
    }
}


function validarSenha() {
    var inputSenha = $('#senha');
    var divMensagens = $('#divMensagens');
    if (inputSenha.val().length === 0) {
        divMensagens
                .html('Preencha a senha')
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
        return false;
    } else {
        $.post(
                "/validarSenha",
                {
                    senha: inputSenha.val()
                },
                function (data) {
                    if (data.response) {
                        divMensagens
                                .addClass('hidden');
                        $('#divSenha').addClass('hidden');
                        $('#botaoSubmeter').removeClass('hidden');
                    } else {
                        divMensagens
                                .html('Senha não confere')
                                .removeClass('alert-success')
                                .removeClass('hidden')
                                .addClass('alert-danger');
                        return false;
                    }
                }, 'json');
    }

}