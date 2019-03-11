<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\EventoTipo;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o cabecalhos de eventos para ciclo, mes e entidade
 */
class CabecalhoDeEventos extends AbstractHelper {

	public function __construct() {

	}

	public function __invoke() {
		return $this->renderHtml();
	}

	public function renderHtml() {
		$html = '';

		$grupoEventoNoPeriodo = $this->view->grupoEventos;
		if (!empty($grupoEventoNoPeriodo)) {
			$numeroDeEvento = count($grupoEventoNoPeriodo);
			foreach ($grupoEventoNoPeriodo as $grupoEvento) {
				$espacamento = 'col-lg-4 col-md-4 col-sm-4 col-xs-4'; 
				if($numeroDeEvento === 1){
					$espacamento = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; 
				}
				if($numeroDeEvento === 2){
					$espacamento = 'col-lg-6 col-md-6 col-sm-6 col-xs-6'; 
				}

				$html .= '<div class="'.$espacamento.' mb5 text-center" style="padding-top: 0px">';
				$diaDaSemanaAjustado = Funcoes::diaDaSemanaPorDia($grupoEvento->getEvento()->getDia());
				$eventoNome = Funcoes::nomeDoEvento($grupoEvento->getEvento()->getTipo_id());
				if($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica){
					$eventoNome = 'Cél. Beta';
				}

				$html .= $this->view->translate($eventoNome).'<br />';
				$html .= $this->view->translate($diaDaSemanaAjustado);
				$html .= $grupoEvento->getEvento()->getHoraFormatoHoraMinuto();

				/* Totais */
				$evento = $grupoEvento->getEvento();
				$eventoFrequencias = $evento->getEventoFrequencia();
				if ($eventoFrequencias) {
					$grupoPessoas = $this->view->grupoPessoas;
					$pessoasParaComparar = array();
					foreach ($this->view->grupo->getResponsabilidadesAtivas() as $grupoResponsavel) {
						$pessoasParaComparar[] = (int) $grupoResponsavel->getPessoa()->getId();
					}
					if ($grupoPessoas) {
						foreach ($grupoPessoas as $grupoPessoa) {
							$pessoasParaComparar[] = (int) $grupoPessoa->getPessoa()->getId();
						}
					}
					$diaDaSemanaDoEvento = (int) $evento->getDia();
					/* Verificar se o dia do culto é igual ou menor que o dia atual */
					if ($diaDaSemanaDoEvento === 1) {
						$diaDaSemanaDoEvento = 7; // domingo
					} else {
						$diaDaSemanaDoEvento--;
					}
					$diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $this->view->periodo);
					$criteria = Criteria::create()
						->andWhere(Criteria::expr()->eq('frequencia', 'S'))
						->andWhere(Criteria::expr()->in('pessoa_id', $pessoasParaComparar))
						;
					$eventosFrequenciaFiltrados = $eventoFrequencias->matching($criteria);
					$contagem = 0;
					foreach ($eventosFrequenciaFiltrados as $frequencia) {
						if ($frequencia->getDia()->format('Y-m-d') === $diaRealDoEvento) {
							$contagem++;
						}
					}
				}
				$html .= '<button id="total_'.$evento->getId().'" type="button" class="btn btn-primary btn-xs disabled btn-block">';
				$html .= $contagem;
				$html .= '</button>';
				$html .= "</div>";
			}
		}
		return $html;
	}
}
