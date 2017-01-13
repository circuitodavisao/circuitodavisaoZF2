
var btnDefault = 'btn-default';
var btnPrimary = 'btn-primary';

function voltarListagemCultos() {
    abrirModalCarregando();
    location.href = "/cadastroCultos";
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
    $('#botaoPasso3')
            .addClass(btnDefault)
            .removeClass(btnPrimary);
}

function retornaDiaDaSemanaPorExtenso(diaDaSemana) {
    var diaDaSemanaPorExtenso;
    var valorInteiro = parseInt(diaDaSemana);
    switch (valorInteiro) {
        case 1:
            diaDaSemanaPorExtenso = 'SUNDAY';
            break;
        case 2:
            diaDaSemanaPorExtenso = 'MONDAY';
            break;
        case 3:
            diaDaSemanaPorExtenso = 'TUESDAY';
            break;
        case 4:
            diaDaSemanaPorExtenso = 'WEDNESDAY';
            break;
        case 5:
            diaDaSemanaPorExtenso = 'THURSDAY';
            break;
        case 6:
            diaDaSemanaPorExtenso = 'FRIDAY';
            break;
        case 7:
            diaDaSemanaPorExtenso = 'SATURDAY';
            break;
    }
    return diaDaSemanaPorExtenso;
}

function submeter(form) {
    abrirModalCarregando();
    form.submit();
}