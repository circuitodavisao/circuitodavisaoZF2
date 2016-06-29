/**
 * Nome: alterar-nome.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para alterar nome da pessoa
 */


function removerPessoa(idPessoaGrupo) {

    var tr = $('#tr_' + idPessoaGrupo);
    $.post(
            "/lancamentoRemoverPessoa",
            {
                idPessoaGrupo: idPessoaGrupo,
            },
            function (data) {
                if (data.response) {
                    tr.css('background-color', '#DDDDDD')
                }
            }, 'json');
}