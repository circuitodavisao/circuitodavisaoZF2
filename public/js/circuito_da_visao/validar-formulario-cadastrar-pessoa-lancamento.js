/**
 * Nome: validar-formulario-cadastrar-pessoa-lancamento.js
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> & Sergio Bezerra da Silva <sergio.silva.unb@gmail.com>
 * Descricao: Função para validar os campos do formulário de cadastro de pessoas na linha de lançamento
 */
jQuery(document).ready(function () {



    $("#CadastrarPessoaForm").validate({
        /* @validation states + elements 
         ------------------------------------------- */

        errorClass: "state-error",
        validClass: "state-success",
        errorElement: "em",
        /* @validation rules 
         ------------------------------------------ */

        rules: {
            nome: {
                required: true,
                minlength: 3,
                maxlength: 80
            },
            ddd: {
                required: true,
                minlength: 2,
                maxlength: 2
            },
            telefone: {
                required: true,
                minlength: 8,
                maxlength: 9
            },
            tipo: {
                required: true
            }
        },
        /* @validation error messages 
         ---------------------------------------------- */

        messages: {
            nome: {
                required: 'Enter Full Name',
                minlength: 'Enter at least 3 characters or more',
                maxlength: 'Enter at 80 characters'
            },
            ddd: {
                required: 'Enter DDD',
                minlength: 'Enter at 2 numbers',
                maxlength: 'Enter at 2 numbers'
            },
            telefone: {
                required: 'Enter Phone',
                minlength: 'Enter at least 8 numbers',
                maxlength: 'Enter at 9 numbers'
            },
            tipo: {
                required: 'Choose a Type'
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