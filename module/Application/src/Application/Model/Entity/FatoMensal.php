<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_mensal")
 */
class FatoMensal extends CircuitoEntity {

	public $cabecalho;
	public $nao1;
	public $nao2;
	public $nao3;
	public $nao4;
	public $nao5;
	public $nao6;

	/** @ORM\Column(type="string") */
	protected $numero_identificador;

	/** @ORM\Column(type="string") */
	protected $nome_igreja;

	/** @ORM\Column(type="string") */
	protected $entidade;

	/** @ORM\Column(type="string") */
	protected $lideres;

	/** @ORM\Column(type="integer") */
	protected $mes;

	/** @ORM\Column(type="integer") */
	protected $ano;

	/** @ORM\Column(type="integer") */
	protected $lb1;

	/** @ORM\Column(type="integer") */
	protected $lb2;

	/** @ORM\Column(type="integer") */
	protected $lb3;

	/** @ORM\Column(type="integer") */
	protected $lb4;

	/** @ORM\Column(type="integer") */
	protected $lb5;

	/** @ORM\Column(type="integer") */
	protected $lb6;

	/** @ORM\Column(type="integer") */
	protected $l1;

	/** @ORM\Column(type="integer") */
	protected $l2;

	/** @ORM\Column(type="integer") */
	protected $l3;

	/** @ORM\Column(type="integer") */
	protected $l4;

	/** @ORM\Column(type="integer") */
	protected $l5;

	/** @ORM\Column(type="integer") */
	protected $l6;

	/** @ORM\Column(type="integer") */
	protected $cu1;

	/** @ORM\Column(type="integer") */
	protected $cu2;

	/** @ORM\Column(type="integer") */
	protected $cu3;

	/** @ORM\Column(type="integer") */
	protected $cu4;

	/** @ORM\Column(type="integer") */
	protected $cu5;

	/** @ORM\Column(type="integer") */
	protected $cu6;

	/** @ORM\Column(type="integer") */
	protected $c1;

	/** @ORM\Column(type="integer") */
	protected $c2;

	/** @ORM\Column(type="integer") */
	protected $c3;

	/** @ORM\Column(type="integer") */
	protected $c4;

	/** @ORM\Column(type="integer") */
	protected $c5;

	/** @ORM\Column(type="integer") */
	protected $c6;

	/** @ORM\Column(type="decimal") */
	protected $cp1;

	/** @ORM\Column(type="decimal") */
	protected $cp2;

	/** @ORM\Column(type="decimal") */
	protected $cp3;

	/** @ORM\Column(type="decimal") */
	protected $cp4;

	/** @ORM\Column(type="decimal") */
	protected $cp5;

	/** @ORM\Column(type="decimal") */
	protected $cp6;

	/** @ORM\Column(type="decimal") */
	protected $mediac;

	/** @ORM\Column(type="decimal") */
	protected $mediacp;

	/** @ORM\Column(type="string") */
	protected $mediacpclass;

	/** @ORM\Column(type="integer") */
	protected $a1;

	/** @ORM\Column(type="integer") */
	protected $a2;

	/** @ORM\Column(type="integer") */
	protected $a3;

	/** @ORM\Column(type="integer") */
	protected $a4;

	/** @ORM\Column(type="integer") */
	protected $a5;

	/** @ORM\Column(type="integer") */
	protected $a6;

	/** @ORM\Column(type="integer") */
	protected $d1;

	/** @ORM\Column(type="integer") */
	protected $d2;

	/** @ORM\Column(type="integer") */
	protected $d3;

	/** @ORM\Column(type="integer") */
	protected $d4;

	/** @ORM\Column(type="integer") */
	protected $d5;

	/** @ORM\Column(type="integer") */
	protected $d6;

	/** @ORM\Column(type="decimal") */
	protected $mem1;

	/** @ORM\Column(type="decimal") */
	protected $mem2;

	/** @ORM\Column(type="decimal") */
	protected $mem3;

	/** @ORM\Column(type="decimal") */
	protected $mem4;

	/** @ORM\Column(type="decimal") */
	protected $mem5;

	/** @ORM\Column(type="decimal") */
	protected $mem6;

	/** @ORM\Column(type="decimal") */
	protected $memp1;

	/** @ORM\Column(type="decimal") */
	protected $memp2;

	/** @ORM\Column(type="decimal") */
	protected $memp3;

	/** @ORM\Column(type="decimal") */
	protected $memp4;

	/** @ORM\Column(type="decimal") */
	protected $memp5;

