/**
 * Nome: selecionar-lider-revisao.js
 * @author Lucas Filipe de Carvalho cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Função para Dual List Box
 */


     // Dual List Plugin Init
    var demo1 = $('.demo1').bootstrapDualListbox({
      nonSelectedListLabel: 'Opções',
      selectedListLabel: 'Selecionados',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: false
    });

    $("#demoform").submit(function() {
      alert("Options Selected: " + $('.demo1').val());
      return false;
    }); 

function confirmar(form){
    var divMensagens = $('#divMensagens');
    divMensagens
                .addClass('hidden');
        form.submit();
//        alert("Options Selected: " + $('.demo1').val());
//      return false;
}
