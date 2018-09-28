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
	private $arrayPeriodo;

    public function __construct() {

    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

		$this->arrayPeriodo = Funcoes::montaPeriodo($this->view->periodo);
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
                if ($pA[1]->getNome() < $pA[2]->getNome()) {
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
                $stringPeriodo = $this->arrayPeriodo[3] . '-' . $this->arrayPeriodo[2] . '-' . $this->arrayPeriodo[1];
                $dataDoInicioDoPeriodoParaComparar = strtotime($stringPeriodo);
                $stringPeriodoFim = $this->arrayPeriodo[6] . '-' . $this->arrayPeriodo[5] . '-' . $this->arrayPeriodo[4];
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
		$html .= '<div id="panel_'.$pessoa->getId().'" name="'.$pessoa->getNome().'" class="panel pessoa" style="margin-bottom: 5px">';
		$html .= '<div class="panel-body p5">';

		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
		$html .= '<div class="row btn-default p5">';

		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 0px">';
        $html .= '<span class="label label-dark">'.$pessoa->getTipo() . '</span> ';

		$validacaoPossoAlterarNome = ($pessoa->getTipo() != 'LP' && $pessoa->getAtivo() && !$pessoa->verificaSeParticipouDoRevisao() && !$pessoa->verificarSeEhAluno());


		if ($validacaoPossoAlterarNome && $this->view->possoAlterar) {
			$html .= '<a id="menudrop_' . $pessoa->getId() . '" class="tdNome text-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		}
		$html .= '<span id="span_nome_' . $pessoa->getId() . '" ' . $corTextoTagsExtrasXs . '>';
		$html .= $pessoa->getNomeListaDeLancamento(5);
		$html .= '</span>';
		$html .= '<span id="span_nome_lg_' . $pessoa->getId() . '"' . $corTextoTagsExtrasLg . '>';
		$html .= $pessoa->getNome();
		$html .= '</span>';
		if ($validacaoPossoAlterarNome && $this->view->possoAlterar) {
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
		$html .= '</div>';
		/* Col 6 */
		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 0px">';
		if($pessoa->getTelefone()){
			$telefone = '<a id="linkWhatsapp_'.$pessoa->getId().'" class="btn btn-success btn-xs" href="https://api.whatsapp.com/send?phone=55'.$pessoa->getTelefone().'"><i class="fa fa-whatsapp"></i></a>';
		}else{
        	$telefone = 'SEM TELEFONE';
		}
		$html .= $telefone;
		$html .= '</div>';
		/* Col 6 */

		$html .= '</div>';
		/* Row */

		$html .= '<div class="row btn-default p5">';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 0px">';
		if($pessoa->getTelefone()){
			$telefone = $pessoa->getTelefone();
		}else{
			$telefone = 'SEM TELEFONE';
		}
		if($this->view->possoAlterar){
			$html .= '<div class="btn-group dropdown">';
			$html .= '<a id="menudrop_telefone_' . $pessoa->getId() . '" class="text-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$html .= '<span id="span_telefone_' . $pessoa->getId() . '">';
			$html .= $telefone;
			$html .= '</span>';
			$html .= '</a>';
			$html .= '<ul class="dropdown-menu sobrepor-elementos modal-edicao-nome">';
			$html .= '<span class="editable-container editable-inline">';
			$html .= '<div class="ml10 campo-edicao-nome">';
			$html .= '<form class="form-inline editableform">';
			$html .= '<div class="control-group form-group">';
			$html .= '<div>';
			$html .= '<div class="input-group">';
			$html .= '<input type="number" class="form-control" id="telefone_' . $pessoa->getId() . '" value="' . $pessoa->getTelefone() . '" />';
			$html .= '<span class="input-group-btn">';
			$html .= '<span onclick="alterarTelefone('.$pessoa->getId().')" class="btn ladda-button btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-check"></i></span></span>';
			$html .= '</span>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			/* Fim Menu dropup */
		}else{
			$html .= $telefone;
		}
		$html .= '</div>';
		/* Col 6 */
		$html .= '</div>';
		/* Row */

		$html .= '</div>';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
		$html .= '<div class="row">';

		$numeroDeEvento = count($grupoEventoNoPeriodo);
        foreach ($grupoEventoNoPeriodo as $grupoEvento) {

            $diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
            if ($diaDaSemanaDoEvento === 1) {
                $diaDaSemanaDoEvento = 7; // domingo
            } else {
                $diaDaSemanaDoEvento--;
            }
            /* Validação Evento mostrar ou não */
            $mostrarParaLancar = false;
            if ($this->view->periodo < 0) {
				$arrayPeriodo = $this->arrayPeriodo;
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
			$espacamento = 'col-lg-4 col-md-4 col-sm-4 col-xs-4';
			$tamanhoBotao = BotaoSimples::larguraMaxima;
			$tamanhoBotaoInativo = 'btn-block';
			if($numeroDeEvento === 1){
				$espacamento = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
			}
			if($numeroDeEvento === 2){
				$espacamento = 'col-lg-6 col-md-6 col-sm-6 col-xs-6';
			}
			if($numeroDeEvento === 3){
				$espacamento = 'col-lg-4 col-md-4 col-sm-4 col-xs-4';
			}
			if($numeroDeEvento === 4){
				//$espacamento = 'col-lg-3 col-md-3 col-sm-3 col-xs-3';
			}
			if($numeroDeEvento > 4){
				//$tamanhoBotao = BotaoSimples::posicaoAoCentro;
				//$tamanhoBotaoInativo = '';
			}

			$html .= '<div ' . $style . ' class="text-center '.$espacamento.' mb5" style="padding-top: 0px">';
			$html .= '<p>';
                $eventoNome = Funcoes::nomeDoEvento($grupoEvento->getEvento()->getTipo_id());
			$diaDaSemanaAjustado = Funcoes::diaDaSemanaPorDia($grupoEvento->getEvento()->getDia());
			$html .= $this->view->translate($eventoNome). ' ';
			$html .= $this->view->translate($diaDaSemanaAjustado);
			$html .= $grupoEvento->getEvento()->getHoraFormatoHoraMinuto();
			$html .= '</p>';
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
				if(!$this->view->possoAlterar){
					$extra .= ' disabled';
				}
				$html .= $this->view->botaoSimples($iconeBotao, $extra, $corDoBotao, $tamanhoBotao);
            } else {/* Eventos futuro */
                $icone = 1;
                $iconeRelogio = 1;

                $html .= '<button type="button" class="btn btn-sm disabled '.$tamanhoBotaoInativo.'">';
                if ($icone === $iconeRelogio) {
                    $html .= '<i class = "fa fa-clock-o"></i>';
                }
                $html .= '</button>';
            }
            $html .= '</div>';
        }
		$html .= '</div>';
		$html .= '</div>';

		$html .= '</div>';
		$html .= '</div>';
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