	/** @ORM\Column(type="decimal") */
	protected $memp6;

	/** @ORM\Column(type="decimal") */
	protected $mediamem;

	/** @ORM\Column(type="decimal") */
	protected $mediamemp;

	/** @ORM\Column(type="string") */
	protected $mediamempclass;

	/** @ORM\Column(type="integer") */
	protected $cq1;

	/** @ORM\Column(type="integer") */
	protected $cq2;

	/** @ORM\Column(type="integer") */
	protected $cq3;
	
	/** @ORM\Column(type="integer") */
	protected $cq4;

	/** @ORM\Column(type="integer") */
	protected $cq5;

	/** @ORM\Column(type="integer") */
	protected $cq6;

	/** @ORM\Column(type="decimal") */
	protected $cqmeta1;

	/** @ORM\Column(type="decimal") */
	protected $cqmeta2;

	/** @ORM\Column(type="decimal") */
	protected $cqmeta3;
	
	/** @ORM\Column(type="decimal") */
	protected $cqmeta4;

	/** @ORM\Column(type="decimal") */
	protected $cqmeta5;

	/** @ORM\Column(type="decimal") */
	protected $cqmeta6;

	/** @ORM\Column(type="integer") */
	protected $cbq1;

	/** @ORM\Column(type="integer") */
	protected $cbq2;

	/** @ORM\Column(type="integer") */
	protected $cbq3;
	
	/** @ORM\Column(type="integer") */
	protected $cbq4;

	/** @ORM\Column(type="integer") */
	protected $cbq5;

	/** @ORM\Column(type="integer") */
	protected $cbq6;

	/** @ORM\Column(type="decimal") */
	protected $cbqmeta1;

	/** @ORM\Column(type="decimal") */
	protected $cbqmeta2;

	/** @ORM\Column(type="decimal") */
	protected $cbqmeta3;
	
	/** @ORM\Column(type="decimal") */
	protected $cbqmeta4;

	/** @ORM\Column(type="decimal") */
	protected $cbqmeta5;

	/** @ORM\Column(type="decimal") */
	protected $cbqmeta6;

	/** @ORM\Column(type="integer") */
	protected $realizada1;

	/** @ORM\Column(type="integer") */
	protected $realizada2;

	/** @ORM\Column(type="integer") */
	protected $realizada3;

	/** @ORM\Column(type="integer") */
	protected $realizada4;

	/** @ORM\Column(type="decimal") */
	protected $realizada5;

	/** @ORM\Column(type="decimal") */
	protected $realizada6;

	/** @ORM\Column(type="decimal") */
	protected $realizadap1;

	/** @ORM\Column(type="decimal") */
	protected $realizadap2;

	/** @ORM\Column(type="decimal") */
	protected $realizadap3;

	/** @ORM\Column(type="decimal") */
	protected $realizadap4;

	/** @ORM\Column(type="decimal") */
	protected $realizadap5;

	/** @ORM\Column(type="decimal") */
	protected $realizadap6;

	/** @ORM\Column(type="decimal") */
	protected $mediarealizada;

	/** @ORM\Column(type="decimal") */
	protected $mediarealizadap;

	/** @ORM\Column(type="string") */
	protected $mediarealizadapclass;

	/** @ORM\Column(type="integer") */
	protected $somacelula;

	/** @ORM\Column(type="integer") */
	protected $somavisitantes;

	/** @ORM\Column(type="decimal") */
	protected $somaparceiro;

	/** @ORM\Column(type="integer") */
	protected $multiplicadormetasetenta;

