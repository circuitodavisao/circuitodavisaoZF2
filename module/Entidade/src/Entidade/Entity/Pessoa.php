<?php

namespace Entidade\Entity;

/**
 * Nome: Pessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa
 */
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Login\Controller\Helper\Funcoes;
use SebastianBergmann\RecursionContext\Exception;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/** @ORM\Entity */
class Pessoa implements InputFilterAwareInterface {

    protected $inputFilter;
    protected $inputFilterPessoaFrequencia;

    /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="pessoa") 
     */
    protected $grupoResponsavel;

    /**
     * @ORM\OneToMany(targetEntity="TurmaAluno", mappedBy="pessoa") 
     */
    protected $turmaAluno;

    /**
     * @ORM\OneToMany(targetEntity="EventoFrequencia", mappedBy="pessoa") 
     */
    protected $eventoFrequencia;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="pessoa") 
     */
    protected $grupoPessoa;

    /**
     * @ORM\OneToMany(targetEntity="PessoaHierarquia", mappedBy="pessoa") 
     */
    protected $pessoaHierarquia;

    public function __construct() {
        $this->turmaAluno = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->eventoFrequencia = new ArrayCollection();
        $this->grupoPessoa = new ArrayCollection();
        $this->pessoaHierarquia = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $telefone;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $senha;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="string") */
    protected $data_nascimento;

    /** @ORM\Column(type="string") */
    protected $documento;

    /** @ORM\Column(type="string") */
    protected $token;

    /** @ORM\Column(type="string") */
    protected $token_data;

    /** @ORM\Column(type="string") */
    protected $token_hora;

    /** @ORM\Column(type="string") */
    protected $data_revisao;

    public function exchangeArray($data) {
        $this->nome = (!empty($data['nome']) ? strtoupper($data['nome']) : null);
    }

    protected $foto;
    protected $tipo;
    protected $transferido;
    protected $dataTransferido;
    protected $dataInativacao;
    protected $idGrupoPessoa;
    protected $ativo;

    /**
     * Recupera as Responsabilidades ativas
     * @return Entidade[]
     */
    function getResponsabilidadesAtivas() {
        $responsabilidadesAtivas = array();
        /* Responsabilidades */
        $responsabilidadesTodosStatus = $this->getGrupoResponsavel();
        if ($responsabilidadesTodosStatus) {
            /* Verificar responsabilidades ativas */
            foreach ($responsabilidadesTodosStatus as $responsabilidadeTodosStatus) {
                if ($responsabilidadeTodosStatus->verificarSeEstaAtivo()) {
                    $responsabilidadesAtivas[] = $responsabilidadeTodosStatus;
                }
            }
        }
        /* Ordenando */
        if ($responsabilidadesAtivas) {
            for ($i = 0; $i < count($responsabilidadesAtivas); $i++) {
                for ($j = 0; $j < count($responsabilidadesAtivas); $j++) {
                    $r[1] = $responsabilidadesAtivas[$i];
                    $tipo[1] = $r[1]->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId();

                    $r[2] = $responsabilidadesAtivas[$j];
                    $tipo[2] = $r[2]->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId();

                    if ($tipo[1] < $tipo[2]) {
                        $responsabilidadesAtivas[$j] = $r[1];
                        $responsabilidadesAtivas[$i] = $r[2];
                    }
                }
            }
        }
        return $responsabilidadesAtivas;
    }

    /**
     * Retorna o primeiro e ultimo nome da pessoa
     * @return String
     */
    function getNomePrimeiroUltimo() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        if (count($explodeNome) > 1) {
            $primeiroNome .= '&nbsp;' . $explodeNome[(count($explodeNome) - 1)];
        }
        return $primeiroNome;
    }

    /**
     * Retorna o primeiro e a sigla do ultimo nome da pessoa
     * @return String
     */
    function getNomePrimeiroPrimeiraSiglaUltimo() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        $ultimoNome = substr($explodeNome[(count($explodeNome) - 1)], 0, 1);
        return $primeiroNome . '&nbsp;' . $ultimoNome . '.';
    }

    /**
     * Retorna o nome formatado em relação a quantidade de eventos no ciclo
     * @param int $tipo
     * @return String
     */
    function getNomeListaDeLancamento($tipo = 0) {
        $nome = '';
        switch ($tipo) {
            case 1:
                if (strlen($this->getNome()) > 28) {
                    $nome = substr($this->getNome(), 0, 26) . '..';
                } else {
                    $nome = $this->getNome();
                }
                break;
            case 2:
                if (strlen($this->getNome()) > 20) {
                    $nome = substr($this->getNome(), 0, 18) . '..';
                } else {
                    $nome = $this->getNome();
                }
                break;
            case 3:
                if (strlen($this->getNome()) > 15) {
                    $nome = substr($this->getNome(), 0, 13) . '..';
                } else {
                    $nome = $this->getNome();
                }
                break;
            default:
                $nome = substr($this->getNome(), 0, 8) . '..';
                break;
        }

        return $nome;
    }

    /**
     * Verificar se esta transferido ou nao
     * @param type $mes
     * @param type $ano
     * @return boolean
     */
    public function verificarSeFoiTransferido($mes, $ano, $tipo = 0) {
        $resposta = false;
        if ($tipo == 0) {
            if ($this->getTransferido() == 'S' && $this->getDataTransferidoMes() == $mes && $this->getDataTransferidoAno() && $ano) {
                $resposta = true;
            } else {
                if (!$this->getAtivo()) {
                    if ($this->getTransferido() == 'S' && $this->getDataInativacaoMes() == $mes && $this->getDataInativacaoAno() && $ano) {
                        $resposta = true;
                    }
                }
            }
        }
        if ($tipo == 1) {
            if ($this->getTransferido() == 'S' && $this->getDataTransferidoMes() == $mes && $this->getDataTransferidoAno() && $ano) {
                $resposta = true;
            }
        }
        if ($tipo == 2) {
            if ($this->getTransferido() == 'S' && $this->getDataInativacaoMes() == $mes && $this->getDataInativacaoAno() && $ano) {
                $resposta = true;
            }
        }
        return $resposta;
    }

    /**
     * Verificar se te alguma responsabilidade que foi inativada no mes informado
     * @param String $data
     * @return GrupoResponsavel
     */
    public function verificarSeTemAlgumaResponsabilidadeInativadoNaDataInformado($data) {
        $grupoResponsavel = null;
        foreach ($this->getGrupoResponsavel() as $gr) {
            if (!$gr->verificarSeEstaAtivo() && $gr->getData_inativacao() == $data) {
                $grupoResponsavel = $gr;
                break;
            }
        }

        return $grupoResponsavel;
    }

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getSenha() {
        return $this->senha;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getData_nascimento() {
        return $this->data_nascimento;
    }

    function getData_nascimentoFormatada() {
        return Funcoes::mudarPadraoData($this->getData_nascimento(), 1);
    }

    function getDocumento() {
        return $this->documento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setSenha($senha) {
        $this->senha = md5($senha);
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    function setDocumento($documento) {
        $this->documento = $documento;
    }

    function getToken() {
        return $this->token;
    }

    /**
     * Seta token e data para validacao
     * @param String $token
     */
    function setToken($token) {
        $this->token = $token;
        $timeNow = new DateTime();
        $this->setToken_data($timeNow->format('Y-m-d'));
        $this->setToken_hora($timeNow->format('H:s:i'));
    }

    function getToken_data() {
        return $this->token_data;
    }

    function getToken_data_ano() {
        return substr($this->token_data, 0, 4);
    }

    function getToken_data_mes() {
        return substr($this->token_data, 5, 2);
    }

    function getToken_data_dia() {
        return substr($this->token_data, 8, 2);
    }

    function setToken_data($token_data) {
        $this->token_data = $token_data;
    }

    function getToken_hora() {
        return $this->token_hora;
    }

    function getToken_hora_hora() {
        return substr($this->token_hora, 0, 2);
    }

    function getToken_hora_minutos() {
        return substr($this->token_hora, 3, 2);
    }

    function getToken_hora_segundos() {
        return substr($this->token_hora, 6, 2);
    }

    function setToken_hora($token_hora) {
        $this->token_hora = $token_hora;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    /**
     * Retorna as responsailidades da pessoa
     * @return GrupoResponsavel
     */
    function getGrupoResponsavel() {
        return $this->grupoResponsavel;
    }

    function setGrupoResponsavel($grupoResponsavel) {
        $this->grupoResponsavel = $grupoResponsavel;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * Retorna os eventoFrequencia
     * @return EventoFrequencia
     */
    function getEventoFrequencia() {
        return $this->eventoFrequencia;
    }

    function setEventoFrequencia($eventoFrequencia) {
        $this->eventoFrequencia = $eventoFrequencia;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getFoto() {
        return $this->foto;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function getTransferido() {
        return $this->transferido;
    }

    function setTransferido($transferido, $dataTransferencia, $dataInativacao) {
        $this->transferido = $transferido;
        $this->setDataTransferido($dataTransferencia);
        $this->setDataInativacao($dataInativacao);
    }

    function getDataTransferido() {
        return $this->dataTransferido;
    }

    function setDataTransferido($dataTransferido) {
        $this->dataTransferido = $dataTransferido;
    }

    function getDataTransferidoAno() {
        return explode('-', $this->getDataTransferido())[0];
    }

    function getDataTransferidoMes() {
        return explode('-', $this->getDataTransferido())[1];
    }

    function getDataTransferidoDia() {
        return explode('-', $this->getDataTransferido())[2];
    }

    function getIdGrupoPessoa() {
        return $this->idGrupoPessoa;
    }

    function setIdGrupoPessoa($idGrupoPessoa) {
        $this->idGrupoPessoa = $idGrupoPessoa;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function getDataInativacao() {
        return $this->dataInativacao;
    }

    function setDataInativacao($dataInativacao) {
        $this->dataInativacao = $dataInativacao;
    }

    function getDataInativacaoAno() {
        $resposta = '';
        if (!empty($this->getDataInativacao())) {
            $resposta = explode('-', $this->getDataInativacao())[0];
        }
        return $resposta;
    }

    function getDataInativacaoMes() {
        $resposta = '';
        if (!empty($this->getDataInativacao())) {
            $resposta = explode('-', $this->getDataInativacao())[1];
        }
        return $resposta;
    }

    function getDataInativacaoDia() {
        $resposta = '';
        if (!empty($this->getDataInativacao())) {
            $resposta = explode('-', $this->getDataInativacao())[2];
        }
        return $resposta;
    }

    function getData_revisao() {
        return $this->data_revisao;
    }

    function setData_revisao($data_revisao) {
        $this->data_revisao = $data_revisao;
    }

    function getData_revisaoAno() {
        $resposta = '';
        if (!empty($this->getData_revisao())) {
            $resposta = explode('-', $this->getData_revisao())[0];
        }
        return $resposta;
    }

    function getData_revisaoMes() {
        $resposta = '';
        if (!empty($this->getData_revisao())) {
            $resposta = explode('-', $this->getData_revisao())[1];
        }
        return $resposta;
    }

    function verificaSeRevisaoFoiCadastraddoNoMesEAno($mes, $ano) {
        $resposta = false;
        if ($mes == $this->getData_revisaoMes() && $ano == $this->getData_revisaoAno()) {
            $resposta = true;
        }
        return $resposta;
    }

    function getTurmaAluno() {
        return $this->turmaAluno;
    }

    /**
     * Retorna a turma aluno ativo
     * @return TurmaAluno
     */
    function getTurmaAlunoAtivo() {
        $turmaAlunoAtiva = null;
        foreach ($this->getTurmaAluno() as $ta) {
            if ($ta->verificarSeEstaAtivo()) {
                $turmaAlunoAtiva = $ta;
                break;
            }
        }

        return $turmaAlunoAtiva;
    }

    /**
     * Retorna o grupo pessoa ativo
     * @return GrupoPessoa
     */
    function getGrupoPessoaAtivo() {
        $grupoPessoaAtiva = null;
        foreach ($this->getGrupoPessoa() as $gp) {
            if ($gp->verificarSeEstaAtivo()) {
                $grupoPessoaAtiva = $gp;
                break;
            }
        }
        return $grupoPessoaAtiva;
    }

    function setTurmaAluno($turmaAluno) {
        $this->turmaAluno = $turmaAluno;
    }

    /**
     * Retorna o GrupoPessoa
     * @return GrupoPessoa
     */
    function getGrupoPessoa() {
        return $this->grupoPessoa;
    }

    function setGrupoPessoa($grupoPessoa) {
        $this->grupoPessoa = $grupoPessoa;
    }

    public function getIdade() {
        $idade = 0;
        if ($this->getData_nascimento()) {
            // Separa em dia, mês e ano
            list($ano, $mes, $dia) = explode('-', $this->getData_nascimento());

            // Descobre que dia é hoje e retorna a unix timestamp
            $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            // Descobre a unix timestamp da data de nascimento
            $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

            // Depois apenas fazemos o cálculo
            $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
        }
        return $idade;
    }

    public function getDataNascimentoFormatada() {
        $resposta = '';
        if ($this->getData_nascimento()) {
            $resposta = Funcoes::mudarPadraoData($this->getData_nascimento(), 1);
        }
        return $resposta;
    }

    public function getInputFilter() {
        
    }

    public function getInputFilterPessoaFrequencia() {
        if (!$this->inputFilterPessoaFrequencia) {
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
                            'max' => 80,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'ddd',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'Int'), // transforma string para inteiro
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 2,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'telefone',
                'required' => true,
                'filter' => array(
                    array('name' => 'StripTags'), // removel xml e html string
                    array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
                    array('name' => 'Int'), // transforma string para inteiro
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 8, # xx xxxx-xxxx
                            'max' => 9, # xx xxxx-xxxxx
                        ),
                    ),
                ),
            ));
            $this->inputFilterPessoaFrequencia = $inputFilter;
        }
        return $this->inputFilterPessoaFrequencia;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }

    function getPessoaHierarquia() {
        return $this->pessoaHierarquia;
    }

    function setPessoaHierarquia($pessoaHierarquia) {
        $this->pessoaHierarquia = $pessoaHierarquia;
    }

}
