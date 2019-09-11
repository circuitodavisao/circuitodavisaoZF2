<?php

namespace Application\Model\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Nome: TrocaResponsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela troca_responsavel  
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="troca_responsavel")
 */
class TrocaResponsavel extends CircuitoEntity {    

    /**
     * @ORM\OneToMany(targetEntity="ResolucaoResponsabilidade", mappedBy="trocaResponsavel") 
     */
    protected $resolucaoResponsabilidade;

    /** @ORM\Column(type="string") */
    protected $situacao;

    /** @ORM\Column(type="integer") */
    protected $regiao_id;

    public function __construct() {
        $this->resolucaoResponsabilidade = new ArrayCollection();
    }   

    function getResolucaoResponsabilidade() {
        return $this->resolucaoResponsabilidade;
    }

    function setResolucaoResponsabilidade($resolucaoResponsabilidade) {
        $this->resolucaoResponsabilidade = $resolucaoResponsabilidade;
    }

    function getSituacao() {
        return $this->situacao;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    function getRegiao_id() {
        return $this->regiao_id;
    }

    function setRegiao_id($regiao_id) {
        $this->regiao_id = $regiao_id;
    }

}
