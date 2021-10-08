<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Correios;
use Application\Controller\Helper\Funcoes;
use Application\Form\AtivarFichaForm;
use Application\Form\AtualizarCadastroForm;
use Application\Form\CadastrarPessoaRevisaoForm;
use Application\Form\CelulaForm;
use Application\Form\EventoForm;
use Application\Form\GrupoForm;
use Application\Form\SelecionarCrachaForm;
use Application\Form\SolicitacaoForm;
use Application\Form\SolicitacaoReceberForm;
use Application\Form\RevisaoForm;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Evento;
use Application\Model\Entity\EventoCelula;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoEvento;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\Entity\GrupoMetasOrdenacao;
use Application\Model\Entity\MetasOrdenacaoCriterio;
use Application\Model\Entity\MetasOrdenacaoTipo;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\ResolucaoResponsabilidade;
use Application\Model\Entity\TrocaResponsavel;
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\Solicitacao;
use Application\Model\Entity\SolicitacaoSituacao;
use Application\Model\Entity\SolicitacaoTipo;
use Application\Model\Entity\FatoLider;
use Application\Model\Entity\CursoAcesso;
use Application\Model\Entity\RegistroAcao;
use Application\Model\Entity\FatoCelulaDiscipulado;
use Application\Model\Entity\FatoRevisao;
use Application\Model\ORM\RepositorioORM;
use DateTime;
use Exception;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class CadastroController extends CircuitoController {

	/**
	 * Função padrão, traz a tela para lancamento
	 * GET /cadastro[:pagina]
	 */
	public function indexAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$sessao->pagina = '';
		$extra = '';
		/* Verificando rota */
		$pagina = $this->getEvent()->getRouteMatch()->getParam(Constantes::$PAGINA, 1);
		if ($pagina == Constantes::$PAGINA_EVENTO_CULTO
			|| $pagina == Constantes::$PAGINA_EVENTO_CELULA
			|| $pagina == Constantes::$PAGINA_EVENTO_DISCIPULADO
		) {
			if ($pagina == Constantes::$PAGINA_EVENTO_CULTO) {
				$sessao->pagina = Constantes::$PAGINA_EVENTO_CULTO;
			}
			if ($pagina == Constantes::$PAGINA_EVENTO_CELULA) {
				$sessao->pagina = Constantes::$PAGINA_EVENTO_CELULA;
			}
			if ($pagina == Constantes::$PAGINA_EVENTO_DISCIPULADO) {
				$sessao->pagina = Constantes::$PAGINA_EVENTO_DISCIPULADO;
			}
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_EVENTO,
			));
		}
		if ($pagina == Constantes::$PAGINA_GRUPO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_GRUPO,
			));
		}
		if ($pagina == Constantes::$PAGINA_GRUPO_FINALIZAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_GRUPO_FINALIZAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_GRUPO_ATUALIZACAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_GRUPO_ATUALIZACAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_GRUPO_ATUALIZAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_GRUPO_ATUALIZAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_EVENTO_CULTO_PERSISTIR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_EVENTO_CULTO_PERSISTIR,
			));
		}
		if ($pagina == Constantes::$PAGINA_EVENTO_DISCIPULADO_PERSISTIR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_EVENTO_DISCIPULADO_PERSISTIR,
			));
		}
		if ($pagina == Constantes::$PAGINA_EVENTO_CELULA_PERSISTIR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_EVENTO_CELULA_PERSISTIR,
			));
		}
		if ($pagina == Constantes::$PAGINA_EVENTO_EXCLUSAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_EVENTO_EXCLUSAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO,
			));
		}
		/* Busca de endereço por CEP JSON */
		if ($pagina == Constantes::$PAGINA_BUSCAR_ENDERECO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_BUSCAR_ENDERECO,
			));
		}
		/* Busca de CPF JSON */
		if ($pagina == Constantes::$PAGINA_BUSCAR_CPF) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_BUSCAR_CPF,
			));
		}
		/* Verificar CPF JSON */
		if ($pagina == Constantes::$PAGINA_VERIFICAR_CPF) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_VERIFICAR_CPF,
			));
		}
		/* Enviar SMS */
		if ($pagina == Constantes::$PAGINA_ENVIAR_SMS) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_ENVIAR_SMS,
			));
		}
		/* Busca de Email JSON */
		if ($pagina == Constantes::$PAGINA_BUSCAR_EMAIL) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_BUSCAR_EMAIL,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACOES) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACOES,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACAO_ACEITAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO_ACEITAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACAO_RECUSAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO_RECUSAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_TROCA_DE_RESPONSAVEL_RECUSAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_TROCA_DE_RESPONSAVEL_RECUSAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACAO_FINALIZAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO_FINALIZAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACAO_RECEBER) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO_RECEBER,
			));
		}
		if ($pagina == Constantes::$PAGINA_SOLICITACAO_RECEBER_FINALIZAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO_RECEBER_FINALIZAR,
			));
		}
		/* Páginas Revisão */
		if ($pagina == Constantes::$PAGINA_SELECIONAR_REVISIONISTA) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_REVISIONISTA,
			));
		}
		if ($pagina == 'SelecionarRevisionistaTodos') {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => 'SelecionarRevisionistaTodos',
			));
		}
	
		/* Páginas Metas */
		if ($pagina == Constantes::$PAGINA_METAS) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_METAS,
			));
		}
		if ($pagina == Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_SALVAR_PESSOA_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SALVAR_PESSOA_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_FICHA_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_FICHA_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_SELECIONAR_FICHA_REVISIONISTA) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_FICHA_REVISIONISTA,
			));
		}
		if ($pagina == Constantes::$PAGINA_LISTA_LIDERES) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_LISTA_LIDERES,
			));
		}
		if ($pagina == Constantes::$PAGINA_LISTA_REVISIONISTAS) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_LISTA_REVISIONISTAS,
			));
		}
		if ($pagina == Constantes::$PAGINA_CONSULTAR_FICHA) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_CONSULTAR_FICHA,
			));
		}
		if ($pagina == Constantes::$PAGINA_ATIVAR_FICHA_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_FICHAS_ATIVAS){
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_FICHAS_ATIVAS,
			));
		}
	
		if ($pagina == Constantes::$PAGINA_RELATORIO_FICHAS_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_RELATORIO_FICHAS_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_ATIVAR_RESERVA_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_ATIVAR_RESERVA_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_SELECIONAR_FICHA_ATIVAS) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_FICHA_ATIVAS,
			));
		}
		if ($pagina == Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO,
			));
		}
		if ($pagina == Constantes::$PAGINA_SELECIONAR_LIDER_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_LIDER_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_ATIVAR_LIDERES_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_ATIVAR_LIDERES_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_SELECIONAR_REVISIONISTA_CRACHA) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_REVISIONISTA_CRACHA,
			));
		}
		if ($pagina == Constantes::$PAGINA_GERAR_CRACHA) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_GERAR_CRACHA,
			));
		}
		if ($pagina == Constantes::$PAGINA_GERAR_CRACHA.'Oito') {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_GERAR_CRACHA.'Oito',
			));
		}
		if ($pagina == Constantes::$PAGINA_REVISAO) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_REVISAO,
			));
		}
		if ($pagina == Constantes::$PAGINA_REVISAO_FINALIZAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_REVISAO_FINALIZAR,
			));
		}
		if ($pagina == Constantes::$PAGINA_REVISAO_EXCLUIR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_REVISAO_EXCLUIR,
			));
		}

		/* Fim Páginas Revisão */
		if ($pagina == Constantes::$PAGINA_TROCAR_RESPONSABILIDADES) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_TROCAR_RESPONSABILIDADES,
			));
		}
		if ($pagina == Constantes::$PAGINA_TROCAR_RESPONSABILIDADES_FINALIZAR) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_TROCAR_RESPONSABILIDADES_FINALIZAR,
			));
		}


		/* Funcoes */
		if ($pagina == Constantes::$PAGINA_FUNCOES) {
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => Constantes::$PAGINA_FUNCOES,
			));
		}

		/* Por titulo e eventos na sessão para passar a tela */
		$listagemDeEventos = null;
		$tituloDaPagina = '';
		/* Listagem de celulas */

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();

		$extra = '';
		$tipoEvento = 0;
		$tipoCelula = EventoTipo::tipoCelula;
		$tipoCelulaEstrategica = EventoTipo::tipoCelulaEstrategica;
		$tipoCulto = EventoTipo::tipoCulto;
		$tipoRevisao = EventoTipo::tipoRevisao;
		$tipoDiscipulado = EventoTipo::tipoDiscipulado;
		if ($pagina == Constantes::$PAGINA_CELULAS) {
			self::registrarLog(RegistroAcao::VER_CELULAS, $extra = '');
			$listagemDeEventos = $grupo->getGrupoEventoAtivosPorTipo($tipoCelula);
			if($listagemDeEventosEstrategica = $grupo->getGrupoEventoAtivosPorTipo($tipoCelulaEstrategica)){
				foreach($listagemDeEventosEstrategica as $grupoEvento){
					$listagemDeEventos[] = $grupoEvento;
				}
			}
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CELULAS . ' <b class="text-danger">' . Constantes::$TRADUCAO_MULTIPLICACAO . '</b>';
			$tipoEvento = 1;
		}
		if ($pagina == Constantes::$PAGINA_CULTOS) {
			self::registrarLog(RegistroAcao::VER_CULTOS, $extra = '');
			$listagemDeEventos = $grupo->getGrupoEventoAtivosPorTipo($tipoCulto);
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CULTOS;
			$tipoEvento = 2;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_REVISOES) {
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISAO;
			$tipoEvento = 3;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_REVISIONISTAS) {
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
			$tipoEvento = 4;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_FICHA_REVISIONISTAS) {
			self::registrarLog(RegistroAcao::VER_FICHAS_DO_REVISAO_DE_VIDAS, $extra = '');
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
			$tipoEvento = 5;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_ATIVOS_REVISIONISTAS) {
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
			$tipoEvento = 6;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_LIDERES_REVISAO) {
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
			$tipoEvento = 7;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_ATIVAR_FICHAS) {
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
			$tipoEvento = 8;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA) {
			$semDataLimite = 1;
			$listagemDeEventos = $grupo->getGrupoEventoRevisao($semDataLimite);
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISAO;
			$tipoEvento = 9;
			/* Id da Turma em que os alunos serão selecionados */						
			$sessao->idTurma = $sessao->idSessao;
		}
		if ($pagina == Constantes::$PAGINA_DISCIPULADOS) {
			$listagemDeEventos = $grupo->getGrupoEventoAtivosPorTipo($tipoDiscipulado);
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_DISCIPULADOS;
			$tipoEvento = 10;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_LISTAGEM_LIDERES) {
			self::registrarLog(RegistroAcao::VER_LISTAGEM_DE_LIDERES_ATIVOS, $extra = '');
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_LIDERES;
			$tipoEvento = 11;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_SELECIONAR_REVISAO_CRACHA) {
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = 'Selecione os revisionistas para gerar o crachás';
			$tipoEvento = 12;
			$extra = $grupo->getId();
		}
		if ($pagina == Constantes::$PAGINA_LISTAGEM_REVISIONISTAS) {
			self::registrarLog(RegistroAcao::VER_LISTAGEM_DE_REVISIONISTAS_ATIVOS, $extra = '');
			$listagemDeEventos = $grupo->getGrupoEventoRevisao();
			$tituloDaPagina = Constantes::$PAGINA_LISTAGEM_REVISIONISTAS_TITULO;
			$tipoEvento = 13;
			$extra = $grupo->getId();
		}
		$view = new ViewModel(array(
			Constantes::$LISTAGEM_EVENTOS => $listagemDeEventos,
			Constantes::$TITULO_DA_PAGINA => $tituloDaPagina,
			Constantes::$TIPO_EVENTO => $tipoEvento,
			Constantes::$EXTRA => $extra,
			Constantes::$ENTIDADE => $entidade,
			'mostrarOpcoes' => true,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	/**
	 * Função para o formulário de eventos
	 * GET /cadastroEvento
	 */
	public function eventoAction() {
		$form = null;
		$enderecoHidden = '';
		$extra = null;
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		if ($sessao->pagina == Constantes::$PAGINA_EVENTO_CULTO) {
			/* Verificando a se tem algum id na sessão */
			$eventoNaSessao = new Evento();

			if (!empty($sessao->idSessao)) {
				$eventoNaSessao = $this->getRepositorio()->getEventoORM()->encontrarPorId($sessao->idSessao);
			}
			$form = new EventoForm(Constantes::$FORM, $eventoNaSessao);
			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
			$grupo = $entidade->getGrupo();
			$extra = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
		}
		if ($sessao->pagina == Constantes::$PAGINA_EVENTO_CELULA) {
			/* Verificando a se tem algum id na sessão */
			$eventoCelulaNaSessao = new EventoCelula();
			if (!empty($sessao->idSessao)) {

				$eventoCelulaNaSessao = $this->getRepositorio()->getEventoCelulaORM()->encontrarPorId($sessao->idSessao);
			} else {
				$enderecoHidden = Constantes::$FORM_HIDDEN;
			}
			$form = new CelulaForm(Constantes::$FORM_CELULA, $eventoCelulaNaSessao);
		}
		if ($sessao->pagina == Constantes::$PAGINA_EVENTO_DISCIPULADO) {
			/* Verificando a se tem algum id na sessão */
			$eventoNaSessao = new Evento();

			if (!empty($sessao->idSessao)) {
				$eventoNaSessao = $this->getRepositorio()->getEventoORM()->encontrarPorId($sessao->idSessao);
			}
			$form = new EventoForm(Constantes::$FORM, $eventoNaSessao);
			$extra = 'discipulado';
		}
		$view = new ViewModel(array(
			Constantes::$FORM => $form,
			Constantes::$FORM_ENDERECO_HIDDEN => $enderecoHidden,
			Constantes::$EXTRA => $extra,
			Constantes::$PAGINA => $sessao->pagina,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTO);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTO);

		return $view;
	}

	/**
	 * Função para persistir o evento culto
	 * POST /eventoCultoPersistir
	 */
	public function eventoCultoPersistirAction() {
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

		$stringCheckEquipe = 'checkEquipe';
		$request = $this->getRequest();
		if ($request->isPost()) {
			/* Repositorios */

			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();

				/* Entidades */
				$evento = new Evento();
				$eventoForm = new EventoForm(Constantes::$FORM, $evento);
				$eventoForm->setInputFilter($evento->getInputFilterEventoCulto());
				$eventoForm->setData($post_data);

				/* validação */
				if ($eventoForm->isValid()) {
					$sessao = new Container(Constantes::$NOME_APLICACAO);
					$criarNovoEvento = true;
					$mudarDataDeCadastroParaProximoDomingo = false;
					$validatedData = $eventoForm->getData();

					/* Entidades */
					$evento = new Evento();
					$grupoEvento = new GrupoEvento();

					/* ALTERANDO */
					if (!empty($post_data[Constantes::$FORM_ID])) {
						$criarNovoEvento = false;
						$eventoAtual = $this->getRepositorio()->getEventoORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

						self::registrarLog(RegistroAcao::ALTEROU_UM_CULTO, $extra = 'Id: '.$eventoAtual->getId());
						$grupoEventoAtivos = $eventoAtual->getGrupoEventoAtivos();
						/* Dia foi alterado */
						if ($post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoAtual->getDia()) {
							/* Persistindo */
							/* Inativando o Evento */
							$eventoParaInativar = $eventoAtual;
							$eventoParaInativar->setDataEHoraDeInativacao();
							$this->getRepositorio()->getEventoORM()->persistir($eventoParaInativar, false);
							/* Inativando todos Grupo Evento */
							foreach ($grupoEventoAtivos as $gea) {
								$gea->setDataEHoraDeInativacao();
								$this->getRepositorio()->getGrupoEventoORM()->persistir($gea, false);
							}
							$criarNovoEvento = true;
							$mudarDataDeCadastroParaProximoDomingo = true;
						} else {
							/* Dia não foi alterado */

							/* Dados exclusivo do Culto */
							if ($post_data[(Constantes::$FORM_NOME)] != $eventoAtual->getNome()) {
								$eventoAtual->setNome(strtoupper($post_data[(Constantes::$FORM_NOME)]));
							}
							$eventoAtual->setHora($post_data[(Constantes::$FORM_HORA)] . ':' . $post_data[(Constantes::$FORM_MINUTOS)] . ':00');
							$this->getRepositorio()->getEventoORM()->persistir($eventoAtual, false);
							/* Sessão */
							$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_ALTERAR_CULTO;
							$sessao->textoMensagem = $eventoAtual->getNome() . ' ' . $eventoAtual->getHoraFormatoHoraMinutoParaListagem();
						}
						/* Verificando Grupos abaixo ou equipes */
						/* Marcação */
						foreach ($post_data as $key => $value) {
							$stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
							if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
								$stringValor = substr($key, strlen($stringParaVerificar));
								/* Verificando marcações */
								$validacaoMarcado = false;
								foreach ($grupoEventoAtivos as $gea) {
									if ($gea->getGrupo()->getId() == $stringValor) {
										$validacaoMarcado = true;
									}
								}
								/* Equipe esta marcada mas não foi gerada ainda */
								if (!$validacaoMarcado) {
									$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($stringValor);
									$grupoEventoEquipe = new GrupoEvento();
									$grupoEventoEquipe->setDataEHoraDeCriacao();
									$grupoEventoEquipe->setGrupo($grupoEquipe);
									$grupoEventoEquipe->setEvento($eventoAtual);
									$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEventoEquipe);
								}
							}
						}
						/* Desmarcação */
						foreach ($grupoEventoAtivos as $grupoEventAtivo) {
							$idEntidadeAtual = $sessao->idEntidadeAtual;
							$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
							$grupo = $entidade->getGrupo();
							if ($grupoEventAtivo->getGrupo()->getId() != $grupo->getId()) {
								$validacaoMarcado = false;
								foreach ($post_data as $key => $value) {
									$stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
									if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
										$stringValor = substr($key, strlen($stringParaVerificar));
										if ($grupoEventAtivo->getGrupo()->getId() == $stringValor) {
											$validacaoMarcado = true;
										}
									}
								}
								/* Equipe esta marcada mas não foi gerada ainda */
								if (!$validacaoMarcado) {
									$grupoEventAtivo->setDataEHoraDeInativacao();
									$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEventAtivo, false);
								}
							}
						}
					}
					if ($criarNovoEvento) {
						/* Entidade selecionada */
						$idEntidadeAtual = $sessao->idEntidadeAtual;
						$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

						$evento->exchangeArray($eventoForm->getData());
						$dataParaCadastro = Funcoes::dataAtual();
						if ($mudarDataDeCadastroParaProximoDomingo) {
							$dataParaCadastro = Funcoes::proximoDomingo();
						}
						$evento->setData_criacao($dataParaCadastro);
						$evento->setHora_criacao(Funcoes::horaAtual());
						$evento->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
						$evento->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
						$evento->setEventoTipo($this->getRepositorio()->getEventoTipoORM()->encontrarPorId(EventoTipo::tipoCulto));

						$grupoEvento->setData_criacao(Funcoes::dataAtual());
						$grupoEvento->setHora_criacao(Funcoes::horaAtual());
						$grupoEvento->setGrupo($entidade->getGrupo());
						$grupoEvento->setEvento($evento);

						/* Persistindo */
						$this->getRepositorio()->getEventoORM()->persistir($evento);
						$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);
						/* Sessão */
						$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_CULTO;
						$sessao->textoMensagem = $evento->getNome();
						$sessao->idSessao = $evento->getId();

						/* Grupos Abaixos ou Equipes */
						foreach ($post_data as $key => $value) {
							$stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
							if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
								$stringValor = substr($key, strlen($stringParaVerificar));
								$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($stringValor);
								$grupoEventoEquipe = new GrupoEvento();
								$grupoEventoEquipe->setData_criacao(Funcoes::dataAtual());
								$grupoEventoEquipe->setHora_criacao(Funcoes::horaAtual());
								$grupoEventoEquipe->setGrupo($grupoEquipe);
								$grupoEventoEquipe->setEvento($evento);
								$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEventoEquipe);
							}
						}
						self::registrarLog(RegistroAcao::CADASTROU_UM_CULTO, $extra = 'Id: '.$evento->getId());
					}
				} else {
					$this->direcionaErroDeCadastro($eventoForm->getMessages());
					CircuitoController::direcionandoAoLogin($this);
				}

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
					Constantes::$PAGINA => Constantes::$PAGINA_CULTOS,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
				CircuitoController::direcionandoAoLogin($this);
			}
		}
	}

	/**
	 * Função para persistir o evento célula
	 * POST /eventoCelulaPersistir
	 */
	public function eventoCelulaPersistirAction() {
		$request = $this->getRequest();
		if ($request->isPost()) {
			$eventoCelula = new EventoCelula();

			$celulaForm = new CelulaForm(Constantes::$FORM_CELULA, $eventoCelula);
			$this->getRepositorio()->iniciarTransacao();
			try {
				$post_data = $request->getPost();

				/* Entidades */
				$celulaForm->setInputFilter($eventoCelula->getInputFilter());
				$celulaForm->setData($post_data);

				/* validação */
				if ($celulaForm->isValid()) {
					$sessao = new Container(Constantes::$NOME_APLICACAO);
					$criarNovaCelula = true;					
					$validatedData = $celulaForm->getData();

					/* Entidades */
					$evento = new Evento();
					$grupoEvento = new GrupoEvento();

					/* ALTERANDO */
					$eventoParaInativar = null;
					if (!empty($post_data[Constantes::$FORM_ID])) {						
						$criarNovaCelula = false;
						$eventoCelulaAtual = $this->getRepositorio()->getEventoCelulaORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

						self::registrarLog(RegistroAcao::ALTEROU_UMA_CELULA, $extra = 'Id: '.$eventoCelulaAtual->getId());
						/* Dia foi alterado */
						if ($post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoCelulaAtual->getEvento()->getDia()) {
							/* Persistindo */
							$dataParaInativacao = Funcoes::proximoDomingo();
							$dataParaInativacaoFormatada = DateTime::createFromFormat('Y-m-d', $dataParaInativacao);

							$eventoParaInativar = $eventoCelulaAtual->getEvento();
							$grupoEventoAtivos = $eventoParaInativar->getGrupoEventoAtivos();

							$eventoParaInativar->setData_inativacao($dataParaInativacaoFormatada);
							$eventoParaInativar->setHora_inativacao('00:00:00');
							$grupoEventoAtivos[0]->setData_inativacao($dataParaInativacaoFormatada);
							$grupoEventoAtivos[0]->setHora_inativacao('00:00:00');

							$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEventoAtivos[0], false);
							$this->getRepositorio()->getEventoORM()->persistir($eventoParaInativar, false);
							$criarNovaCelula = true;
						} else {
							/* Dia não foi alterado */

							/* Dados exclusivo da célula */
							if ($validatedData[Constantes::$FORM_NOME_HOSPEDEIRO] != $eventoCelulaAtual->getNome_hospedeiro()) {
								$eventoCelulaAtual->setNome_hospedeiro($validatedData[Constantes::$FORM_NOME_HOSPEDEIRO]);
							}
							if ($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] != $eventoCelulaAtual->getTelefone_hospedeiroDDDSemTelefone()) {
								$eventoCelulaAtual->setTelefone_hospedeiro($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] . $validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO]);
							}
							if ($validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO] != $eventoCelulaAtual->getTelefone_hospedeiroTelefoneSemDDD()) {
								$eventoCelulaAtual->setTelefone_hospedeiro($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] . $validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO]);
							}
							if ($post_data[Constantes::$FORM_CEP_LOGRADOURO] != $eventoCelulaAtual->getCep()) {
								$eventoCelulaAtual->setCep($validatedData[Constantes::$FORM_CEP_LOGRADOURO]);
							}
							if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_UF)] != $eventoCelulaAtual->getUf()) {
								$eventoCelulaAtual->setUf($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_UF)]);
							}
							if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_CIDADE)] != $eventoCelulaAtual->getCidade()) {
								$eventoCelulaAtual->setCidade($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_CIDADE)]);
							}
							if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_BAIRRO)] != $eventoCelulaAtual->getBairro()) {
								$eventoCelulaAtual->setBairro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_BAIRRO)]);
							}
							if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_LOGRADOURO)] != $eventoCelulaAtual->getLogradouro()) {
								$eventoCelulaAtual->setLogradouro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_LOGRADOURO)]);
							}
							if ($post_data[(Constantes::$FORM_COMPLEMENTO)] != $eventoCelulaAtual->getComplemento()) {
								$eventoCelulaAtual->setComplemento(strtoupper($post_data[(Constantes::$FORM_COMPLEMENTO)]));
							}
							$this->getRepositorio()->getEventoCelulaORM()->persistir($eventoCelulaAtual, false);
							/* Dados do Evento - Hora */
							$eventoAtual = $eventoCelulaAtual->getEvento();
							if ($validatedData[Constantes::$FORM_HORA] != $eventoAtual->getHoraSemMinutosESegundos()) {
								$eventoAtual->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
							}
							if ($validatedData[Constantes::$FORM_MINUTOS] != $eventoAtual->getMinutosSemHorasESegundos()) {
								$eventoAtual->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
							}
							$this->getRepositorio()->getEventoORM()->persistir($eventoAtual);
							/* Sessão */
							$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_ALTERAR_CELULA;
							$sessao->textoMensagem = $eventoCelulaAtual->getNome_hospedeiro();
						}
					}
					if ($criarNovaCelula) {
						/* Entidade selecionada */
						$idEntidadeAtual = $sessao->idEntidadeAtual;
						$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

						/* Evento */
						$alterarDataDeCriacao = false;
						$dataParaCriacao = Funcoes::proximaSegunda();
						$dataParaCriacaoFormatada = DateTime::createFromFormat('Y-m-d', $dataParaCriacao);
						$evento->setData_criacao($dataParaCriacaoFormatada);
						$evento->setHora_criacao('00:00:00');
						$grupoEvento->setData_criacao($dataParaCriacaoFormatada);
						$grupoEvento->setHora_criacao('00:00:00');

						$evento->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
						$evento->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
						$tipoDeCelula = EventoTipo::tipoCelulaEstrategica;
						if ($eventoParaInativar && $post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoCelulaAtual->getEvento()->getDia()) {
							$tipoDeCelula = $eventoParaInativar->getEventoTipo()->getId();
							$evento->setEvento_id($eventoParaInativar->getId());
						}
						$evento->setEventoTipo($this->getRepositorio()->getEventoTipoORM()->encontrarPorId($tipoDeCelula));
						$this->getRepositorio()->getEventoORM()->persistir($evento, $alterarDataDeCriacao);

						/* Evento celula */
						$eventoCelula->exchangeArray($celulaForm->getData());
						$eventoCelula->setTelefone_hospedeiro($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] . $validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO]);
						$eventoCelula->setUf($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_UF)]);
						$eventoCelula->setCidade($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_CIDADE)]);
						$eventoCelula->setLogradouro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_LOGRADOURO)]);
						$eventoCelula->setBairro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_BAIRRO)]);
						$eventoCelula->setBairro('');
						$eventoCelula->setComplemento(strtoupper($post_data[Constantes::$FORM_COMPLEMENTO]));
						$eventoCelula->setCep($post_data[Constantes::$FORM_CEP_LOGRADOURO]);
						$eventoCelula->setEvento($evento);
						$this->getRepositorio()->getEventoCelulaORM()->persistir($eventoCelula, $alterarDataDeCriacao);

						$grupoEvento->setGrupo($entidade->getGrupo());
						$grupoEvento->setEvento($evento);
						$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento, $alterarDataDeCriacao);


						$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
						if ($fatosLider = $this->getRepositorio()->getFatoLiderORM()->encontrarMultiplosFatosLiderPorNumeroIdentificador($numeroIdentificador)) {
							foreach($fatosLider as $fatoLider){ 
								if($fatoLider->verificarSeEstaAtivo()){
									$fatoLider->setDataEHoraDeInativacao(Funcoes::proximoDomingo());
									$this->getRepositorio()->getFatoLiderORM()->persistir($fatoLider, $alterarDataDeCriacao);
								}								
							}							
						}
						$quantidadeLideres = count($entidade->getGrupo()->getResponsabilidadesAtivas());
						$fatoLider = new FatoLider();
						$fatoLider->setLideres($quantidadeLideres);
						$fatoLider->setNumero_identificador($numeroIdentificador);
						$fatoLider->setDataEHoraDeCriacao(Funcoes::proximaSegunda());
						$this->getRepositorio()->getFatoLiderORM()->persistir($fatoLider, $alterarDataDeCriacao);

						self::registrarLog(RegistroAcao::CADASTROU_UMA_CELULA, $extra = 'Id: '.$evento->getId());

					}
					$this->getRepositorio()->fecharTransacao();

					return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
						Constantes::$PAGINA => Constantes::$PAGINA_CELULAS,
					));
				} else {
					$this->direcionaErroDeCadastro($celulaForm->getMessages());
					CircuitoController::direcionandoAoLogin($this);
				}
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				$this->direcionaErroDeCadastro($celulaForm->getMessages());
				CircuitoController::direcionandoAoLogin($this);
			}
		}
	}

	public function eventoDiscipuladoPersistirAction() {
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

		$stringCheckEquipe = 'checkEquipe';
		$request = $this->getRequest();
		if ($request->isPost()) {
			/* Repositorios */

			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();

				/* Entidades */
				$evento = new Evento();
				$eventoForm = new EventoForm(Constantes::$FORM, $evento);
				$eventoForm->setInputFilter($evento->getInputFilterEventoCulto());
				$eventoForm->setData($post_data);

				/* validação */
				if ($eventoForm->isValid()) {
					$sessao = new Container(Constantes::$NOME_APLICACAO);
					$criarNovoEvento = true;
					$mudarDataDeCadastroParaProximoDomingo = false;
					$validatedData = $eventoForm->getData();

					/* Entidades */
					$evento = new Evento();
					$grupoEvento = new GrupoEvento();

					/* ALTERANDO */
					if (!empty($post_data[Constantes::$FORM_ID])) {
						$criarNovoEvento = false;
						$eventoAtual = $this->getRepositorio()->getEventoORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

						/* Dados exclusivo do Culto */
						if ($post_data[(Constantes::$FORM_NOME)] != $eventoAtual->getNome()) {
							$eventoAtual->setNome(strtoupper($post_data[(Constantes::$FORM_NOME)]));
						}
						$eventoAtual->setHora($post_data[(Constantes::$FORM_HORA)] . ':' . $post_data[(Constantes::$FORM_MINUTOS)] . ':00');
						$eventoAtual->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
						$this->getRepositorio()->getEventoORM()->persistir($eventoAtual, false);
						/* Sessão */
						$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_ALTERAR_CULTO;
						$sessao->textoMensagem = $eventoAtual->getNome() . ' ' . $eventoAtual->getHoraFormatoHoraMinutoParaListagem();
					}
					if ($criarNovoEvento) {
						/* Entidade selecionada */
						$idEntidadeAtual = $sessao->idEntidadeAtual;
						$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

						$evento->exchangeArray($eventoForm->getData());
						$dataParaCadastro = Funcoes::dataAtual();
						if ($mudarDataDeCadastroParaProximoDomingo) {
							$dataParaCadastro = Funcoes::proximoDomingo();
						}
						$evento->setData_criacao($dataParaCadastro);
						$evento->setHora_criacao(Funcoes::horaAtual());
						$evento->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
						$evento->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
						$evento->setEventoTipo($this->getRepositorio()->getEventoTipoORM()->encontrarPorId(EventoTipo::tipoDiscipulado));

						$grupoEvento->setData_criacao(Funcoes::dataAtual());
						$grupoEvento->setHora_criacao(Funcoes::horaAtual());
						$grupoEvento->setGrupo($entidade->getGrupo());
						$grupoEvento->setEvento($evento);

						/* Persistindo */
						$this->getRepositorio()->getEventoORM()->persistir($evento);
						$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);

						$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $entidade->getGrupo());
						$fatoCelulaDiscipulado = new FatoCelulaDiscipulado();
						$fatoCelulaDiscipulado->setNumero_identificador($numeroIdentificador);
						$fatoCelulaDiscipulado->setGrupo_evento_id($grupoEvento->getId());
						$this->getRepositorio()->getFatoCelulaDiscipuladoORM()->persistir($fatoCelulaDiscipulado);

						/* Sessão */
						$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_DISCIPULADO;
						$sessao->textoMensagem = $evento->getNome();
						$sessao->idSessao = $evento->getId();
					}

					self::registrarLog(RegistroAcao::CADASTROU_UM_DISCIPULADO, $extra = 'Id: '.$evento->getId());
					$this->getRepositorio()->fecharTransacao();
					return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
						Constantes::$PAGINA => Constantes::$PAGINA_DISCIPULADOS,
					));
				} else {
					$this->direcionaErroDeCadastro($eventoForm->getMessages());
					CircuitoController::direcionandoAoLogin($this);
				}
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
				CircuitoController::direcionandoAoLogin($this);
			}
		}
	}

	/**
	 * Tela com formulário de exclusão de evento
	 * GET /cadastroEventoExclusao
	 */
	public function eventoExclusaoAction() {
		/* Verificando a se tem algum id na sessão */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$extra = null;
		$eventoNaSessao = new Evento();

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		if (!empty($sessao->idSessao)) {
			$eventoNaSessao = $this->getRepositorio()->getEventoORM()->encontrarPorId($sessao->idSessao);
			if ($eventoNaSessao->verificaSeECelula()) {
				$entidade = $eventoNaSessao->getGrupoEventoAtivos()[0]->getGrupo()->getEntidadeAtiva();
			}
			if ($eventoNaSessao->getGrupoEventoAtivos() > 1) {
				$grupo = $entidade->getGrupo();
				foreach ($eventoNaSessao->getGrupoEventoAtivos() as $eg) {
					if ($eg->getGrupo()->getId() != $grupo->getId()) {
						$grupo = $eg->getGrupo();
						$entidadeMarcada = $grupo->getEntidadeAtiva();
						$extra .= $entidadeMarcada->infoEntidade() . "<br />";
					}
				}
			}
		}

		$view = new ViewModel(array(
			Constantes::$EVENTO => $eventoNaSessao,
			Constantes::$ENTIDADE => $entidade,
			Constantes::$EXTRA => $extra,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_EVENTO);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_EVENTO);

		return $view;
	}

	/**
	 * Tela com formulário de exclusão de celula
	 * GET /cadastroEventoConfirmacao
	 */
	public function eventoExclusaoConfirmacaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

		$this->getRepositorio()->iniciarTransacao();
		try {
			/* Verificando a se tem algum id na sessão */
			$eventoNaSessao = new Evento();
			if ($sessao->idSessao) {
				$eventoNaSessao = $this->getRepositorio()->getEventoORM()->encontrarPorId($sessao->idSessao);

				/* Persistindo */

				/* Relatório de célula */
				if ($eventoNaSessao->getEventoCelula()) {
					/* Somente inativar caso o dia do evento seja posterior ao dia da exclusao */
					$timeNow = new DateTime();
					$format = 'N';
					$diaDaSemana = $timeNow->format($format);
					if ($diaDaSemana == 7) {
						$diaDaSemana = 1;
					} else {
						$diaDaSemana++;
					}
					if ($eventoNaSessao->getDia() > $diaDaSemana) {
						$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $eventoNaSessao->getGrupoEventoAtivo()->getGrupo());
						$periodo = 0;
						$arrayPeriodo = Funcoes::montaPeriodo($periodo);
						$stringData = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
						$dateFormatada = DateTime::createFromFormat('Y-m-d', $stringData);
						if ($fatoCiclo = $this->getRepositorio()->getFatoCicloORM()->
							encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dateFormatada, $this->getRepositorio())) {
								$fatoCelula = $this->getRepositorio()->getFatoCelulaORM()->encontrarPorEventoCelulaIdEFatoCiclo($eventoNaSessao->getEventoCelula()->getId(), $fatoCiclo->getId());
								$fatoCelula->setDataEHoraDeInativacao();
								$this->getRepositorio()->getFatoCelulaORM()->persistir($fatoCelula, false);
							}
					}
					/* Inativando fato-lider caso nao tenha mais celulas */
					$grupoDoEvento = $eventoNaSessao->getGrupoEventoAtivo()->getGrupo();
					if (count($grupoDoEvento->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula)) === 0) {
						$this->inativarFatoLiderPorGrupo($grupoDoEvento);
					}
				}

				/* Inativando o Evento */
				$eventoNaSessao->setDataEHoraDeInativacao();
				$this->getRepositorio()->getEventoORM()->persistir($eventoNaSessao, false);

				/* Inativando o Grupo Evento */
				$grupoSessao = $eventoNaSessao->getGrupoEventoAtivo()->getGrupo();
				$grupoEventoAtivos = $eventoNaSessao->getGrupoEventoAtivos();

				foreach ($grupoEventoAtivos as $grupoEventoAtivo) {
					$grupoEventoAtivo->setDataEHoraDeInativacao();
					$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEventoAtivo, false);
					if ($eventoNaSessao->getEventoTipo()->getId() === EventoTipo::tipoDiscipulado) {
						$fatoCelulaDiscipulado = $this->getRepositorio()->getFatoCelulaDiscipuladoORM()->encontrarPorGrupoEvento($grupoEventoAtivo->getId());
						if($fatoCelulaDiscipulado){
							$fatoCelulaDiscipulado->setDataEHoraDeInativacao();
							$this->getRepositorio()->getFatoCelulaDiscipuladoORM()->persistir($fatoCelulaDiscipulado);
						}												
					}
				}

				$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_CULTO;
				$sessao->textoMensagem = $eventoNaSessao->getNome();
				if ($eventoNaSessao->verificaSeECelula()) {
					$celula = $eventoNaSessao->getEventoCelula();
					$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_CELULA;
					$sessao->textoMensagem = $celula->getNome_hospedeiro();
				}
				if ($eventoNaSessao->getEventoTipo()->getId() === EventoTipo::tipoDiscipulado) {
					$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_DISCIPULADO;
					$sessao->textoMensagem = $eventoNaSessao->getNome();
				}
				$sessao->nomeEventoExcluido = $eventoNaSessao->getNome();
				unset($sessao->idSessao);

				$this->getRepositorio()->fecharTransacao();
				$tipoCelula = !empty($eventoNaSessao->verificaSeECelula());
				$pagina = Constantes::$PAGINA_CULTOS;
				if ($tipoCelula) {
					$sessao->idSessao = $grupoSessao->getId();
					return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
						Constantes::$ACTION => 'ver',
					));
				} else {
					if ($eventoNaSessao->getEventoTipo()->getId() === EventoTipo::tipoDiscipulado) {
						$pagina = Constantes::$PAGINA_DISCIPULADOS;
					} else {
						$pagina = Constantes::$PAGINA_CULTOS;
					}
					return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
						Constantes::$PAGINA => $pagina,
					));
				}
			}
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getTraceAsString();
			CircuitoController::direcionandoAoLogin($this);
		}
	}

	/**
	 * Tela com formulário de cadastro de grupo
	 * GET /cadastroGrupo
	 */
	public function grupoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
