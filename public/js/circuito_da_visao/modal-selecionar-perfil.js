/**
 * Nome: modal-selecionar-perfil.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para abrir o modal com loader
 */

function abrirModal(idIcone, idResponsabilidade, acao) {
    $.magnificPopup.open({
        removalDelay: 500, //delay removal by X to allow out-animation,
        items: {
            src: "#" + idIcone
        },
        modal: true,
        // overflowY: 'hidden', //
        callbacks: {
            beforeOpen: function (e) {
                var Animation = "mfp-zoomIn";
                this.st.mainClass = Animation;
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

    var url = '/' + acao;
    $.post(
            url,
            {
                id: idResponsabilidade,
            },
            function (data) {
                if (data.response) {
                    location.href = '/principal';
                }
            }, 'json');

}