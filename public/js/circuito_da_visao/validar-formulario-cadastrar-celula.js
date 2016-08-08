/**
 * Nome: validar-formulario-cadastrar-pessoa-lancamento.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> & Sergio Bezerra da Silva <sergio.silva.unb@gmail.com>
 * Descricao: Função para validar os campos do formulário de cadastro de pessoas na linha de lançamento
 */
jQuery(document).ready(function () {



    $("#CadastroCelula").validate({
        /* @validation states + elements 
         ------------------------------------------- */

        errorClass: "state-error",
        validClass: "state-success",
        errorElement: "em",
        /* @validation rules 
         ------------------------------------------ */

        rules: {
            dia: {
                required: true
            },
            hora: {
                required: true
            },
            cep: {
                required: true
            },
            nome_hospedeiro: {
                required: true
            },
            ddd: {
                required: true
            },
            telefone: {
                required: true,
                minlength: 8,
                maxlength: 9,
                number: true
            }
        },
        /* @validation error messages 
         ---------------------------------------------- */

        messages: {
            dia: {
                required: 'Infome o dia de sua célula'
            },
            hora: {
                required: 'Infome o horário de sua célula'
            },
            cep: {
                required: 'Informe o CEP ou parte do seu logradouro'
            },
            nome_hospedeiro: {
                required: 'Diga o nome do hospedeiro onde será sua célula'
            },
            ddd: {
                required: 'Informe o DDD da sua cidade, é obrigatório'
            },
            telefone: {
                required: 'O telefone também é obrigatório!',
                minlength: 'No mínimo 8 caracteres no telefone por favor!',
                maxlength: 'Pronto, já temos 9 caracteres!',
                number: 'Neste campo somente números!'
            }
        },
        /* @validation highlighting + error placement  
         ---------------------------------------------------- */

        highlight: function (element, errorClass, validClass) {
            $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.field').removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio") || element.is(":checkbox")) {
                element.closest('.option-group').after(error);
            } else {
                error.insertAfter(element.parent());
            }
        }

    });
});