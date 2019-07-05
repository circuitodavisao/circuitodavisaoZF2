<?php

namespace Application\Model\Entity;
use Exception;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

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
class Entidade extends CircuitoEntity implements InputFilterAwareInterface {

    const SECRETARIO = 8;
    const SUBEQUIPE = 7;
    const EQUIPE = 6;
    const IGREJA = 5;
    const COORDENACAO = 4;
    const REGIONAL = 3;
    const NACIONAL = 2;
    const PRESIDENTE = 1;

    protected $inputFilter;
    protected $inputFilterAlterarNome;

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

     /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="entidade")
     * @ORM\JoinColumn(name="secretario_grupo_id", referencedColumnName="id")
     */
    private $grupoSecretario;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $sigla;

    /** @ORM\Column(type="integer") */
    protected $numero;

    /** @ORM\Column(type="integer") */
    protected $tipo_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $secretario_grupo_id;

    public function infoEntidade($somenteNumero = false) {
        $resposta = '';
        $grupoSelecionado = $this->getGrupo();
        if ($this->verificarSeEstaAtivo()) {
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                $numeroSub = '';
                $contagemHierarquica = 0;
                while ($grupoSelecionado->getEntidadeAtiva() && $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                    if ($contagemHierarquica == 0) {
                        $numeroSub = $grupoSelecionado->getEntidadeAtiva()->getNumero();
                    } else {
                        $numeroSub = $grupoSelecionado->getEntidadeAtiva()->getNumero() . '.' . $numeroSub;
                    }
                    $contagemHierarquica++;
                    if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
                        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                        if ($grupoSelecionado->getEntidadeAtiva() && $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                            break;
                        }
                    } else {
                        break;
                    }
                }
				if($grupoSelecionado->getEntidadeAtiva()){
					$resposta = $grupoSelecionado->getEntidadeAtiva()->getNome() . "." . $numeroSub;
				}
                if ($somenteNumero) {
                    $resposta = $numeroSub;
                }
            } 
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::COORDENACAO) {
                $resposta = 'COORDENAÇÃO: ' . $grupoSelecionado->getEntidadeAtiva()->getNumero();
            }
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() !== Entidade::COORDENACAO
                || $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() !== Entidade::SUBEQUIPE) {
                $resposta = $grupoSelecionado->getEntidadeAtiva()->getNome();
            }            
        }
        return $resposta;
    }

    public function getGrupoEquipe() {
        $resposta = 0;
        $grupoSelecionado = $this->getGrupo();
        if ($this->verificarSeEstaAtivo()) {
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                    if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
                        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                            break;
                        }
                    } else {
                        break;
                    }
                }
            }
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                $resposta = $grupoSelecionado->getId();
            }
        }
        return $resposta;
    }

    public function getInputFilterAlterarNome() {
        if (!$this->inputFilterAlterarNome) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'nome',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'StringToUpper'), // transforma em maiusculo
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 15,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'sigla',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'StringToUpper'), // transforma em maiusculo
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 3,
                        ),
                    ),
                ),
            ));
            $this->inputFilterPessoaFrequencia = $inputFilter;
        }
        return $this->inputFilterPessoaFrequencia;
    }

    /**
     * Retorna a entidade tipo da entidade
     * @return EntidadeTipo
     */
    function getEntidadeTipo() {
        if($this->grupoSecretario){
            return $this->getGrupo()->getEntidadeAtiva()->getEntidadeTipo();
        } else {
            return $this->entidadeTipo;
        }        
    }

    /**
     * Retorna o grupo da Entidade
     * @return Grupo
     */
    function getGrupo() {
        if($this->grupoSecretario){
            return $this->grupoSecretario;
        } else {
            return $this->grupo;
        }        
    }

    function getNome() {
        return $this->nome;
    }

    function getSigla() {
        return $this->sigla;
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

    function getSecretario_Grupo_id() {
        return $this->secretario_grupo_id;
    }   
    
    function setSecretario_Grupo_id($secretario_grupo_id) {
        $this->secretario_grupo_id = $secretario_grupo_id;
    }

    function setEntidadeTipo($entidadeTipo) {
        $this->entidadeTipo = $entidadeTipo;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setGrupoSecretario($grupoSecretario) {
        $this->grupoSecretario = $grupoSecretario;
    }

    function setNome($nome) {
        $this->nome = strtoupper($nome);
    }

    function setSigla($sigla) {
        $this->sigla = strtoupper($sigla);
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

    public function getInputFilter() {

    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }

}
