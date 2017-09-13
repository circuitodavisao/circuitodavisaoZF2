/**
 * Nome: funcao-cadastro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para executar uma função
 */


function funcaoCircuito(funcao, id) {
    var resposta = true;
 
    if (resposta) {
        $.post(
                '/principalFuncoes',
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