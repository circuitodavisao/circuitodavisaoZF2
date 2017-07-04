<?php

namespace Application\Model\Entity;

/**
 * Nome: Situacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela situacao 
 * 1 - ATIVO
 * 2 - DESISTENTE
 * 3 - NÃO ENTROU
 * 4 - ESPECIAL
 * 5 - REPROVADO
 * 9 - RESERVA
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="situacao")
 */
class Situacao extends CircuitoEntity {

    /**
     * @ORM\OneToMany(targetEntity="AlunoSituacao", mappedBy="alunoSituacao") 
     */
    protected $alunoSituacao;

    public function __construct() {
        $this->alunoSituacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getAlunoSituacao() {
        return $this->alunoSituacao;
    }

    function setAlunoSituacao($alunoSituacao) {
        $this->alunoSituacao = $alunoSituacao;
    }

}
