/**
 * Nome: buscar-cep-logradouro.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para buscar endereço por cep ou logradouro
 */
var hidden = 'hidden';
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

function buscarEndereco() {
    var cep_logradouro = $('#cep_logradouro').val();
    $('#endereco').addClass(hidden);
    var spanMensagemDeErro = $('#spanMensagemDeErro');
    var divMensagens = $('#divMensagens');

    if (cep_logradouro.length === 0) {
        spanMensagemDeErro
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html('Preencha o CEP')
                .removeClass(hidden);
        divMensagens
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html('Preencha o CEP')
                .removeClass(hidden);
        return false;
    }
    if (!isNumber(cep_logradouro) || cep_logradouro.length !== 8) {
        spanMensagemDeErro
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html('Cep é invalido!')
                .removeClass(hidden);
        divMensagens
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html('Cep é invalido!')
                .removeClass(hidden);
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

    spanMensagemDeErro
            .addClass(hidden);
    divMensagens
            .addClass(hidden);
    $.post(
            "/cadastroBuscarEndereco",
            {
                cep_logradouro: cep_logradouro,
            },
            function (data) {
                $('#endereco').addClass(hidden);
                $('#resultadoBusca').html('');
                if (data.quantidadeDeResultados === 0) {
                    var html = '';
                    html += '<div class="col-md-12 alert alert-danger">';
                    html += 'No results found';
                    html += '</div>';
                    $('#botaoContinuar').attr({disabled: "disabled"});
                    $('#resultadoBusca').html(html);
                    spanMensagemDeErro
                            .removeClass('alert-success')
                            .addClass('alert-danger')
                            .html('CEP não encontrado');
                    divMensagens
                            .removeClass('alert-success')
                            .addClass('alert-danger')
                            .html('CEP não encontrado');
                }
                if (data.quantidadeDeResultados === 1) {
                    $('#endereco').removeClass(hidden);
                    $('#cep_logradouro').html(data.pesquisa[0]['cep']);
                    $('#uf').val(data.pesquisa[0]['uf']);
                    $('#cidade').val(data.pesquisa[0]['cidade']);
                    $('#bairro').val(data.pesquisa[0]['bairro']);
                    $('#logradouro').val(data.pesquisa[0]['logradouro']);
                    $('#hiddenuf').val(data.pesquisa[0]['uf']);
                    $('#hiddencidade').val(data.pesquisa[0]['cidade']);
                    $('#hiddenbairro').val(data.pesquisa[0]['bairro']);
                    $('#hiddenlogradouro').val(data.pesquisa[0]['logradouro']);
                    $('#botaoContinuar').removeAttr("disabled");
                    $('#complemento').focus();
                    spanMensagemDeErro.addClass(hidden);
                    divMensagens.addClass(hidden);
                    $('#divBotaoBuscarCep').addClass(hidden);
                    $('#divBotaoCepContinuar').removeClass(hidden);
                }
            }
    , 'json');
}