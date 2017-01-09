/**
 * Nome: alterar-nome.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para alterar nome da pessoa
 */


function alterarNome(idPessoa) {

    var botao = $('#nome_' + idPessoa);
    var spanNome = $('#span_nome_' + idPessoa);
    var dropDown = $('#menudrop_' + idPessoa);
    $.post(
            "/lancamentoAlterarNome",
            {
                idPessoa: idPessoa,
                nome: botao.val()
            },
            function (data) {
                if (data.response) {
                    spanNome.html(data.nomeAjustado);
                    dropDown.dropdown('toggle');
                }
            }, 'json');
}