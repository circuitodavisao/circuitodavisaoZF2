
function mostrarMotivos() {
    $('#divMotivo').toggleClass('hidden');
    $('#divBotaoVoltar').toggleClass('hidden');
}

function validarExclusao(idAula) {
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
                        mostrarSplash();
                        divMensagens
                                .addClass('hidden');
                        funcaoCircuito('cursoAulaExcluir', idAula);
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
