<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Evento;
use Application\Model\Entity\EventoCelula;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoEvento;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoPessoa;
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
     * GET /migracao
     */
    public function indexAction() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '60');

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
                $informacaoEntidade = $row[$stringNome];
                $grupoIgreja = $this->cadastrarEntidade($row[$stringIdResponsavel1], $idPerfilIgreja, $informacaoEntidade, null, $row[$stringIdResponsavel2]);
//                $this->cadastrarPessoasVolateis($row[$stringIdResponsavel1], $grupoIgreja);
                $eventosCulto = $this->cadastrarCulto($row[$stringIdResponsavel1], $grupoIgreja);
                $this->cadastrarCelulas($row[$stringIdResponsavel1], $grupoIgreja, $row[$stringIdResponsavel2]);

                $urlEquipe = 'SELECT * FROM ursula_equipe_ursula WHERE id = 1 AND ativa = "S" AND idIgreja = ' . $row['id'];
//                $urlEquipe = 'SELECT * FROM ursula_equipe_ursula WHERE ativa = "S" AND idIgreja = 14';
                $queryEquipes = mysqli_query($this->getConexao(), $urlEquipe);
                while ($rowEquipe = mysqli_fetch_array($queryEquipes)) {
                    $idPerfilEquipe = 15;
                    $informacaoEntidade = $rowEquipe[$stringNome];
                    $grupoEquipe = $this->cadastrarEntidade($rowEquipe[$stringIdResponsavel1], $idPerfilEquipe, $informacaoEntidade, $grupoIgreja, $rowEquipe[$stringIdResponsavel2]);
//                    $this->cadastrarPessoasVolateis($rowEquipe[$stringIdResponsavel1], $grupoEquipe);
                    $this->cadastrarCultoEquipe($eventosCulto, $rowEquipe['id'], $grupoEquipe);
                    $this->cadastrarCelulas($rowEquipe[$stringIdResponsavel1], $grupoEquipe, $rowEquipe[$stringIdResponsavel2]);
                    $urlSub = 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND dataInativacao IS NULL AND idSubEquipePai = 0 and idEquipe = ' . $rowEquipe['id'];
//                    $urlSub = 'SELECT * FROM ursula_subequipe_ursula WHERE id = 16';
                    $querySubEquipes = mysqli_query($this->getConexao(), $urlSub);
                    while ($rowSubs = mysqli_fetch_array($querySubEquipes)) {
                        $idPerfilSub = 17;
                        $informacaoEntidade = $rowEquipe[$stringNome] . '.' . $rowSubs[$stringNumero];
                        $grupoSub = $this->cadastrarEntidade($rowSubs[$stringIdResponsavel1], $idPerfilSub, $informacaoEntidade, $grupoEquipe, $rowSubs[$stringIdResponsavel2]);
//                        $this->cadastrarPessoasVolateis($rowSubs[$stringIdResponsavel1], $grupoSub);
                        $this->cadastrarCelulas($rowSubs[$stringIdResponsavel1], $grupoSub, $rowSubs[$stringIdResponsavel2]);
                        $querySubEquipes144 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND dataInativacao IS NULL AND idSubEquipePai = ' . $rowSubs['id']);
//                        $querySubEquipes144 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE id = 11888;');
                        while ($rowSubs144 = mysqli_fetch_array($querySubEquipes144)) {
                            $informacaoEntidade = $rowEquipe[$stringNome] . '.' . $rowSubs[$stringNumero] . '.' . $rowSubs144[$stringNumero];
                            $grupoSub144 = $this->cadastrarEntidade($rowSubs144[$stringIdResponsavel1], $idPerfilSub, $informacaoEntidade, $grupoSub, $rowSubs144[$stringIdResponsavel2]);
                            $this->cadastrarPessoasVolateis($rowSubs144[$stringIdResponsavel1], $grupoSub144);
                            $this->cadastrarCelulas($rowSubs144[$stringIdResponsavel1], $grupoSub144, $rowSubs144[$stringIdResponsavel2]);

                            $querySubEquipes1728 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs144['id']);
                            while ($rowSubs1728 = mysqli_fetch_array($querySubEquipes1728)) {
                                $grupoSub1728 = $this->cadastrarEntidade($rowSubs1728[$stringIdResponsavel1], $idPerfilSub, $rowSubs1728[$stringNumero], $grupoSub144, $rowSubs1728[$stringIdResponsavel2]);
                                $this->cadastrarPessoasVolateis($rowSubs1728[$stringIdResponsavel1], $grupoSub1728);
                                $this->cadastrarCelulas($rowSubs1728[$stringIdResponsavel1], $grupoSub1728, $rowSubs1728[$stringIdResponsavel2]);

                                $querySubEquipes20736 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs1728['id']);
                                while ($rowSubs20736 = mysqli_fetch_array($querySubEquipes20736)) {
                                    $grupoSub20736 = $this->cadastrarEntidade($rowSubs20736[$stringIdResponsavel1], $idPerfilSub, $rowSubs20736[$stringNumero], $grupoSub1728, $rowSubs20736[$stringIdResponsavel2]);
                                    $this->cadastrarPessoasVolateis($rowSubs20736[$stringIdResponsavel1], $grupoSub20736);
                                    $this->cadastrarCelulas($rowSubs20736[$stringIdResponsavel1], $grupoSub20736, $rowSubs20736[$stringIdResponsavel2]);
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

    /**
     * Função padrão, traz a tela para login
     * GET /migracao
     */
    public function relatorioAction() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '60');

        list($usec, $sec) = explode(' ', microtime());
        $script_start = (float) $sec + (float) $usec;
        $html = '';

        $mesSelecionado = date('n');
        $anoSelecionado = date('Y');
        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
        $tipoCelula = 2;

        $grupos = $this->getRepositorio()->getGrupoORM()->encontrarTodos();
        foreach ($grupos as $grupo) {
            if ($grupo->getId() > 7) {
                $html .= "<br /><br /><br />Grupo: " . $grupo->getId();
                if ($grupo->getEntidadeAtiva()) {
                    $html .= "<br />Entidade " . $grupo->getEntidadeAtiva()->infoEntidade();
                }

                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($grupo);
                $html .= "<br />NumeroIdentificador: " . $numeroIdentificador;
                if ($numeroIdentificador > 0) {

                    $fatoCiclo = $this->getRepositorio()->getFatoCicloORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $this->getRepositorio());

                    /* Celulas */
//                        $cicloSelecionado = 4;
//                        $mesSelecionado = 3;
                    $grupoEventosNoCiclo = $grupo->getGrupoEventoNoCiclo($cicloSelecionado, $mesSelecionado, $anoSelecionado);
                    $quantidadeDeEventosNoCiclo = count($grupoEventosNoCiclo);
                    if ($quantidadeDeEventosNoCiclo > 0) {
                        foreach ($grupoEventosNoCiclo as $grupoEventoNoCiclo) {
                            if ($grupoEventoNoCiclo->getEvento()->verificaSeECelula()) {
                                $this->getRepositorio()->getFatoCelulaORM()->criarFatoCelula($fatoCiclo, $grupoEventoNoCiclo->getEvento()->getEventoCelula()->getId());
                            }
                        }
                    }

                    $quantidadeLideres = 0;
                    if ($grupo->getGrupoEventoAtivosPorTipo($tipoCelula)) {
                        $quantidadeLideres = count($grupo->getResponsabilidadesAtivas());
                    }
                    $html .= "<br />quantidadeLideres" . $quantidadeLideres;
                    $this->getRepositorio()->getFatoLiderORM()->criarFatoLider($numeroIdentificador, $quantidadeLideres);
                }
            }
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
        while ($rowPessoa = mysqli_fetch_array($queryPessoa)) {
            $pessoa = new Pessoa();
            $pessoa->setNome($rowPessoa['nome']);
            $pessoa->setDocumento($rowPessoa['documento']);
            $pessoa->setEmail($rowPessoa['email']);
            $pessoa->setAtualizar_dados('N');
            $sqlUsuario = 'SELECT senha FROM ursula_usuario_ursula WHERE status = "A" AND idPerfil = ' . $idPerfil . ' AND idPessoa = ' . $idInt . ' LIMIT 1';
            $queryUsuario = mysqli_query($this->getConexao(), $sqlUsuario);
            while ($rowUsuario = mysqli_fetch_array($queryUsuario)) {
                $pessoa->setSenha($rowUsuario['senha'], false);
            }
//            $pessoa->setSenha(123);
        }
        return $pessoa;
    }

    private function buscaCultosPorIgreja($id) {
        $idInt = (int) $id;
        $eventos = null;
        $eventoTipo = $this->getRepositorio()->getEventoTipoORM()->encontrarPorId(1);
        $sqlCultos = 'SELECT * FROM ursula_igreja_culto_ursula WHERE mes = MONTH(NOW()) AND ano = YEAR(NOW()) AND idIgreja = ' . $idInt;
        $queryCultos = mysqli_query($this->getConexao(), $sqlCultos);
        while ($rowCultos = mysqli_fetch_array($queryCultos)) {
            $evento = new Evento();
            $evento->setNome($rowCultos['nome']);
            $evento->setHora($rowCultos['horario']);
            $evento->setDia($rowCultos['dia']);
            $evento->setEventoTipo($eventoTipo);
            $evento->setIdAntigo($rowCultos['id']);
            $eventos[] = $evento;
        }
        return $eventos;
    }

    private function buscaCelulasPorLideres($idLider1, $idLider2 = null) {
        $eventos = null;
        $idLider1Int = (int) $idLider1;
        $eventoTipo = $this->getRepositorio()->getEventoTipoORM()->encontrarPorId(2);
        $sqlCelulas1 = 'SELECT 
                            *
                        FROM
                            ursula_celula_ursula
                        WHERE
                            (idLider1 = ' . $idLider1Int . ' OR idlider2 = ' . $idLider1Int . ' #condicao2) AND tipo = "A" AND status = "A" AND dia IS NOT NULL
                                AND mes = MONTH(NOW())
                                AND ano = YEAR(NOW());';
        if ($idLider2 != 0 && $idLider2 != null) {
            $idLider2Int = (int) $idLider2;
            $sqlCelulas = str_replace('#condicao2', ' OR idLider1 = ' . $idLider2Int . ' OR idlider2 = ' . $idLider2Int, $sqlCelulas1);
        } else {
            $sqlCelulas = str_replace('#condicao2', '', $sqlCelulas1);
        }
//        echo "<br />$sqlCelulas";
        $queryCelulas1 = mysqli_query($this->getConexao(), $sqlCelulas);
        while ($rowCelulas = mysqli_fetch_array($queryCelulas1)) {
            $evento = new Evento();
            $evento->setHora($rowCelulas['hora']);
            $evento->setDia($rowCelulas['dia']);
            $evento->setEventoTipo($eventoTipo);

            $eventoCelula = new EventoCelula();
            $eventoCelula->setEvento($evento);
            $eventoCelula->setNome_hospedeiro($rowCelulas['nomeHospedeiro']);
            $ddd = $rowCelulas['ddd'];
            if (empty($ddd)) {
                $ddd = 61;
            }
            $telefone = $ddd . $rowCelulas['telefoneHospedeiro'];
            $telefone = str_replace('-', '', $telefone);
            $eventoCelula->setTelefone_hospedeiro($telefone);
            $eventoCelula->setLogradouro($rowCelulas['logradouro']);
            $eventoCelula->setComplemento($rowCelulas['complemento']);
            $eventoCelula->setBairro($rowCelulas['idBairro']);
            $eventoCelula->setCidade($rowCelulas['idCidade']);
            $eventoCelula->setUf($rowCelulas['idUF']);
            $eventoCelula->setCep(0);
            $evento->setEventoCelula($eventoCelula);

            $eventos[] = $evento;
        }
        return $eventos;
    }

    private function consultarSeExiteCultoParaEquipe($idCulto, $idEquipe) {
        $resposta = false;
        $idCultoInteiro = (int) $idCulto;
        $idEquipeInteiro = (int) $idEquipe;
        $sql = 'SELECT * FROM ursula_igreja_culto_equipe_ursula WHERE idCulto = ' . $idCultoInteiro . ' AND idEquipe = ' . $idEquipeInteiro . ' AND dataInativacao IS NULL;';
        $query = mysqli_query($this->getConexao(), $sql);
        if (mysqli_num_rows($query) === 1) {
            $resposta = true;
        }
        return $resposta;
    }

    private function buscaPessoasVolateis($id) {
        $idInt = (int) $id;
        $pessoas = null;
        $idGrupoMensal = 0;
        $sqlGrupoAtual = '
        SELECT 
            *
        FROM
            circuito_visao.ursula_grupo_ursula
        WHERE
            idLider1 = ' . $idInt . ' AND mes = MONTH(NOW())
        AND ano = YEAR(NOW())
        AND status = "A"';
        $queryGrupo = mysqli_query($this->getConexao(), $sqlGrupoAtual);
        while ($rowGrupo = mysqli_fetch_array($queryGrupo)) {
            $idGrupoMensal = $rowGrupo['id'];
        }
        $pessoas;
        if ($idGrupoMensal) {
            $sqlPessoasVolateis = 'SELECT * FROM circuito_visao.ursula_pessoa_ursula where idGrupoMensal = ' . $idGrupoMensal;
            $queryPessoasVolateis = mysqli_query($this->getConexao(), $sqlPessoasVolateis);
            while ($rowPessoasVolateis = mysqli_fetch_array($queryPessoasVolateis)) {
                $telefone = 0;
                if (strlen($rowPessoasVolateis['dddCelular'] . $rowPessoasVolateis['telefoneCelular']) <= 11 && strlen($rowPessoasVolateis['dddCelular'] . $rowPessoasVolateis['telefoneCelular']) >= 10) {
                    $telefone = $rowPessoasVolateis['dddCelular'] . $rowPessoasVolateis['telefoneCelular'];
                    $pessoa = new Pessoa();
                    $pessoa->setNome($rowPessoasVolateis['nome']);
                    $pessoa->setTelefone($telefone);
                    $pessoa->setTipo($rowPessoasVolateis['idClassificacao']);
                    $pessoas[] = $pessoa;
                }
            }
        }
        return $pessoas;
    }

    private function cadastrarCulto($id, $grupo) {
        $eventos = $this->buscaCultosPorIgreja($id);
        if ($eventos) {
            foreach ($eventos as $evento) {
                $this->getRepositorio()->getEventoORM()->persistir($evento);

                $grupoEvento = new GrupoEvento();
                $grupoEvento->setGrupo($grupo);
                $grupoEvento->setEvento($evento);
                $this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);
            }
        }
        return $eventos;
    }

    private function cadastrarCelulas($idLider, $grupo, $idLider2 = null) {
        $eventos = $this->buscaCelulasPorLideres($idLider, $idLider2);
        if ($eventos) {
            foreach ($eventos as $evento) {
                if (strlen($evento->getHora()) > 8) {
                    $inicio = strlen($evento->getHora()) - 8;
                    $horaAjustada = substr($evento->getHora(), $inicio);
                    $evento->setHora($horaAjustada);
                }
                if (substr($evento->getHora(), 0, 1) > 2) {
                    $evento->setHora('00:00:00');
                }
                $eventoCelula = $evento->getEventoCelula();
                $evento->setEventoCelula(null);
                $this->getRepositorio()->getEventoORM()->persistir($evento);
                $this->getRepositorio()->getEventoCelulaORM()->persistir($eventoCelula, false);

                $grupoEvento = new GrupoEvento();
                $grupoEvento->setGrupo($grupo);
                $grupoEvento->setEvento($evento);
                $this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);
            }
        }
    }

    private function cadastrarFatoLider($grupo, $idLider2 = null) {
        $quantidadeDeLideres = 0;
        if ($idLider2) {
            $quantidadeDeLideres = 2;
        } else {
            $quantidadeDeLideres = 1;
        }
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($grupo);
        $this->getRepositorio()->getFatoLiderORM()->criarFatoLider($numeroIdentificador, $quantidadeDeLideres);
    }

    private function cadastrarCultoEquipe($eventosCulto, $idEquipe, $grupoEquipe) {
        if ($eventosCulto) {
            foreach ($eventosCulto as $eventoCulto) {
                if ($this->consultarSeExiteCultoParaEquipe($eventoCulto->getIdAntigo(), $idEquipe)) {
                    $grupoEvento = new GrupoEvento();
                    $grupoEvento->setGrupo($grupoEquipe);
                    $grupoEvento->setEvento($eventoCulto);
                    $this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);
                }
            }
        }
    }

    private function cadastrarPessoasVolateis($id, $grupo) {
        $pessoasVolateis = $this->buscaPessoasVolateis($id);
        if ($pessoasVolateis) {
            foreach ($pessoasVolateis as $pessoaVolatil) {
                $this->getRepositorio()->getPessoaORM()->persistir($pessoaVolatil);

                $tipo = 1;
                if ($pessoaVolatil->getTipo()) {
                    $tipo = $pessoaVolatil->getTipo();
                }

                $grupoPessoaTipo = $this->getRepositorio()->getGrupoPessoaTipoORM()->encontrarPorId($tipo);
                $grupoPessoa = new GrupoPessoa();
                $grupoPessoa->setGrupo($grupo);
                $grupoPessoa->setPessoa($pessoaVolatil);
                $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
                $this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa);
            }
        }
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
        unset($lideres);
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
//        if ($idPerfil === $idPerfilSub) {
//            $entidade->setNumero($informacaoEntidade);
//        }
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
