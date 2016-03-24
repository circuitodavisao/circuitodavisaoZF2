/*! circuito-mascaras_e_validacoes.js - v0.1
 * http://circuitodavisao.com.br
 * Copyright (c) 2016 Circuito da Visão;*/

$(document).ready(function(){
  $(".campo-dica").hide();

  $(".mostrar-dica").click(function(){
    $(".campo-dica").fadeIn("slow");
  });

  $(".nao-mostrar-dica").click(function(){
    $(".campo-dica").fadeOut("slow");
  });
});
