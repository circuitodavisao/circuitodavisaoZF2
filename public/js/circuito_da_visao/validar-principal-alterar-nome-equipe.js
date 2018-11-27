
function validarPrincipalAlterarNomeEquipe(form) {
    let nome = $('#nome').val();

    let temErro = false;
    let divMensagens = $('#divMensagens');
    let mensagem = '';

    if (nome.trim().length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Nome';
    } else {
        let reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ0-9 ]+$/;
        if (!reg.exec(nome)) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Nome Inválido';
        }
        if (nome.length > 15) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Nome pode ter no máximo 15 caracteres';
        }
        if (nome.length < 4) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Nome precisa ter no mínimo 3 caracteres';
        }
    }

    if (temErro) {
        divMensagens
                .html('Preencha os seguintes campos corretamente: ' + mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        mostrarSplash();
        divMensagens
                .addClass('hidden');

        form.submit();
    }
}
