<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\LancamentoController;
use Application\Model\Entity\Grupo;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeAtendimentos.php
 * @author Luca Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar o numero de discipulos atendidos e o progresso do líder 
 */
class CabecalhoDeAtendimentos extends AbstractHelper {

    protected $gruposAbaixo;

    public function __construct() {
        
    }

    public function __invoke($gruposAbaixo) {
        $this->setGruposAbaixo($gruposAbaixo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        if ($this->getGruposAbaixo()) {
            $relatorioAtendimento = Grupo::relatorioDeAtendimentosAbaixo(
                            $this->getGruposAbaixo(), $this->view->mes, $this->view->ano
            );
           
            $valorBarraFormatada = 0;
            if ($relatorioAtendimento[0] > 0) {
                $valorBarraFormatada = number_format($relatorioAtendimento[0], 2, '.', '');
            }
            $html .= '<span id="totalGruposAtendidos">' . $relatorioAtendimento[1] . ' </span> '
                    . $this->view->translate('of')
                    . ' <span id="totalGruposFilhos">' . $relatorioAtendimento[2] . '</span> '
                    . '<span class="hidden-xs">' . $this->view->translate(Constantes::$TRADUCAO_SUBTITULO_CABECALHO_ATENDIMENTO) . '</span>';
        }
        return $html;
    }

    function getGruposAbaixo() {
        return $this->gruposAbaixo;
    }

    function setGruposAbaixo($gruposAbaixo) {
        $this->gruposAbaixo = $gruposAbaixo;
    }

}