	function getRealizada1(){
		return $this->realizada1;
	}
	function getRealizada2(){
		return $this->realizada2;
	}
	function getRealizada3(){
		return $this->realizada3;
	}
	function getRealizada4(){
		return $this->realizada4;
	}
	function getRealizada5(){
		return $this->realizada5;
	}
	function getRealizada6(){
		return $this->realizada6;
	}
	function getRealizadap1(){
		return $this->realizadap1;
	}
	function getRealizadap2(){
		return $this->realizadap2;
	}
	function getRealizadap3(){
		return $this->realizadap3;
	}
	function getRealizadap4(){
		return $this->realizadap4;
	}
	function getRealizadap5(){
		return $this->realizadap5;
	}
	function getRealizadap6(){
		return $this->realizadap6;
	}
	function getMediarealizada(){
		return $this->mediarealizada;
	}
	function getMediarealizadap(){
		return $this->mediarealizadap;
	}
	function getMediarealizadapclass(){
		return $this->mediarealizadapclass;
	}
	function getCp1(){
		return $this->cp1;
	}
	function getCp2(){
		return $this->cp2;
	}
	function getCp3(){
		return $this->cp3;
	}
	function getCp4(){
		return $this->cp4;
	}
	function getCp5(){
		return $this->cp5;
	}
	function getCp6(){
		return $this->cp6;
	}
	function getMediac(){
		return $this->mediac;
	}
	function getMediacp(){
		return $this->mediacp;
	}
	function getMediacpclass(){
		return $this->mediacpclass;
	}
	function getNumero_identificador() {
		return $this->numero_identificador;
	}
	function getNome_igreja(){
		return $this->nome_igreja;
	}
	function getEntidade(){
		return $this->entidade;
	}
	function getLideres(){
		return $this->lideres;
	}
	function getMes(){
		return $this->mes;
	}
	function getAno(){
		return $this->ano;
	}
	function getCu1(){
		return $this->cu1;
	}
	function getCu2(){
		return $this->cu2;
	}
	function getCu3(){
		return $this->cu3;
	}
	function getCu4(){
		return $this->cu4;
	}
	function getCu5(){
		return $this->cu5;
	}
	function getCu6(){
		return $this->cu6;
	}
	function getC1(){
		return $this->c1;
	}
	function getC2(){
		return $this->c2;
	}
	function getC3(){
		return $this->c3;
	}
	function getC4(){
		return $this->c4;
	}
	function getC5(){
		return $this->c5;
	}
	function getC6(){
		return $this->c6;
	}
	function getA1(){
		return $this->a1;
	}
	function getA2(){
		return $this->a2;
	}
	function getA3(){
		return $this->a3;
	}
	function getA4(){
		return $this->a4;
	}
	function getA5(){
		return $this->a5;
	}
	function getA6(){
		return $this->a6;
	}
	function getD1(){
		return $this->d1;
	}
	function getD2(){
		return $this->d2;
	}
	function getD3(){
		return $this->d3;
	}
	function getD4(){
		return $this->d4;
	}
	function getD5(){
		return $this->d5;
	}
	function getD6(){
		return $this->d6;
	}
	function getMem1(){
		return $this->mem1;
	}
	function getMem2(){
		return $this->mem2;
	}
	function getMem3(){
		return $this->mem3;
	}
	function getMem4(){
		return $this->mem4;
	}
	function getMem5(){
		return $this->mem5;
	}
	function getMem6(){
		return $this->mem6;
	}
	function getMemp1(){
		return $this->memp1;
	}
	function getMemp2(){
		return $this->memp2;
	}
	function getMemp3(){
		return $this->memp3;
	}
	function getMemp4(){
		return $this->memp4;
	}
	function getMemp5(){
		return $this->memp5;
	}
	function getMemp6(){
		return $this->memp6;
	}
	function getMediamem(){
		return $this->mediamem;
	}
	function getMediamemp(){
		return $this->mediamemp;
	}
	function getMediamempclass(){
		return $this->mediamempclass;
	}
	function getCq1(){
		return $this->cq1;
	}
	function getCq2(){
		return $this->cq2;
	}
	function getCq3(){
		return $this->cq3;
	}
	function getCq4(){
		return $this->cq4;
	}
	function getCq5(){
		return $this->cq5;
	}
	function getCq6(){
		return $this->cq6;
	}
	function getCqmeta1(){
		return $this->cqmeta1;
	}
	function getCqmeta2(){
		return $this->cqmeta2;
	}
	function getCqmeta3(){
		return $this->cqmeta3;
	}
	function getCqmeta4(){
		return $this->cqmeta4;
	}
	function getCqmeta5(){
		return $this->cqmeta5;
	}
	function getCqmeta6(){
		return $this->cqmeta6;
	}
	function getCbq1(){
		return $this->cbq1;
	}
	function getCbq2(){
		return $this->cbq2;
	}
	function getCbq3(){
		return $this->cbq3;
	}
	function getCbq4(){
		return $this->cbq4;
	}
	function getCbq5(){
		return $this->cbq5;
	}
	function getCbq6(){
		return $this->cbq6;
	}
	function getCbqmeta1(){
		return $this->cbqmeta1;
	}
	function getCbqmeta2(){
		return $this->cbqmeta2;
	}
	function getCbqmeta3(){
		return $this->cbqmeta3;
	}
	function getCbqmeta4(){
		return $this->cbqmeta4;
	}
	function getCbqmeta5(){
		return $this->cbqmeta5;
	}
	function getCbqmeta6(){
		return $this->cbqmeta6;
	}

