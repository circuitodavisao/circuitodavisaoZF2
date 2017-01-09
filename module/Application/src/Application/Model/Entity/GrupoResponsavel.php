<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoResponsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_responsavel
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_responsavel")
 */
class GrupoResponsavel extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="grupoResponsavel")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoResponsavel")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /**
     * Verificar se a responsabilidade foi cadastrada nesse mês
     * @return boolean
     */
    public function verificarSeFoiCadastradoNesseMes() {
        $resposta = false;
        if ($this->getData_criacaoMes() == date('n') && $this->getData_criacaoAno() == date('Y')) {
            $resposta = true;
        }
        return $resposta;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * Retorna o grupo da responsabilidade
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    function getData_criacaoMes() {
        return explode('-', $this->data_criacao)[1];
    }

    function getData_criacaoAno() {
        return explode('-', $this->data_criacao)[0];
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

}
