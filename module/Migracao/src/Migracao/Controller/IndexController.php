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
    private $repositorio;
    private $entidadeTipoIgreja;
    private $entidadeTipoEquipe;
    private $entidadeTipoSub;

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
        list($usec, $sec) = explode(' ', microtime());
        $script_start = (float) $sec + (float) $usec;
        $html = '';
        $stringIdResponsavel1 = 'idResponsavel1';
        $stringIdResponsavel2 = 'idResponsavel2';
        $stringNome = 'nome';
        $stringNumero = 'numero';
        try {
            $this->abreConexao();
            $this->getRepositorio()->iniciarTransacao();

            $queryIgrejas = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_igreja_ursula WHERE idCoordenacao = 8');
            while ($row = mysqli_fetch_array($queryIgrejas)) {
                $idPerfilIgreja = 18;
                $grupoIgreja = $this->cadastrarEntidade($row[$stringIdResponsavel1], $idPerfilIgreja, $row[$stringNome], null, $row[$stringIdResponsavel2]);

                $urlEquipe = 'SELECT * FROM ursula_equipe_ursula WHERE ativa = "S" AND idIgreja = ' . $row['id'];
                $queryEquipes = mysqli_query($this->getConexao(), $urlEquipe);
                while ($rowEquipe = mysqli_fetch_array($queryEquipes)) {
                    $idPerfilEquipe = 15;
                    $grupoEquipe = $this->cadastrarEntidade($rowEquipe[$stringIdResponsavel1], $idPerfilEquipe, $rowEquipe[$stringNome], $grupoIgreja, $rowEquipe[$stringIdResponsavel2]);

                    $urlSub = 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = 0 and idEquipe = ' . $rowEquipe['id'];
                    $querySubEquipes = mysqli_query($this->getConexao(), $urlSub);
                    while ($rowSubs = mysqli_fetch_array($querySubEquipes)) {
                        $idPerfilSub = 17;
                        $grupoSub = $this->cadastrarEntidade($rowSubs[$stringIdResponsavel1], $idPerfilSub, $rowSubs[$stringNumero], $grupoEquipe, $rowSubs[$stringIdResponsavel2]);

                        $querySubEquipes144 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs['id']);
                        while ($rowSubs144 = mysqli_fetch_array($querySubEquipes144)) {
                            $grupoSub144 = $this->cadastrarEntidade($rowSubs144[$stringIdResponsavel1], $idPerfilSub, $rowSubs144[$stringNumero], $grupoSub, $rowSubs144[$stringIdResponsavel2]);

                            $querySubEquipes1728 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs144['id']);
                            while ($rowSubs1728 = mysqli_fetch_array($querySubEquipes1728)) {
                                $grupoSub1728 = $this->cadastrarEntidade($rowSubs1728[$stringIdResponsavel1], $idPerfilSub, $rowSubs1728[$stringNumero], $grupoSub144, $rowSubs1728[$stringIdResponsavel2]);

                                $querySubEquipes20736 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs1728['id']);
                                while ($rowSubs20736 = mysqli_fetch_array($querySubEquipes20736)) {
                                    $grupoSub20736 = $this->cadastrarEntidade($rowSubs20736[$stringIdResponsavel1], $idPerfilSub, $rowSubs20736[$stringNumero], $grupoSub1728, $rowSubs20736[$stringIdResponsavel2]);
                                }
                            }
                        }
                    }
                }
            }

            $this->getRepositorio()->fecharTransacao();
        } catch (Exception $exc) {
            $this->getRepositorio()->desfazerTransacao();
            $html = $exc->getTraceAsString();
        }
        list($usec, $sec) = explode(' ', microtime());
        $script_end = (float) $sec + (float) $usec;
        $elapsed_time = round($script_end - $script_start, 5);

        $html .= 'Elapsed time: ' . $elapsed_time . ' secs. Memory usage: ' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . 'Mb';
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

    private function buscaPessoaPorId($id, $idPerfil) {
        $idInt = (int) $id;
        $pessoa = null;
        $queryPessoa = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_pessoa_ursula WHERE id = ' . $idInt);
        while ($row = mysqli_fetch_array($queryPessoa)) {
            $pessoa = new Pessoa();
            $pessoa->setNome($row['nome']);
//            $pessoa->setDocumento($row['documento']);
            $queryUsuario = mysqli_query($this->getConexao(), 'SELECT usuario, senha FROM ursula_usuario_ursula WHERE status = "A" AND idPerfil = ' . $idPerfil . ' AND idPessoa = ' . $idInt);
            while ($row = mysqli_fetch_array($queryUsuario)) {
                $pessoa->setEmail($row['usuario']);
                $pessoa->setSenha($row['senha'], false);
            }
        }
        return $pessoa;
    }

    private function cadastrarEntidade($idLider1, $idPerfil, $informacaoEntidade, $grupoPai = null, $idLider2 = null) {
        $idPerfilIgreja = 18;
        $idPerfilEquipe = 15;
        $idPerfilSub = 17;
        switch ($idPerfil) {
            case $idPerfilIgreja:
                $entidadeTipo = $this->getEntidadeTipoIgreja();
                break;
            case $idPerfilEquipe:
                $entidadeTipo = $this->getEntidadeTipoEquipe();
                break;
            case $idPerfilSub:
                $entidadeTipo = $this->getEntidadeTipoSub();
                break;
        }
        $lideres = [];
        $idLider1Inteiro = (int) $idLider1;
        $lideres[] = $this->buscaPessoaPorId($idLider1Inteiro, $idPerfil);
        if ($idLider2) {
            $idLider2Inteiro = (int) $idLider2;
            $lideres[] = $this->buscaPessoaPorId($idLider2Inteiro, $idPerfil);
        }

        /* Gerando */
        $grupo = new Grupo();
        $this->getRepositorio()->getGrupoORM()->persistir($grupo);
        $entidade = new Entidade();
        $entidade->setEntidadeTipo($entidadeTipo);
        $entidade->setGrupo($grupo);

        $entidade->setNome($informacaoEntidade);
        if ($idPerfil === $idPerfilSub) {
            $entidade->setNumero($informacaoEntidade);
        }
        $this->getRepositorio()->getEntidadeORM()->persistir($entidade);

        if ($grupoPai) {
            $grupoPaiFilho = new GrupoPaiFilho();
            $grupoPaiFilho->setGrupoPaiFilhoPai($grupoPai);
            $grupoPaiFilho->setGrupoPaiFilhoFilho($grupo);
            $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPaiFilho);
        }
        foreach ($lideres as $lider) {
            if ($lider) {
                $this->getRepositorio()->getPessoaORM()->persistir($lider);
                $grupoResponsavel = new GrupoResponsavel();
                $grupoResponsavel->setGrupo($grupo);
                $grupoResponsavel->setPessoa($lider);
                $this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavel);
            }
        }
        /* Fim gerando */
        return $grupo;
    }

    private function getEntidadeTipoIgreja() {
        if (empty($this->entidadeTipoIgreja)) {
            $entidadeTipoIgreja = 5;
            $this->entidadeTipoIgreja = $this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId($entidadeTipoIgreja);
        }
        return $this->entidadeTipoIgreja;
    }

    private function getEntidadeTipoEquipe() {
        if (empty($this->entidadeTipoEquipe)) {
            $entidadeTipoEquipe = 6;
            $this->entidadeTipoEquipe = $this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId($entidadeTipoEquipe);
        }
        return $this->entidadeTipoEquipe;
    }

    private function getEntidadeTipoSub() {
        if (empty($this->entidadeTipoSub)) {
            $entidadeTipoSub = 7;
            $this->entidadeTipoSub = $this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId($entidadeTipoSub);
        }
        return $this->entidadeTipoSub;
    }

    function getConexao() {
        return $this->conexao;
    }

    function setConexao($conexao) {
        $this->conexao = $conexao;
        return $this;
    }

    function getRepositorio() {
        if (empty($this->repositorio)) {
            $this->repositorio = new RepositorioORM($this->getDoctrineORMEntityManager());
        }
        return $this->repositorio;
    }

    function setRepositorio($repositorio) {
        $this->repositorio = $repositorio;
    }

}
