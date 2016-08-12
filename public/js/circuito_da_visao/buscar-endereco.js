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
                var html;
                if (data.quantidadeDeResultados === 0) {
                    html += '<div class="alert alert-danger">';
                    html += 'No results found';
                    html += '</div>';
                }
                if (data.quantidadeDeResultados === 1) {
                    $('#cep_logradouro').html(data.pesquisa[0]['cep']);
                    $('#uf').html(data.pesquisa[0]['uf']);
                    $('#cidade').html(data.pesquisa[0]['cidade']);
                    $('#bairro').html(data.pesquisa[0]['bairro']);
                    $('#logradouro').html(data.pesquisa[0]['logradouro']);
                }
                if (data.quantidadeDeResultados > 1) {
                    html += '<table id="tabelaResultadosBuscaCepLogradouro" class="table table-condesed">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th>CEP</th>';
                    html += '<th>UF</th>';
                    html += '<th>Cidade</th>';
                    html += '<th>Bairro</th>';
                    html += '<th>Logradouro</th>';
                    html += '<th></th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody>';
                    var indiceDeResultados;
                    for (indiceDeResultados = 0; indiceDeResultados < data.quantidadeDeResultados; indiceDeResultados++) {
                        html += '<tr>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['cep'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['uf'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['cidade'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['bairro'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['logradouro'] + '</td>';
                        html += '<td><button type="button" class="btn btn-success btn-xs" onclick="selecionarEndereco(' + data.pesquisa[indiceDeResultados] + ');">Incluir</button></td>';
                        html += '</tr>';
                    }
                    html += '</tbody>';
                    html += '</table">';
                }
                $('#resultadoBusca').html(html);
            }, 'json');
}


function selecionarEndereco(pesquisa) {
    $('#cep_logradouro').html(pesquisa['cep']);
    $('#uf').html(pesquisa['uf']);
    $('#cidade').html(pesquisa['cidade']);
    $('#bairro').html(pesquisa['bairro']);
    $('#logradouro').html(pesquisa['logradouro']);
}