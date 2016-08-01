/**
 * Nome: funcao-pessoa-lancamento.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para executar uma função
 */


function funcaoPessoa(funcao, id) {

    $.post(
            '/lancamentoFuncoes',
            {
                id: id,
                funcao: funcao
            },
    function (data) {
        if (data.response) {
            if (data.tipoDeRetorno === 1) {
                location.href = data.url;
            }
        }
    }, 'json');

}