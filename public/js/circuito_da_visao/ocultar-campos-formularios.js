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