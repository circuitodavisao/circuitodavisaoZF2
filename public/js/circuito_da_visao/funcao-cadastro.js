/**
 * Nome: funcao-cadastro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para executar uma função
 */


function funcaoCadastro(funcao, id) {
    var resposta = true;

    if (resposta) {
        $.post(
                '/cadastroFuncoes',
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

function funcaoSelecionarAlunosTurma(idTurma, idRevisao) {
    var resposta = true;

    if (resposta) {
        if (resposta) {
            $.post(
                    '/cadastroFuncoesSelecionarAlunos',
                    {
                        idTurma: idTurma,
                        idRevisao: idRevisao 
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
}