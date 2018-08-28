<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use DateTime;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemDePessoasComEventos extends AbstractHelper {

    private $diaDeSemanaHoje;

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $pessoas = $this->montaListagemDePessoas();

        $grupoEventoNoPeriodo = $this->view->grupo->getGrupoEventoNoPeriodo($this->view->periodo);
        if (count($grupoEventoNoPeriodo) == 0) {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem eventos cadastrados!</div>';
        } else {
            $this->setDiaDeSemanaHoje(date('N'));
            foreach ($pessoas as $pessoa) {
                $html .= $this->montaLinhaDaPessoa($pessoa, $grupoEventoNoPeriodo);
            }
        }
        return $html;
    }

    private function montaListagemDePessoas() {
        $pessoas = array();
        $pessoasGrupo = array();

        $grupoResponsabilidadesAtivas = $this->view->grupo->getResponsabilidadesAtivas();
        foreach ($grupoResponsabilidadesAtivas as $gr) {
            $p = $gr->getPessoa();
            $p->setTipo('LP');
            $pessoas[] = $p;
        }

        $grupoPessoas = $this->view->grupo->getGrupoPessoasNoPeriodo($this->view->periodo);
        if ($grupoPessoas) {
            foreach ($grupoPessoas as $grupoPessoa) {
                $pessoa = $grupoPessoa->getPessoa();
                if (empty($grupoPessoa->getNucleo_perfeito())) {
                    $pessoa->setTipo($grupoPessoa->getGrupoPessoaTipo()->getNomeSimplificado());
                } else {
                    if ($grupoPessoa->getNucleo_perfeito() == "C") {
                        $pessoa->setTipo('CO');
                    }
                    if ($grupoPessoa->getNucleo_perfeito() == "L") {
                        $pessoa->setTipo('LT');
                    }
                }
                $pessoa->setIdGrupoPessoa($grupoPessoa->getId());
                $pessoa->setAtivo($grupoPessoa->verificarSeEstaAtivo());
                if (!$pessoa->getAtivo()) {
                    $pessoa->setDataInativacao($grupoPessoa->getData_inativacaoStringPadraoBanco());
                }
                $pessoasGrupo[] = $pessoa;
            }
        }

        /* Ordenacao de pessoas */
        $valores = array();
        foreach ($pessoasGrupo as $pg) {
            $valor = 0;
            switch ($pg->getTipo()) {
                case 'VI':
                    $valor = 1;
                    break;
                case 'CO':
                    $valor = 2;
                    break;
                case 'ME':
                    $valor = 3;
                    break;
            }
            if (!$pg->getAtivo()) {
                $valor = -2;
            }
            $valores[$pg->getId()] = $valor;
        }
        $pA = array();
        $res = array();
        for ($i = 0; $i < count($pessoasGrupo); $i++) {
            for ($j = 0; $j < count($pessoasGrupo); $j++) {
                $pA[1] = $pessoasGrupo[$i];
                $pA[2] = $pessoasGrupo[$j];
                $res[1] = $valores[$pA[1]->getId()];
                $res[2] = $valores[$pA[2]->getId()];
                if ($res[1] > $res[2]) {
                    $pessoasGrupo[$i] = $pA[2];
                    $pessoasGrupo[$j] = $pA[1];
                }
            }
        }
        foreach ($pessoasGrupo as $pgA) {
            $pessoas[] = $pgA;
        }

        return $pessoas;
    }

    private function montaLinhaDaPessoa($pessoa, $grupoEventoNoPeriodo) {
        $html = '';
        $classLinha = '';
        $corBotao = 'btn-dark';
        $corTextoTagsExtrasXs = ' class="hidden-lg" ';
        $corTextoTagsExtrasLg = ' class="hidden-xs hidden-sm hidden-md" ';
        $classLinha2 = '';
        if ($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo()) {
            if ($pessoa->getDataInativacao()) {
                /* Verificando em qual periodo foi inativado */
                $arrayPeriodo = Funcoes::montaPeriodo($this->view->periodo);
                $stringPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
                $dataDoInicioDoPeriodoParaComparar = strtotime($stringPeriodo);
                $stringPeriodoFim = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
                $dataDoFimDoPeriodoParaComparar = strtotime($stringPeriodoFim);
                $dataDeInativacaoDaPessoaParaComparar = strtotime($pessoa->getDataInativacao());
                if ($dataDeInativacaoDaPessoaParaComparar >= $dataDoInicioDoPeriodoParaComparar && $dataDeInativacaoDaPessoaParaComparar <= $dataDoFimDoPeriodoParaComparar) {
                    $classLinha = 'class="row-warning warning"';
                    $classLinha2 = 'footable-visible footable-first-column';
                    $corBotao = 'btn-warning disabled';
                    $base = ' text-warning" data-toggle="tooltip" data-placement="center" title data-original-title="Inativo"';
                    $corTextoTagsExtrasXs = 'class="hidden-lg' . $base;
                    $corTextoTagsExtrasLg = 'class="hidden-xs hidden-sm hidden-md' . $base;
                }
            }
        }
        $html .= '<tr id="tr_' . $pessoa->getIdGrupoPessoa() . '" ' . $classLinha . '>';

        /* TIPO */
        $html .= '<td class="tdTipo ' . $classLinha2 . '">';
        /* Menu dropup Tipo */
        $html .= '<div class="btn-group btn-block btn-xs dropdown">';
        $html .= '<span class="btn ' . $corBotao . ' btn-xs btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html .= $pessoa->getTipo();
        $html .= '<span class="sr-only"></span>';
        $html .= '</span>';

        if ($this->view->periodo == 0 && $pessoa->getTipo() != 'LP' && $pessoa->getAtivo()) {
            $html .= '<ul class="dropdown-menu sobrepor-elementos" style="min-width: 43px;">';
            $html .= '<span class="editable-container editable-inline">';
            $html .= '<div class="definicao-altura-30">';

            $html .= '<div class="control-group form-group">';

            /* Remover Pessoa */
            $html .= '<span class="input-group-btn">';
            $html .= '<span onclick="funcaoPessoa(\'' . Constantes::$ROUTE_REMOVER_PESSOA . '\', ' . $pessoa->getIdGrupoPessoa() . ');" '
                    . 'class="btn ladda-button btn-sm" style="margin-left:5px;"><i class="fa fa-trash-o"></i></span>';
            $html .= '</span>';

            $html .= '</div>';

            $html .= '</div>';
            $html .= '</span>';
            $html .= '</ul>';
        }

        /* Fim Menu dropup */

        $html .= '</td>';

        $empuraColunas = '';
        if ($this->view->quantidadeDeEventosNoCiclo < 4) {
            $empuraColunas = 'col-xs-10 col-sm-10 col-md-10';
        }
        $validacaoPossoAlterarNome = ($pessoa->getTipo() != 'LP' && $pessoa->getAtivo() && !$pessoa->verificaSeParticipouDoRevisao() && !$pessoa->verificarSeEhAluno());
        /* NOME */
        $html .= '<td class="text-left ' . $empuraColunas . '">&nbsp;';
        /* Menu dropup Nome */
        $html .= '<div class="btn-group dropdown">';
        if ($validacaoPossoAlterarNome) {
            $html .= '<a id="menudrop_' . $pessoa->getId() . '" class="tdNome text-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        }
        /* nome */
        /* Indicação de que eh aluno */
		if($pessoa->getTipo() != 'LP' && $pessoa->verificarSeEhAluno()){
				$html .= '<i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp;';
				}
        $html .= '<span id="span_nome_' . $pessoa->getId() . '" ' . $corTextoTagsExtrasXs . '>';
        $html .= $pessoa->getNomeListaDeLancamento(5);
        $html .= '</span>';
        $html .= '<span id="span_nome_lg_' . $pessoa->getId() . '"' . $corTextoTagsExtrasLg . '>';
        $html .= $pessoa->getNome();
        $html .= '</span>';
        /* fim nome */

        /* Alteracao de nome */
        if ($validacaoPossoAlterarNome) {
            $html .= '</a>';
            $html .= '<ul class="dropdown-menu sobrepor-elementos modal-edicao-nome">';
            $html .= '<span class="editable-container editable-inline">';
            $html .= '<div class="ml10 campo-edicao-nome">';
            $html .= '<form class="form-inline editableform">';
            $html .= '<div class="control-group form-group">';
            $html .= '<div>';
            $html .= '<div class="input-group">';
            $html .= '<input type="text" class="form-control" id="nome_' . $pessoa->getId() . '" value="' . $pessoa->getNome() . '" />';
            $html .= '<span class="input-group-btn">';
            $html .= '<span onclick="alterarNome(' . $pessoa->getId() . ')" class="btn ladda-button btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-check"></i></span></span>';
            $html .= '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
        }
        /* Fim Menu dropup */
        $html .= '</td>';
        foreach ($grupoEventoNoPeriodo as $grupoEvento) {
            switch ($this->view->quantidadeDeEventosNoCiclo) {
                case 1:
                    $style = 'style="width:100%;"';
                    break;
                case 2:
                    $style = 'style="width:50%;"';
                    break;
                case 3:
                    $style = 'style="width:33%;"';
                    break;
                case 4:
                    $style = 'style="width:25%;"';
                    break;
                case 5:
                    $style = 'style="width:20%;"';
                    break;
                case 6:
                    $style = 'style="width:18%;"';
                    break;
                case 7:
                    $style = 'style="width:15%;"';
                    break;
                case 8:
                    $style = 'style="width:12%;"';
                    break;
                default:
                    $style = 'style="width:20%;"';
                    break;
            }

            $diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
            if ($diaDaSemanaDoEvento === 1) {
                $diaDaSemanaDoEvento = 7; // domingo
            } else {
                $diaDaSemanaDoEvento--;
            }
            /* Validação Evento mostrar ou não */
            $mostrarParaLancar = false;
            if ($this->view->periodo < 0) {
                $arrayPeriodo = Funcoes::montaPeriodo($this->view->periodo);
                $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
                $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);
                $dataDoGrupoEventoParaComparar = strtotime($grupoEvento->getData_criacaoStringPadraoBanco());

                if ($dataDoGrupoEventoParaComparar <= $dataDoInicioDoPeriodoParaComparar) {
                    $mostrarParaLancar = true;
                }
            }

            if ($this->view->periodo == 0) {
                /* Verificar se o dia do evento é igual ou menor que o dia atual */
                if ($diaDaSemanaDoEvento <= $this->getDiaDeSemanaHoje()) {
                    $mostrarParaLancar = true;
                }
            }
            if ($this->view->periodo < 0) {
                $mostrarParaLancar = true;
            }
            $html .= '<td ' . $style . ' class="text-center">';
            $html .= '<div class="btn-group">';

            if ($mostrarParaLancar) {
                $corDoBotao = BotaoSimples::botaoPequenoMenosImportante;
                $icone = 'fa-thumbs-down';
                $diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $this->view->periodo);
                $eventoFrequencia = $grupoEvento->getEvento()->getEventoFrequencia();
                if ($eventoFrequencia) {
                    $eventosFrequenciaSelecionado = $pessoa->getEventoFrequenciaFiltradoPorEventoEDia(
                            $grupoEvento->getEvento()->getId(), $diaRealDoEvento
                    );
                    if ($eventosFrequenciaSelecionado) {
                        $valor = $eventosFrequenciaSelecionado->getFrequencia();
                        if ($valor == 'S') {
                            $corDoBotao = BotaoSimples::botaoPequenoImportante;
                            $icone = 'fa-thumbs-up';
                        }
                    }
                }

                $idEventoFrequencia = $pessoa->getId() . '_' . $grupoEvento->getEvento()->getId();
                $iconeBotao = '<i id="icone_' . $idEventoFrequencia . '" class="fa ' . $icone . '"></i>';
                $idDoBotao = 'id="botao_' . $idEventoFrequencia . '"';
                $parametrosMudarFrequencia = $pessoa->getId() . ',' . $grupoEvento->getEvento()->getId() . ', "' . $diaRealDoEvento . '", ' . $this->view->grupo->getId() . ', ' . $this->view->periodo;
                $funcaoMudarFrequencia = 'mudarFrequencia(' . $parametrosMudarFrequencia . ')';
                $funcaoOnclick = $this->view->funcaoOnClick($funcaoMudarFrequencia);
                $extra = $idDoBotao . ' ' . $funcaoOnclick;
                $html .= $this->view->botaoSimples($iconeBotao, $extra, $corDoBotao, BotaoSimples::posicaoAoCentro);
            } else {/* Eventos futuro */
                $icone = 1;
                $iconeRelogio = 1;

                $html .= '<button type = "button" class = "btn btn-sm disabled">';
                if ($icone === $iconeRelogio) {
                    $html .= '<i class = "fa fa-clock-o"></i>';
                }
                $html .= '</button>';
            }
            $html .= '</div>';
            $html .= '</td>';
        }
        $html .= '</tr>';

        return $html;
    }

    public static function diaRealDoEvento($diaDaSemanaDoEvento, $periodo = 0) {
        $diaDaSemanaSegunda = 1;
        $stringSegunda = '';

        if (date('N') == $diaDaSemanaSegunda) {
            $stringSegunda .= 'Now';
        } else {
            $stringSegunda .= 'Last Monday';
        }
        if ($periodo < 0) {
            $stringSegunda = $periodo . ' week ' . $stringSegunda;
        }

        $stringDia = $stringSegunda . ' + ' . ($diaDaSemanaDoEvento - 1) . ' day';
        $resposta = date("Y-m-d", strtotime($stringDia));
        return $resposta;
    }

    function getDiaDeSemanaHoje() {
        return $this->diaDeSemanaHoje;
    }

    function setDiaDeSemanaHoje($diaDeSemanaHoje) {
        $this->diaDeSemanaHoje = $diaDeSemanaHoje;
    }

}
