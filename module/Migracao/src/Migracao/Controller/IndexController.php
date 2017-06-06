<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Evento;
use Application\Model\Entity\EventoCelula;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoCv;
use Application\Model\Entity\GrupoEvento;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\PessoaHierarquia;
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
//        $stringIdResponsavel1 = 'idResponsavel1';
//        $stringIdResponsavel2 = 'idResponsavel2';
//        $stringNome = 'nome';
//        $stringNumero = 'numero';
//
//        $numeroIdentidficador = 0;
//        $codigoRegiao = "001"; // 3 casas
//        $codigoCoordenacao = "008"; // 3 casas
//        $codigoIgreja = "0001"; // 4 casas
//        $codigoEquipe = "000001"; // 6 casas
//        $codigoSub0 = ""; // 8 casas
//
//        try {
//            $this->abreConexao();
//            $this->getRepositorio()->iniciarTransacao();
//
//            $queryIgrejas = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_igreja_ursula WHERE idCoordenacao = 8');
//            while ($row = mysqli_fetch_array($queryIgrejas)) {
//                $idPerfilIgreja = 18;
//                $numeroIdentificadorIgreja = "$codigoRegiao$codigoCoordenacao$codigoIgreja";
//                $informacaoEntidade = $row[$stringNome];
//                $grupoIgreja = $this->cadastrarEntidade($row[$stringIdResponsavel1], $idPerfilIgreja, $informacaoEntidade, null, $row[$stringIdResponsavel2], $row['id'], $numeroIdentificadorIgreja);
////                $this->cadastrarPessoasVolateis($row[$stringIdResponsavel1], $grupoIgreja);
//                $eventosCulto = $this->cadastrarCulto($row[$stringIdResponsavel1], $grupoIgreja);
////                $this->cadastrarCelulas($row[$stringIdResponsavel1], $grupoIgreja, $row[$stringIdResponsavel2]);
//
//                $urlEquipe = 'SELECT * FROM ursula_equipe_ursula WHERE id = 1 AND ativa = "S" AND idIgreja = ' . $row['id'];
////                $urlEquipe = 'SELECT * FROM ursula_equipe_ursula WHERE ativa = "S" AND idIgreja = 14';
//                $queryEquipes = mysqli_query($this->getConexao(), $urlEquipe);
//                while ($rowEquipe = mysqli_fetch_array($queryEquipes)) {
//                    $idPerfilEquipe = 15;
//                    $numeroIdentificadorEquipe = "$numeroIdentificadorIgreja$codigoEquipe";
//                    $informacaoEntidade = $rowEquipe[$stringNome];
//                    $grupoEquipe = $this->cadastrarEntidade($rowEquipe[$stringIdResponsavel1], $idPerfilEquipe, $informacaoEntidade, $grupoIgreja, $rowEquipe[$stringIdResponsavel2], $rowEquipe['id'], $numeroIdentificadorEquipe);
//                    $this->cadastrarPessoasVolateis($rowEquipe[$stringIdResponsavel1], $grupoEquipe);
//                    $this->cadastrarCultoEquipe($eventosCulto, $rowEquipe['id'], $grupoEquipe);
//                    $this->cadastrarCelulas($rowEquipe[$stringIdResponsavel1], $grupoEquipe, $rowEquipe[$stringIdResponsavel2]);
//                    $urlSub = 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND dataInativacao IS NULL AND idSubEquipePai = 0 and idEquipe = ' . $rowEquipe['id'] . ' AND id <> 53842 AND id <> 53944;';
////                    $urlSub = 'SELECT * FROM ursula_subequipe_ursula WHERE id = 16';
//                    $querySubEquipes = mysqli_query($this->getConexao(), $urlSub);
//                    while ($rowSubs = mysqli_fetch_array($querySubEquipes)) {
//                        $idPerfilSub = 17;
//                        $numero = str_pad($rowSubs['id'], 8, 0, STR_PAD_LEFT);
//                        $numeroIdentificadorSubEquipe = "$numeroIdentificadorEquipe$numero";
////                        $informacaoEntidade = $rowEquipe[$stringNome] . '.' . $rowSubs[$stringNumero];
//                        $informacaoEntidade = $rowSubs[$stringNumero];
//                        $grupoSub = $this->cadastrarEntidade($rowSubs[$stringIdResponsavel1], $idPerfilSub, $informacaoEntidade, $grupoEquipe, $rowSubs[$stringIdResponsavel2], $rowSubs['id'], $numeroIdentificadorSubEquipe);
//                        $this->cadastrarPessoasVolateis($rowSubs[$stringIdResponsavel1], $grupoSub);
//                        $this->cadastrarCelulas($rowSubs[$stringIdResponsavel1], $grupoSub, $rowSubs[$stringIdResponsavel2]);
//                        $querySubEquipes144 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND dataInativacao IS NULL AND idSubEquipePai = ' . $rowSubs['id']);
////                        $querySubEquipes144 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE id = 11888;');
//                        while ($rowSubs144 = mysqli_fetch_array($querySubEquipes144)) {
//                            $numero = str_pad($rowSubs144['id'], 8, 0, STR_PAD_LEFT);
//                            $numeroIdentificadorSubEquipe144 = "$numeroIdentificadorSubEquipe$numero";
////                            $informacaoEntidade = $rowEquipe[$stringNome] . '.' . $rowSubs[$stringNumero] . '.' . $rowSubs144[$stringNumero];
//                            $informacaoEntidade = $rowSubs144[$stringNumero];
//                            $grupoSub144 = $this->cadastrarEntidade($rowSubs144[$stringIdResponsavel1], $idPerfilSub, $informacaoEntidade, $grupoSub, $rowSubs144[$stringIdResponsavel2], $rowSubs144['id'], $numeroIdentificadorSubEquipe144);
//                            $this->cadastrarPessoasVolateis($rowSubs144[$stringIdResponsavel1], $grupoSub144);
//                            $this->cadastrarCelulas($rowSubs144[$stringIdResponsavel1], $grupoSub144, $rowSubs144[$stringIdResponsavel2]);
//
//                            $querySubEquipes1728 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs144['id']);
//                            while ($rowSubs1728 = mysqli_fetch_array($querySubEquipes1728)) {
//                                $numero = str_pad($rowSubs1728['id'], 8, 0, STR_PAD_LEFT);
//                                $numeroIdentificadorSubEquipe1728 = "$numeroIdentificadorSubEquipe144$numero";
//                                $grupoSub1728 = $this->cadastrarEntidade($rowSubs1728[$stringIdResponsavel1], $idPerfilSub, $rowSubs1728[$stringNumero], $grupoSub144, $rowSubs1728[$stringIdResponsavel2], $rowSubs1728['id'], $numeroIdentificadorSubEquipe1728);
//                                $this->cadastrarPessoasVolateis($rowSubs1728[$stringIdResponsavel1], $grupoSub1728);
//                                $this->cadastrarCelulas($rowSubs1728[$stringIdResponsavel1], $grupoSub1728, $rowSubs1728[$stringIdResponsavel2]);
//
//                                $querySubEquipes20736 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE ativa = "S" AND idSubEquipePai = ' . $rowSubs1728['id']);
//                                while ($rowSubs20736 = mysqli_fetch_array($querySubEquipes20736)) {
//                                    $numero = str_pad($rowSubs20736['id'], 8, 0, STR_PAD_LEFT);
//                                    $numeroIdentificadorSubEquipe20736 = "$numeroIdentificadorSubEquipe1728$numero";
//                                    $grupoSub20736 = $this->cadastrarEntidade($rowSubs20736[$stringIdResponsavel1], $idPerfilSub, $rowSubs20736[$stringNumero], $grupoSub1728, $rowSubs20736[$stringIdResponsavel2], $rowSubs20736['id'], $numeroIdentificadorSubEquipe20736);
//                                    $this->cadastrarPessoasVolateis($rowSubs20736[$stringIdResponsavel1], $grupoSub20736);
//                                    $this->cadastrarCelulas($rowSubs20736[$stringIdResponsavel1], $grupoSub20736, $rowSubs20736[$stringIdResponsavel2]);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//
//
//
//            $this->getRepositorio()->fecharTransacao();
//        } catch (Exception $exc) {
//            $this->getRepositorio()->desfazerTransacao();
//            $html = $exc->getTraceAsString();
//        }


        list($usec, $sec) = explode(' ', microtime());
        $script_end = (float) $sec + (float) $usec;
        $elapsed_time = round($script_end - $script_start, 5);

        $html .= 'Elapsed time: ' . $elapsed_time . ' secs. Memory usage: ' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . 'Mb';
        return new ViewModel(array('html' => $html));
    }

    /**
     * Gera os realtorios
     * GET /migracaoRelatorio
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
        if ($this->params()->fromRoute(Constantes::$ID)) {
            $cicloSelecionado = $this->params()->fromRoute(Constantes::$ID);
        }
        $html .= "<br /><br /><br />CicloSelecionado: " . $cicloSelecionado;
        $tipoCelula = 2;

        $grupos = $this->getRepositorio()->getGrupoORM()->encontrarTodos();
        foreach ($grupos as $grupo) {
            $html .= "<br /><br /><br />Grupo: " . $grupo->getId();
            if ($grupo->getEntidadeAtiva()) {
                $html .= "<br />Entidade " . $grupo->getEntidadeAtiva()->infoEntidade();
            }

            $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($grupo);
            $html .= "<br />NumeroIdentificador: " . $numeroIdentificador;
            if ($numeroIdentificador) {
                $fatoCiclo = $this->getRepositorio()->getFatoCicloORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $this->getRepositorio());
                /* Celulas */
                $grupoEventosNoCiclo = $grupo->getGrupoEventoNoCiclo($cicloSelecionado, $mesSelecionado, $anoSelecionado);
                $quantidadeDeEventosNoCiclo = count($grupoEventosNoCiclo);
                $temCelula = false;
                $html .= "<br />quantidadeDeEventosNoCiclo $quantidadeDeEventosNoCiclo";
                if ($quantidadeDeEventosNoCiclo > 0) {
                    foreach ($grupoEventosNoCiclo as $grupoEventoNoCiclo) {
                        $html .= "<br />verificaSeECelula: " . $grupoEventoNoCiclo->getEvento()->verificaSeECelula();
                        if ($grupoEventoNoCiclo->getEvento()->verificaSeECelula()) {
                            $this->getRepositorio()->getFatoCelulaORM()->criarFatoCelula($fatoCiclo, $grupoEventoNoCiclo->getEvento()->getEventoCelula()->getId());
                            $html .= "<br />Fato Celula ";
                            $temCelula = true;
                        }
                    }
                }

