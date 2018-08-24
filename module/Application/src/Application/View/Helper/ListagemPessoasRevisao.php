<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Pessoa;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de pesoas ativas no revisão seleiconado
 */
class ListagemPessoasRevisao extends AbstractHelper {

	public function __construct() {

	}

	public function __invoke() {
		return $this->renderHtml();
	}

	public function renderHtml() {
		$html = '';
		$pessoas = array();
		$frequencias = $this->view->evento->getEventoFrequencia();
		if (($frequencias)) {
			foreach ($frequencias as $frequencia) {
				if ($frequencia->getFrequencia() == 'S' && $frequencia->getPessoa()->getGrupoPessoaAtivo()) {
					$pessoas[] = $frequencia->getPessoa();
				}
			}
		}

		/* Sem pessoas cadastrados */
		if (count($pessoas) == 0) {
			$html .= '<div class="panel-body bg-light">';
			$html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Fichas Ativas</div>';
			$html .= '</div>';
		} else {
			$html .= '<div id="painelAlunos">';
			$html .= $this->view->templateFormularioTopo('Selecione os alunos que participarão da turma');
			$html .= '<div class="panel-body bg-light">';
			$html .= '<input id="fooFilter" type="text" class="form-control" placeholder="Filtro">';
			$html .= '<table class="table table-condensed table-hover bg-light mt15 footable" data-filter="#fooFilter">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_MATRICULA);
			$html .= '</th>';
			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_NOME_REVISIONISTA);
			$html .= '</th>';
			$html .= '<th class="text-center">Time</th>';
			$html .= '<td class="text-center"><input type="checkbox" onclick="marcarTodos(this);"/></th>';
			$html .= '</td>';
			$html .= '</thead>';

			$html .= '<tbody>';
			foreach ($pessoas as $pessoa) {
				$html .= '<tr>';
				$html .= '<td class="text-center">' . $pessoa->getId() . '</td>';
				$html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';
				$html .= '<td class="text-center">'.$pessoa->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade().'</td>';
				$html .= '<td class="text-center"><input type="checkbox" name="aluno'.$pessoa->getId().'" id="' . $pessoa->getNome() . '" value="' . $pessoa->getId() . '"></td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';

			$html .= '</table>';
			$html .= '</div>';
			/* Fim panel-body */
			$html .= '<div class="panel-footer text-right">';
			$stringNomeDaFuncaoOnClickVoltar = 'funcaoCircuito("' . Constantes::$ROUTE_CADASTRO . Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA . '", ' . $pessoa->getId() . ')';
			$html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
			$stringNomeDaFuncaoOnClickProsseguir = 'mostrarResumo()';
			$html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CONFIRMACAO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickProsseguir));
			/* Fim Botões */
			$html .= '</div>';

			/* Fim panel-footer */
			$html .= $this->view->templateFormularioRodape();
			$html .= '</div>';
		}

		return $html;
	}

}
