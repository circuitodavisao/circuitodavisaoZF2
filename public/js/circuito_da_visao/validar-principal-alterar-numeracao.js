
function validarPrincipalAlterarNumeracao(form) {
    let numeracao = $('#numeracao').val();

    let temErro = false;
    let divMensagens = $('#divMensagens');
    let mensagem = '';

    if (numeracao == 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Numeracao';
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
