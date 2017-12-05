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

function mudarPeriodo() {
    var url = document.URL;

    var periodoInicial = $('#periodoInicial').val();
    var periodoFinal = $('#periodoFinal').val();

    var explodeURL = url.split("/");

    var novaURL = explodeURL[0] + '//' + explodeURL[2] + '/' + explodeURL[3] + '/' + explodeURL[4] + '/' + periodoInicial + '/' + periodoFinal;

    location.href = novaURL;
}

function mostrarSplash() {
    $('.splash').css('display', 'block');
}
