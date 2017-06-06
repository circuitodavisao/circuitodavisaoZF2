/**
 * Nome: funcao-cadastro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para executar uma função
 */


function funcaoLancamento(funcao, id) {
    var resposta = true;
 
    if (resposta) {
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
}