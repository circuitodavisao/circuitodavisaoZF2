/**
 * Nome: buscar-cep-logradouro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para buscar endereço por cep ou logradouro
 */


function buscarEndereco(cep_logradouro) {

    $.post(
            "/cadastroBuscarEndereco",
            {
                cep_logradouro: cep_logradouro,
            },
            function (data) {
                if (data.response) {
                    $('#resultadoBusca').html(data.teste);
                }
            }, 'json');
}