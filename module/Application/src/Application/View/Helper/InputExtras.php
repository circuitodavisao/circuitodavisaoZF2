<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Form\CelulaForm;
use Application\Form\EventoForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputExtras.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os inputs extras do formulário de eventos
 */
class InputExtras extends AbstractHelper {

    protected $form;
    protected $extra;

    public function __construct() {
        
    }

    public function __invoke($form, $extra = null) {
        $this->setForm($form);
        if ($extra) {
            $this->setExtra($extra);
        }
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        if ($this->getForm() instanceof CelulaForm) {
            /* Montar endereço */
            $html .= '<div id="divEndereco">';
            $html .= $this->view->montarEndereco($this->getForm());
            $html .= '</div>';
            /* Dados do Hospedeiro */
            $html .= '<div id="divDadosHospedeiro" class="hidden">';
            $html .= '<div class="section-divider mv40">';
            $html .= '<span>' . $this->view->translate(Constantes::$TRADUCAO_DADOS_DO_HOSPEDEIRO) . '</span>';
            $html .= '</div>';
            $html .= '<div class="section row">';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<div class="section">';
            $html .= $this->view->inputFormulario(Constantes::$TRADUCAO_NOME_HOSPEDEIRO, $this->getForm(), Constantes::$FORM_NOME_HOSPEDEIRO, Constantes::$FORM_ICONE_NOME_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="section">';
            $html .= '<div class="row">';
            $html .= '<div class="col-xs-5 col-sm-5 col-md-2">';
            $html .= $this->view->inputFormulario(Constantes::$TRADUCAO_DDD_HOSPEDEIRO, $this->getForm(), Constantes::$FORM_DDD_HOSPEDEIRO, Constantes::$FORM_ICONE_DDD_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '<div class="col-xs-7 col-sm-7 col-md-10">';
            $html .= $this->view->inputFormulario(Constantes::$TRADUCAO_TELEFONE_HOSPEDEIRO, $this->getForm(), Constantes::$FORM_TELEFONE_HOSPEDEIRO, Constantes::$FORM_ICONE_TELEFONE_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            if ($this->getForm() instanceof EventoForm) {
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<div class="section">';
                $html .= $this->view->inputFormulario(Constantes::$TRADUCAO_NOME, $this->getForm(), Constantes::$FORM_NOME, Constantes::$FORM_ICONE_NOME_HOSPEDEIRO);
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                if ($this->getExtra()) {
                    $html .= '<div class="section-divider mv40">';
                    if (count($this->getExtra())) {
                        $html .= '<span>' . $this->view->translate('Selecione as equipes que participaram desse culto') . '</span>';
                        $html .= '</div>';
                        $html .= '<div class="row">';
                        foreach ($this->getExtra() as $gpFilho) {
                            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                            $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                            $checked = '';
                            if ($this->getForm()->get(Constantes::$FORM_ID)->getValue()) {
                                if ($grupoFilho->verificaSeParticipaDoEvento($this->getForm()->get(Constantes::$FORM_ID)->getValue())) {
                                    $checked = 'checked';
                                }
                            }
                            $html .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">';
                            $html .= '<div class="section">';

                            $html .= '<label class="option">';
                            $html .= '<input type="checkbox" name="checkEquipe' . $grupoFilho->getId() . '" value="' . $entidadeFilho->infoEntidade() . '" ' . $checked . '>';
                            $html .= '<span class="checkbox"></span>';
                            $html .= $entidadeFilho->infoEntidade();
                            $html .= '</label>';

                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    } else {
                        $html .= '<span>Sem equipes cadastradas!</span>';
                    }
                    $html .= '</div>';
                }
            }
        }
        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function getExtra() {
        return $this->extra;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

}
