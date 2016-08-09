/**
 * Nome: validar-formulario-cadastrar-pessoa-lancamento.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> & Sergio Bezerra da Silva <sergio.silva.unb@gmail.com>
 * Descricao: Função para validar os campos do formulário de cadastro de pessoas na linha de lançamento
 */
jQuery(document).ready(function () {



    $("#CadastrarGrupoPessoaForm").validate({
        /* @validation states + elements 
         ------------------------------------------- */

        errorClass: "state-error",
        validClass: "state-success",
        errorElement: "em",
        /* @validation rules 
         ------------------------------------------ */

        rules: {
            estado_civil: {
                required: true
            },
            cep: {
                required: true,
                minlength: 8,
                number: true
            },
            bairro: {
                required: true
            },
            uf: {
                required: true
            },
            cidade: {
                required: true
            },
            logradouro: {
                required: true
            },
            complemento: {
                required: true
            },
            cpf: {
                required: true,
                minlength: 11
            },
            sexo: {
                required: true
            },
            nome_completo: {
                required: true,
                minlength: 3,
            },
            data_nascimento: {
                required: true,
                number: true
            },
             
        },
        /* @validation error messages 
         ---------------------------------------------- */

        messages: {
            estado_civil: {
                required: 'Escolha pelo 1 estado civil',
            },
            cep: {
                required: 'Informe o CEP',
                minlength: 'No mínimo 8 dígitos',
                number: 'Somente numeros',      
            },
            bairro: {
                required: 'Informe um Bairro',
            },
            uf: {
                required: 'Selecione uma UF'
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