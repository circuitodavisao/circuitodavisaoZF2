<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\GrupoAluno;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: SolicitacaoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar de solicitação
 */
class SolicitacaoForm extends Form {

    public function __construct($name = null, $solicitacaoTipo = null) {
        parent::__construct($name);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
            Constantes::$FORM_ACTION => Constantes::$FORM_ACTION_CADASTRO_SOLICITACAO_FINALIZAR,
        ));

        /* SolicitacaoTipo */
        $arraySolicitacaoTipo = array();
        $arraySolicitacaoTipo[0] = Constantes::$TRADUCAO_SELECIONE;
        foreach ($solicitacaoTipo as $tipo) {
            $arraySolicitacaoTipo[$tipo->getId()] = $tipo->getNome();
        }

        $inputSelectTipo = new Select();
        $inputSelectTipo->setName('solicitacaoTipoId');
        $inputSelectTipo->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => 'solicitacaoTipoId',
            Constantes::$FORM_ONCHANGE => 'selecionarTipo(this.value);',
        ));
        $inputSelectTipo->setValueOptions($arraySolicitacaoTipo);
        $this->add($inputSelectTipo);
        /* objeto1 */
        $this->add(
                (new Select())
                        ->setName('objeto1')
                        ->setAttributes([
                            Constantes::$FORM_ID => 'objeto1',
                        ])
        );
        /* objeto2 */
        $this->add(
                (new Select())
                        ->setName('objeto2')
                        ->setAttributes([
                            Constantes::$FORM_ID => 'objeto2',
                        ])
        );
    }

}
