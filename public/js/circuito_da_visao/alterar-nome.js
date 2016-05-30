/**
 * Nome: alterar-nome.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para alterar nome da pessoa
 */


function alterarNome(idPessoa) {

    var botao = $('#nome_' + idPessoa);
    $.post(
            "/lancamentoAlterarNome",
            {
                idPessoa: idPessoa,
                nome: botao.val(),
            },
            function (data) {
                if (data.response) {
                    alert(1);
                }
            }, 'json');
}