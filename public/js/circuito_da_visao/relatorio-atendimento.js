function abrir144(idGrupo12) {
    var div144 = $('#grupos144' + idGrupo12);
    var botaoMais = $('#divBotaoVer' + idGrupo12);
    var botaoMenos = $('#divBotaoEsconder' + idGrupo12);
    div144.removeClass('hidden');
    botaoMais.addClass('hidden');
    botaoMenos.removeClass('hidden');
}
function fechar144(idGrupo12) {
    var div144 = $('#grupos144' + idGrupo12);
    var botaoMenos = $('#divBotaoEsconder' + idGrupo12);
    var botaoMais = $('#divBotaoVer' + idGrupo12);
    div144.addClass('hidden');
    botaoMenos.addClass('hidden');
    botaoMais.removeClass('hidden');
}