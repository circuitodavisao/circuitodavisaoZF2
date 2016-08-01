/* 
 * Gerando filtro de campos
 */

$(document).ready(function(){
    $("#ocultar_aluno").hide();
    $("#ocultar_dados").hide();

    $("#campos_casado").click(function(){
        $("#ocultar_aluno").show();
        $("#ocultar_dados").hide();
    });

    $("#campos_solteiro").click(function(){
        $("#ocultar_aluno").hide();
        $("#ocultar_dados").show();
    });
  
  
    $("#tipo").change(function(){

    var opSelected = $("#tipo option:selected").val();

    if(opSelected == 0 || opSelected == 1){
        $("#ocultar_checkbox").hide();
    }else{
        $("#ocultar_checkbox").show();
    }
 
  });
});


function validar_cadastro_pessoa(CadastrarPessoaForm) {
    var msg_erro = "";
    var tipo = CadastrarPessoaForm.tipo.value;
    
    //NÃO SEI O QUE QUER DIZER TIPO
    //Verificar se o campo [NOME] esta vazio
    if (tipo == "A"){
        if (CadastrarPessoaForm.nome.value == "") {
            if (msg_erro == "") {
                msg_erro = "Nome da Pessoa";
            } else {
                msg_erro += ", Nome da Pessoa";
            }
        }

        if (CadastrarPessoaForm.telefone.value == "") {
            if (msg_erro == "") {
                msg_erro = "Telefone da Pessoa";
            } else {
                msg_erro += ", Telefone da Pessoa";
            }
        }
    }
    
    //NÃO ENTENDI ESSE CÓDIGO :/
    if (msg_erro == "") {
        $('#botaoAlterar').css('display', 'none');
        $('#loader').css('display', 'block');
        f.submit();
    } else {
        alert("Preencha o(s) seguinte(s) campo(s): " + msg_erro);
        return 'false';
    }
}