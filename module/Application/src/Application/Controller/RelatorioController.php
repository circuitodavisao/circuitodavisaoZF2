<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoEvento;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: RelatorioController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da tela principal
 */
class RelatorioController extends CircuitoController {

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela principal
     * GET /relatorio
     */
    public function indexAction() {
        
    }

    /**
     * Função padrão, traz a tela principal
     * GET /relatorioMembresia
     */
    public function membresiaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupo);

        /* Aba selecionada e ciclo */
        $abaSelecionada = $this->params()->fromRoute(Constantes::$ID);
        if (empty($abaSelecionada)) {
            $abaSelecionada = 1;
        }
        $mesSelecionado = date('n');
        $anoSelecionado = date('Y');
        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);

        if ($abaSelecionada == 2) {
            if ($cicloSelecionado > 1) {
                $cicloSelecionado--;
            } else {
                /* Mês Passado */
                if ($cicloSelecionado == 1) {
                    if (date('n') == 1) {
                        $mesSelecionado = 12;
                        $anoSelecionado = date('Y') - 1;
                    } else {
                        $mesSelecionado = date('n') - 1;
                        $anoSelecionado = date('Y');
                    }
                    $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
                }
            }
        }
        $tipoRelatorioPessoal = 1;
        $relatorio = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioPessoal);
        $discipulos = $grupo->getGrupoPaiFilhoFilhos();
        $periodoSelecionado = Funcoes::periodoCicloMesAno($cicloSelecionado, $mesSelecionado, $anoSelecionado);

        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorioPessoal);

        $relatorioCelula = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioPessoal);

        return new ViewModel(
                array(
            'relatorio' => $relatorio,
            'periodoSelecionado' => $periodoSelecionado,
            'discipulos' => $discipulos,
            'repositorioORM' => $repositorioORM,
            'abaSelecionada' => $abaSelecionada,
            'fatoLider' => $fatoLider,
            'relatorioCelula' => $relatorioCelula,
                )
        );
    }

    public function testeAction() {
        try {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $setarDataEHora = false;

            $pessoa = $repositorioORM->getPessoaORM()->encontrarPorEmail('falecomleonardopereira@gmail.com');

            /* Inativando */
            $grupoResponsavels = $pessoa->getGrupoResponsavel();
            $gruposAtual = null;
            foreach ($grupoResponsavels as $gr) {
                $gr->setDataEHoraDeInativacao();
                $repositorioORM->getGrupoResponsavelORM()->persistir($gr, $setarDataEHora);
                $gruposAtual = $gr->getGrupo();
            }
            $gruposAtual->setDataEHoraDeInativacao();
            $repositorioORM->getGrupoORM()->persistir($gruposAtual, $setarDataEHora);

            $gpf = $gruposAtual->getGrupoPaiFilhoPai();
            $gpf->setDataEHoraDeInativacao();
            $repositorioORM->getGrupoPaiFilhoORM()->persistir($gpf, $setarDataEHora);

            $entidade = $gruposAtual->getEntidadeAtiva();
            $entidade->setNome('TRANSFERIDA - ' . $entidade->getNome());
            $repositorioORM->getEntidadeORM()->persistir($entidade, $setarDataEHora);

            /* Cadastrando */
            $grupoNovo = new Grupo();
            $repositorioORM->getGrupoORM()->persistir($grupoNovo);

            $novaEntidade = new Entidade();
            $novaEntidade->setGrupo($grupoNovo);
            $novaEntidade->setNome('NOVO GRUPO');
            $novaEntidade->setEntidadeTipo($repositorioORM->getEntidadeTipoORM()->encontrarPorId(EntidadeTipo::subEquipe));
            $repositorioORM->getEntidadeORM()->persistir($novaEntidade);

            $pessoaPai = $repositorioORM->getPessoaORM()->encontrarPorEmail('rsilverio2012@hotmail.com');
            $grPai = $pessoaPai->getGrupoResponsavel()[0];
            $grupoPai = $grPai->getGrupo();

            $grupoPF = new GrupoPaiFilho();
            $grupoPF->setGrupoPaiFilhoFilho($grupoNovo);
            $grupoPF->setGrupoPaiFilhoPai($grupoPai);
            $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPF);

            $grupoResponsavelNovo = new GrupoResponsavel();
            $grupoResponsavelNovo->setGrupo($grupoNovo);
            $grupoResponsavelNovo->setPessoa($pessoa);
            $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);

            $gpessoas = $gruposAtual->getGrupoPessoa();
            foreach ($gpessoas as $gp) {
                $grupoPessoa = new GrupoPessoa();
                $grupoPessoa->setGrupo($grupoNovo);
                $grupoPessoa->setPessoa($gp->getPessoa());
                $grupoPessoa->setGrupoPessoaTipo($gp->getGrupoPessoaTipo());
                $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);
            }
            $geventos = $gruposAtual->getGrupoEventoAtivosPorTipo(GrupoEvento::CELULA);
            foreach ($geventos as $ge) {
                $grupoEvento = new GrupoEvento();
                $grupoEvento->setGrupo($grupoNovo);
                $grupoEvento->setEvento($ge->getEvento());
                $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento);
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
