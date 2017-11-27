/**
 * Nome: funcao-curso.js
 * @author Lucas Filipe de Carvalho Cunha  <lucascarvalho.esw@gmail.com>
 * Descricao: Função para executar uma função
 */


function funcaoCurso(funcao, id) {
    var resposta = true;

    if (resposta) {
        $.post(
                '/cursoFuncoes',
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
