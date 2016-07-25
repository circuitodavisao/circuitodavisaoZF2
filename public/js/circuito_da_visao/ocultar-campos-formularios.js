/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showDiv(div){
    document.getElementById("plano1").className = "invisivel";
    document.getElementById("plano2").className = "invisivel";
    document.getElementById("plano3").className = "invisivel";

    document.getElementById(div).className = "visivel";
}

