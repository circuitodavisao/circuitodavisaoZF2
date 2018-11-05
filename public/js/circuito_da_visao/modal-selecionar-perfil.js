/**
 * Nome: modal-selecionar-perfil.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para abrir o modal com loader
 */

function abrirModal(idIcone, id, acao) {
    mostrarSplash();
    var url = '/' + acao;
    $.post(
            url,
            {
                id: id,
            },
            function (data) {
                if (data.response) {
                    location.href = '/principal';
                }
            }, 'json');

}
