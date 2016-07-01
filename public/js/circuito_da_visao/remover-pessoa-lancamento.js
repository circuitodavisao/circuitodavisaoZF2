/**
 * Nome: alterar-nome.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para alterar nome da pessoa
 */


function removerPessoa(idGrupoPessoa) {

    var tr = $('#tr_' + idGrupoPessoa);
    $.post(
            "/lancamentoRemoverPessoa",
            {
                idGrupoPessoa: idGrupoPessoa,
            },
            function (data) {
                if (data.response) {
                    tr.css('background-color', '#DDDDDD')
                }
            }, 'json');
}