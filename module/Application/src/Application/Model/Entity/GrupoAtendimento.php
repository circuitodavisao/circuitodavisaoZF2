<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoAtendimento.php
 * @author Lucas Carvalho <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_atendimento
 */
use Application\Controller\Helper\Funcoes;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_atendimento")
 */
class GrupoAtendimento extends CircuitoEntity {

    protected $inputFilter;
    protected $inputFilterCadastrarAtendimento;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoAtendimento")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="string") */
    protected $dia;

    /** @ORM\Column(type="integer") */
    protected $quem;

    /** @ORM\Column(type="string") */
    protected $observacao;

    function getGrupo() {
        return $this->grupo;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function getDia() {
        $diaFormatado = Funcoes::mudarPadraoData($this->dia, 1);
        return $diaFormatado;
    }

    function getQuem() {
        return $this->quem;
    }

    function getObservacao() {
        return $this->observacao;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function setDia($dia) {
        $this->dia = $dia;
    }

    function setQuem($quem) {
        $this->quem = $quem;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    function getInputFilterCadastrarAtendimento() {
        if (!$this->inputFilterCadastrarAtendimento) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'dataAtendimento',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array(
                            'format' => "d/m/Y"
                        )
                    )
                ),
            ));

            $this->inputFilterCadastrarAtendimento = $inputFilter;
        }
        return $this->inputFilterCadastrarAtendimento;
    }

    function setInputFilter($inputFilter) {
        $this->inputFilter = $inputFilter;
    }

}