	function setRealizada1($item){
		$this->realizada1 = $item;
	}
	function setRealizada2($item){
		$this->realizada2 = $item;
	}
	function setRealizada3($item){
		$this->realizada3 = $item;
	}
	function setRealizada4($item){
		$this->realizada4 = $item;
	}
	function setRealizada5($item){
		$this->realizada5 = $item;
	}
	function setRealizada6($item){
		$this->realizada6 = $item;
	}
	function setRealizadap1($item){
		$this->realizadap1 = $item;
	}
	function setRealizadap2($item){
		$this->realizadap2 = $item;
	}
	function setRealizadap3($item){
		$this->realizadap3 = $item;
	}
	function setRealizadap4($item){
		$this->realizadap4 = $item;
	}
	function setRealizadap5($item){
		$this->realizadap5 = $item;
	}
	function setRealizadap6($item){
		$this->realizadap6 = $item;
	}
	function setMediarealizada($item){
		$this->mediarealizada = $item;
	}
	function setMediarealizadap($item){
		$this->mediarealizadap = $item;
	}
	function setMediarealizadapclass($item){
		$this->mediarealizadapclass = $item;
	}
	function setEntidade($item){
		$this->entidade = $item;
	}
	function setLideres($item){
		$this->lideres = $item;
	}
	function setMes($item){
		$this->mes = $item;
	}
	function setAno($item){
		$this->ano = $item;
	}
	function setCu1($item){
		$this->cu1 = $item;
	}
	function setCu2($item){
		$this->cu2 = $item;
	}
	function setCu3($item){
		$this->cu3 = $item;
	}
	function setCu4($item){
		$this->cu4 = $item;
	}
	function setCu5($item){
		$this->cu5 = $item;
	}
	function setCu6($item){
		$this->cu6 = $item;
	}
	function setC1($item){
		$this->c1 = $item;
	}
	function setC2($item){
		$this->c2 = $item;
	}
	function setC3($item){
		$this->c3 = $item;
	}
	function setC4($item){
		$this->c4 = $item;
	}
	function setC5($item){
		$this->c5 = $item;
	}
	function setC6($item){
		$this->c6 = $item;
	}
	function setA1($item){
		$this->a1 = $item;
	}
	function setA2($item){
		$this->a2 = $item;
	}
	function setA3($item){
		$this->a3 = $item;
	}
	function setA4($item){
		$this->a4 = $item;
	}
	function setA5($item){
		$this->a5 = $item;
	}
	function setA6($item){
		$this->a6 = $item;
	}
	function setD1($item){
		$this->d1 = $item;
	}
	function setD2($item){
		$this->d2 = $item;
	}
	function setD3($item){
		$this->d3 = $item;
	}
	function setD4($item){
		$this->d4 = $item;
	}
	function setD5($item){
		$this->d5 = $item;
	}
	function setD6($item){
		$this->d6 = $item;
	}
	function setMem1($item){
		$this->mem1 = $item;
	}
	function setMem2($item){
		$this->mem2 = $item;
	}
	function setMem3($item){
		$this->mem3 = $item;
	}
	function setMem4($item){
		$this->mem4 = $item;
	}
	function setMem5($item){
		$this->mem5 = $item;
	}
	function setMem6($item){
		$this->mem6 = $item;
	}
	function setMemp1($item){
		$this->memp1 = $item;
	}
	function setMemp2($item){
		$this->memp2 = $item;
	}
	function setMemp3($item){
		$this->memp3 = $item;
	}
	function setMemp4($item){
		$this->memp4 = $item;
	}
	function setMemp5($item){
		$this->memp5 = $item;
	}
	function setMemp6($item){
		$this->memp6 = $item;
	}
	function setMediamem($item){
		$this->mediamem = $item;
	}
	function setMediamemp($item){
		$this->mediamemp = $item;
	}
	function setMediamempclass($item){
		$this->mediamempclass = $item;
	}
	function setNumero_identificador($numero_identificador) {
		$this->numero_identificador = $numero_identificador;
	}
	function setNome_igreja($item){
		$this->nome_igreja = $item;
	}
	function setCq1($item){
		$this->cq1 = $item;
	}
	function setCq2($item){
		$this->cq2 = $item;
	}
	function setCq3($item){
		$this->cq3 = $item;
	}
	function setCq4($item){
		$this->cq4 = $item;
	}
	function setCq5($item){
		$this->cq5 = $item;
	}
	function setCq6($item){
		$this->cq6 = $item;
	}
	function setCqmeta1($item){
		$this->cqmeta1 = $item;
	}
	function setCqmeta2($item){
		$this->cqmeta2 = $item;
	}
	function setCqmeta3($item){
		$this->cqmeta3 = $item;
	}
	function setCqmeta4($item){
		$this->cqmeta4 = $item;
	}
	function setCqmeta5($item){
		$this->cqmeta5 = $item;
	}
	function setCqmeta6($item){
		$this->cqmeta6 = $item;
	}
	function setCbq1($item){
		$this->cbq1 = $item;
	}
	function setCbq2($item){
		$this->cbq2 = $item;
	}
	function setCbq3($item){
		$this->cbq3 = $item;
	}
	function setCbq4($item){
		$this->cbq4 = $item;
	}
	function setCbq5($item){
		$this->cbq5 = $item;
	}
	function setCbq6($item){
		$this->cbq6 = $item;
	}
	function setCbqmeta1($item){
		$this->cbqmeta1 = $item;
	}
	function setCbqmeta2($item){
		$this->cbqmeta2 = $item;
	}
	function setCbqmeta3($item){
		$this->cbqmeta3 = $item;
	}
	function setCbqmeta4($item){
		$this->cbqmeta4 = $item;
	}
	function setCbqmeta5($item){
		$this->cbqmeta5 = $item;
	}
	function setCbqmeta6($item){
		$this->cbqmeta6 = $item;
	}
	function setCp1($item){
		$this->cp1 = $item;
	}
	function setCp2($item){
		$this->cp2 = $item;
	}
	function setCp3($item){
		$this->cp3 = $item;
	}
	function setCp4($item){
		$this->cp4 = $item;
	}
	function setCp5($item){
		$this->cp5 = $item;
	}
	function setCp6($item){
		$this->cp6 = $item;
	}
	function setMediac($item){
		$this->mediac = $item;
	}
	function setMediacp($item){
		$this->mediacp = $item;
	}
	function setMediacpclass($item){
		$this->mediacpclass = $item;
	}

