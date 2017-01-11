/**
 * Nome: capslock.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para verificar se o capslock está ativado
 */

function capsLock(e) {
    kc = e.keyCode ? e.keyCode : e.which;
    sk = e.shiftKey ? e.shiftKey : ((kc == 16) ? true : false);
    if (((kc >= 65 && kc <= 90) && !sk) || ((kc >= 97 && kc <= 122) && sk)) {
        $('#divMensagens')
                .removeClass('hidden')
                .removeClass('alert-success')
                .addClass('alert-danger');
        $('#divMensagens').html('Caps Lock is on.');
    } else {
        $('#divMensagens').addClass('hidden');
    }
}