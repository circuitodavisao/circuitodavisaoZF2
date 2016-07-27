/* 
 * Gerando filtro de campos
 */

$(document).ready(function(){
  $("#hidealuno").hide();
  $("#hidedados").hide();

  $("#campos_casado").click(function(){
    $("#hidealuno").show();
    $("#hidedados").hide();
  });

  $("#campos_solteiro").click(function(){
    $("#hidealuno").hide();
    $("#hidedados").show();
  });

});
