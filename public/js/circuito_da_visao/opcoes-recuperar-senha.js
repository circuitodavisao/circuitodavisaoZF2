/**
 * Nome: opcoes-recuperar-senha.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para abrir  fechar div com opções
 */

/**
 * Habilita botão para continuar
 * @returns {void}
 */
function abrirContinuar() {
    var botaoContinuar = document.getElementById('continuar');
    botaoContinuar.disabled = false;
}

/**
 * Abrir div da opção selecionada
 * @param {int} valor
 * @returns {Boolean}
 */
function abrirOpcao(valor) {
    var resposta = false;
    var divMensagens = $('#divMensagens');
    divMensagens.addClass('hidden');
    if (valor != 0) {
        var check_value = $('.opcao:checked').val();
        if (check_value == 1) {
            $('#opcoes').addClass('hidden');
            $('#opcoes_label').addClass('hidden');
            $('#opcao_1').removeClass('hidden');
            $('#opcao_2').addClass('hidden');
            $('#tipo').val(1);
        }
        if (check_value == 2) {
            $('#opcoes').addClass('hidden');
            $('#opcoes_label').addClass('hidden');
            $('#opcao_1').addClass('hidden');
            $('#opcao_2').removeClass('hidden');
            $('#tipo').val(2);
        }
    } else {
        $('#opcoes').removeClass('hidden');
        $('#opcoes_label').removeClass('hidden');
        $('#opcao_1').addClass('hidden');
        $('#opcao_2').addClass('hidden');
        $('#tipo').val(0);
        $('#divMensagemEstatica').addClass('hidden');
    }

    return resposta;
}