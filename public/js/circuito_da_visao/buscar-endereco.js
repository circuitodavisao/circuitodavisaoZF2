/**
 * Nome: buscar-cep-logradouro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para buscar endereço por cep ou logradouro
 */

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function buscarEndereco(cep_logradouro) {

    if (cep_logradouro.length === 0) {
        return false;
    }
    if (!isNumber(cep_logradouro) || cep_logradouro.length !== 8) {
        alert('Cep é invalido!');
        return false;
    }

    $('#resultadoBusca').html('');
    $('#uf').val('');
    $('#cidade').val('');
    $('#bairro').val('');
    $('#logradouro').val('');
    $.post(
            "/cadastroBuscarEndereco",
            {
                cep_logradouro: cep_logradouro,
            },
            function (data) {

                var html = '';
                $('#resultadoBusca').html('');
                if (data.quantidadeDeResultados === 0) {
                    html += '<div class="col-md-12 alert alert-danger">';
                    html += 'No results found';
                    html += '</div>';
                    $('#submit').attr({disabled: "disabled"});
                }
                if (data.quantidadeDeResultados === 1) {
                    $('#cep_logradouro').val(data.pesquisa[0]['cep']);
                    $('#uf').val(data.pesquisa[0]['uf']);
                    $('#cidade').val(data.pesquisa[0]['cidade']);
                    $('#bairro').val(data.pesquisa[0]['bairro']);
                    $('#logradouro').val(data.pesquisa[0]['logradouro']);
                    $('#submit').removeAttr("disabled");
                }
                if (data.quantidadeDeResultados > 1) {
                    $('#submit').attr({disabled: "disabled"});
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
                        var dadosDoEnderecoCep = data.pesquisa[indiceDeResultados]['cep'];
                        var dadosDoEnderecoUf = data.pesquisa[indiceDeResultados]['uf'];
                        var dadosDoEnderecoCidade = data.pesquisa[indiceDeResultados]['cidade'];
                        var dadosDoEnderecoBairro = data.pesquisa[indiceDeResultados]['bairro'];
                        var dadosDoEnderecoLogradouro = data.pesquisa[indiceDeResultados]['logradouro'];
                        var dadosDoEndereco = dadosDoEnderecoCep + ', \'' + dadosDoEnderecoUf + '\', \'' + dadosDoEnderecoCidade + '\', \'' + dadosDoEnderecoBairro + '\', \'' + dadosDoEnderecoLogradouro + '\'';
                        html += '<tr>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['cep'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['uf'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['cidade'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['bairro'] + '</td>';
                        html += '<td>' + data.pesquisa[indiceDeResultados]['logradouro'] + '</td>';
                        html += '<td><button type="button" class="btn btn-success btn-xs" onclick="selecionarEndereco(' + dadosDoEndereco + ');">Incluir</button></td>';
                        html += '</tr>';
                    }
                    html += '</tbody>';
                    html += '</table">';
                }
                $('#resultadoBusca').html(html);
            }, 'json');
}


function selecionarEndereco(cep, uf, cidade, bairro, logradouro) {
    $('#resultadoBusca').html('');
    $('#cep_logradouro').val(cep);
    $('#uf').val(uf);
    $('#cidade').val(cidade);
    $('#bairro').val(bairro);
    $('#logradouro').val(logradouro);
    $('#submit').removeAttr("disabled");
}