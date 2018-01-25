<?php

namespace Application\Model\Entity;

/**
 * Nome: Entidade.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela entidade
 * 1 - PRESIDENCIAL
 * 2 - NACIONAL
 * 3 - REGIÃO
 * 4 - SUB REGIÃO
 * 5 - COORDENAÇÃO
 * 6 - SUB COORDENAÇÃO
 * 7 - IGREJA
 * 8 - EQUIPE
 * 9 - SUB EQUIPE
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="entidade")
 */
class Entidade extends CircuitoEntity {

    const SUBEQUIPE = 7;
    const EQUIPE = 6;
    const IGREJA = 5;
    const COORDENACAO = 4;
    const REGIONAL = 3;
    const NACIONAL = 2;
    const PRESIDENTE = 1;

    /**
     * @ORM\ManyToOne(targetEntity="EntidadeTipo", inversedBy="entidade")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $entidadeTipo;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="entidade")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $numero;

    /** @ORM\Column(type="integer") */
    protected $tipo_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    public function infoEntidade() {
        $resposta = '';
        $grupoSelecionado = $this->getGrupo();
        if ($this->verificarSeEstaAtivo()) {
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                $numeroSub = '';
                $contagemHierarquica = 0;
                while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                    if ($contagemHierarquica == 0) {
                        $numeroSub = $grupoSelecionado->getEntidadeAtiva()->getNumero();
                    } else {
                        $numeroSub = $grupoSelecionado->getEntidadeAtiva()->getNumero() . '.' . $numeroSub;
                    }
                    $contagemHierarquica++;
                    if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
                        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                            break;
                        }
                    } else {
                        break;
                    }
                }
                $resposta = $grupoSelecionado->getEntidadeAtiva()->getNome() . "." . $numeroSub;
            } else {
                $resposta = $this->getNome();
            }
        } 
        return $resposta;
    }

    /**
     * Retorna a entidade tipo da entidade
     * @return EntidadeTipo
     */
    function getEntidadeTipo() {
        return $this->entidadeTipo;
    }

    /**
     * Retorna o grupo da Entidade
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    function getNome() {
        return $this->nome;
    }

    function getNumero() {
        return $this->numero;
    }

    function getTipo_id() {
        return $this->tipo_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setEntidadeTipo($entidadeTipo) {
        $this->entidadeTipo = $entidadeTipo;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setNome($nome) {
        $this->nome = strtoupper($nome);
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setTipo_id($tipo_id) {
        $this->tipo_id = $tipo_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

}
