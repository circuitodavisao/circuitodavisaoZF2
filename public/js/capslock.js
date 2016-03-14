/**
 * Nome: index.phtml
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> e Sergio Bezerra da Silva <sergio.silva.unb@gmail.com>
 * Descricao: Função para verificar se o capslock está ativado
 */

function capsLock(e) {
    kc = e.keyCode ? e.keyCode : e.which;
    sk = e.shiftKey ? e.shiftKey : ((kc == 16) ? true : false);
    if (((kc >= 65 && kc <= 90) && !sk) || ((kc >= 97 && kc <= 122) && sk))
        document.getElementById('divCapslock').className = 'alert alert-info';
    else
        document.getElementById('divCapslock').className = 'alert alert-info hidden';
}