	function setL1($item){
		$this->l1 = $item;
	}
	function setL2($item){
		$this->l2 = $item;
	}
	function setL3($item){
		$this->l3 = $item;
	}
	function setL4($item){
		$this->l4 = $item;
	}
	function setL5($item){
		$this->l5 = $item;
	}
	function setL6($item){
		$this->l6 = $item;
	}
	function setLb1($item){
		$this->lb1 = $item;
	}
	function setLb2($item){
		$this->lb2 = $item;
	}
	function setLb3($item){
		$this->lb3 = $item;
	}
	function setLb4($item){
		$this->lb4 = $item;
	}
	function setLb5($item){
		$this->lb5 = $item;
	}
	function setLb6($item){
		$this->lb6 = $item;
	}
	
	function getL1(){
		return $this->l1;
	}
	function getL2(){
		return $this->l2;
	}
	function getL3(){
		return $this->l3;
	}
	function getL4(){
		return $this->l4;
	}
	function getL5(){
		return $this->l5;
	}
	function getL6(){
		return $this->l6;
	}
	function getLb1(){
		return $this->lb1;
	}
	function getLb2(){
		return $this->lb2;
	}
	function getLb3(){
		return $this->lb3;
	}
	function getLb4(){
		return $this->lb4;
	}
	function getLb5(){
		return $this->lb5;
	}
	function getLb6(){
		return $this->lb6;
	}

	function setSomacelula($somacelula) {
		$this->somacelula = $somacelula;
	}
	function setSomavisitantes($somavisitantes) {
		$this->somavisitantes = $somavisitantes;
	}
	function setSomaparceiro($somaparceiro) {
		$this->somaparceiro = $somaparceiro;
	}
	function setMultiplicadormetasetenta($multiplicadormetasetenta) {
		$this->multiplicadormetasetenta = $multiplicadormetasetenta;
	}
	function getSomacelula(){
		return $this->somacelula;
	}
	function getSomavisitantes(){
		return $this->somavisitantes;
	}
	function getSomaparceiro() {
		return $this->somaparceiro;
	}
	function getMultiplicadormetasetenta(){
		return $this->multiplicadormetasetenta;
	}

	public function __set($name,$value) {
		$functionname='set'.ucfirst($name);
		return $this->$functionname($value);
	}
	public function __get($name) {
		$functionname='get'.ucfirst($name);
		return $this->$functionname();
	}

}
