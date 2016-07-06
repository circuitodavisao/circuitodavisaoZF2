/**
 * Nome: verificar-orientacao-tela-lancamento.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para fechar modal caso a tela seja virada
 */
function verificarMuitosCadastros(validacao, abaSelecionada) {
    if (validacao == 1) {
        $.magnificPopup.open({
            removalDelay: 500, //delay removal by X to allow out-animation,
            items: {
                src: "#ModalMuitosCadastros"
            },
            modal: true,
            callbacks: {
                beforeOpen: function (e) {
                    var Animation = "mfp-zoomIn";
                    this.st.mainClass = Animation;
                }
            },
            midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
        });
    } else {
        location.href = '/lancamentoCadastrarPessoa/' + abaSelecionada;
    }
}