//		unset($sessao->token);
//		while (!$sessao->token) {
//			$comandoPegaToken = 'curl -k -d "grant_type=client_credentials" -H "Authorization: Basic RU93V3VrcTh3X29yblV5MGVYc1lrZkRnbUhJYTplSEFJam5aclliYjdLNXl1TTc5Nm5RUmhXZzRh" https://apigateway.serpro.gov.br/token';
//			$arrayToken = system($comandoPegaToken);
//			$sessao->token = explode('"', $arrayToken)[13];
//		}
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);		
		$grupo = $entidade->getGrupo();

		if($entidade->getTipo_id() == 8){
			$secretario = true;
			$pessoa = $grupo->getPessoasAtivas()[0];
		} else { 
			$secretario = false;
			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		}

		$mostrarCadastro = true;		
		$arrayHierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());

		$arrayDeNumerosUsados = array();
		if ($grupo->getGrupoPaiFilhoFilhosAtivos(1)) {
			$filhos = $grupo->getGrupoPaiFilhoFilhosAtivos(1);
			foreach ($filhos as $filho) {
				if ($filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero()) {
					$numero = $filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero();
					$arrayDeNumerosUsados[] = $numero;
				}
			}
		}
		$form = new GrupoForm(Constantes::$FORM, $arrayHierarquia, $arrayDeNumerosUsados);
		$entidadeTipos = $this->getRepositorio()->getEntidadeTipoORM()->buscarTodosRegistrosEntidade('id', 'asc');
		
		$view = new ViewModel(array(
			Constantes::$FORM => $form,
			'tipoEntidade' => $entidade->getEntidadeTipo()->getId(),
			'entidadeTipos' => $entidadeTipos,
			'secretario' => $secretario,
			'mostrarCadastro' => $mostrarCadastro,
			'tituloDaPagina' => 'Cadastro de Time',
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_GRUPO_VALIDACAO);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_GRUPO_VALIDACAO);

		return $view;
	}

	public static function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
		$ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
		$mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
		$nu = "0123456789"; // $nu contem os números
		$si = "!@#$%¨&*()_+="; // $si contem os símbolos

		if ($maiusculas){
			// se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
			$senha .= str_shuffle($ma);
		}

		if ($minusculas){
			// se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
			$senha .= str_shuffle($mi);
		}

		if ($numeros){
			// se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
			$senha .= str_shuffle($nu);
		}

		if ($simbolos){
			// se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
			$senha .= str_shuffle($si);
		}

		// retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
		return substr(str_shuffle($senha),0,$tamanho);
	}

	/**
	 * Tela com confrmação de cadastro de grupo
	 * POST /cadastroGrupoFinalizar
	 */
	public function grupoFinalizarAction() {
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				
				$post_data = $request->getPost();

				$dataParaCriacao = Funcoes::proximaSegunda();

				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$idEntidadeAtual = $sessao->idEntidadeAtual;
				$entidadeLogada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

				$inputEstadoCivil = intval($post_data[Constantes::$INPUT_ESTADO_CIVIL]);
				/* Alterar dados do aluno */
				/* Solteiro */
				$indicePessoasInicio = 0;
				$indicePessoasFim = 0;
				/* Casado */
				if ($inputEstadoCivil === 2) {
					$indicePessoasInicio = 1;
					$indicePessoasFim = 2;
				}

				$grupoJaCadastrado = false;

						if(
							$entidadeLogada->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial &&
							$entidadeLogada->getEntidadeTipo()->getId() !== EntidadeTipo::regiao &&
							$entidadeLogada->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao
						){

							for ($indicePessoas = $indicePessoasInicio; $indicePessoas <= $indicePessoasFim; $indicePessoas++) {
								$cpf = $post_data[Constantes::$FORM_CPF . $indicePessoas];
								if ($this->getRepositorio()->getPessoaORM()->verificarSeTemCPFCadastrado($cpf)) {					
									$pessoaSelecionada = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($cpf);
									foreach($pessoaSelecionada->getGrupoResponsavel() as $grupoResponsavel){
										if($grupoResponsavel->verificarSeEstaAtivo()){
											if($grupoResponsavel->getGrupo()->getGrupoPaiFilhoPaiAtivo()){
												$idPai = $grupoResponsavel->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getId();
												if($idPai == $entidadeLogada->getGrupo()->getId()){
													$grupoJaCadastrado = true;
												}																	
											}
										}
									}
								}
							}
						}

				if($grupoJaCadastrado){
					$sessao->mensagemSemAcesso = '<i class = "fa fa-warning text-danger"></i>';
					$sessao->mensagemSemAcesso .= ' Pessoa já cadastrada!';
					$sessao->corDoTexto = 'text-danger';
					return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
						Constantes::$ACTION => 'semAcesso',
					));
				}

				$this->getRepositorio()->iniciarTransacao();
				
				/* Criar Grupo */
				$grupoNovo = new Grupo();
				$grupoNovo->setDataEHoraDeCriacao($dataParaCriacao);
				$this->getRepositorio()->getGrupoORM()->persistir($grupoNovo, $mudarData = false);

				/* Entidade abaixo do perfil selecionado/logado */
				$tipoEntidadeAbaixo = Entidade::SUBEQUIPE; // sub equipe por padrao
				if ($entidadeLogada->getEntidadeTipo()->getId() != Entidade::SUBEQUIPE) {
					$tipoEntidadeAbaixo = $entidadeLogada->getEntidadeTipo()->getId() + 1;
				}
				/* Entidades acima da igreja */
				if($entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao
					|| $entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::regiao
					|| $entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::nacional
					|| $entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::presidencial){
						$tipoEntidadeAbaixo = $post_data['idEntidadeTipo'];
					}
				$entidadeNova = new Entidade();
				$entidadeNova->setEntidadeTipo(
					$this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId($tipoEntidadeAbaixo)
				);
				if($tipoEntidadeAbaixo == EntidadeTipo::secretario){
					$entidadeNova->setGrupoSecretario($entidadeLogada->getGrupo());
					$entidadeNova->setNome('SECRETÁRIO');
				}
				$entidadeNova->setGrupo($grupoNovo);
				if ($post_data[Constantes::$FORM_NUMERACAO]) {
					$entidadeNova->setNumero($post_data[Constantes::$FORM_NUMERACAO]);
				}
				if ($post_data['nomeEntidade']) {
					$entidadeNova->setNome($post_data['nomeEntidade']);
				}
				$entidadeNova->setDataEHoraDeCriacao($dataParaCriacao);
				$this->getRepositorio()->getEntidadeORM()->persistir($entidadeNova, $mudarData = false);
				
				for ($indicePessoas = $indicePessoasInicio; $indicePessoas <= $indicePessoasFim; $indicePessoas++) {
					$mudarDataDeCriacao = true;
					$cpf = $post_data[Constantes::$FORM_CPF . $indicePessoas];
					if ($this->getRepositorio()->getPessoaORM()->verificarSeTemCPFCadastrado($cpf)) {
						$pessoaSelecionada = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($cpf);
						$senha = self::gerar_senha(6,false,true,false,false);
						$pessoaSelecionada->setSenha($senha, true);
						$pessoaSelecionada->setSenhaLimpa($senha);
						$pessoaSelecionada->setPrecisaAtualizarDados();
						$mudarDataDeCriacao = false;
						foreach($pessoaSelecionada->getGrupoResponsavel() as $grupoResponsavel){
							if($grupoResponsavel->verificarSeEstaAtivo()){
								if($grupoResponsavel->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getId() == $entidadeLogada->getGrupo()->getId()){
									$grupoJaCadastrado = true;
								}
							}
						}
					} else {
						$pessoaSelecionada = new Pessoa();
					}
					$pessoaSelecionada->setNome($post_data[Constantes::$FORM_NOME . $indicePessoas]);
					$pessoaSelecionada->setEmail($post_data[Constantes::$FORM_EMAIL . $indicePessoas]);
					$pessoaSelecionada->setDocumento($cpf);
					$pessoaSelecionada->setData_nascimento(Funcoes::mudarPadraoData($post_data[Constantes::$FORM_DATA_NASCIMENTO . $indicePessoas], 0));
					$pessoaSelecionada->setDataEHoraDeCriacao($dataParaCriacao);
					$this->getRepositorio()->getPessoaORM()->persistir($pessoaSelecionada, $mudarDataDeCriacao);

					/* Apenas para uma nova pessoa, quem ja tem nao muda apenas pelo juridico */
					if ($mudarDataDeCriacao) {
						/* Criar hierarquia */
						$idHierarquia = $post_data[Constantes::$FORM_HIERARQUIA . $indicePessoas];
						$hierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarPorId($idHierarquia);
						$pessoaHierarquia = new PessoaHierarquia();
						$pessoaHierarquia->setPessoa($pessoaSelecionada);
						$pessoaHierarquia->setHierarquia($hierarquia);
						$pessoaHierarquia->setDataEHoraDeCriacao($dataParaCriacao);
						$this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquia, $mudarData = false);
					}

					/* Criar Grupo_Responsavel */
					$grupoResponsavelNovo = new GrupoResponsavel();
					$grupoResponsavelNovo->setPessoa($pessoaSelecionada);
					$grupoResponsavelNovo->setGrupo($grupoNovo);
					$grupoResponsavelNovo->setDataEHoraDeCriacao($dataParaCriacao);
					$this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo, $mudarData = false);
				}

				/* Criar Grupo_Pai_Filho */
				$grupoAtualSelecionado = $entidadeLogada->getGrupo();
				$grupoPaiFilhoNovo = new GrupoPaiFilho();
				$grupoPaiFilhoNovo->setGrupoPaiFilhoPai($grupoAtualSelecionado);
				$grupoPaiFilhoNovo->setGrupoPaiFilhoFilho($grupoNovo);
				$grupoPaiFilhoNovo->setDataEHoraDeCriacao($dataParaCriacao);
				$this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoNovo, $mudarData = false);

				$this->getRepositorio()->fecharTransacao();

				for ($indicePessoas = $indicePessoasInicio; $indicePessoas <= $indicePessoasFim; $indicePessoas++) {
					$cpf = $post_data[Constantes::$FORM_CPF . $indicePessoas];
					$pessoaSelecionada = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($cpf);
					/* Enviar Email */
					$this->enviarEmailParaCompletarOsDados($this->getRepositorio(), $sessao->idPessoa, $pessoaSelecionada);
				}

				self::registrarLog(RegistroAcao::CADASTROU_UM_LIDER, $extra = 'Id: '.$grupoNovo->getId());
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
				$this->direcionaErroDeCadastro($exc->getMessage());
				CircuitoController::direcionandoAoLogin($this);
			}
		}
	}

	/**
	 * Tela com atualização de cadastro de grupo
	 * GET /cadastroGrupoAtualizar
	 */
	public function grupoAtualizacaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$form = new AtualizarCadastroForm(Constantes::$FORM, $sessao->idPessoa);

		$view = new ViewModel(array(
			Constantes::$FORM => $form,
			Constantes::$FORM_ENDERECO_HIDDEN => Constantes::$FORM_HIDDEN
		));

		return $view;
	}

	/**
	 * Atualização dos dados depois de cadastrar o grupo
	 * POST /cadastroGrupoAtualizar
	 */
	public function grupoAtualizarAction() {
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
				$pessoa = $loginORM->getPessoaORM()->encontrarPorId($post_data[Constantes::$ID]);
				$telefone = $post_data[Constantes::$FORM_INPUT_DDD] . $post_data[Constantes::$FORM_INPUT_CELULAR];
				$pessoa->setTelefone($telefone);				
				$pessoa->dadosAtualizados();
				$loginORM->getPessoaORM()->persistir($pessoa);
			} catch (Exception $exc) {
				$this->direcionaErroDeCadastro($exc->getMessage());
				CircuitoController::direcionandoAoLogin($this);
			}
			return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
				Constantes::$ACTION => Constantes::$ACTION_SELECIONAR_PERFIL,
			));
		}
	}

	/**
	 * Busca de endereço por cep ou logradouro
	 * @return Json
	 */
	public function buscarEnderecoAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$cep_logradouro = $post_data[Constantes::$FORM_CEP_LOGRADOURO];

				$pesquisa = Correios::cep($cep_logradouro);
				$quantidadeDeResultados = count($pesquisa);

				$dadosDeResposta = array(
					'quantidadeDeResultados' => $quantidadeDeResultados,
					'pesquisa' => $pesquisa
				);
				$response->setContent(Json::encode($dadosDeResposta));
			} catch (Exception $exc) {
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	/**
	 * Busca de email
	 * Resposta: 0 - Não utilizado; 1 - Utilizado;
	 * @return Json
	 */
	public function buscarEmailAction() {
		$resposta = 0;
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$email = $post_data[Constantes::$FORM_EMAIL];

				if ($pessoaPesquisada = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email)) {
					if ($pessoaPesquisada->getResponsabilidadesAtivas()) {
						$sessao = new Container(Constantes::$NOME_APLICACAO);
						$idEntidadeAtual = $sessao->idEntidadeAtual;
						$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
						if(
							$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial &&
							$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao &&
							$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao
						){

							$resposta = 1;
						}
					}
				}
				$dadosDeResposta = array(
					'resposta' => $resposta,
				);

				$response->setContent(Json::encode($dadosDeResposta));
			} catch (Exception $exc) {
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	/**
	 * Busca de cpf
	 * 1 - Sucesso
	 * 2 - Não encontrou ou dados errados
	 * @return Json
	 */
	public function buscarCPFAction() {
		$resposta = 1;
		$respostaSucesso = 1;
		$respostaNaoEncotrado = 2;
		$respostaTemCadastroAtivo = 3;
		$respostaTemCadastroInativo = 4;
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$idPessoa = 0;
				$post_data = $request->getPost();
				$cpf = $post_data[Constantes::$FORM_CPF];
				$dataDeNascimento = $post_data['dataNascimento'];

				$dados = array();
				if($pessoaEncotrada = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($cpf)) {
					$responsabilidadesAtivas = count($pessoaEncotrada->getResponsabilidadesAtivas());
					if ($responsabilidadesAtivas === 0) {
						$resposta = $respostaTemCadastroInativo;
						$idPessoa = $pessoaEncotrada->getId();
						$dados['idHierarquia'] = $pessoaEncotrada->getPessoaHierarquiaAtivo()->getHierarquia()->getId();
					}
					if ($responsabilidadesAtivas > 0) {							
						$entidadeDaPessoaEncontrada = $pessoaEncotrada->getResponsabilidadesAtivas()[0]->getGrupo()->getEntidadeAtiva();
						if($entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao || $entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
							$resposta = $respostaSucesso;
						}
						if($entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao && $entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() !== EntidadeTipo::regiao){
							$stringOndeEstaCadastrado = '';
							$entidadeDaIgreja = $entidadeDaPessoaEncontrada->getGrupo()->getGrupoIgreja()->getEntidadeAtiva();
							$entidadeAcimaDaIgreja =  $entidadeDaIgreja->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();
							if($entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){									
								$responsavel = $entidadeDaPessoaEncontrada->getGrupo()->getGrupoEquipe()->getGrupoResponsavelAtivo()->getPessoa();									
								$nomeDoResponsavel =  $responsavel->getNomePrimeiroUltimo();
								$minhaEntidade = $entidadeDaPessoaEncontrada->infoEntidade() . ', ';
							}

							if($entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() === EntidadeTipo::equipe){	
								$responsavel = $entidadeDaPessoaEncontrada->getGrupo()->getGrupoIgreja()->getGrupoResponsavelAtivo()->getPessoa();																	
								$nomeDoResponsavel =  $responsavel->getNomePrimeiroUltimo();			
								$minhaEntidade = $entidadeDaPessoaEncontrada->infoEntidade() . ', ';
							}	

							if($minhaEntidade){
								$stringOndeEstaCadastrado .= $minhaEntidade;
							}

							if($entidadeDaIgreja || $entidadeDaPessoaEncontrada->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
								$stringOndeEstaCadastrado .= 'IGREJA: ' .  $entidadeDaIgreja->getNome() . ', ';
							}	
							if($entidadeAcimaDaIgreja){
								if($entidadeAcimaDaIgreja->getEntidadeTipo()->getId() === Entidade::COORDENACAO){                                  
									$nomeEntidadeAcimaArrumado = ' COORDENAÇÃO: ' . $entidadeAcimaDaIgreja->getNumero() . ' RESPONSÁVEIS: ' .$entidadeAcimaDaIgreja->getGrupo()->getNomeLideresAtivos();                    
								}  
								if($entidadeAcimaDaIgreja->getEntidadeTipo()->getId() === Entidade::REGIONAL){                                  
									$nomeEntidadeAcimaArrumado = ' REGIÃO: ' . $entidadeAcimaDaIgreja->getNome();                    
								}
							}			

							if($nomeEntidadeAcimaArrumado){
								$stringOndeEstaCadastrado .= ' NÍVEL ACIMA: ' . $nomeEntidadeAcimaArrumado;
							}

							$resposta = $respostaTemCadastroAtivo;								
							$idEntidadeAtual = $sessao->idEntidadeAtual;
							$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
							if(
								$entidade->getEntidadeTipo()->getId() === EntidadeTipo::presidencial ||
								$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao ||
								$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao
							){
								$resposta = $respostaSucesso;
							}

							if($nomeDoResponsavel){
								$dados['responsavel']['nome'] = $nomeDoResponsavel;
							}										

							$dados['ondeEsta'] = $stringOndeEstaCadastrado;
						}							
					}
				}

				$dados['resposta'] = $resposta;
				$dados['cpf'] = $cpf;
				$dados['dataNascimento'] = $dataDeNascimento;
				$dados['idPessoa'] = $idPessoa;
				$response->setContent(Json::encode($dados));
			} catch (Exception $exc) {
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	public function verificarCPFAction() {		
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {			
			$body = $request->getContent();
			$json = Json::decode($body);			
			$cpf = $json->cpf;
			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($cpf);
			if($pessoa){				
				if($pessoa->verificarSeTemAlgumaResponsabilidadeAtiva()){
					$temSecretario = null;
					foreach($pessoa->getResponsabilidadesAtivas() as $grupoResponsavel){
						$entidadeDaPessoa = $grupoResponsavel->getGrupo()->getEntidadeAtiva();
						if($entidadeDaPessoa->getTipo_id() == EntidadeTipo::secretario){
							$temSecretario++;
							$dados['entidadeSecretario'][$temSecretario]['id'] = $entidadeDaPessoa->getId();		
							$dados['entidadeSecretario'][$temSecretario]['infoEntidade'] = $entidadeDaPessoa->infoEntidade();		
							$dados['entidadeSecretario'][$temSecretario]['tipoEntidade'] = $entidadeDaPessoa->getEntidadeTipo()->getNome();									
						}
					}
					$nome = $pessoa->getNome(); 
					$idPessoa = $pessoa->getId();                							
					$dados['temSecretario'] = $temSecretario;
					$dados['idPessoa'] = $idPessoa;
					$dados['nome'] = $nome;					
					
				} 
				if(!$pessoa->verificarSeTemAlgumaResponsabilidadeAtiva()){
					$status = 'PESSOA INATIVADA';
					$dados['status'] = $status;		
				}
			} 
			if(!$pessoa){
				$status = 'PESSOA NÃO ENCONTRADA';
				$dados['status'] = $status;				
			}
			error_log(print_r($dados, true)); 
			$response->setContent(Json::encode($dados));
		}		
		
		return $response;		
	}

	public function enviarSMSAction() {
		$resposta = false;
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$numero = $post_data['numero'];

				$resposta = Funcoes::enviarSMS($numero);

				$dados = array();
				$dados['resposta'] = $resposta;
				$response->setContent(Json::encode($dados));
			} catch (Exception $exc) {
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	/**
	 * Controle de funçoes da tela de cadastro
	 * @return Json
	 */
	public function funcoesAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$funcao = $post_data[Constantes::$FUNCAO];
				$id = $post_data[Constantes::$ID];
				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$sessao->idSessao = $id;
				$response->setContent(Json::encode(
					array(
						'response' => 'true',
						'tipoDeRetorno' => 1,
						'url' => '/cadastro' . $funcao,
					)));
			} catch (Exception $exc) {
				echo $exc->get();
			}
		}
		return $response;
	}

	/**
	 * Envia email de convte para o novo grupo
	 * @param string $tokenDeAgora
	 * @param Pessoa $pessoa
	 */
	public static function enviarEmailParaCompletarOsDados($repositorio, $idPessoaLogada, $pessoa) {
		$pessoaLogada = $repositorio->getPessoaORM()->encontrarPorId($idPessoaLogada);

		$Subject = 'Cadastro Circuito da Visão';
		$ToEmail = $pessoa->getEmail();
		$nomeLider = str_replace(' ', '', $pessoaLogada->getNomePrimeiro());
		$nomePessoaEmail = str_replace(' ', '', $pessoa->getNomePrimeiro());
		$email = $pessoa->getEmail();
		$senha = $pessoa->getSenhaLimpa();
		$conteudo = '<pre>Você foi cadastrado no Circuito da Visão pelo seu líder</pre>
			<pre>Clique no link abaixo ou cole o link no seu navegador</pre>
			<pre>Email: '.$email.'</pre>
			<pre>Senha: '.$senha.'</pre>
			<pre><a href="www.circuitodavisaonovo.com.br">www.circuitodavisaonovo.com.br</a>';
		Funcoes::enviarEmail($ToEmail, $Subject, $conteudo);
	}

	public function metasAction() {
		self::validarSeSouPresidencial();
		$repositorio = $this->getRepositorio();
		$sessao = new Container(Constantes::$NOME_APLICACAO);		
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoLogado = $entidade->getGrupo();					
		$request = $this->getRequest();
		$tiposDeMetasOrdenacao = $repositorio->getMetasOrdenacaoTipoORM()->buscarTodosRegistrosEntidade();	
		$ordenacaoTipoBispo = 5;
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();		
				$post_data = $request->getPost();	
				$metasAtivas = $grupoLogado->getGrupoMetasOrdenacaoAtivas();
				foreach($metasAtivas as $metaAtiva){			
					$metaAtiva->setDataEHoraDeInativacao();
					$this->getRepositorio()->getGrupoPessoaORM()->persistir($metaAtiva, $mudarDataDeCriacao = false);
				}
				foreach ($tiposDeMetasOrdenacao as $ordenacaoTipo) {																													
					if($ordenacaoTipo->getId() !== $ordenacaoTipoBispo){
						$pessoasEmCelulaAdultos = $post_data['pessoasEmCelulaAdultos'.$ordenacaoTipo->getId()];
						$pessoasEmCelulaJovens = $post_data['pessoasEmCelulaJovens'.$ordenacaoTipo->getId()];
						
						$parceiroDeDeusAdultos = $post_data['parceiroDeDeusAdultos'.$ordenacaoTipo->getId()];
						$parceiroDeDeusJovens = $post_data['parceiroDeDeusJovens'.$ordenacaoTipo->getId()];		

						$membresiaAdultos = $post_data['membresiaAdultos'.$ordenacaoTipo->getId()];
						$membresiaJovens = $post_data['membresiaJovens'.$ordenacaoTipo->getId()];
						
						$lideresAdultos = $post_data['lideresAdultos'.$ordenacaoTipo->getId()];				
						$lideresJovens = $post_data['lideresJovens'.$ordenacaoTipo->getId()];

						// Gerando meta de Pessoas em Célula
						$metaPessoasEmCelula = new GrupoMetasOrdenacao;
						$criterioPessoasEmCelula = $this->getRepositorio()->getMetasOrdenacaoCriterioORM()->encontrarPorId(MetasOrdenacaoCriterio::pessoasEmCelula);
						$metaPessoasEmCelula->setMetasOrdenacaoTipo($ordenacaoTipo);
						$metaPessoasEmCelula->setMetasOrdenacaoCriterio($criterioPessoasEmCelula);												
						$metaPessoasEmCelula->setGrupo($grupoLogado);
						$metaPessoasEmCelula->setValorAdulto($pessoasEmCelulaAdultos);
						$metaPessoasEmCelula->setValorJovem($pessoasEmCelulaJovens);
						$this->getRepositorio()->getGrupoMetasOrdenacaoORM()->persistir($metaPessoasEmCelula);				

						// Gerando meta de Parceiro de Deus
						$metaParceiroDeDeus = new GrupoMetasOrdenacao;
						$criterioParceiroDeDeus = $this->getRepositorio()->getMetasOrdenacaoCriterioORM()->encontrarPorId(MetasOrdenacaoCriterio::parceiroDeDeus);
						$metaParceiroDeDeus->setMetasOrdenacaoTipo($ordenacaoTipo);
						$metaParceiroDeDeus->setMetasOrdenacaoCriterio($criterioParceiroDeDeus);												
						$metaParceiroDeDeus->setGrupo($grupoLogado);
						$metaParceiroDeDeus->setValorAdulto($parceiroDeDeusAdultos);
						$metaParceiroDeDeus->setValorJovem($parceiroDeDeusJovens);
						$this->getRepositorio()->getGrupoMetasOrdenacaoORM()->persistir($metaParceiroDeDeus);

						// Gerando meta de Membresia
						$metaMembresia = new GrupoMetasOrdenacao;
						$criterioMembresia = $this->getRepositorio()->getMetasOrdenacaoCriterioORM()->encontrarPorId(MetasOrdenacaoCriterio::membresia);
						$metaMembresia->setMetasOrdenacaoTipo($ordenacaoTipo);
						$metaMembresia->setMetasOrdenacaoCriterio($criterioMembresia);												
						$metaMembresia->setGrupo($grupoLogado);
						$metaMembresia->setValorAdulto($membresiaAdultos);
						$metaMembresia->setValorJovem($membresiaJovens);
						$this->getRepositorio()->getGrupoMetasOrdenacaoORM()->persistir($metaMembresia);

						// Gerando meta de Lideres
						$metaLideres = new GrupoMetasOrdenacao;
						$criterioLideres = $this->getRepositorio()->getMetasOrdenacaoCriterioORM()->encontrarPorId(MetasOrdenacaoCriterio::lideres);
						$metaLideres->setMetasOrdenacaoTipo($ordenacaoTipo);
						$metaLideres->setMetasOrdenacaoCriterio($criterioLideres);												
						$metaLideres->setGrupo($grupoLogado);
						$metaLideres->setValorAdulto($lideresAdultos);
						$metaLideres->setValorJovem($lideresJovens);
						$this->getRepositorio()->getGrupoMetasOrdenacaoORM()->persistir($metaLideres);
					}				
					if($ordenacaoTipo->getId() === $ordenacaoTipoBispo){
						$parceiroDeDeusBispo = $post_data['parceiroDeDeusBispo'];
						$igrejas = $post_data['igrejas'];

						// Gerando meta de Parceiro de Deus para Bispos
						$metaParceiroDeDeusBispo = new GrupoMetasOrdenacao;
						$criterioParceiroDeDeusBispo = $this->getRepositorio()->getMetasOrdenacaoCriterioORM()->encontrarPorId(MetasOrdenacaoCriterio::parceiroDeDeus);
						$metaParceiroDeDeusBispo->setMetasOrdenacaoTipo($ordenacaoTipo);
						$metaParceiroDeDeusBispo->setMetasOrdenacaoCriterio($criterioParceiroDeDeusBispo);												
						$metaParceiroDeDeusBispo->setGrupo($grupoLogado);
						$metaParceiroDeDeusBispo->setValorAdulto($parceiroDeDeusBispo);
						$metaParceiroDeDeusBispo->setValorJovem($parceiroDeDeusBispo);
						$this->getRepositorio()->getGrupoMetasOrdenacaoORM()->persistir($metaParceiroDeDeusBispo);

						// Gerando meta de igrejas para Bispos
						$metaIgrejasBispo = new GrupoMetasOrdenacao;
						$criterioIgrejasBispo = $this->getRepositorio()->getMetasOrdenacaoCriterioORM()->encontrarPorId(MetasOrdenacaoCriterio::igrejas);
						$metaIgrejasBispo->setMetasOrdenacaoTipo($ordenacaoTipo);
						$metaIgrejasBispo->setMetasOrdenacaoCriterio($criterioIgrejasBispo);												
						$metaIgrejasBispo->setGrupo($grupoLogado);
						$metaIgrejasBispo->setValorAdulto($igrejas);
						$metaIgrejasBispo->setValorJovem($igrejas);
						$this->getRepositorio()->getGrupoMetasOrdenacaoORM()->persistir($metaIgrejasBispo);
					}
				}				
				$this->getRepositorio()->fecharTransacao();				
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();				
				echo $exc->getMessage();
			}
		}
		$dados = Array();
		$dados['tiposDeMetasOrdenacao'] = $tiposDeMetasOrdenacao;
		return new ViewModel($dados);
	}

	public function selecionarRevisionistaAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$idRevisao = $sessao->idSessao;
		$sessao->idRevisao = $idRevisao;
		//unset($sessao->idSessao);

		$view = new ViewModel(array(
			Constantes::$ENTIDADE => $entidade,
			'repositorioORM' => $this->getRepositorio(),
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	public function selecionarRevisionistaTodosAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$idRevisao = $sessao->idSessao;
		$sessao->idRevisao = $idRevisao;
		//unset($sessao->idSessao);

		$view = new ViewModel(array(
			Constantes::$ENTIDADE => $entidade,
			'repositorioORM' => $this->getRepositorio(),
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	public function cadastrarPessoaRevisaoAction() {
		/* Helper Controller */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		if ($sessao->idSessao == null || $sessao->idRevisao == null) {
			return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
				Constantes::$PAGINA => Constantes::$PAGINA_REVISIONISTAS,
			));
		}
		$idPessoa = $sessao->idSessao;

		$tipos = $this->getRepositorio()->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
		$grupoPessoa = $pessoa->getGrupoPessoaAtivo();


		/* Formulario */
		$formCadastrarPessoaRevisao = new CadastrarPessoaRevisaoForm(Constantes::$FORM_CADASTRAR_PESSOA_REVISAO, $pessoa);

		$view = new ViewModel(array(
			Constantes::$FORM_CADASTRAR_PESSOA_REVISAO => $formCadastrarPessoaRevisao,
		));

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA_REVISAO);
		$view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA_REVISAO);



		return $view;
	}

	/**
	 * Recupera o grupo do perfil selecionado
	 * @param RepositorioORM $repositorioORM
	 * @return Grupo
	 */
	private function getGrupoSelecionado($repositorioORM) {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		return $entidade->getGrupo();
	}

	public function salvarPessoaRevisaoAction() {
		$request = $this->getRequest();

		try {
			$this->getRepositorio()->iniciarTransacao();
			$post_data = $request->getPost();
			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

			/* validação */
			$pessoa->setNome($post_data[Constantes::$INPUT_PRIMEIRO_NOME] . " " . $post_data[Constantes::$INPUT_ULTIMO_NOME]);
			$pessoa->setTelefone($post_data[Constantes::$INPUT_DDD] . $post_data[Constantes::$INPUT_TELEFONE]);
			$pessoa->setData_nascimento($post_data[Constantes::$FORM_INPUT_ANO] . "-" . $post_data[Constantes::$FORM_INPUT_MES] . "-" .
				$post_data[Constantes::$FORM_INPUT_DIA]);
			$pessoa->setSexo($post_data[Constantes::$INPUT_NUCLEO_PERFEITO]);
			$pessoa->setEmail_revisao($post_data['email_revisao']);

			/* Salvar a pessoa e o grupo pessoa correspondente */
			$this->getRepositorio()->getPessoaORM()->persistir($pessoa, false);
			$sessao = new Container(Constantes::$NOME_APLICACAO);
			$sessao->idRevisionista = $pessoa->getId();

			/* Bloco para inclusao da pessoa no evento frequencia */
			$idRevisao = $sessao->idRevisao;
			if ($sessao->idRevisao == null) {
				return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
					Constantes::$PAGINA => Constantes::$PAGINA_REVISIONISTAS,
				));
			}
			unset($sessao->idRevisao);
			$eventoFrequencia = new EventoFrequencia();
			$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
			$eventoFrequencia->setEvento($eventoRevisao);
			$eventoFrequencia->setPessoa($pessoa);
			$eventoFrequencia->setFrequencia('N');
			$this->getRepositorio()->getEventoFrequenciaORM()->persistir($eventoFrequencia);
			$sessao->idSessao = $eventoFrequencia->getId();

			$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
			$equipe = $entidade->getGrupo()->getGrupoEquipe();
			$igreja = $entidade->getGrupo()->getGrupoIgreja();
			$date = new DateTime($pessoa->getData_nascimento());
			$idade = $date->diff(new DateTime(date('Y-m-d')));
			$idade = intVal($idade->format('%y'));
			$fatoRevisao = new FatoRevisao();
			$fatoRevisao->numero_identificador = $numeroIdentificador;
			$fatoRevisao->matricula = $eventoFrequencia->getId();
			$fatoRevisao->ativo = 'N';
			$fatoRevisao->sexo = $pessoa->getSexo();;
			$fatoRevisao->evento_id = $idRevisao;
			$fatoRevisao->nome = $pessoa->getNome();
			$fatoRevisao->email_revisao = $pessoa->getEmail_revisao();
			$fatoRevisao->nome_equipe = $equipe->getEntidadeAtiva()->getNome();
			$fatoRevisao->entidade = $entidade->infoEntidade();
			$fatoRevisao->nome_igreja = $igreja->getEntidadeAtiva()->getNome();
			$fatoRevisao->lideres = $entidade->getGrupo()->getNomeLideresAtivos();
			$fatoRevisao->data_nascimento = $pessoa->getData_nascimento();
			$fatoRevisao->data_revisao = $eventoRevisao->getData();
			$fatoRevisao->idade = $idade;
			$fatoRevisao->tipo = 1;
			$this->getRepositorio()->getFatoRevisaoORM()->persistir($fatoRevisao);

			self::registrarLog(RegistroAcao::CADASTROU_UM_REVISIONISTA, $extra = 'Id: '.$eventoFrequencia->getId());

			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
				Constantes::$PAGINA => Constantes::$PAGINA_FICHA_REVISAO,
			));
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getMessage();
		}
	}

	public function fichaRevisaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEventoFrequencia = $sessao->idSessao;
		$eventoFrequencia = $this->getRepositorio()->getEventoFrequenciaORM()->encontrarPorId($idEventoFrequencia);
		$pessoaRevisionista = $eventoFrequencia->getPessoa();
		$idRevisao = $eventoFrequencia->getEvento()->getId();
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$grupoPessoaRevisionista = $pessoaRevisionista->getGrupoPessoaAtivo();
		$grupoLider = $grupoPessoaRevisionista->getGrupo();
		$nomeEntidadeLider = $grupoLider->getEntidadeAtiva()->infoEntidade();
		$grupoIgreja = $grupoLider->getGrupoIgreja();
		$nomeIgreja = $grupoIgreja->getEntidadeAtiva()->infoEntidade();
		$grupoResponsavel = $grupoLider->getResponsabilidadesAtivas();
		$pessoas = array();
		foreach ($grupoResponsavel as $gr) {
			$p = $gr->getPessoa();
			$pessoas[] = $p;
		}
		$view = new ViewModel(array(
			Constantes::$PESSOA_REVISAO => $pessoaRevisionista,
			Constantes::$REVISAO_VIEW => $eventoRevisao,
			Constantes::$PESSOA_LIDER_REVISAO => $pessoas,
			Constantes::$ENTIDADE_REVISAO => $nomeEntidadeLider,
			Constantes::$NOME_IGREJA_FICHA_REVISAO => $nomeIgreja,
			Constantes::$STRING_ID_EVENTO_FREQUENCIA => $idEventoFrequencia,
		));

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$TEMPLATE_JS_FICHA_REVISAO);
		$view->addChild($layoutJS, Constantes::$STRING_JS_FICHA_REVISAO);

		return $view;
	}

	public function selecionarFichasRevisionistaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idRevisao = $sessao->idSessao;
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$sessao->idRevisao = $idRevisao;
		$arrayDeEventosRevisoes = array();
		if(
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao || 
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao
		){
			$request = $this->getRequest();
			if ($request->isPost()) {
				$arrayDeRevisoes = Array();
				foreach ($_POST as $key => $value) {
					if (substr($key, 0, 7) == 'revisao') {
						$arrayDeEventosRevisoes[] = $value;
					}
				}
			}
		}else{
			$arrayDeEventosRevisoes[] = $idRevisao;
		}

		$fatosRevisao = array();
		foreach($arrayDeEventosRevisoes as $idRevisaoParaPesquisar){
			$fatosRevisaoPesquisado = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisaoParaPesquisar));
			foreach($fatosRevisaoPesquisado as $fatoRevisaoPesquisado){
				$fatosRevisao[] = $fatoRevisaoPesquisado; 
			}
		}

		$listas = array();
		if(count($fatosRevisao) > 0) {
			foreach ($fatosRevisao as $fatoRevisao) {
				$listas[$fatoRevisao->tipo][] = $fatoRevisao;
			}
		}
	
		$view = new ViewModel(array(
			'evento' => $eventoRevisao,
			'listas' => $listas,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	public function listaLideresAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$arrayDeEventosRevisoes = array();
		if(
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao || 
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao
		){
			$request = $this->getRequest();
			if ($request->isPost()) {
				$arrayDeRevisoes = Array();
				foreach ($_POST as $key => $value) {
					if (substr($key, 0, 7) == 'revisao') {
						$arrayDeEventosRevisoes[] = $value;
					}
				}
			}
		}else{
			$idRevisao = $sessao->idSessao;
			$arrayDeEventosRevisoes[] = $idRevisao;
		}

		$fatosRevisao = array();
		foreach($arrayDeEventosRevisoes as $idRevisaoParaPesquisar){
			$fatosRevisaoPesquisado = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisaoParaPesquisar));
			foreach($fatosRevisaoPesquisado as $fatoRevisaoPesquisado){
				$fatosRevisao[] = $fatoRevisaoPesquisado; 
			}
		}

		$listas = array();
		if(count($fatosRevisao) > 0) {
			foreach ($fatosRevisao as $fatoRevisao) {
				if ($fatoRevisao->tipo === 2 && $fatoRevisao->ativo === 'S') {
					$listas[$fatoRevisao->sexo][] = $fatoRevisao;
				}
			}
		}

		$view = new ViewModel(array(
			'evento' => $eventoRevisao,
			'listas' => $listas,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	public function listaRevisionistasAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$arrayDeEventosRevisoes = array();
		if(
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao || 
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao
		){
			$request = $this->getRequest();
			if ($request->isPost()) {
				$arrayDeRevisoes = Array();
				foreach ($_POST as $key => $value) {
					if (substr($key, 0, 7) == 'revisao') {
						$arrayDeEventosRevisoes[] = $value;
					}
				}
			}
		}else{
			$idRevisao = $sessao->idSessao;
			$arrayDeEventosRevisoes[] = $idRevisao;
		}

		$fatosRevisao = array();
		foreach($arrayDeEventosRevisoes as $idRevisaoParaPesquisar){
			$fatosRevisaoPesquisado = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisaoParaPesquisar));
			foreach($fatosRevisaoPesquisado as $fatoRevisaoPesquisado){
				$fatosRevisao[] = $fatoRevisaoPesquisado; 
			}
		}

		$listas = array();
		if($fatosRevisao) {
			foreach ($fatosRevisao as $fatoRevisao) {
				if ($fatoRevisao->tipo === 1 && $fatoRevisao->ativo === 'S') {
					$listas[$fatoRevisao->sexo][] = $fatoRevisao;
				}
			}
		}

		$view = new ViewModel(array(
			'evento' => $eventoRevisao,
			'listas' => $listas,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	public function ativarFichaRevisaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idRevisao = $sessao->idSessao;
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$formAtivarFicha = new AtivarFichaForm(Constantes::$FORM_ATIVAR_FICHA, null);

		$view = new ViewModel(array(
			Constantes::$FORM_ATIVAR_FICHA => $formAtivarFicha,
			'evento' => $eventoRevisao,
		));

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$TEMPLATE_JS_ATIVAR_FICHA);
		$view->addChild($layoutJS, Constantes::$STRING_JS_ATIVAR_FICHA_REVISAO);

		return $view;
	}

	public function fichasAtivasAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idRevisao = $sessao->idSessao;
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$fatosRevisao = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisao));

		$listas = array();
		if($fatosRevisao) {
			foreach ($fatosRevisao as $fatoRevisao) {
				if ($fatoRevisao->ativo === 'S') {
					$listas[$fatoRevisao->tipo][] = $fatoRevisao;
				}
			}
		}
		$view = new ViewModel(array(
			'evento' => $eventoRevisao,
			'listas' => $listas,
		));

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$TEMPLATE_JS_ATIVAR_FICHA);
		$view->addChild($layoutJS, Constantes::$STRING_JS_ATIVAR_FICHA_REVISAO);

		return $view;
	}

	public function selecionarFichasAtivasAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idRevisao = $sessao->idSessao;
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$sessao->idRevisao = $idRevisao;
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$fatosRevisao = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisao));

		$listas = array();
		if($fatosRevisao) {
			foreach ($fatosRevisao as $fatoRevisao) {
				if ($fatoRevisao->ativo === 'S') {
					if ($fatoRevisao->tipo === 1) {
						$listas[1][] = $fatoRevisao;
					}
					if ($fatoRevisao->tipo === 2) {
						$listas[2][] = $fatoRevisao;
					}
				}
			}
		}
	
		$view = new ViewModel(array(
			'evento' => $eventoRevisao,
			'listas' => $listas,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

		$layoutJSValidacao = new ViewModel();
		$layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
		$view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

		return $view;
	}

	public function consultarFichaAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();

		if ($request->isPost()) {
			$dados = array();
			$dados['response'] = true;
			$post_data = $request->getPost();
			$idEventoFrequencia = $post_data['idEventoFrequencia'];
			if ($idEventoFrequencia != null || $idEventoFrequencia == 0) {
				if ($eventoFrequencia = $this->getRepositorio()->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia)) {
					$dados['status'] = 1;
					/* Revisionista */
					if ($grupoPessoaRevisionista = $eventoFrequencia->getPessoa()->getGrupoPessoaAtivo()) {
						$dados['label'] = 'Revisionista';
						$dados['nomeRevisionista'] = $eventoFrequencia->getPessoa()->getNome();
						$dados['nomeEntidadeLider'] = $grupoPessoaRevisionista->getGrupo()->getEntidadeAtiva()->infoEntidade();
						$dados['idEventoFrequencia'] = $eventoFrequencia->getId();
					} else {
						/* Lider */
						if ($responsabilidadeAtivas = $eventoFrequencia->getPessoa()->getResponsabilidadesAtivas()) {
							$dados['label'] = 'Líder';
							$dados['nomeRevisionista'] = $eventoFrequencia->getPessoa()->getNome();
							$dados['nomeEntidadeLider'] = $responsabilidadeAtivas[0]->getGrupo()->getEntidadeAtiva()->infoEntidade();
							$dados['idEventoFrequencia'] = $eventoFrequencia->getId();
						}
					}
				} else {
					$dados['status'] = 0;
				}
				$response->setContent(Json::encode($dados));
			} else {
				$response->setContent(Json::encode(array('response' => 'true', 'status' => 0,)));
			}
			return $response;
		}
	}

	/*
	 * Função para trocar GrupoPessoaTipo
	 *  idTipo 1 = Visitante
	 *  idTipo 2 = Consolidação
	 *  idTipo 3 = Membro
	 */

	private function alterarGrupoPessoaTipo($idTipo, RepositorioORM $repositorioORM, Pessoa $pessoaRevisionista) {

		/* Inativando o grupo pessoa antigo */
		$grupoPessoaRevisionistaAntigo = $pessoaRevisionista->getGrupoPessoaAtivo();
		$grupoPessoaRevisionistaAntigo->setDataEHoraDeInativacao(Funcoes::proximoDomingo());
		$this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoaRevisionistaAntigo, $mudarDataDeCricao = false);

		/* Busca GrupoPessoaTipo */
		$grupoPessoaTipo = $this->getRepositorio()->getGrupoPessoaTipoORM()->encontrarPorId($idTipo);

		/* Bloco para inclusao da pessoa no grupo Pessoa como membro. */
		$grupoPessoa = new GrupoPessoa();
		$grupoPessoa->setPessoa($pessoaRevisionista);
		$grupoPessoa->setGrupo($grupoPessoaRevisionistaAntigo->getGrupo());
		$grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
		$grupoPessoa->setDataEHoraDeCriacao(Funcoes::proximaSegunda());
		$this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa, $mudarDataDeCricao = false);

		return $grupoPessoa;
	}

	public function ativarReservaRevisaoAction() {
		$request = $this->getRequest();

		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();
				$idEventoFrequencia = $post_data['codigo'];

				$eventoFrequencia = $this->getRepositorio()->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);

				/* Revisionista */
				if ($eventoFrequencia->getPessoa()->getGrupoPessoaAtivo()) {
					if ($eventoFrequencia->getFrequencia() == 'N') {
						$pessoaRevisionista = $eventoFrequencia->getPessoa();
						$this->alterarGrupoPessoaTipo(GrupoPessoaTipo::MEMBRO, $this->getRepositorio(), $pessoaRevisionista);

						/* Ativando a presença do Revisionista  */
						$eventoFrequencia->setFrequencia('S');
						$this->getRepositorio()->getEventoFrequenciaORM()->persistir($eventoFrequencia, false);
					}
				} else {
					/* Lider */
					if ($eventoFrequencia->getPessoa()->getResponsabilidadesAtivas()) {
						/* Ativando a presença do Lider  */
						$eventoFrequencia->setFrequencia('S');
						$this->getRepositorio()->getEventoFrequenciaORM()->persistir($eventoFrequencia, false);
					}
				}

				$fatoRevisao = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorMatricula($idEventoFrequencia);
				$fatoRevisao->setAtivo('S');
				$this->getRepositorio()->getFatoRevisaoORM()->persistir($fatoRevisao, $mudatDataDeCriacao = false);

				self::registrarLog(RegistroAcao::LANCOU_UMA_FICHA_NO_REVISAO_DE_VIDAS, $extra = 'Id: ' . $eventoFrequencia->getId());
				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
					Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
	}

	public function ativarReservaRevisaoQrCodeAction() {
		/* Busca numero do IdEventoMatricula */
		$parametro = $this->params()->fromRoute(Constantes::$ID);
		$idEventoFrequencia = $parametro;

		/* Resgatando Dados do EventoFrequencia e do Revisionista */
		$eventoFrequencia = $this->getRepositorio()->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);
		$pessoaRevisionista = $eventoFrequencia->getPessoa();
		/* Membro = idTipo 3 */
		$this->alterarGrupoPessoaTipo(3, $this->getRepositorio(), $pessoaRevisionista);

		/* Ativando a presença do Revisionista  */
		$eventoFrequencia->setFrequencia('S');

		/* Mensagens de retorno */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$sessao->mostrarNotificacao = true;
		$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_REVISIONISTA;
		$sessao->textoMensagem = $pessoaRevisionista->getNome();
		$sessao->idSessao = $eventoFrequencia->getId();

		return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
			Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
		));
	}

	public function selecionarLiderRevisaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idRevisao = $sessao->idSessao;
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$view = new ViewModel(array(
			Constantes::$ENTIDADE => $entidade,
			'idRevisao' => $idRevisao,
		));

		/* Javascript */
		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$TEMPLATE_JS_SELECIONAR_LIDER_REVISAO);
		$view->addChild($layoutJS, Constantes::$STRING_JS_SELECIONAR_LIDER_REVISAO);

		return $view;
	}

	public function ativarLideresRevisaoAction() {

		try {
			$this->getRepositorio()->iniciarTransacao();
			$sessao = new Container(Constantes::$NOME_APLICACAO);
			$idRevisao = $sessao->idSessao;
			$idPessoa = $sessao->idPessoa;
			$pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
			$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
			$eventoFrequencia = new EventoFrequencia();
			$eventoFrequencia->setEvento($eventoRevisao);
			$eventoFrequencia->setPessoa($pessoaLogada);
			$eventoFrequencia->setFrequencia('N');
			$this->getRepositorio()->getEventoFrequenciaORM()->persistir($eventoFrequencia);
			$sessao->idSessao = $eventoFrequencia->getId();

			$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
			$equipe = $entidade->getGrupo()->getGrupoEquipe();
			$igreja = $entidade->getGrupo()->getGrupoIgreja();
			$date = new DateTime($pessoaLogada->getData_nascimento());
			$idade = $date->diff(new DateTime(date('Y-m-d')));
			$idade = intVal($idade->format('%y'));
			$hierarquia = $eventoFrequencia->getPessoa()->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
			if($eventoFrequencia->getPessoa()->getSexo() == 'F'){
				$hierarquia = $eventoFrequencia->getPessoa()->getPessoaHierarquiaAtivo()->getHierarquia()->getNome_feminino();
			}
			$fatoRevisao = new FatoRevisao();
			$fatoRevisao->numero_identificador = $numeroIdentificador;
			$fatoRevisao->matricula = $eventoFrequencia->getId();
			$fatoRevisao->ativo = 'N';
			$fatoRevisao->sexo = $pessoaLogada->getSexo();;
			$fatoRevisao->evento_id = $idRevisao;
			$fatoRevisao->nome = $pessoaLogada->getNome();
			$fatoRevisao->nome_equipe = $equipe->getEntidadeAtiva()->getNome();
			$fatoRevisao->entidade = $entidade->infoEntidade();
			$fatoRevisao->nome_igreja = $igreja->getEntidadeAtiva()->getNome();
			$fatoRevisao->lideres = $entidade->getGrupo()->getNomeLideresAtivos();
			$fatoRevisao->data_nascimento = $pessoaLogada->getData_nascimento();
			$fatoRevisao->data_revisao = $eventoRevisao->getData();
			$fatoRevisao->idade = $idade;
			$fatoRevisao->tipo = 2;
			$fatoRevisao->hierarquia = $hierarquia;
			$this->getRepositorio()->getFatoRevisaoORM()->persistir($fatoRevisao);

			self::registrarLog(RegistroAcao::CADASTROU_UM_LIDER_AO_REVISAO_DE_VIDAS, $extra = 'Id: '.$idPessoa);
			$this->getRepositorio()->fecharTransacao();
			return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
				Constantes::$ACTION => 'LiderRevisao',
			));
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getTraceAsString();
		}
	}

	public function removerRevisionistaAtivoAction() {

		try {
			$sessao = new Container(Constantes::$NOME_APLICACAO);
			$this->getRepositorio()->iniciarTransacao();
			$idEventoFrequencia = $sessao->idSessao;

			/* Resgatando Dados do EventoFrequencia e do Revisionista */
			$eventoFrequencia = $this->getRepositorio()->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);
			if ($eventoFrequencia->getFrequencia() == 'S') {
				if ($eventoFrequencia->getPessoa()->getGrupoPessoaAtivo()) {
					$this->alterarGrupoPessoaTipo(GrupoPessoaTipo::CONSOLIDACAO, $this->getRepositorio(), $eventoFrequencia->getPessoa());
				}

				$eventoFrequencia->setFrequencia('N');
				$this->getRepositorio()->getEventoFrequenciaORM()->persistir($eventoFrequencia, false);

				/* Mensagens de retorno */
				$sessao->mostrarNotificacao = true;
				$sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_REVISIONISTA;
				$sessao->textoMensagem = $eventoFrequencia->getPessoa()->getNome();
				$sessao->idSessao = $eventoFrequencia->getEvento()->getId();

				$fatoRevisao = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorMatricula($idEventoFrequencia);
				$fatoRevisao->setAtivo('N');
				$this->getRepositorio()->getFatoRevisaoORM()->persistir($fatoRevisao, $mudatDataDeCriacao = false);

				self::registrarLog(RegistroAcao::REMOVEU_UMA_FICHA_DO_REVISAO_DE_VIDAS, $extra = 'Id: ' . $eventoFrequencia->getId());
				$this->getRepositorio()->fecharTransacao();
			} else {
				$this->getRepositorio()->desfazerTransacao();
			}
			return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
				Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
			));
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getTraceAsString();
		}
	}

	public function solicitacoesAction() {
		self::validarSeSouIgrejaOuEquipeOuCoordencaoOuRegiao();
		$request = $this->getRequest();
		$dados = array();
		if($request->isPost()){
			$postDados = $request->getPost();
			$mes = $postDados['mes'];
			$ano = $postDados['ano'];
		} else{
			$mes = date('m');
			$ano = date('Y');
		}
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		$periodoInicial = $arrayPeriodoDoMes[0];
		$periodoFinal = $arrayPeriodoDoMes[1];

		if($mes == date('m') && $ano = date('Y')){
			$periodoFinal = 0;
		}

		$arrayPeriodoInicial = Funcoes::montaPeriodo($periodoInicial);
		$arrayPeriodoFinal = Funcoes::montaPeriodo($periodoFinal);
		$dataInicio = $arrayPeriodoInicial[3].'-'.$arrayPeriodoInicial[2].'-'.$arrayPeriodoInicial[1];
		$dataFim = $arrayPeriodoFinal[6].'-'.$arrayPeriodoFinal[5].'-'.$arrayPeriodoFinal[4];

		$solicitacoesDivididasPorTipo = array();

		if(
			$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial &&
			$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao &&
		$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao){
			$idIgreja = $entidade->getGrupo()->getGrupoIgreja()->getId();
			$solicitacoes = $this->getRepositorio()->getSolicitacaoORM()->encontrarTodosPorDataDeCriacao($dataInicio, $dataFim, $idIgreja);

			$solicitacoesTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarTodos();
		}
		if(
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::presidencial ||
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao ||
		$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
			$idRegiao = $entidade->getGrupo()->getId();
			$solicitacoes = $this->getRepositorio()->getSolicitacaoORM()->encontrarTodosPorDataDeCriacao($dataInicio, $dataFim, $idRegiao);

			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::REMOVER_IGREJA);
			$solicitacoesTipo[] = $solicitacaoTipo;
			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::TRANSFERIR_IGREJA);
			$solicitacoesTipo[] = $solicitacaoTipo;
			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::ADICIONAR_RESPONSABILIDADE_SECRETARIO);
			$solicitacoesTipo[] = $solicitacaoTipo;
			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::REMOVER_RESPONSABILIDADE_SECRETARIO);
			$solicitacoesTipo[] = $solicitacaoTipo;
			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA);
			$solicitacoesTipo[] = $solicitacaoTipo;
			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE);
			$solicitacoesTipo[] = $solicitacaoTipo;

			$trocaDeResponsaveis = $this->getRepositorio()->getTrocaResponsavelORM()->encontrarTodosPorDataDeCriacao($dataInicio, $dataFim, $idRegiao);
			$dados['trocaDeResponsaveis'] = $trocaDeResponsaveis;
		}

		foreach($solicitacoes as $solicitacaoPorData){
			$solicitacao = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorId($solicitacaoPorData['id']);
			$adicionar = true;
			if($solicitacao->getSolicitacaoTipo()->getId() !== SolicitacaoTipo::TRANSFERIR_ALUNO){
				if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe){
					$objeto = $solicitacao->getObjeto1();
					$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($objeto);
					if($grupo && $grupo->getGrupoEquipe()){
						if($entidade->getGrupo()->getId() !== $grupo->getGrupoEquipe()->getId()){
							$adicionar = false;
						}
						if($adicionar === false && 
							$solicitacao->getSolicitacaoTipo()->getId() === SolicitacaoTipo::TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE){
							$objeto2 = $solicitacao->getObjeto2();
							$grupo2 = $this->getRepositorio()->getGrupoORM()->encontrarPorId($objeto2);
							if($entidade->getGrupo()->getId() === $grupo2->getGrupoEquipe()->getId()){
								$adicionar = true;
							}
						}
					}
				}
			}
			if($adicionar){
				$solicitacoesDivididasPorTipo[$solicitacao->getSolicitacaoTipo()->getId()][] = $solicitacao;
			}
		}


		$dados['grupo'] = $entidade->getGrupo();
		$dados['entidade'] = $entidade;		
		$dados['solicitacoes'] = $solicitacoesDivididasPorTipo;
		$dados['solicitacoesTipo'] = $solicitacoesTipo;
		$dados['titulo'] = 'Solicitações';
		$dados['repositorio'] = $this->getRepositorio();

		$dados['mes'] = $mes;
		$dados['ano'] = $ano;
		self::registrarLog(RegistroAcao::VER_SOLICITACOES, $extra = '');
		return new ViewModel($dados);
	}

	public function solicitacaoAceitarAction(){
		self::validarSeSouIgreja();
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		if($idSessao = $sessao->idSessao){
			$solicitacao = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorId($idSessao);
			$solicitacaoSituacaoAtual = $solicitacao->getSolicitacaoSituacaoAtiva();
			$solicitacaoSituacaoAtual->setDataEHoraDeInativacao();
			$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoAtual, $mudarDataDeCriacao = false);

			$solicitacaoSituacaoAceito = new SolicitacaoSituacao();
			$solicitacaoSituacaoAceito->setSolicitacao($solicitacao);
			$solicitacaoSituacaoAceito->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ACEITO_AGENDADO));
			$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoAceito);

			self::registrarLog(RegistroAcao::ACEITAR_SOLICITACAO, $extra = 'Id: ' + $idSessao);
		}
		return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
			Constantes::$PAGINA => Constantes::$PAGINA_SOLICITACOES,
		));
	}

	public function trocaDeResponsavelRecusarAction(){
		self::validarSeSouRegiao();
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		if($idSessao = $sessao->idSessao){
			$trocaDeResponsaveisAtivas = $this->getRepositorio()->getTrocaResponsavelORM()->encontrarPorId($idSessao);						
			$trocaDeResponsaveisAtivas->setDataEHoraDeInativacao();
			$this->getRepositorio()->getTrocaResponsavelORM()->persistir($trocaDeResponsaveisAtivas, $mudarDataDeCriacao = false);			
		}
		return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
			Constantes::$PAGINA => Constantes::$PAGINA_SOLICITACOES,
		));
	}

	public function solicitacaoRecusarAction(){
		self::validarSeSouRegiaoOuIgreja();
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		if($idSessao = $sessao->idSessao){
			$solicitacao = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorId($idSessao);
			$solicitacaoSituacaoAtual = $solicitacao->getSolicitacaoSituacaoAtiva();
			$solicitacaoSituacaoAtual->setDataEHoraDeInativacao();
			$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoAtual, $mudarDataDeCriacao = false);

			$solicitacaoSituacaoRecusada = new SolicitacaoSituacao();
			$solicitacaoSituacaoRecusada->setSolicitacao($solicitacao);
			$solicitacaoSituacaoRecusada->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::RECUSAO));
			$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoRecusada);

			self::registrarLog(RegistroAcao::RECUSAR_SOLICITACAO, $extra = 'Id: ' + $idSessao);
		}
		return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
			Constantes::$PAGINA => Constantes::$PAGINA_SOLICITACOES,
		));
	}	

	public function solicitacaoAction() {
		ini_set('memory_limit', '1024M');
		self::validarSeSouIgrejaOuEquipeOuCoordencaoOuRegiao();

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		$grupo = $entidade->getGrupo();

		$solicitacaoTiposSemAjuste = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarTodos();
		$solicitacaoTiposAjustado = array();

		if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao ||
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){

				$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE);
				$solicitacaoTiposAjustado[] = $solicitacaoTipo;

				if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
					$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::REMOVER_IGREJA);
					$solicitacaoTiposAjustado[] = $solicitacaoTipo;
					$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::TRANSFERIR_IGREJA);
					$solicitacaoTiposAjustado[] = $solicitacaoTipo;
					$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::ADICIONAR_RESPONSABILIDADE_SECRETARIO);
					$solicitacaoTiposAjustado[] = $solicitacaoTipo;
					$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::REMOVER_RESPONSABILIDADE_SECRETARIO);
					$solicitacaoTiposAjustado[] = $solicitacaoTipo;
				}
				//$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId(SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA);
				//$solicitacaoTiposAjustado[] = $solicitacaoTipo;

				$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
				$igrejas = array();
				$equipes = array();
				$paraOndeTransferir = array();
				foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho12) {
					$grupo12 = $grupoPaiFilhoFilho12->getGrupoPaiFilhoFilho();

					if($grupo12->getEntidadeAtiva() && $grupo12->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
						$igrejas[] = $grupo12;
						foreach($grupo12->getGrupoPaiFilhoFilhosAtivosReal() as $gpfEquipe){
							$equipes[] = $gpfEquipe->getGrupoPaiFilhoFilho();
						}
					}	

					if($grupo12->getEntidadeAtiva() 
						&& ($grupo12->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao
						|| $grupo12->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao)){
							$paraOndeTransferir[] = $grupo12;
							$grupoPaiFilhoFilhos144 = $grupo12->getGrupoPaiFilhoFilhosAtivosReal();
							foreach ($grupoPaiFilhoFilhos144 as $grupoPaiFilhoFilho144) {
								$grupo144 = $grupoPaiFilhoFilho144->getGrupoPaiFilhoFilho();

								if($grupo144->getEntidadeAtiva() && $grupo144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
									$igrejas[] = $grupo144;
									foreach($grupo144->getGrupoPaiFilhoFilhosAtivosReal() as $gpfEquipe){
										$equipes[] = $gpfEquipe->getGrupoPaiFilhoFilho();
									}
								}	

								if($grupo144->getEntidadeAtiva() 
									&& ($grupo144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao
									|| $grupo144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao)){
										$paraOndeTransferir[] = $grupo144;
										$grupoPaiFilhoFilhos1728 = $grupo144->getGrupoPaiFilhoFilhosAtivosReal();
										foreach ($grupoPaiFilhoFilhos1728 as $grupoPaiFilhoFilho1728) {
											$grupo1728 = $grupoPaiFilhoFilho1728->getGrupoPaiFilhoFilho();

											if($grupo1728->getEntidadeAtiva() && $grupo1728->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
												$igrejas[] = $grupo1728;
												foreach($grupo1728->getGrupoPaiFilhoFilhosAtivosReal() as $gpfEquipe){
													$equipes[] = $gpfEquipe->getGrupoPaiFilhoFilho();
												}
											}

											if($grupo1728->getEntidadeAtiva() 
												&& ($grupo1728->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao
												|| $grupo1728->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao)){
													$paraOndeTransferir[] = $grupo1728;
													$grupoPaiFilhoFilhos20736 = $grupo1728->getGrupoPaiFilhoFilhosAtivosReal();
													foreach ($grupoPaiFilhoFilhos20736 as $grupoPaiFilhoFilho20736) {
														$grupo20736 = $grupoPaiFilhoFilho20736->getGrupoPaiFilhoFilho();

														if($grupo20736->getEntidadeAtiva() && $grupo20736->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
															$igrejas[] = $grupo20736;
															foreach($grupo20736->getGrupoPaiFilhoFilhosAtivosReal() as $gpfEquipe){
																$equipes[] = $gpfEquipe->getGrupoPaiFilhoFilho();
															}
														}
													}
												}
										}
									}
							}
						}
				}
			}

		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao &&
		$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao){
			if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
				foreach($solicitacaoTiposSemAjuste as $solicitacaoTipo){
					if($solicitacaoTipo->getId() !== SolicitacaoTipo::TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::REMOVER_IGREJA
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::TRANSFERIR_IGREJA){
							$solicitacaoTiposAjustado[] = $solicitacaoTipo;
						}	
				}	
			}else{
				foreach($solicitacaoTiposSemAjuste as $solicitacaoTipo){
					if($solicitacaoTipo->getId() !== SolicitacaoTipo::SUBIR_LIDER
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::TRANSFERIR_IGREJA
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::ADICIONAR_RESPONSABILIDADE_SECRETARIO
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::REMOVER_RESPONSABILIDADE_SECRETARIO
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::ABRIR_EQUIPE_COM_LIDER_DA_IGREJA
						&& $solicitacaoTipo->getId() !== SolicitacaoTipo::REMOVER_IGREJA){
							$solicitacaoTiposAjustado[] = $solicitacaoTipo;
						}	
				}	
			}

		}
		$formSolicitacao = new SolicitacaoForm('formSolicitacao');

		$view = new ViewModel(array(
			'entidade' => $entidade,
			'grupo' => $grupo,
			'solicitacaoTipos' => $solicitacaoTiposAjustado,
			Constantes::$FORM => $formSolicitacao,
			'titulo' => 'Solicitação',
			'discipulos' => $grupoPaiFilhoFilhos,
			'discipulosIgreja' => $discipulosIgreja,
			'grupoPaiFilhoEquipes' => $grupoPaiFilhoEquipes,
			'grupoPaiFilhoHomens' => $arrayHomens,
			'grupoPaiFilhoMulheres' => $arrayMulheres,
			'grupoPaiFilhoCasais' => $arrayCasais,
			'alunos' => $alunos,
			'igrejas' => $igrejas,
			'equipes' => $equipes,
			'paraOndeTransferir' => $paraOndeTransferir,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate('layout/layout-js-cadastrar-solicitacao');
		$view->addChild($layoutJS, 'layoutJSCadastrarSolicitacao');

		return $view;
	}

	/**
	 * Tela com confrmação de cadastro de grupo
	 * POST /cadastroSolicitacaoFinalizar
	 */
	public function solicitacaoFinalizarAction() {
		self::validarSeSouIgrejaOuEquipe();
		$sessao = new Container(Constantes::$NOME_APLICACAO);		
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoLogado = $entidade->getGrupo();
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
		$request = $this->getRequest();
		if ($request->isPost()) {			
			$post_data = $request->getPost();
			$solicitacaoTipo = $this->getRepositorio()->getSolicitacaoTipoORM()->encontrarPorId($post_data['solicitacaoTipoId']);
			if ($solicitacaoTipo->getId() == SolicitacaoTipo::ADICIONAR_RESPONSABILIDADE_SECRETARIO
			|| $solicitacaoTipo->getId() == SolicitacaoTipo::REMOVER_RESPONSABILIDADE_SECRETARIO) {				
				$solicitacoesParaVerificarObjeto1 = array();
				$solicitacoesParaVerificarObjeto1Auxiliar = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto1($post_data['idPessoa']);								
				foreach($solicitacoesParaVerificarObjeto1Auxiliar as $solicitacao){
					$solicitacoesParaVerificarObjeto1[] = $solicitacao;
				}
				$solicitacoesParaVerificarObjeto1Auxiliar = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto1($grupoLogado->getId());								
				foreach($solicitacoesParaVerificarObjeto1Auxiliar as $solicitacao){
					$solicitacoesParaVerificarObjeto1[] = $solicitacao;
				}									
			} else {				
				$solicitacoesParaVerificarObjeto1 = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto1($post_data['objeto1']);
				//Nota importante: Verificando o objeto 2 através do parametro $post_data['objeto1'] pois se há solicitação pendente do objeto 1 recebendo, o mesmo não pode estar sendo transferido
				$solicitacoesParaVerificarObjeto2 = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto2($post_data['objeto1']);				
			}
						
			/* validar se ja tem solicitacao */
			if($solicitacoesParaVerificarObjeto1 || $solicitacoesParaVerificarObjeto2){					
				$temSolicitacoesPendentes = false;
				foreach($solicitacoesParaVerificarObjeto1 as $solicitacaoParaVerificar){					
					if($solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::CONCLUIDO
						&& $solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::RECUSAO){							
						$temSolicitacoesPendentes = true;
					}
				}	
				foreach($solicitacoesParaVerificarObjeto2 as $solicitacaoParaVerificar){					
					if($solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::CONCLUIDO
						&& $solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::RECUSAO){							
						$temSolicitacoesPendentes = true;
					}
				}			
				
				if($temSolicitacoesPendentes){
					$sessao->mensagemSemAcesso = '<i class = "fa fa-warning text-warning"></i>';
					$sessao->mensagemSemAcesso .= ' O objeto selecionado em questão possui solicitações pendentes!';
					$sessao->corDoTexto = 'text-warning';
					return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
						Constantes::$ACTION => 'semAcesso',
					));
				}
			}
			if($post_data['objeto1'] == $post_data['objeto2']){
				if($temSolicitacoesPendentes){
					$sessao->mensagemSemAcesso = '<i class = "fa fa-warning text-warning"></i>';
					$sessao->mensagemSemAcesso .= ' O objeto selecionado em questão possui solicitações pendentes!';
					$sessao->corDoTexto = 'text-warning';
					return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
						Constantes::$ACTION => 'semAcesso',						
					));
				}
			}
			try {
				$this->getRepositorio()->iniciarTransacao();
				
				$idPessoaAtual = $sessao->idPessoa;
				$pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoaAtual);

				$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
				if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao){
					$grupoIgreja = $entidade->getGrupo()->getGrupoIgreja();
				}
				if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao ||
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
						$grupoIgreja = $entidade->getGrupo();
					}				

				/* Criando */
				$solicitacao = new Solicitacao();
				$solicitacao->setSolicitante($pessoaLogada);
				$solicitacao->setGrupo($grupoIgreja);
				$solicitacao->setSolicitacaoTipo($solicitacaoTipo);
				if($solicitacaoTipo->getId() === SolicitacaoTipo::ABRIR_EQUIPE_COM_LIDER_DA_IGREJA){
					$objeto1 = $entidade->getGrupo()->getId();
					$solicitacao->setObjeto1($objeto1);
				}else{
					$solicitacao->setObjeto1($post_data['objeto1']);
				}

				if ($solicitacaoTipo->getId() !== SolicitacaoTipo::SEPARAR) {
					$objeto2 = $post_data['objeto2'];
					$explodeObjeto2 = explode('_', $objeto2);
					if ($explodeObjeto2[1]) {
						$objeto2 = $explodeObjeto2[1];
					}
					if($solicitacaoTipo->getId() === SolicitacaoTipo::REMOVER_LIDER){
						$objeto2 = 0;
						$motivoFinal = 'Saiu da igreja';
						if($post_data['motivo'] == 'outro'){
							$motivoFinal = $post_data['textareaMotivo'];
						}
						$solicitacao->setMotivo($motivoFinal);
					}
					if($solicitacaoTipo->getId() === SolicitacaoTipo::REMOVER_CELULA){
						$objeto2 = $post_data['idGrupoEvento'];
					}
					if($solicitacaoTipo->getId() === SolicitacaoTipo::SUBIR_LIDER){
						$objeto2 = $grupoIgreja->getId();
					}
					if($solicitacaoTipo->getId() === SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA ||
						$solicitacaoTipo->getId() === SolicitacaoTipo::ABRIR_EQUIPE_COM_LIDER_DA_IGREJA){
							$objeto2 = $entidade->getGrupo()->getId();
						}
					$solicitacao->setObjeto2($objeto2);
				}
				if ($solicitacaoTipo->getId() === SolicitacaoTipo::SEPARAR) {
					$grupoCasal = $this->getRepositorio()->getGrupoORM()->encontrarPorId($solicitacao->getObjeto1());
					$grupoResponsaveis = $grupoCasal->getResponsabilidadesAtivas();
					if ($post_data['quemVaiSair'] == 1) {
						foreach ($grupoResponsaveis as $grupoResponsavel) {
							if ($grupoResponsavel->getPessoa()->getSexo() == 'M') {
								$objeto2 = $grupoResponsavel->getPessoa()->getId();
							}
						}
					} else {
						foreach ($grupoResponsaveis as $grupoResponsavel) {
							if ($grupoResponsavel->getPessoa()->getSexo() == 'F') {
								$objeto2 = $grupoResponsavel->getPessoa()->getId();
							}
						}
					}
					$solicitacao->setObjeto2($objeto2);
				}

				if ($solicitacaoTipo->getId() === SolicitacaoTipo::REMOVER_IGREJA) {
					$objeto2 = 0;
					$solicitacao->setObjeto2($objeto2);
				}
				
				if ($solicitacaoTipo->getId() == SolicitacaoTipo::ADICIONAR_RESPONSABILIDADE_SECRETARIO) {										
					$objeto1 = $post_data['idPessoa'];						
					$objeto2 = $grupoLogado->getId();
					$solicitacao->setObjeto1($objeto1);
					$solicitacao->setObjeto2($objeto2);					
				}
				
				if ($solicitacaoTipo->getId() == SolicitacaoTipo::REMOVER_RESPONSABILIDADE_SECRETARIO) {
					$idEntidadeASerInativada = $post_data['selecionarEntidadeSecretarioParaInativar']	;							
					$entidadeASerInativada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeASerInativada);						
					$objeto1 = $entidadeASerInativada->getGrupo_id();	
					if($entidadeASerInativada->getGrupo()->getId() !== $grupoLogado->getId()){
						$sessao->mensagemSemAcesso = '<i class = "fa fa-warning text-danger"></i>';
						$sessao->mensagemSemAcesso .= ' Apenas quem cadastrou o secretário tem o poder de remover a responsabilidade!';
						$sessao->corDoTexto = 'text-danger';
						return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
							Constantes::$ACTION => 'semAcesso',						
						));
					}										
					$solicitacao->setObjeto1($objeto1);
					$objeto2 = $entidadeASerInativada->getGrupo()->getId();
					$solicitacao->setObjeto2($objeto2);										
				}

				if ($post_data['numero']) {
					$solicitacao->setNumero($post_data['numero']);
				}
				if ($post_data['numeracao']) {
					$solicitacao->setNumero($post_data['numeracao']);
				}
				if ($post_data['nome']) {
					$solicitacao->setNome($post_data['nome']);
				}
				if ($solicitacaoTipo->getId() === SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::ABRIR_EQUIPE_COM_LIDER_DA_IGREJA ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::SUBIR_LIDER) {
						if ($post_data['nomeEquipe']) {
							$solicitacao->setNome($post_data['nomeEquipe']);
						}
					}
				
				$this->getRepositorio()->getSolicitacaoORM()->persistir($solicitacao);				

				$solicitacaoSituacao = new SolicitacaoSituacao();
				$solicitacaoSituacao->setSolicitacao($solicitacao);
				$solicitacaoSituacao->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::PENDENTE_DE_ACEITACAO));
				$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacao);

				$idEntidadeAtual = $sessao->idEntidadeAtual;
				$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
				if (
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao ||
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::REMOVER_IGREJA ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::TRANSFERIR_IGREJA ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::ADICIONAR_RESPONSABILIDADE_SECRETARIO ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::REMOVER_RESPONSABILIDADE_SECRETARIO ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::UNIR_CASAL ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::SEPARAR ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::TRANSFERIR_ALUNO ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::ABRIR_IGREJA_COM_EQUIPE_COMPLETA ||
					$solicitacaoTipo->getId() === SolicitacaoTipo::TROCAR_RESPONSABILIDADES
				) {
						$solicitacaoSituacao->setDataEHoraDeInativacao();
						$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacao, false);

						$solicitacaoSituacaoAceito = new SolicitacaoSituacao();
						$solicitacaoSituacaoAceito->setSolicitacao($solicitacao);
						$solicitacaoSituacaoAceito->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ACEITO_AGENDADO));
						$setarDataEHora = true;
						if($solicitacaoTipo->getId() === SolicitacaoTipo::UNIR_CASAL){
							$solicitacaoSituacaoAceito->setDataEHoraDeCriacao(date('Y-m-t'));
							$setarDataEHora = false;
						}
						
						$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoAceito, $setarDataEHora);
					}

				self::registrarLog(RegistroAcao::CADASTROU_UMA_SOLICITACAO, $extra = 'Id: '.$solicitacao->getId());
				$this->getRepositorio()->fecharTransacao();

				return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
					Constantes::$PAGINA => Constantes::$PAGINA_SOLICITACOES,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
				$this->direcionaErroDeCadastro($exc->getMessage());
			}
		}
	}

	public function solicitacaoReceberAction() {
		self::validarSeSouIgrejaOuEquipe();
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();

		$form = new SolicitacaoReceberForm('formSolicitacaoReceber');

		$solicitacao = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorId($sessao->idSessao);
		$view = new ViewModel(array(
			'idSolicitacao' => $sessao->idSessao,
			'idSolicitacaoTipo' => $solicitacao->getSolicitacaoTipo()->getId(),
			'grupo' => $grupo,
			'discipulos' => $grupoPaiFilhoFilhos,
			Constantes::$FORM => $form,
			'titulo' => 'Receber Solicitação',
			'naoMostrarQuemEstaLogado' => true,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate('layout/layout-js-cadastrar-solicitacao');
		$view->addChild($layoutJS, 'layoutJSCadastrarSolicitacao');
		return $view;
	}

	public function solicitacaoReceberFinalizarAction() {
		self::validarSeSouIgrejaOuEquipe();
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();

				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$idPessoaAtual = $sessao->idPessoa;
				$pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoaAtual);

				$solicitacao = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorId($post_data['idSolicitacao']);

				/* Criando */
				$solicitacao->setReceptor_id($pessoaLogada->getId());

				$objeto2 = $post_data['objeto2'];
				$explodeObjeto2 = explode('_', $objeto2);
				if ($explodeObjeto2[1]) {
					$objeto2 = $explodeObjeto2[1];
				}
				$solicitacao->setObjeto2($objeto2);

				if ($post_data['numero']) {
					$solicitacao->setNumero($post_data['numero']);
				}
				if ($post_data['nome']) {
					$solicitacao->setNome($post_data['nome']);
				}
				$semMudarDataDeCadastro = false;
				$this->getRepositorio()->getSolicitacaoORM()->persistir($solicitacao, $semMudarDataDeCadastro);

				$solicitacaoSituacaoAtiva = $solicitacao->getSolicitacaoSituacaoAtiva();
				$solicitacaoSituacaoAtiva->setDataEHoraDeInativacao();
				$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoAtiva, false);

				$solicitacaoSituacaoAceito = new SolicitacaoSituacao();
				$solicitacaoSituacaoAceito->setSolicitacao($solicitacao);
				$solicitacaoSituacaoAceito->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ACEITO_AGENDADO));
				$this->getRepositorio()->getSolicitacaoSituacaoORM()->persistir($solicitacaoSituacaoAceito);

				$this->getRepositorio()->fecharTransacao();

				return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
					Constantes::$PAGINA => Constantes::$PAGINA_SOLICITACOES,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
				$this->direcionaErroDeCadastro($exc->getMessage());
			}
		}
	}

	public function liderRevisaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		return new ViewModel(array('id' => $sessao->idSessao));
	}

	public function relatorioFichasRevisaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idRevisao = $sessao->idSessao;
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$fatosRevisao = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisao));

		$relatorio = array();
		$soma = array();
		foreach ($fatosRevisao as $fatoRevisao) {
			if ($fatoRevisao->tipo === 1) {
				if ($fatoRevisao->ativo === 'S') {
					$relatorio[$fatoRevisao->nome_equipe]['ativas'] ++;
					$soma['ativas'] ++;
				}
				$relatorio[$fatoRevisao->nome_equipe]['reservas'] ++;
				$soma['reservas'] ++;
			} else {
				if ($fatoRevisao->tipo === 2) {
					if ($fatoRevisao->ativo === 'S') {
						$relatorio[$fatoRevisao->nome_equipe]['lideres'] ++;
						$soma['lideres'] ++;
					}
				}
			}
		}

		$relatorio['TOTAL']['ativas'] = $soma['ativas'];
		$relatorio['TOTAL']['reservas'] = $soma['reservas'];
		$relatorio['TOTAL']['lideres'] = $soma['lideres'];

		return new ViewModel(array('relatorio' => $relatorio, 'evento' => $eventoRevisao));
	}

	public function selecionarRevisionistaCrachaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$arrayDeEventosRevisoes = array();
		if(
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao || 
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao
		){
			$request = $this->getRequest();
			if ($request->isPost()) {
				$arrayDeRevisoes = Array();
				foreach ($_POST as $key => $value) {
					if (substr($key, 0, 7) == 'revisao') {
						$arrayDeEventosRevisoes[] = $value;
					}
				}
			}
		}else{
			$idRevisao = $sessao->idSessao;
			$arrayDeEventosRevisoes[] = $idRevisao;
		}

		$fatosRevisao = array();
		foreach($arrayDeEventosRevisoes as $idRevisaoParaPesquisar){
			$fatosRevisaoPesquisado = $this->getRepositorio()->getFatoRevisaoORM()->encontrarPorIdEvento(intVal($idRevisaoParaPesquisar));
			foreach($fatosRevisaoPesquisado as $fatoRevisaoPesquisado){
				$fatosRevisao[] = $fatoRevisaoPesquisado; 
			}
		}

		$listas = array();
		$homensRevisionistas = 0;
		$mulheresRevisionistas = 0;
		$homensLideres = 0;
		$mulheresLideres = 0;
		if(count($fatosRevisao) > 0) {
				foreach ($fatosRevisao as $fatoRevisao) {
				if($fatoRevisao->ativo === 'S'){
					$listas[$fatoRevisao->tipo][] = $fatoRevisao;
					if($fatoRevisao->tipo === 1){
						if($fatoRevisao->sexo === 'M'){
							$homensRevisionistas++;
						}else{
							$mulheresRevisionistas++;
						}
					}
					if($fatoRevisao->tipo === 2){
						if($fatoRevisao->sexo === 'M'){
							$homensLideres++;
						}else{
							$mulheresLideres++;
						}
					}
				}
			}
		}

		$formulario = new SelecionarCrachaForm('formulario');
		$view = new ViewModel(array(
			'formulario' => $formulario,
			'listas' => $listas,
			'homensRevisionistas' => $homensRevisionistas,
			'mulheresRevisionistas' => $mulheresRevisionistas,
			'homensLideres' => $homensLideres,
			'mulheresLideres' => $mulheresLideres,
		));

		return $view;
	}

	public function gerarCrachaAction() {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '120');

		$request = $this->getRequest();
		if ($request->isPost()) {
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 12) == 'revisionista') {
					$lista['revisionistas'][] = $value;
				}
				if (substr($key, 0, 5) == 'lider') {
					$lista['lideres'][] = $value;
				}
			}
		}
		$viewModel = new ViewModel(array('lista' => $lista, 'repositorio' => $this->getRepositorio(),));
		$viewModel->setTerminal(true);
		return $viewModel;
	}

	public function gerarCrachaOitoAction() {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '120');

		$request = $this->getRequest();
		if ($request->isPost()) {
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 12) == 'revisionista') {
					$lista['revisionistas'][] = $value;
				}
				if (substr($key, 0, 5) == 'lider') {
					$lista['lideres'][] = $value;
				}
			}
		}
		$viewModel = new ViewModel(array('lista' => $lista, 'repositorio' => $this->getRepositorio(),));
		$viewModel->setTerminal(true);
		return $viewModel;
	}

	public function revisaoAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		self::validarSeSouIgreja();
		$eventoRevisao = null;
		if($idRevisao = $sessao->idSessao){
			$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
			unset($sessao->idSessao);
		}
		$formulario = new RevisaoForm('formulario', $eventoRevisao);

		$dados = array();
		$dados['formulario'] = $formulario;
		$view = new ViewModel($dados);

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate('layout/layout-js-cadastro-revisao');
		$view->addChild($layoutJS, 'layoutJsCadastroRevisao');

		return $view;
	}


	public function revisaoFinalizarAction() {
		CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
		self::validarSeSouIgreja();
		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();

				/* Entidades */
				$evento = new Evento();
				$formulario = new RevisaoForm(Constantes::$FORM, $evento);
				$formulario->setInputFilter($evento->getInputFilterRevisao());
				$formulario->setData($post_data);

				/* validação */
				if ($formulario->isValid()) {
					$sessao = new Container(Constantes::$NOME_APLICACAO);
					$criarNovoEvento = true;
					$validatedData = $formulario->getData();
					$dataDoRevisao = $validatedData[Constantes::$FORM_INPUT_ANO].'-'.$validatedData[Constantes::$FORM_INPUT_MES].'-'.$validatedData[Constantes::$FORM_INPUT_DIA];

					/* Entidades */
					$evento = new Evento();
					$grupoEvento = new GrupoEvento();

					/* ALTERANDO */
					if (!empty($post_data[Constantes::$FORM_ID])) {
						$criarNovoEvento = false;
						$eventoAtual = $this->getRepositorio()->getEventoORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);
						$eventoAtual->setData($dataDoRevisao);
						$observacao = trim($validatedData[Constantes::$FORM_NOME]);
						$eventoAtual->setNome($observacao);
						$this->getRepositorio()->getEventoORM()->persistir($eventoAtual, $alterarDataDeCriacao = false);
					}
					if ($criarNovoEvento) {
						/* Entidade selecionada */
						$idEntidadeAtual = $sessao->idEntidadeAtual;
						$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

						$dataParaCadastro = Funcoes::dataAtual();
						$evento->setData_criacao($dataParaCadastro);
						$evento->setHora_criacao(Funcoes::horaAtual());
						$evento->setHora('19:00:00');
						$evento->setDia($sextaFeira = 6);
						$evento->setEventoTipo($this->getRepositorio()->getEventoTipoORM()->encontrarPorId(EventoTipo::tipoRevisao));
						$evento->setData($dataDoRevisao);
						$observacao = trim($validatedData[Constantes::$FORM_NOME]);
						$evento->setNome($observacao);

						$grupoEvento->setData_criacao(Funcoes::dataAtual());
						$grupoEvento->setHora_criacao(Funcoes::horaAtual());
						$grupoEvento->setGrupo($entidade->getGrupo());
						$grupoEvento->setEvento($evento);

						/* Persistindo */
						$this->getRepositorio()->getEventoORM()->persistir($evento);
						$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);

						$pessoa = new Pessoa();
						$pessoa->setNome('revisao');
						$pessoa->setEmail('revisao'.$evento->getId());
						$pessoa->setSenha($evento->getId());
						$pessoa->setAtualizar_dados('N');
						$pessoa->setEvento_id($evento->getId());
						$this->getRepositorio()->getPessoaORM()->persistir($pessoa);
					}
				} else {
					error_log(print_r($formulario->getMessages(), TRUE));					
				}

				self::registrarLog(RegistroAcao::CADASTROU_UM_REVISAO_DE_VIDAS, $extra = 'Id: '.$evento->getId());
				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
					Constantes::$PAGINA => Constantes::$PAGINA_REVISOES,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
				CircuitoController::direcionandoAoLogin($this);
			}
		}
	}

	public function revisaoExcluirAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		self::validarSeSouIgreja();

		$idGrupoEvento = $sessao->idSessao;
		$grupoEventoRevisao = $this->getRepositorio()->getGrupoEventoORM()->encontrarPorId($idGrupoEvento);
		$grupoEventoRevisao->setDataEHoraDeInativacao();
		$this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEventoRevisao, $mudarDataDeCriacao = false);

		unset($sessao->idSessao);
		return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
			Constantes::$PAGINA => Constantes::$PAGINA_REVISOES,
		));
	}

	public function trocarResponsabilidadesAction(){
		//self::validarSeSouRegiao();

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$grupo = $entidade->getGrupo();

		$dados = array();
		$dados['grupo'] = $grupo;
		return new ViewModel($dados);
	}

	public function trocarResponsabilidadesFinalizarAction(){
		//self::validarSeSouRegiao();
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$grupoLogado = $entidade->getGrupo();
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$body = $request->getContent();
				$json = Json::decode($body);
				$dataParaInativar = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
				$operacaoAdicionar = 'A'; // Adicionando nova responsabilidade
				$operacaoRemover = 'R'; // Remover responsabilidade
				$situacaoPendente = 'P'; // Troca de responsavel pendente
				$trocaResponsavel = new TrocaResponsavel();
				$trocaResponsavel->setDataEHoraDeCriacao();
				$trocaResponsavel->setSituacao($situacaoPendente);							
				$trocaResponsavel->setRegiao_id($grupoLogado->getId());
				$this->getRepositorio()->getTrocaResponsavelORM()->persistir($trocaResponsavel);	

				foreach($json->listaDeTimesEResponsabilidades as $item){			
					$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($item->idTime);	
					$pessoas = array();
					foreach($grupo->getResponsabilidadesAtivas() as  $grupoResponsavel){
						$pessoas[] = $grupoResponsavel->getPessoa();
					}

					foreach($item->responsabilidades as $responsabilidade){
						if($responsabilidade->id !== 'removido'){							

							// Se o grupo envolvido na troca de responsabilidade estiver passando por solicitacao, não pode ser trocado
							$solicitacoesParaVerificarObjeto1 = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto1($responsabilidade->id);							
							$solicitacoesParaVerificarObjeto2 = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto2($responsabilidade->id);				
						
									
							/* validar se ja tem solicitacao */
							if($solicitacoesParaVerificarObjeto1 || $solicitacoesParaVerificarObjeto2){					
								$temSolicitacoesPendentes = false;
								foreach($solicitacoesParaVerificarObjeto1 as $solicitacaoParaVerificar){					
									if($solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::CONCLUIDO
										&& $solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::RECUSAO){							
										$temSolicitacoesPendentes = true;
									}
								}	
								foreach($solicitacoesParaVerificarObjeto2 as $solicitacaoParaVerificar){					
									if($solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::CONCLUIDO
										&& $solicitacaoParaVerificar->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::RECUSAO){							
										$temSolicitacoesPendentes = true;
									}
								}	
								
								$resolucaoResponsabilidadesPendentes = $this->getRepositorio()->getResolucaoResponsabilidadeORM()->encontrarResolucaoResponsabilidadePorGrupo($responsabilidade->id);
								foreach($resolucaoResponsabilidadesPendentes as $resolucaoResponsabilidade){																
									if($resolucaoResponsabilidade->getTrocaResponsavel()->verificarSeEstaAtivo() 
									&& $resolucaoResponsabilidade->getTrocaResponsavel()->getSituacao() == $situacaoPendente){										
										$temSolicitacoesPendentes = true;
									}
								}
								
								if($temSolicitacoesPendentes){
									$dados['solicitacoesPendentes'] = true;									
								}
							}									
													
							$grupoParaNovaResponsabilidades = $this->getRepositorio()->getGrupoORM()->encontrarPorId($responsabilidade->id);	

							$criarNovo = true;
							foreach($pessoas as $pessoaParaVerificar){
								foreach($pessoaParaVerificar->getResponsabilidadesAtivas() as $grupoResponsavelParaVerificar){
									if($grupoResponsavelParaVerificar->getGrupo()->getId() === $grupoParaNovaResponsabilidades->getId()){
										$criarNovo = false;
									}
								}
							}
							if($criarNovo){			
								$pessoasResponsaveisAnteriormente = array();
								foreach($grupoParaNovaResponsabilidades->getResponsabilidadesAtivas() as  $grupoResponsavel){
									$pessoasResponsaveisAnteriormente[] = $grupoResponsavel->getPessoa();
								}	
								
								/* Removendo responsabilidade dos antigos lideres do grupo*/
								foreach($pessoasResponsaveisAnteriormente as $responsavelAnterior){
									$resolucaoRemoverResponsabilidade = new ResolucaoResponsabilidade();
									$resolucaoRemoverResponsabilidade->setTrocaResponsavel($trocaResponsavel);
									$resolucaoRemoverResponsabilidade->setPessoa_id($responsavelAnterior->getId());
									$resolucaoRemoverResponsabilidade->setGrupo_id($responsabilidade->id);
									$resolucaoRemoverResponsabilidade->setOperacao($operacaoRemover);	
									$resolucaoRemoverResponsabilidade->setDataEHoraDeCriacao();	
									$this->getRepositorio()->getResolucaoResponsabilidadeORM()->persistir($resolucaoRemoverResponsabilidade);																	
								}

								/* Adicionando responsabilidade para os novos lideres do grupo*/
								foreach($pessoas as $pessoaEnvolvidaNaTroca){
									$resolucaoNovaResponsabilidade = new ResolucaoResponsabilidade();
									$resolucaoNovaResponsabilidade->setTrocaResponsavel($trocaResponsavel);
									$resolucaoNovaResponsabilidade->setPessoa_id($pessoaEnvolvidaNaTroca->getId());
									$resolucaoNovaResponsabilidade->setGrupo_id($responsabilidade->id);
									$resolucaoNovaResponsabilidade->setOperacao($operacaoAdicionar);	
									$resolucaoNovaResponsabilidade->setDataEHoraDeCriacao();	
									$this->getRepositorio()->getResolucaoResponsabilidadeORM()->persistir($resolucaoNovaResponsabilidade);																	
								}																							
							}
						}					
					}
				}
				if(!$dados['solicitacoesPendentes']){
					$this->getRepositorio()->fecharTransacao();				
				}				
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();				
				echo $exc->getMessage();
				$dados['message'] = $exc->getMessage();
			}
			$response->setContent(Json::encode($dados));
			return $response;
		}
	}
}
