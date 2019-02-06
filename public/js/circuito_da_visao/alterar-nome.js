/**
 * Nome: alterar-nome.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para alterar nome da pessoa
 */
 function alterarPessoa(dados) {
  funcaoCircuito('lancamentoAlterarPessoa', dados);
 }

 function ajustarPessoa(dados) {
  funcaoCircuito('lancamentoAjustarPessoa', dados);
 }
 
 $(document).ready(function(){
     $('[data-toggle="popover"]').popover();
 });

 function removerPessoa(dados){
   var confirmacao = confirm('Tem certeza que deseja remover essa pessoa?');
   if(confirmacao){
     mostrarSplash();
     funcaoCircuito('lancamentoRemoverPessoa', dados);
   }
 }
function alterarNome(idPessoa) {
    let inputNome = $('#nome_' + idPessoa);
    let spanNome = $('#span_nome_' + idPessoa);
    let spanNomeLg = $('#span_nome_lg_' + idPessoa);
    let dropDown = $('#menudrop_' + idPessoa);

    let reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/;
    if (!reg.exec(inputNome.val())) {
        alert('Nome inválido');
    } else {
        $.post(
                "/lancamentoAlterarNome",
                {
                    idPessoa: idPessoa,
                    nome: inputNome.val()
                },
                function (data) {
                    if (data.response) {
                        spanNome.html(data.nomeAjustado);
                        spanNomeLg.html(data.nome);
                        inputNome.val(data.nome);
                        dropDown.dropdown('toggle');
                    }
                }, 'json');
    }
}

function alterarTelefone(idPessoa) {
    let input = $('#telefone_' + idPessoa);
    let spanTelefone = $('#span_telefone_' + idPessoa);
    let dropDown = $('#menudrop_telefone_' + idPessoa);
    let linkWhatsapp = $('#linkWhatsapp_' + idPessoa);

    if (input.val().length != 11){
        alert('Telefone inválido');
    } else {
        $.post(
                "/lancamentoAlterarTelefone",
                {
                    idPessoa: idPessoa,
                    telefone: input.val()
                },
                function (data) {
                    if (data.response) {
                        spanTelefone.html(data.telefone);
                        input.val(data.telefone);
                        dropDown.dropdown('toggle');
                        linkWhatsapp.attr('href', 'https://api.whatsapp.com/send?phone=55'+data.telefone);
                    }
                }, 'json');
    }
}

function filtrar(){
	let elementoFiltro = document.getElementById('filtro')
	let elementos = document.getElementsByClassName('pessoa')

	for(let elemento of elementos){
		if(!elemento.getAttribute('name').includes(elementoFiltro.value.toUpperCase())){
			elemento.classList.add('hidden')
		}else{
			elemento.classList.remove('hidden')
		}
	}
}
