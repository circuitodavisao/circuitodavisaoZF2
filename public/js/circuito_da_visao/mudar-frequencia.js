/**
 * Nome: mudar-frequencia.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Função para lançar frequência de evento
 */


function mudarFrequencia(checkbox) {
    var valor = 'N';
    if (checkbox.checked) {
        valor = "S";
    }
    $.post(
            "/lancamentoMudarFrequencia",
            {
                valor: valor,
                checkbox: checkbox.id,
            },
            function (data) {
                if (data.response) {
                    alert(4);
                } else {
                    alert(5);
                }
            }, 'json');
}