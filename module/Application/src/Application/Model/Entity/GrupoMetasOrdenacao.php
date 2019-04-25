<?php

namespace Application\Model\Entity;
use Exception;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Nome: GrupoMetasOrdenacao.php
 * @author Ivan Tavares <ivanlsjt@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_metas_ordenacao 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="grupo_metas_ordenacao")
 */
class GrupoMetasOrdenacao extends CircuitoEntity {    
    
    /**
     * @ORM\ManyToOne(targetEntity="MetasOrdenacaoTipo", inversedBy="grupoMetasOrdenacao")
     * @ORM\JoinColumn(name="metas_ordenacao_tipo_id", referencedColumnName="id")
     */
    private $metasOrdenacaoTipo;

    /**
     * @ORM\ManyToOne(targetEntity="MetasOrdenacaoCriterio", inversedBy="grupoMetasOrdenacao")
     * @ORM\JoinColumn(name="metas_ordenacao_criterio_id", referencedColumnName="id")
     */
    private $metasOrdenacaoCriterio;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoMetasOrdenacao")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="decimal") */
    protected $valor_jovem;

    /** @ORM\Column(type="decimal") */
    protected $valor_adulto;

    /** @ORM\Column(type="integer") */
    protected $metas_ordenacao_criterio_id;

    /** @ORM\Column(type="integer") */
    protected $metas_ordenacao_tipo_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;         

    /**
     * Retorna a entidade MetasOrdenacaoTipo
     * @return MetasOrdenacaoTipo
     */
    function getMetasOrdenacaoTipo() {
        return $this->metasOrdenacaoTipo;
    }

    /**
     * Retorna a entidade MetasOrdenacaoCriterio
     * @return MetasOrdenacaoCriterio
     */
    function getMetasOrdenacaoCriterio() {
        return $this->metasOrdenacaoCriterio;
    }

    /**
     * Retorna o grupo da região vinculada a meta
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    function getMetas_ordenacao_criterio_id() {
        return $this->metas_ordenacao_criterio_id;
    }

    function getMetas_ordenacao_tipo_id() {
        return $this->metas_ordenacao_tipo_id;
    }    

    function getGrupo_id() {
        return $this->grupo_id;
    }   

    function getValorJovem() {
        return $this->valor_jovem;
    }

    function getValorAdulto() {
        return $this->valor_adulto;
    }

    function setMetasOrdenacaoTipo($metasOrdenacaoTipo) {
        $this->metasOrdenacaoTipo = $metasOrdenacaoTipo;
    }

    function setMetasOrdenacaoCriterio($metasOrdenacaoCriterio) {
        $this->metasOrdenacaoCriterio = $metasOrdenacaoCriterio;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setValorJovem($valor_jovem) {
        $this->valor_jovem = $valor_jovem;
    }

    function setValorAdulto($valor_adulto) {
        $this->valor_adulto = $valor_adulto;
    }    

    function setMetas_ordenacao_criterio_id($metas_ordenacao_criterio_id) {
        $this->metas_ordenacao_criterio_id = $metas_ordenacao_criterio_id;
    }

    function setMetas_ordenacao_tipo_id($metas_ordenacao_tipo_id) {
        $this->metas_ordenacao_tipo_id = $metas_ordenacao_tipo_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }
    
    function metaPorDificuldade($dificuldade = 1) {
        $meta = 0;
        switch($dificuldade){
            case 1: 
                $meta = $this->getValorJovem();
                break;
            case 2: 
                $meta = $this->getValorAdulto();                
                break;
        }
        return $meta;
    }

}
