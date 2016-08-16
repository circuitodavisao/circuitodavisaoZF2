/**
 * Nome: buscar-cep-logradouro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para buscar endereço por cep ou logradouro
 */

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}
function submitEnter(myfield, e) {
    var keycode;
    if (window.event)
        keycode = window.event.keyCode;
    else if (e)
        keycode = e.which;
    else
        return true;
    if (keycode == 13) {
        buscarEndereco($('#cep_logradouro').val());
        return false;
    } else
        return true;
}

function buscarEndereco(cep_logradouro) {
    $('#endereco').addClass('hidden');
    $('#loadercep_logradouro').removeClass('hidden');
    if (cep_logradouro.length === 0) {
        $('#loadercep_logradouro').addClass('hidden');
        $('#submit').attr({disabled: "disabled"});
        return false;
    }
    if (!isNumber(cep_logradouro) || cep_logradouro.length !== 8) {
        $('#loadercep_logradouro').addClass('hidden');
        $('#submit').attr({disabled: "disabled"});
        alert('Cep é inhtmlido!');

        return false;
    }

    $('#resultadoBusca').html('');
    $('#uf').val('');
    $('#cidade').val('');
    $('#bairro').val('');
    $('#logradouro').val('');
    $('#hiddenuf').val('');
    $('#hiddencidade').val('');
    $('#hiddenbairro').val('');
    $('#hiddenlogradouro').val('');
    $.post(
            "/cadastroBuscarEndereco",
            {
                cep_logradouro: cep_logradouro,
            },
            function (data) {
                $('#endereco').addClass('hidden');
                $('#resultadoBusca').html('');
                if (data.quantidadeDeResultados === 0) {
                    var html = '';
                    html += '<div class="col-md-12 alert alert-danger">';
                    html += 'No results found';
                    html += '</div>';
                    $('#submit').attr({disabled: "disabled"});
                    $('#resultadoBusca').html(html);
                }
                if (data.quantidadeDeResultados === 1) {
                    $('#endereco').removeClass('hidden');
                    $('#cep_logradouro').html(data.pesquisa[0]['cep']);
                    $('#uf').val(data.pesquisa[0]['uf']);
                    $('#cidade').val(data.pesquisa[0]['cidade']);
                    $('#bairro').val(data.pesquisa[0]['bairro']);
                    $('#logradouro').val(data.pesquisa[0]['logradouro']);
                    $('#hiddenuf').val(data.pesquisa[0]['uf']);
                    $('#hiddencidade').val(data.pesquisa[0]['cidade']);
                    $('#hiddenbairro').val(data.pesquisa[0]['bairro']);
                    $('#hiddenlogradouro').val(data.pesquisa[0]['logradouro']);
                    $('#submit').removeAttr("disabled");
                    var validator = $("#CelulaForm").validate();
                    validator.element("#cep_logradouro");
                    $('#complemento').focus();
                }
                $('#loadercep_logradouro').addClass('hidden');
            }, 'json');
}