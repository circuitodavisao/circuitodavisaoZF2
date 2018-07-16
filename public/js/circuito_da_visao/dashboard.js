function buscarDados() {
    $.post(
            "/principalDashboard",
            {},
            (data) => {
        if (data.response) {
            /* Loader */
            $('#divProgressBar').css('width', '100%')
            $('#divProgressBar').html('100%')
            $('#loader').addClass('hidden')
            $('#divDados').removeClass('hidden')
            /* Dados principais */
            $('#spanNomeDeQuemEstaLogado').html(data.spanNomeDeQuemEstaLogado)
            $('#fotoPerfil').attr('src', '/img/fotos/' + data.fotoPerfil)
            $('#divModalDadosPrincipais').html(data.divModalDadosPrincipais)
            $('#classePessoal').addClass('label-' + data.classePessoalCor)
            $('#classePessoal').html(data.classePessoalString)
            $('#classeEquipe').addClass('label-' + data.classeEquipeCor)
            $('#classeEquipe').html(data.classeEquipeString)
            /* Circuito me ajuda */
            $('#divTabelaCircuitoMeAjuda').html(data.divTabelaCircuitoMeAjuda)
            /* Barras de progressos */
            $('#paragrafoBarraDeProgressoPessoalMembresia').addClass('text-' + data.corBarraDeProgressoPessoalMembresia)
            $('#paragrafoBarraDeProgressoPessoalMembresia').html(data.fraseBarraDeProgressoPessoalMembresia)
            $('#paragrafoBarraDeProgressoEquipeMembresia').addClass('text-' + data.corBarraDeProgressoEquipeMembresia)
            $('#paragrafoBarraDeProgressoEquipeMembresia').html(data.fraseBarraDeProgressoEquipeMembresia)
            $('#divBarraDeProgressoPessoalMembresia').html(data.divBarraDeProgressoPessoalMembresia)
            $('#divBarraDeProgressoEquipeMembresia').html(data.divBarraDeProgressoEquipeMembresia)
            $('#divDadosMembresia').html(data.divDadosMembresia)
            $('#paragrafoBarraDeProgressoPessoalCelula').addClass('text-' + data.corBarraDeProgressoPessoalCelula)
            $('#paragrafoBarraDeProgressoPessoalCelula').html(data.fraseBarraDeProgressoPessoalCelula)
            $('#paragrafoBarraDeProgressoEquipeCelula').addClass('text-' + data.corBarraDeProgressoEquipeCelula)
            $('#paragrafoBarraDeProgressoEquipeCelula').html(data.fraseBarraDeProgressoEquipeCelula)
            $('#divBarraDeProgressoPessoalCelula').html(data.divBarraDeProgressoPessoalCelula)
            $('#divBarraDeProgressoEquipeCelula').html(data.divBarraDeProgressoEquipeCelula)
            $('#divDadosCelula').html(data.divDadosCelula)
            $('#divDadosProximoNivel').html(data.divDadosProximoNivel)
        }
    }
    , 'json');
    setTimeout(() => {
        $('#divProgressBar').css('width', '25%')
        $('#divProgressBar').html('25%')
    }, 1000);
    setTimeout(() => {
        $('#divProgressBar').css('width', '50%')
        $('#divProgressBar').html('50%')
    }, 2000);
    
    for (let i = 51; i < 85; i++) {
        setTimeout(() => {
            $('#divProgressBar').css('width', i + '%')
            $('#divProgressBar').html(i + '%')
        }, (i * 200));
    }
    for (let i = 86; i < 100; i++) {
        setTimeout(() => {
            $('#divProgressBar').css('width', i + '%')
            $('#divProgressBar').html(i + '%')
        }, (i * 250));
    }
}

document.addEventListener('DOMContentLoad', buscarDados(), false)

function mostrarModalClasse() {
    $.magnificPopup.open({
        removalDelay: 500, //delay removal by X to allow out-animation,
        items: {
            src: "#modalClassificacao"
        },
        modal: true,
        callbacks: {
            beforeOpen: function (e) {
                var Animation = "mfp-zoomIn";
                this.st.mainClass = Animation;
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    })
}
