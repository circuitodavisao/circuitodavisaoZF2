<?php

namespace Cadastro\View\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Form\ConstantesForm;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TemplateFormularioRodape.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos
 */
class ListagemDeEventos extends AbstractHelper {

    private $titulo;
    private $eventos;

    public function __construct() {
        
    }

    public function __invoke($titulo, $eventos) {
        $this->setTitulo($titulo);
        $this->setEventos($eventos);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= $this->view->templateFormularioTopo($this->getTitulo());
        $html .= '<div class="panel-body bg-light">';
        if (!empty($this->getEventos())) {
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';

            /* Caso seja evento do tipo Célula */
            $tipoCelula = !empty($this->getEventos()[0]->getEvento()->verificaSeECelula());
            $tipoCulto = !empty($this->getEventos()[0]->getEvento()->verificaSeECulto());
            if ($tipoCelula) {
                $html .= '<th class="text-center">';
                $html .=$this->view->translate(ConstantesForm::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(ConstantesForm::$TRADUCAO_HORA);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .=$this->view->translate(ConstantesForm::$TRADUCAO_NOME_HOSPEDEIRO);
                $html .= '</th>';
                $html .= '<th class="text-center visible-lg visible-md visible-sm">';
                $html .=$this->view->translate(ConstantesForm::$TRADUCAO_TELEFONE_HOSPEDEIRO);
                $html .= '</th>';
                $html .= '<th class="text-center visible-lg visible-md visible-sm">';
                $html .=$this->view->translate(ConstantesForm::$TRADUCAO_LOGRADOURO);
                $html .= '</th>';
                $html .= '<th class="text-center"></th>';
            }
            if ($tipoCulto) {
                $html .= '<th class="text-center">';
                $html .=$this->view->translate(ConstantesForm::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(ConstantesForm::$TRADUCAO_HORA);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .=$this->view->translate(ConstantesForm::$TRADUCAO_NOME);
                $html .= '</th>';
                $html .= '<th class="text-center"></th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            /* Caso seja evento do tipo Célula */
            if ($tipoCelula) {
                foreach ($this->getEventos() as $ge) {
                    $evento = $ge->getEvento();
                    $celula = $evento->getEventoCelula();
                    $diaDaSemanaAjustado = FuncoesLancamento::diaDaSemanaPorDia($evento->getDia());
                    $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . ConstantesCadastro::$PAGINA_CELULA . '", ' . $celula->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . ConstantesCadastro::$PAGINA_CELULA_EXCLUSAO . '", ' . $celula->getId() . ')';
                    $html .= '<tr>';
                    $html .= '<td class="text-center">' . $diaDaSemanaAjustado . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
                    $html .= '<td class="text-center">' . $celula->getNome_hospedeiroPrimeiroNome() . '</td>';
                    $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $celula->getTelefone_hospedeiroFormatado() . '</td>';
                    $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $celula->getLogradouro() . '&nbsp;' . $celula->getComplemento() . '</td>';
                    $html .= '<td class="text-center">';
                    $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_PENCIL, ConstantesForm::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                    $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_TIMES, ConstantesForm::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            }
            if ($tipoCulto) {
                foreach ($this->getEventos() as $ge) {
                    $evento = $ge->getEvento();
                    $diaDaSemanaAjustado = FuncoesLancamento::diaDaSemanaPorDia($evento->getDia());
                    $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . ConstantesCadastro::$PAGINA_CELULA . '", ' . $evento->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . ConstantesCadastro::$PAGINA_CELULA_EXCLUSAO . '", ' . $evento->getId() . ')';
                    $html .= '<tr>';
                    $html .= '<td class="text-center">' . $diaDaSemanaAjustado . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
                    $html .= '<td class="text-center">' . $evento->getNome() . '</td>';
                    $html .= '<td class="text-center">';
                    $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_PENCIL, ConstantesForm::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                    $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_TIMES, ConstantesForm::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Eventos cadastrados!</div>';
        }
        $html .= '</div>';
        /* Fim panel-body */
        $html .= '<div class="panel-footer text-right">';
        /* Botões */
        if ($tipoCelula) {
            if (count($this->getEventos()) < 2) {
                $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . ConstantesCadastro::$PAGINA_CELULA . '", 0)';
                $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_PLUS . ' ' . $this->view->translate(ConstantesForm::$TRADUCAO_NOVA_CELULA), ConstantesForm::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            } else {
                $html .= '<div class="alert alert-micro alert-warning">';
                $html .= '<i class="fa fa-warning pr10" aria-hidden="true"></i>';
                $html .= $this->view->translate(ConstantesForm::$TRADUCAO_NUMERO_MAXIMO_CELULAS);
                $html .= '</div>';
            }
        }
        if ($tipoCulto) {
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . ConstantesCadastro::$PAGINA_EVENTO_CULTO . '", 0)';
            $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_PLUS . ' ' . $this->view->translate(ConstantesForm::$TRADUCAO_NOVO_CULTO), ConstantesForm::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
        }
        /* Fim Botões */
        $html .= '</div>';
        /* Fim panel-footer */
        $html .= $this->view->templateFormularioRodape();
        return $html;
    }

    function getEventos() {
        return $this->eventos;
    }

    function setEventos($eventos) {
        $this->eventos = $eventos;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

}
