/**
 * Nome: modal-selecionar-aba.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para abrir o modal com loader
 */

function abrirModal() {
    $.magnificPopup.open({
        removalDelay: 500, //delay removal by X to allow out-animation,
        items: {
            src: "#modalAba"
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

}