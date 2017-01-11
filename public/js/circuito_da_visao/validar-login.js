/**
 * Nome: validarLogin.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para validar o login e por mensagem estatica na tela
 */

function validarLogin() {
    var usuario = $('#usuario').val();
    var senha = $('#senha').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');

    if (usuario.length === 0) {
        temErro = true;
    }
    if (senha.length === 0) {
        temErro = true;
    }

    if (temErro) {
        divMensagens
                .html('Preencha o usuário e senha')
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        abrirModalCarregando();
        divMensagens
                .addClass('hidden');
        $('#LoginForm').submit();
    }
}