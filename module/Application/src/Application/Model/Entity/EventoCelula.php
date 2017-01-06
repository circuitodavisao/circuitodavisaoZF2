<?php

namespace Application\Model\Entity;

/**
 * Nome: EventoCelula.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela evento_celula
 */
use Cadastro\Form\ConstantesForm;
use Doctrine\ORM\Mapping as ORM;
use SebastianBergmann\RecursionContext\Exception;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity 
 * @ORM\Table(name="evento_celula")
 */
class EventoCelula implements InputFilterAwareInterface {

    public function exchangeArray($data) {
        $this->nome_hospedeiro = (!empty($data[ConstantesForm::$FORM_NOME_HOSPEDEIRO]) ? strtoupper($data[ConstantesForm::$FORM_NOME_HOSPEDEIRO]) : null);
        $this->complemento = (!empty($data[ConstantesForm::$FORM_COMPLEMENTO]) ? strtoupper($data[ConstantesForm::$FORM_COMPLEMENTO]) : null);
    }

    protected $inputFilter;

    /**
     * @ORM\OneToOne(targetEntity="Evento")
     * @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $nome_hospedeiro;

    /** @ORM\Column(type="integer") */
    protected $telefone_hospedeiro;

    /** @ORM\Column(type="string") */
    protected $logradouro;

    /** @ORM\Column(type="string") */
    protected $complemento;

    /** @ORM\Column(type="string") */
    protected $cidade;

    /** @ORM\Column(type="string") */
    protected $bairro;

    /** @ORM\Column(type="integer") */
    protected $cep;

    /** @ORM\Column(type="string") */
    protected $uf;

    /** @ORM\Column(type="integer") */
    protected $evento_id;

    /**
     * Retorna o evento da célula
     * @return Evento
     */
    function getEvento() {
        return $this->evento;
    }

    function getId() {
        return $this->id;
    }

    function getNome_hospedeiro() {
        return $this->nome_hospedeiro;
    }

    function getNome_hospedeiroPrimeiroNome() {
        return explode(' ', $this->nome_hospedeiro)[0];
    }

    function getTelefone_hospedeiro() {
        return $this->telefone_hospedeiro;
    }

    function getTelefone_hospedeiroDDDSemTelefone() {
        return substr($this->telefone_hospedeiro, 0, 2);
    }

    function getTelefone_hospedeiroTelefoneSemDDD() {
        return substr($this->telefone_hospedeiro, 2);
    }

    function getTelefone_hospedeiroFormatado() {
        $telefoneFormatado = '(' .
                substr($this->getTelefone_hospedeiro(), 0, 2) .
                ')&nbsp;' .
                substr($this->getTelefone_hospedeiro(), 2, 4) .
                '-' .
                substr($this->getTelefone_hospedeiro(), 6);
        return $telefoneFormatado;
    }

    function getLogradouro() {
        return $this->logradouro;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome_hospedeiro($nome_hospedeiro) {
        $this->nome_hospedeiro = $nome_hospedeiro;
    }

    function setTelefone_hospedeiro($telefone_hospedeiro) {
        $this->telefone_hospedeiro = $telefone_hospedeiro;
    }

    function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    function getEvento_id() {
        return $this->evento_id;
    }

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCep() {
        return $this->cep;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function getUf() {
        return $this->uf;
    }

    function setUf($uf) {
        $this->uf = $uf;
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = Evento::getInputFilterEvento();

            /* CEP */
            $inputFilter->add(array(
                ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$FORM_CEP_LOGRADOURO,
                ConstantesForm::$VALIDACAO_REQUIRED => true,
                ConstantesForm::$VALIDACAO_FILTER => array(
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TAGS), // removel xml e html string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TRIM), // removel espaco do inicio e do final da string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_INT), //transforma string para inteiro
                ),
                ConstantesForm::$VALIDACAO_VALIDATORS => array(
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_NOT_EMPTY,
                    ),
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_LENGTH,
                        ConstantesForm::$VALIDACAO_OPTIONS => array(
                            ConstantesForm::$VALIDACAO_MIN => 8,
                            ConstantesForm::$VALIDACAO_MAX => 8,
                        ),
                    ),
                ),
            ));
            /* Nome Hospedeiro */
            $inputFilter->add(array(
                ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$FORM_NOME_HOSPEDEIRO,
                ConstantesForm::$VALIDACAO_REQUIRED => true,
                ConstantesForm::$VALIDACAO_FILTER => array(
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TAGS), // removel xml e html string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TRIM), // removel espaco do inicio e do final da string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TO_UPPER), // transforma em maiusculo
                ),
                ConstantesForm::$VALIDACAO_VALIDATORS => array(
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_NOT_EMPTY,
                    ),
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_LENGTH,
                        ConstantesForm::$VALIDACAO_OPTIONS => array(
                            ConstantesForm::$VALIDACAO_ENCODING => ConstantesForm::$VALIDACAO_UTF_8,
                            ConstantesForm::$VALIDACAO_MIN => 3,
                            ConstantesForm::$VALIDACAO_MAX => 80,
                        ),
                    ),
                ),
            ));

            /* DDD */
            $inputFilter->add(array(
                ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$FORM_DDD_HOSPEDEIRO,
                ConstantesForm::$VALIDACAO_REQUIRED => true,
                ConstantesForm::$VALIDACAO_FILTER => array(
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TAGS), // removel xml e html string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TRIM), // removel espaco do inicio e do final da string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_INT), //transforma string para inteiro
                ),
                ConstantesForm::$VALIDACAO_VALIDATORS => array(
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_NOT_EMPTY,
                    ),
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_LENGTH,
                        ConstantesForm::$VALIDACAO_OPTIONS => array(
                            ConstantesForm::$VALIDACAO_MIN => 2,
                            ConstantesForm::$VALIDACAO_MAX => 2,
                        ),
                    ),
                ),
            ));

            /* Telefone */
            $inputFilter->add(array(
                ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO,
                ConstantesForm::$VALIDACAO_REQUIRED => true,
                ConstantesForm::$VALIDACAO_FILTER => array(
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TAGS), // removel xml e html string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_TRIM), // removel espaco do inicio e do final da string
                    array(ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_INT), //transforma string para inteiro
                ),
                ConstantesForm::$VALIDACAO_VALIDATORS => array(
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_NOT_EMPTY,
                    ),
                    array(
                        ConstantesForm::$VALIDACAO_NAME => ConstantesForm::$VALIDACAO_STRING_LENGTH,
                        ConstantesForm::$VALIDACAO_OPTIONS => array(
                            ConstantesForm::$VALIDACAO_MIN => 8,
                            ConstantesForm::$VALIDACAO_MAX => 9,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }

}
