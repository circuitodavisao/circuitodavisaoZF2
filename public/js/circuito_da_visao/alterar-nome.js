/**
 * Nome: alterar-nome.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para alterar nome da pessoa
 */


function alterarNome(idPessoa) {
    var inputNome = $('#nome_' + idPessoa);
    var spanNome = $('#span_nome_' + idPessoa);
    var spanNomeLg = $('#span_nome_lg_' + idPessoa);
    var dropDown = $('#menudrop_' + idPessoa);

    var reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/;
    if (!reg.exec(inputNome.val())) {
        alert('Nome inválido');
    } else {
        $.post(
                "/lancamentoAlterarNome",
                {
                    idPessoa: idPessoa,
                    nome: inputNome.val()
                },
                function (data) {
                    if (data.response) {
                        spanNome.html(data.nomeAjustado);
                        spanNomeLg.html(data.nome);
                        inputNome.val(data.nome);
                        dropDown.dropdown('toggle');
                    }
                }, 'json');
    }
}