//                $quantidadeLideres = 0;
//                if ($temCelula) {
//                    $quantidadeLideres = count($grupo->getResponsabilidadesAtivas());
//                }
//                $html .= "<br />quantidadeLideres" . $quantidadeLideres;
//                $this->getRepositorio()->getFatoLiderORM()->criarFatoLider($numeroIdentificador, $quantidadeLideres);
            }
        }

        list($usec, $sec) = explode(' ', microtime());
        $script_end = (float) $sec + (float) $usec;
        $elapsed_time = round($script_end - $script_start, 5);

        $html .= '<br /><br />Elapsed time: ' . $elapsed_time . ' secs. Memory usage: ' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . 'Mb';
        return new ViewModel(array('html' => $html));
    }

    /**
     * Gera os realtorios
     * GET /migracaoLideres
     */
    public function lideresAction() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '60');

        list($usec, $sec) = explode(' ', microtime());
        $script_start = (float) $sec + (float) $usec;
        $html = '';

        $mesSelecionado = date('n');
        $anoSelecionado = date('Y');
        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
        if ($this->params()->fromRoute(Constantes::$ID)) {
            $cicloSelecionado = $this->params()->fromRoute(Constantes::$ID);
        }
        $html .= "<br /><br /><br />CicloSelecionado: " . $cicloSelecionado;
        $tipoCelula = 2;

        $grupos = $this->getRepositorio()->getGrupoORM()->encontrarTodos();
        foreach ($grupos as $grupo) {
            $html .= "<br /><br /><br />Grupo: " . $grupo->getId();
            if ($grupo->getEntidadeAtiva()) {
                $html .= "<br />Entidade " . $grupo->getEntidadeAtiva()->infoEntidade();
            }

            $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($grupo);
            $html .= "<br />NumeroIdentificador: " . $numeroIdentificador;
            if ($numeroIdentificador) {
                $fatoCiclo = $this->getRepositorio()->getFatoCicloORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $this->getRepositorio());
                /* Celulas */
                $grupoEventosNoCiclo = $grupo->getGrupoEventoNoCiclo($cicloSelecionado, $mesSelecionado, $anoSelecionado);
                $quantidadeDeEventosNoCiclo = count($grupoEventosNoCiclo);
                $temCelula = false;
                $html .= "<br />quantidadeDeEventosNoCiclo $quantidadeDeEventosNoCiclo";
                if ($quantidadeDeEventosNoCiclo > 0) {
                    foreach ($grupoEventosNoCiclo as $grupoEventoNoCiclo) {
                        $html .= "<br />verificaSeECelula: " . $grupoEventoNoCiclo->getEvento()->verificaSeECelula();
                        if ($grupoEventoNoCiclo->getEvento()->verificaSeECelula()) {
//                            $this->getRepositorio()->getFatoCelulaORM()->criarFatoCelula($fatoCiclo, $grupoEventoNoCiclo->getEvento()->getEventoCelula()->getId());
                            $html .= "<br />Fato Celula ";
                            $temCelula = true;
                        }
                    }
                }
                $quantidadeLideres = 0;
                if ($temCelula) {
                    $quantidadeLideres = count($grupo->getResponsabilidadesAtivas());
                }
                $html .= "<br />quantidadeLideres" . $quantidadeLideres;
                $this->getRepositorio()->getFatoLiderORM()->criarFatoLider($numeroIdentificador, $quantidadeLideres);
            }
        }

        list($usec, $sec) = explode(' ', microtime());
        $script_end = (float) $sec + (float) $usec;
        $elapsed_time = round($script_end - $script_start, 5);

        $html .= '<br /><br />Elapsed time: ' . $elapsed_time . ' secs. Memory usage: ' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . 'Mb';
        return new ViewModel(array('html' => $html));
    }

    public function abreConexao() {
        try {
            if (empty($this->getConexao())) {
                $this->setConexao(mysqli_connect('167.114.118.195', 'circuito_visao2', 'Z#03SOye(hRN', 'circuito_visao', '3306'));
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public static function pegaConexaoStatica() {
        return mysqli_connect('167.114.118.195', 'circuito_visao2', 'Z#03SOye(hRN', 'circuito_visao', '3306');
    }

    public static function pegaConexaoStaticaDW() {
        return mysqli_connect('167.114.118.195', 'circuito_visao2', 'Z#03SOye(hRN', 'circuito_dw', '3306');
    }

    public static function buscaIdAtendimentoPorLideres($mes, $ano, $lider1, $lider2 = null) {
        $atendimento = null;
        $sqlAtendimento = "SELECT id
                    FROM
                        ursula_atendimento_ursula
                    WHERE
                        mes = $mes AND ano = $ano AND idLider1 = $lider1";
        $queryAtendimento = mysqli_query(IndexController::pegaConexaoStatica(), $sqlAtendimento);
        if (mysqli_num_rows($queryAtendimento) > 0) {
            while ($rowAtendimento = mysqli_fetch_array($queryAtendimento)) {
                $atendimento = $rowAtendimento['id'];
            }
        } else {
            IndexController::cadastrarVazioAtendimentoPorLideres($mes, $ano, $lider1, $lider2);
        }

        return $atendimento;
    }

    public static function cadastrarVazioAtendimentoPorLideres($mes, $ano, $lider1, $lider2 = null) {
        if ($lider2) {
            $campos = 'idLider1, idLider2, mes, ano';
            $stringValues = "$lider1, $lider2, $mes, $ano";
        } else {
            $campos = 'idLider1, mes, ano';
            $stringValues = "$lider1, $mes, $ano";
        }

        $sqlAtendimentoInsert = "INSERT INTO ursula_atendimento_ursula ($campos) VALUES ($stringValues);";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlAtendimentoInsert);
    }

    public static function cadastrarAtendimentoPorid($id, $atendimentoLancado) {
        $stringValues = "s1 = '$atendimentoLancado[1]', s2 = '$atendimentoLancado[2]', s3 = '$atendimentoLancado[3]', s4 = '$atendimentoLancado[4]', s5 = '$atendimentoLancado[5]'";
        $sqlAtendimentoUpdate = "UPDATE ursula_atendimento_ursula SET $stringValues WHERE id = $id;";
//        echo "$sqlAtendimentoUpdate";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlAtendimentoUpdate);
    }

    public static function mudarFrequencia($numeroIdentificador, $mes, $ano, $tipoCampo, $tipoPessoa, $ciclo, $soma, $idTipo = 0, $idEntidade = 0, $idPai = 0) {
        $dimensoes = IndexController::buscaDimensoesPorIdFatoGrupo($numeroIdentificador, $mes, $ano, $idTipo, $idEntidade, $idPai);
        $tabela = "";
        $idTabela = 0;

        /* 1º CAMPO & 2º CAMPO */
        $campo1 = "";
        switch ($tipoCampo) {
            case 1: $campo1 = "c";
                break;
            case 2: $campo1 = "cu";
                break;
            case 3: $campo1 = "a";
                break;
            case 4: $campo1 = "d";
                break;
        }
        $campo1 = $campo1 . $ciclo;

        $campo2 = "";
        switch ($tipoPessoa) {
            case 4:$campo2 = $campo1 . "l";
                break;
            case 1:$campo2 = $campo1 . "v";
                break;
            case 2:$campo2 = $campo1 . "c";
                break;
            case 3:$campo2 = $campo1 . "m";
                break;
        }

        switch ($tipoCampo) {
            case 1: {
                    $tabela = "ursula_dim_celula_ursula";
                    $idTabela = $dimensoes[1];
                }
                break;
            case 2: {
                    $tabela = "ursula_dim_culto_ursula";
                    $idTabela = $dimensoes[2];
                }
                break;
            case 3: {
                    $tabela = "ursula_dim_arregimentacao_ursula";
                    $idTabela = $dimensoes[3];
                }
                break;
            case 4: {
                    $tabela = "ursula_dim_domingo_ursula";
                    $idTabela = $dimensoes[4];
                }
                break;
        }

        $sqlUpdate = "UPDATE #tabela SET #campo1 = (#campo1 + #soma), #campo2 = (#campo2 + #soma) where id = #idTabela";

        $sqlUpdate = str_replace("#tabela", $tabela, $sqlUpdate);
        $sqlUpdate = str_replace("#campo1", $campo1, $sqlUpdate);
        $sqlUpdate = str_replace("#campo2", $campo2, $sqlUpdate);
        $sqlUpdate = str_replace("#idTabela", $idTabela, $sqlUpdate);
        $sqlUpdate = str_replace("#soma", $soma, $sqlUpdate);

//        echo "$sqlUpdate<br /><br />";

        mysqli_query(IndexController::pegaConexaoStaticaDW(), $sqlUpdate);
//
//        $sqlAtualizarDataEnvio = "UPDATE ursula_fato_grupo_ursula SET "
//                . "dataEnvio = CURDATE(), "
//                . "horaEnvio = CURTIME() "
//                . "WHERE id = #idFatoGrupo;";
//
//        $sqlAtualizarDataEnvio = str_replace("#idFatoGrupo", $fato2->id, $sqlAtualizarDataEnvio);
//
//        mysql_query($sqlAtualizarDataEnvio);
    }

    public static function mudarCelulasRealizadas($numeroIdentificador, $mes, $ano, $ciclo, $realizada, $realizadaAntesDeMudar, $idTipo = 0, $idEntidade = 0, $idPai = 0) {
        $dimensoes = IndexController::buscaDimensoesPorIdFatoGrupo($numeroIdentificador, $mes, $ano, $idTipo, $idEntidade, $idPai);
        $valorDoCampo = IndexController::buscaValorDoCampoDimensaoelula($ciclo, $dimensoes[1]);
        $tabela = "ursula_dim_celula_ursula";
        $campo = 'c' . $ciclo . 'n';

        $valor = null;

        /* Foi realizada e saiu do zero */
        if ($realizada === 1 && $realizadaAntesDeMudar === 0) {
            if ($valorDoCampo > 0) {
                $valor = $valorDoCampo - 1;
            }
        }

        /* Nao realizada e existe */
        if ($realizada === 0 && $realizadaAntesDeMudar === 1) {
            $valor = $valorDoCampo + 1;
        }

        $sqlMudarCelulasRealizadas = 'UPDATE ' . $tabela . ' SET ' . $campo . ' = ' . $valor . ' WHERE id = ' . $dimensoes[1] . ';';
        mysqli_query(IndexController::pegaConexaoStaticaDW(), $sqlMudarCelulasRealizadas);
    }

    public static function buscaValorDoCampoDimensaoelula($ciclo, $idDimCelula) {
        $valor = null;
        $tabela = "ursula_dim_celula_ursula";
        $campo = 'c' . $ciclo . 'n';
        $sqlCampoDimensaoCelula = 'SELECT ' . $campo . ' FROM ' . $tabela . ' WHERE id = ' . $idDimCelula . ';';
        $queryCampoDimensaoCelula = mysqli_query(IndexController::pegaConexaoStaticaDW(), $sqlCampoDimensaoCelula);
        if (mysqli_num_rows($queryCampoDimensaoCelula) > 0) {
            while ($rowFatoGrupo = mysqli_fetch_array($queryCampoDimensaoCelula)) {
                $valor = $rowFatoGrupo[$campo];
            }
        }
        return $valor;
    }

    public static function buscaIdFatoGrupoPorNumeroIdentificador($numeroIdentificador, $mes, $ano, $idTipo = 0, $idEntidade = 0, $idPai = 0) {
        $fatoGrupo = null;
        $sqlFatoGrupo = "SELECT id
                    FROM
                        ursula_fato_grupo_ursula
                    WHERE
                        numeroIdentificador = '$numeroIdentificador' AND mes = $mes AND ano = $ano";
        $queryFatoGrupo = mysqli_query(IndexController::pegaConexaoStaticaDW(), $sqlFatoGrupo);
        if (mysqli_num_rows($queryFatoGrupo) > 0) {
            while ($rowFatoGrupo = mysqli_fetch_array($queryFatoGrupo)) {
                $fatoGrupo = $rowFatoGrupo['id'];
            }
        } else {
//            IndexController::cadastrarFatoGrupo($idTipo, $idEntidade, $mes, $ano, $idPai);
        }

        return $fatoGrupo;
    }

    public static function buscaDimensoesPorIdFatoGrupo($numeroIdentificador, $mes, $ano, $idTipo = 0, $idEntidade = 0, $idPai = 0) {
        $idFatoGrupo = IndexController::buscaIdFatoGrupoPorNumeroIdentificador($numeroIdentificador, $mes, $ano, $idTipo, $idEntidade, $idPai);
        $dimensoes = null;
        $sqlFatoGrupo = "SELECT idDimArregimentacao, idDimDomingo, idDimCulto, idDimCelula
                    FROM
                        ursula_fato_grupo_ursula
                    WHERE
                        id = $idFatoGrupo;";
        $queryFatoGrupo = mysqli_query(IndexController::pegaConexaoStaticaDW(), $sqlFatoGrupo);
        if (mysqli_num_rows($queryFatoGrupo) > 0) {
            while ($rowFatoGrupo = mysqli_fetch_array($queryFatoGrupo)) {
                $dimensoes[1] = $rowFatoGrupo['idDimCelula'];
                $dimensoes[2] = $rowFatoGrupo['idDimCulto'];
                $dimensoes[3] = $rowFatoGrupo['idDimArregimentacao'];
                $dimensoes[4] = $rowFatoGrupo['idDimDomingo'];
            }
        } else {
//            IndexController::cadastrarFatoGrupo($idTipo, $idEntidade, $mes, $ano, $idPai);
        }

        return $dimensoes;
    }

    public static function cadastrarFatoGrupo($idTipo, $idEntidade, $mes, $ano, $idPai) {
        $sql = 'INSERT INTO ursula_fato_grupo_ursula (idTipo, idEntidade, mes, ano, idPai, idTipoRelatorio, dataEnvio, horaEnvio)
                VALUES (#idTipo, #idEntidade, #mes, #ano, #idPai, 1, CURDATE(), CURTIME())';
        $sql = str_replace("#idTipo", $idTipo, $sql);
        $sql = str_replace("#idEntidade", $idEntidade, $sql);
        $sql = str_replace("#mes", $mes, $sql);
        $sql = str_replace("#ano", $ano, $sql);
        $sql = str_replace("#idPai", $idPai, $sql);

        echo "$sql<br /><br />";
        mysqli_query(IndexController::pegaConexaoStatica(), $sql);

        $sqlDimCelula = "INSERT INTO ursula_dim_celula_ursula (c1, c2, c3, c4, c5, c6, c1l, c2l, c3l, c4l, c5l, c6l, c1v, c2v, c3v, c4v, c5v, c6v, c1c, c2c, c3c, c4c, c5c, c6c, c1m, c2m, c3m, c4m, c5m, c6m, c1n, c2n, c3n, c4n, c5n, c6n, c1e, c2e, c3e, c4e, c5e, c6e, c3c1, c3c2, c3c3, c3c4, c3c5, c3c6, c6c1, c6c2, c6c3, c6c4, c6c5, c6c6, c1q, c2q, c3q, c4q, c5q, c6q) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
        $sqlDimCulto = "INSERT INTO ursula_dim_culto_ursula (cu1, cu2, cu3, cu4, cu5, cu6, cu1l, cu2l, cu3l, cu4l, cu5l, cu6l, cu1a, cu2a, cu3a, cu4a, cu5a, cu6a, cu1v, cu2v, cu3v, cu4v, cu5v, cu6v, cu1c, cu2c, cu3c, cu4c, cu5c, cu6c, cu1m, cu2m, cu3m, cu4m, cu5m, cu6m) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
        $sqlDimArregimentacao = "INSERT INTO ursula_dim_arregimentacao_ursula (a1, a2, a3, a4, a5, a6, a1l, a2l, a3l, a4l, a5l, a6l, a1a, a2a, a3a, a4a, a5a, a6a, a1v, a2v, a3v, a4v, a5v, a6v, a1c, a2c, a3c, a4c, a5c, a6c, a1m, a2m, a3m, a4m, a5m, a6m) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
        $sqlDimDomingo = "INSERT INTO ursula_dim_domingo_ursula (d1, d2, d3, d4, d5, d6, d1l, d2l, d3l, d4l, d5l, d6l, d1a, d2a, d3a, d4a, d5a, d6a, d1v, d2v, d3v, d4v, d5v, d6v, d1c, d2c, d3c, d4c, d5c, d6c, d1m, d2m, d3m, d4m, d5m, d6m) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";

        echo "$sqlDimCelula<br /><br />";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlDimCelula);
        $idDimCelula = mysql_insert_id();

        echo "$sqlDimCulto<br /><br />";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlDimCulto);
        $idDimCulto = mysql_insert_id();

        echo "$sqlDimArregimentacao<br /><br />";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlDimArregimentacao);
        $idDimArregimentacao = mysql_insert_id();

        echo "$sqlDimDomingo<br /><br />";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlDimDomingo);
        $idDimDomingo = mysql_insert_id();

        $this->daoGeral->abreConexao();
        $sqlAtualizarFato = 'UPDATE ursula_fato_grupo_ursula SET idDimCelula = #idDimCelula, idDimCulto = #idDimCulto, idDimArregimentacao = #idDimArregimentacao,
                idDimDomingo = #idDimDomingo, idDimInstituto = #idDimInstituto WHERE idTipo = #idTipo AND idEntidade = #idEntidade AND mes = #mes AND ano = #ano AND idPai = #idPai AND idTipoRelatorio = 1';

        $sqlAtualizarFato = str_replace("#idDimCelula", $idDimCelula, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#idDimArregimentacao", $idDimArregimentacao, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#idDimDomingo", $idDimDomingo, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#idDimCulto", $idDimCulto, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#idDimInstituto", $idDimCulto, $sqlAtualizarFato);

        $sqlAtualizarFato = str_replace("#idTipo", $idTipo, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#idEntidade", $idEntidade, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#mes", $mes, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#ano", $ano, $sqlAtualizarFato);
        $sqlAtualizarFato = str_replace("#idPai", $idPai, $sqlAtualizarFato);
        echo "$sqlAtualizarFato<br /><br />";
        mysqli_query(IndexController::pegaConexaoStatica(), $sqlAtualizarFato);
    }

    private function buscaPessoaPorId($id, $idPerfil) {
        $idInt = (int) $id;
        $pessoa = null;
        $queryPessoa = mysqli_query($this->getConexao(), 'SELECT nome, documento, email FROM ursula_pessoa_ursula WHERE id = ' . $idInt);
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

    private function buscaHierarquiaPorIdGrupoEPerfil($id, $idPerfil) {
        $idInt = (int) $id;
        switch ($idPerfil) {
            case 18:
                $sql = 'SELECT idHierarquia AS idHierarquia FROM ursula_igreja_ursula WHERE id = ' . $idInt;
                break;
            case 15:
                $sql = 'SELECT idHierarquia1 AS idHierarquia FROM ursula_equipe_ursula WHERE id = ' . $idInt;
                break;
            case 17:
                $sql = 'SELECT idHierarquia1 AS idHierarquia FROM ursula_subequipe_ursula WHERE id = ' . $idInt;
                break;
        }
        $query = mysqli_query($this->getConexao(), $sql);

        while ($row = mysqli_fetch_array($query)) {
            $hierarquia = $row['idHierarquia'];
        }

        return $hierarquia;
    }

    private function cadastrarHierarquia($idGrupo, $idPerfil, $pessoa) {
        $hierarquiaAntigo = $this->buscaHierarquiaPorIdGrupoEPerfil($idGrupo, $idPerfil);
        if ($hierarquiaAntigo) {
            $idSistemaNovo = 0;
            switch ($hierarquiaAntigo) {
                case 1:
                    $idSistemaNovo = 6;
                    break;
                case 16:
                    $idSistemaNovo = 5;
                    break;
                case 2:
                    $idSistemaNovo = 4;
                    break;
                case 3:
                    $idSistemaNovo = 2;
                    break;
                case 4:
                    $idSistemaNovo = 2;
                    break;
                case 9:
                    $idSistemaNovo = 1;
                    break;
                case 10:
                    $idSistemaNovo = 1;
                    break;
                case 11:
                    $idSistemaNovo = 6;
                    break;
                case 12:
                    $idSistemaNovo = 6;
                    break;
                case 13:
                    $idSistemaNovo = 6;
                    break;
                case 14:
                    $idSistemaNovo = 6;
                    break;
                case 15:
                    $idSistemaNovo = 3;
                    break;

                default:
                    $idSistemaNovo = 6;
                    break;
            }
            $hierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarPorId($idSistemaNovo);
            $pessoaHierarquia = new PessoaHierarquia();
            $pessoaHierarquia->setHierarquia($hierarquia);
            $pessoaHierarquia->setPessoa($pessoa);

            $this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);
        }
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
            if ($queryPessoasVolateis) {
                while ($rowPessoasVolateis = mysqli_fetch_array($queryPessoasVolateis)) {
                    $telefone = 0;
                    if (strlen($rowPessoasVolateis['dddCelular'] . $rowPessoasVolateis['telefoneCelular']) <= 11) {
                        $telefone = $rowPessoasVolateis['dddCelular'] . $rowPessoasVolateis['telefoneCelular'];
                        $pessoa = new Pessoa();
                        $pessoa->setNome($rowPessoasVolateis['nome']);
                        $pessoa->setTelefone($telefone);
                        $pessoa->setTipo($rowPessoasVolateis['idClassificacao']);
                        $pessoas[] = $pessoa;
                    }
                }
            }
        }
        /* Alunos para pessoas */
        $sqlAlunos = "SELECT  
        p.nome, p.dddCelular, p.telefoneCelular
        FROM ursula_pessoa_ursula AS p, ursula_turma_aluno_ursula AS ta WHERE p.idLider = '. $idInt . '
         AND ta.status = 'A' AND ta.idSituacao <> 9 AND ta.idAluno = p.id AND p.mostrarCiclos = 'S' ";
        $queryAlunos = mysqli_query($this->getConexao(), $sqlAlunos);
        if ($queryAlunos) {
            while ($rowAlunos = mysqli_fetch_array($queryAlunos)) {
                $telefone = 0;
                if (strlen($rowAlunos['dddCelular'] . $rowAlunos['telefoneCelular']) <= 11) {
                    $telefone = $rowAlunos['dddCelular'] . $rowAlunos['telefoneCelular'];
                    $pessoa = new Pessoa();
                    $pessoa->setNome($rowAlunos['nome']);
                    $pessoa->setTelefone($telefone);
                    $pessoa->setTipo(3);
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
                    $evento->setHora('00:00

            :00 

                

              '
                    )

                    ;
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

    private function cadastrarEntidade($idLider1, $idPerfil, $informacaoEntidade, $grupoPai = null, $idLider2 = null, $idGrupoAntigo = null, $numeroIdentificador = null) {
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
        if ($idPerfil === $idPerfilSub) {
            $entidade->setNumero($informacaoEntidade);
        } else {
            $entidade->setNome($informacaoEntidade);
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
                if ($idGrupoAntigo > 0) {
                    $this->cadastrarHierarquia($idGrupoAntigo, $idPerfil, $lider);
                }
            }
        }

        /* Cadastro do grupo_cv */
        $grupoCV = new GrupoCv();
        $grupoCV->setGrupo($grupo);
        $grupoCV->setLider1($idLider1);
        $grupoCV->setLider2($idLider2);
        $grupoCV->setNumero_identificador($numeroIdentificador);
        $this->getRepositorio()->getGrupoCvORM()->persistir($grupoCV, false);

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
