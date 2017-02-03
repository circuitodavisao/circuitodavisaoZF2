<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\Entity\Pessoa;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\View\Model\ViewModel;

/**
 * Nome: IndexController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ação de migração
 */
class IndexController extends CircuitoController {

    private $conexao;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela para login
     * GET /
     */
    public function indexAction() {
        $html = '';
        $stringBr = '<br />';
        $indiceNome = 1;
        $indiceDocumento = 2;
        $indiceDataNascimento = 3;
        $indiceSexo = 4;
        $stringIdResponsavel1 = 'idResponsavel1';
        $stringIdResponsavel2 = 'idResponsavel2';
        $stringNome = 'nome';
        $lideres = [];
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $entidadeTipoIgreja = 5;
        $entidadeIgreja = $repositorioORM->getEntidadeTipoORM()->encontrarPorId($entidadeTipoIgreja);
        $entidadeTipoEquipe = 6;
        $entidadeEquipe = $repositorioORM->getEntidadeTipoORM()->encontrarPorId($entidadeTipoEquipe);
        $entidadeTipoSub = 7;
        $entidadeSub = $repositorioORM->getEntidadeTipoORM()->encontrarPorId($entidadeTipoSub);
        $stringSeparacao = '#############################################';
        $html .=$stringBr . "Abrindo a conexao";
        try {
            $this->abreConexao();
            $repositorioORM->iniciarTransacao();

            $queryIgrejas = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_igreja_ursula WHERE id = 1');
            while ($row = mysqli_fetch_array($queryIgrejas)) {
                unset($lideres);
                $lideres[] = $this->buscaPessoaPorId($row[$stringIdResponsavel1]);
                if ($row[$stringIdResponsavel2]) {
                    $lideres[] = $this->buscaPessoaPorId($row[$stringIdResponsavel2]);
                }
                $nomeIgreja = $row[$stringNome];

                $html .= $stringBr . $stringSeparacao . $stringBr . 'Igreja: ' . $nomeIgreja;

                /* Gerando */
                $entidadeTipoIgreja = 5;
                $grupoIgreja = new Grupo();
                $repositorioORM->getGrupoORM()->persistir($grupoIgreja);
                $entidadeNova = new Entidade();
                $entidadeNova->setEntidadeTipo($entidadeIgreja);
                $entidadeNova->setGrupo($grupoIgreja);
                $entidadeNova->setNome($nomeIgreja);
                $repositorioORM->getEntidadeORM()->persistir($entidadeNova);

                $grupoResposanvel = new GrupoResponsavel();
                $grupoResposanvel->setGrupo($grupoIgreja);
                $grupoResposanvel->setPessoa($repositorioORM->getPessoaORM()->encontrarPorId(1));
                $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResposanvel);
                /* Fim gerando */

                $queryEquipes = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_equipe_ursula WHERE ativa = "S" AND idIgreja = 1');
                while ($rowEquipe = mysqli_fetch_array($queryEquipes)) {
                    unset($lideres);
                    $lideres[] = $this->buscaPessoaPorId($rowEquipe[$stringIdResponsavel1]);
                    if ($rowEquipe[$stringIdResponsavel2]) {
                        $lideres[] = $this->buscaPessoaPorId($rowEquipe[$stringIdResponsavel2]);
                    }
                    $nomeEquipe = $rowEquipe['nome'];
                    $html .= $stringBr . $stringSeparacao . $stringBr . 'Equipe: ' . $nomeEquipe;

                    /* Gerando */
                    $entidadeTipoEquipe = 6;
                    $grupoEquipe = new Grupo();
                    $repositorioORM->getGrupoORM()->persistir($grupoEquipe);
                    $entidadeNova = new Entidade();
                    $entidadeNova->setEntidadeTipo($entidadeEquipe);
                    $entidadeNova->setGrupo($grupoEquipe);
                    $entidadeNova->setNome($nomeEquipe);
                    $repositorioORM->getEntidadeORM()->persistir($entidadeNova);

                    $grupoPaiFilho = new GrupoPaiFilho();
                    $grupoPaiFilho->setGrupoPaiFilhoPai($grupoIgreja);
                    $grupoPaiFilho->setGrupoPaiFilhoFilho($grupoEquipe);
                    $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPaiFilho);

                    foreach ($lideres as $lider) {
                        $repositorioORM->getPessoaORM()->persistir($lider);
                        $grupoResposanvel = new GrupoResponsavel();
                        $grupoResposanvel->setGrupo($grupoEquipe);
                        $grupoResposanvel->setPessoa($lider);
                        $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResposanvel);
                    }
                    /* Fim gerando */

                    $querySubEquipes = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = 0 and idEquipe = ' . $rowEquipe['id']);

                    while ($rowSubs = mysqli_fetch_array($querySubEquipes)) {
                        unset($lideres);
                        $lideres[] = $this->buscaPessoaPorId($rowSubs[$stringIdResponsavel1]);
                        if ($rowSubs[$stringIdResponsavel2]) {
                            $lideres[] = $this->buscaPessoaPorId($rowSubs[$stringIdResponsavel2]);
                        }
                        $numeroSubequipe = $rowSubs['numero'];
                        $html .= $stringBr . 'Sub: ' . $numeroSubequipe;

                        /* Gerando */
                        $grupoSub = new Grupo();
                        $repositorioORM->getGrupoORM()->persistir($grupoSub);
                        $entidadeNova = new Entidade();
                        $entidadeNova->setEntidadeTipo($entidadeSub);
                        $entidadeNova->setGrupo($grupoSub);
                        $entidadeNova->setNumero($numeroSubequipe);
                        $repositorioORM->getEntidadeORM()->persistir($entidadeNova);

                        $grupoPaiFilho = new GrupoPaiFilho();
                        $grupoPaiFilho->setGrupoPaiFilhoPai($grupoEquipe);
                        $grupoPaiFilho->setGrupoPaiFilhoFilho($grupoSub);
                        $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPaiFilho);

                        foreach ($lideres as $lider) {
                            $repositorioORM->getPessoaORM()->persistir($lider);
                            $grupoResposanvel = new GrupoResponsavel();
                            $grupoResposanvel->setGrupo($grupoSub);
                            $grupoResposanvel->setPessoa($lider);
                            $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResposanvel);
                        }
                        /* Fim gerando */
                    }
                }
            }
            $repositorioORM->fecharTransacao();
        } catch (Exception $exc) {
            $repositorioORM->desfazerTransacao();
            $html = $exc->getTraceAsString();
        }

        return new ViewModel(array('html' => $html));
    }

    private function abreConexao() {
        try {
            if (empty($this->getConexao())) {
                $this->setConexao(mysqli_connect('167.114.118.195', 'circuito_visao2', 'Z#03SOye(hRN', 'circuito_visao', '3306'));
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    private function buscaPessoaPorId($id) {
        $idInt = (int) $id;
        $pessoa = null;
        $queryPessoa = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_pessoa_ursula WHERE id = ' . $idInt);
        while ($row = mysqli_fetch_array($queryPessoa)) {
            $pessoa = new Pessoa();
            $pessoa->setNome($row['nome']);
            $pessoa->setDocumento($row['documento']);
            $pessoa->setData_nascimento($row['dataNascimento']);
        }
        return $pessoa;
    }

    function getConexao() {
        return $this->conexao;
    }

    function setConexao($conexao) {
        $this->conexao = $conexao;
        return $this;
    }

}
