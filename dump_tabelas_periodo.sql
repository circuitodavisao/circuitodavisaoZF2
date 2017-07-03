--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- Name: enumcl; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE enumcl AS ENUM (
    'C',
    'L'
);


ALTER TYPE enumcl OWNER TO postgres;

--
-- Name: enummf; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE enummf AS ENUM (
    'M',
    'F'
);


ALTER TYPE enummf OWNER TO postgres;

--
-- Name: enumsn; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE enumsn AS ENUM (
    'S',
    'N'
);


ALTER TYPE enumsn OWNER TO postgres;

--
-- Name: status; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE status AS ENUM (
    'A',
    'I'
);


ALTER TYPE status OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: aluno_situacao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE aluno_situacao (
    id integer NOT NULL,
    situacao_id integer NOT NULL,
    turma_aluno_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE aluno_situacao OWNER TO postgres;

--
-- Name: TABLE aluno_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE aluno_situacao IS 'Associação entre o aluno e sua situacao';


--
-- Name: COLUMN aluno_situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.id IS 'Identificação da associação do aluno com a situação';


--
-- Name: COLUMN aluno_situacao.situacao_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.situacao_id IS 'Identificação da situação';


--
-- Name: COLUMN aluno_situacao.turma_aluno_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.turma_aluno_id IS 'Identificação (matricula) da turma aluno';


--
-- Name: COLUMN aluno_situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.data_criacao IS 'Data de criação da associação da situação com o aluno';


--
-- Name: COLUMN aluno_situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.hora_criacao IS 'Hora de criação da associação da situação com o aluno';


--
-- Name: COLUMN aluno_situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.data_inativacao IS 'Data de inativação da associação da situação com aluno';


--
-- Name: COLUMN aluno_situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.hora_inativacao IS 'Hora da inativação da associação situação com aluno';


--
-- Name: aluno_situacao_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE aluno_situacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE aluno_situacao_id_seq OWNER TO postgres;

--
-- Name: aluno_situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE aluno_situacao_id_seq OWNED BY aluno_situacao.id;


--
-- Name: dimensao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE dimensao (
    id integer NOT NULL,
    fato_ciclo_id integer NOT NULL,
    dimensao_tipo_id integer NOT NULL,
    visitante integer DEFAULT 0 NOT NULL,
    consolidacao integer DEFAULT 0 NOT NULL,
    membro integer DEFAULT 0 NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    lider integer DEFAULT 0 NOT NULL
);


ALTER TABLE dimensao OWNER TO postgres;

--
-- Name: TABLE dimensao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE dimensao IS 'Dimensão dos dados';


--
-- Name: COLUMN dimensao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.id IS 'Identificação';


--
-- Name: COLUMN dimensao.fato_ciclo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.fato_ciclo_id IS 'Identificação do fato ciclo';


--
-- Name: COLUMN dimensao.dimensao_tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.dimensao_tipo_id IS 'Identificação do tipo dos dados';


--
-- Name: COLUMN dimensao.visitante; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.visitante IS 'Número de visitantes lançados';


--
-- Name: COLUMN dimensao.consolidacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.consolidacao IS 'Número de consolidações lançadas';


--
-- Name: COLUMN dimensao.membro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.membro IS 'Número de membros lançados';


--
-- Name: COLUMN dimensao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.data_criacao IS 'Data de criação da dimensão';


--
-- Name: COLUMN dimensao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.hora_criacao IS 'Hora de criação da dimensão';


--
-- Name: COLUMN dimensao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.data_inativacao IS 'Data de inativação da dimensão';


--
-- Name: COLUMN dimensao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.hora_inativacao IS 'Hora da inativação da dimensão';


--
-- Name: COLUMN dimensao.lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.lider IS 'Número de líderes lançados';


--
-- Name: dimensao_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE dimensao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE dimensao_id_seq OWNER TO postgres;

--
-- Name: dimensao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dimensao_id_seq OWNED BY dimensao.id;


--
-- Name: dimensao_tipo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE dimensao_tipo (
    id integer NOT NULL,
    nome character varying(20) NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE dimensao_tipo OWNER TO postgres;

--
-- Name: TABLE dimensao_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE dimensao_tipo IS 'Tabela com os tipos de dimensões';


--
-- Name: COLUMN dimensao_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.id IS 'Identificação';


--
-- Name: COLUMN dimensao_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.nome IS 'Nome tipo da dimensão';


--
-- Name: COLUMN dimensao_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.data_criacao IS 'Data de criação do tipo da dimensão';


--
-- Name: COLUMN dimensao_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.hora_criacao IS 'Hora de criação do tipo da dimensão';


--
-- Name: COLUMN dimensao_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.data_inativacao IS 'Data de inativação do tipo da dimensão';


--
-- Name: COLUMN dimensao_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.hora_inativacao IS 'Hora de inativação do tipo da dimensão';


--
-- Name: dimensao_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE dimensao_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE dimensao_tipo_id_seq OWNER TO postgres;

--
-- Name: dimensao_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dimensao_tipo_id_seq OWNED BY dimensao_tipo.id;


--
-- Name: entidade; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE entidade (
    id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    nome character varying(45),
    numero integer,
    data_inativacao date,
    hora_inativacao time without time zone,
    tipo_id integer NOT NULL,
    grupo_id bigint NOT NULL
);


ALTER TABLE entidade OWNER TO postgres;

--
-- Name: TABLE entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entidade IS 'Tabela que armazena os dados das diversas entidades com número, nomes, telefone e endereços';


--
-- Name: COLUMN entidade.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.id IS 'Identificação da entidade';


--
-- Name: COLUMN entidade.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.data_criacao IS 'Data de criação da entidade';


--
-- Name: COLUMN entidade.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.hora_criacao IS 'Hora de criação da entidade';


--
-- Name: COLUMN entidade.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.nome IS 'Nome para as entidades: igreja, equipes';


--
-- Name: COLUMN entidade.numero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.numero IS 'Número para as entidades: região, coordenação e subs';


--
-- Name: COLUMN entidade.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.data_inativacao IS 'Data de inativação da entidade';


--
-- Name: COLUMN entidade.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.hora_inativacao IS 'Hora da inativação da entidade';


--
-- Name: COLUMN entidade.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.tipo_id IS 'Indetificação do tipo de entidade';


--
-- Name: COLUMN entidade.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.grupo_id IS 'Identificação do grupo';


--
-- Name: entidade_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE entidade_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE entidade_id_seq OWNER TO postgres;

--
-- Name: entidade_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entidade_id_seq OWNED BY entidade.id;


--
-- Name: entidade_tipo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE entidade_tipo (
    id integer NOT NULL,
    nome character varying(45) NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE entidade_tipo OWNER TO postgres;

--
-- Name: TABLE entidade_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entidade_tipo IS 'Tabela com os tipo de entidades';


--
-- Name: COLUMN entidade_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.id IS 'Identificação do tipo de entidade';


--
-- Name: COLUMN entidade_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.nome IS 'Nome da entidade';


--
-- Name: COLUMN entidade_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.data_criacao IS 'Data de criação do tipo da entidade';


--
-- Name: COLUMN entidade_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.hora_criacao IS 'Hora de criação do tipo da entidade';


--
-- Name: COLUMN entidade_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.data_inativacao IS 'Data de inativação do tipo da entidade';


--
-- Name: COLUMN entidade_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.hora_inativacao IS 'Hora inativação do tipo da entidade';


--
-- Name: entidade_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE entidade_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE entidade_tipo_id_seq OWNER TO postgres;

--
-- Name: entidade_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entidade_tipo_id_seq OWNED BY entidade_tipo.id;


--
-- Name: evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE evento (
    id integer NOT NULL,
    dia integer NOT NULL,
    hora time without time zone NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    tipo_id integer NOT NULL,
    nome character varying(30),
    data date
);


ALTER TABLE evento OWNER TO postgres;

--
-- Name: TABLE evento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento IS 'Tabela que armazena dados dos eventos em geral';


--
-- Name: COLUMN evento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.id IS 'Identificação do evento';


--
-- Name: COLUMN evento.dia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.dia IS 'Dia da semana que ocorre o evento.
1 - domingo
7 - sabado';


--
-- Name: COLUMN evento.hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora IS 'Hora que ocorre o evento';


--
-- Name: COLUMN evento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data_criacao IS 'Data de criação do evento';


--
-- Name: COLUMN evento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora_criacao IS 'Hora de criação do evento';


--
-- Name: COLUMN evento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data_inativacao IS 'Data de inativação do evento';


--
-- Name: COLUMN evento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora_inativacao IS 'Hora de inativação do evento';


--
-- Name: COLUMN evento.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.tipo_id IS 'Identificação do tipo do evento';


--
-- Name: COLUMN evento.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.nome IS 'Nome do evento';


--
-- Name: COLUMN evento.data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data IS 'Data do evento';


--
-- Name: evento_celula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE evento_celula (
    id integer NOT NULL,
    nome_hospedeiro character varying(80) NOT NULL,
    telefone_hospedeiro bigint NOT NULL,
    logradouro character varying(80) NOT NULL,
    complemento character varying(80),
    evento_id integer NOT NULL,
    bairro character varying(30),
    cidade character varying(30) NOT NULL,
    cep bigint NOT NULL,
    uf character varying(30) NOT NULL
);


ALTER TABLE evento_celula OWNER TO postgres;

--
-- Name: TABLE evento_celula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_celula IS 'Tabela para amarzenas dados do evento tipo célula';


--
-- Name: COLUMN evento_celula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.id IS 'Identificação do dados do evento tipo célula';


--
-- Name: COLUMN evento_celula.nome_hospedeiro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.nome_hospedeiro IS 'Nome do hospedeiro da célula';


--
-- Name: COLUMN evento_celula.telefone_hospedeiro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.telefone_hospedeiro IS 'Telefone do hospedeiro com 9 digitos com DDD';


--
-- Name: COLUMN evento_celula.logradouro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.logradouro IS 'Logradouro da célula';


--
-- Name: COLUMN evento_celula.complemento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.complemento IS 'Complemento do endereço da célula';


--
-- Name: COLUMN evento_celula.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.evento_id IS 'Identificação do evento';


--
-- Name: COLUMN evento_celula.bairro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.bairro IS 'Bairro do local da célula';


--
-- Name: COLUMN evento_celula.cidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.cidade IS 'Cidade do local da célula';


--
-- Name: COLUMN evento_celula.cep; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.cep IS 'CEP do local da célula';


--
-- Name: COLUMN evento_celula.uf; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.uf IS 'Unidade Federativa da célula';


--
-- Name: evento_celula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE evento_celula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE evento_celula_id_seq OWNER TO postgres;

--
-- Name: evento_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_celula_id_seq OWNED BY evento_celula.id;


--
-- Name: evento_frequencia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE evento_frequencia (
    id integer NOT NULL,
    evento_id bigint NOT NULL,
    pessoa_id bigint NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    frequencia enumsn DEFAULT 'N'::enumsn NOT NULL,
    dia date
);


ALTER TABLE evento_frequencia OWNER TO postgres;

--
-- Name: TABLE evento_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_frequencia IS 'Tabela associativa da pessoa no evento';


--
-- Name: COLUMN evento_frequencia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.id IS 'Identificação da frequência no evento';


--
-- Name: COLUMN evento_frequencia.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.evento_id IS 'Identificação do evento';


--
-- Name: COLUMN evento_frequencia.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.pessoa_id IS 'Identificação da pessoa';


--
-- Name: COLUMN evento_frequencia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN evento_frequencia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.hora_criacao IS 'Hora de criação da associativa';


--
-- Name: COLUMN evento_frequencia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.data_inativacao IS 'Data da inativação da associativa';


--
-- Name: COLUMN evento_frequencia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: COLUMN evento_frequencia.frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.frequencia IS 'Frequência da pessoa no evento';


--
-- Name: evento_frequencia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE evento_frequencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE evento_frequencia_id_seq OWNER TO postgres;

--
-- Name: evento_frequencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_frequencia_id_seq OWNED BY evento_frequencia.id;


--
-- Name: evento_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE evento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE evento_id_seq OWNER TO postgres;

--
-- Name: evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_id_seq OWNED BY evento.id;


--
-- Name: evento_tipo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE evento_tipo (
    id integer NOT NULL,
    nome character varying(45) NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE evento_tipo OWNER TO postgres;

--
-- Name: TABLE evento_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_tipo IS 'Tipo de evento';


--
-- Name: COLUMN evento_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.id IS 'Identificação dos tipos de evento';


--
-- Name: COLUMN evento_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.nome IS 'Nome do tipo de evento';


--
-- Name: COLUMN evento_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.data_criacao IS 'Data de criação do tipo do evento';


--
-- Name: COLUMN evento_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.hora_criacao IS 'Hora de criação do tipo de evento';


--
-- Name: COLUMN evento_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.data_inativacao IS 'Data de inativação do tipo de evento';


--
-- Name: COLUMN evento_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.hora_inativacao IS 'Hora de inativação do tipo de evento';


--
-- Name: evento_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE evento_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE evento_tipo_id_seq OWNER TO postgres;

--
-- Name: evento_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_tipo_id_seq OWNED BY evento_tipo.id;


--
-- Name: fato_celula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fato_celula (
    id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    realizada smallint DEFAULT 0 NOT NULL,
    fato_ciclo_id integer NOT NULL,
    evento_celula_id integer NOT NULL
);


ALTER TABLE fato_celula OWNER TO postgres;

--
-- Name: TABLE fato_celula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_celula IS 'Tabela com a quantidade de celulas atuais e realizadas no ciclo';


--
-- Name: COLUMN fato_celula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.id IS 'Identificação do relatório';


--
-- Name: COLUMN fato_celula.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.data_criacao IS 'Data de criação do relatório';


--
-- Name: COLUMN fato_celula.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.hora_criacao IS 'Hora da criação do relatório';


--
-- Name: COLUMN fato_celula.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.data_inativacao IS 'Data da inativação do relatório';


--
-- Name: COLUMN fato_celula.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.hora_inativacao IS 'Hora da inativação do relatório';


--
-- Name: COLUMN fato_celula.realizada; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.realizada IS 'Realizada 1 ou 0';


--
-- Name: COLUMN fato_celula.fato_ciclo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.fato_ciclo_id IS 'Identificação do fato ciclo';


--
-- Name: COLUMN fato_celula.evento_celula_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.evento_celula_id IS 'Identificação do evento célula';


--
-- Name: fato_celula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fato_celula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fato_celula_id_seq OWNER TO postgres;

--
-- Name: fato_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_celula_id_seq OWNED BY fato_celula.id;


--
-- Name: fato_ciclo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fato_ciclo (
    id integer NOT NULL,
    numero_identificador character varying(64) NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL
);


ALTER TABLE fato_ciclo OWNER TO postgres;

--
-- Name: TABLE fato_ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_ciclo IS 'Tabela com os dados consolidados do lançamento de dados das igrejas, equipes e subs';


--
-- Name: COLUMN fato_ciclo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.id IS 'Identificação';


--
-- Name: COLUMN fato_ciclo.numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.numero_identificador IS 'Número para saber de qual igreja pertenço
8 espaços para cada posição';


--
-- Name: COLUMN fato_ciclo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.data_criacao IS 'Data de criação do fato';


--
-- Name: COLUMN fato_ciclo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.data_inativacao IS 'Data de inativação';


--
-- Name: COLUMN fato_ciclo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.hora_inativacao IS 'Hora de inativação do fato';


--
-- Name: COLUMN fato_ciclo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.hora_criacao IS 'Hora de criação do fato';


--
-- Name: fato_ciclo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fato_ciclo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fato_ciclo_id_seq OWNER TO postgres;

--
-- Name: fato_ciclo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_ciclo_id_seq OWNED BY fato_ciclo.id;


--
-- Name: fato_lider; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fato_lider (
    id integer NOT NULL,
    numero_identificador character varying(64) NOT NULL,
    lideres smallint DEFAULT 0 NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE fato_lider OWNER TO postgres;

--
-- Name: TABLE fato_lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_lider IS 'Tabela com a quantidade de lideres por número identificador';


--
-- Name: COLUMN fato_lider.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.id IS 'Identificação do fato lider';


--
-- Name: COLUMN fato_lider.numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.numero_identificador IS 'Número identificador do grupo';


--
-- Name: COLUMN fato_lider.lideres; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.lideres IS 'Quantidade de lideres';


--
-- Name: COLUMN fato_lider.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.data_criacao IS 'Data de criação do relatório';


--
-- Name: COLUMN fato_lider.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.hora_criacao IS 'Hora de criação do relatório';


--
-- Name: COLUMN fato_lider.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.data_inativacao IS 'Data de inativação do relatório';


--
-- Name: COLUMN fato_lider.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.hora_inativacao IS 'Hora de inativação do relatório';


--
-- Name: fato_lider_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fato_lider_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fato_lider_id_seq OWNER TO postgres;

--
-- Name: fato_lider_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_lider_id_seq OWNED BY fato_lider.id;


--
-- Name: grupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo (
    id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE grupo OWNER TO postgres;

--
-- Name: TABLE grupo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo IS 'Tabela com os grupos ';


--
-- Name: COLUMN grupo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.id IS 'Identificação do grupo';


--
-- Name: COLUMN grupo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.data_criacao IS 'Data de criação do grupo';


--
-- Name: COLUMN grupo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.hora_criacao IS 'Hora de criação do grupo';


--
-- Name: COLUMN grupo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.data_inativacao IS 'Data de inativação do grupo';


--
-- Name: COLUMN grupo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.hora_inativacao IS 'Hora de inativação do grupo';


--
-- Name: grupo_aluno; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_aluno (
    id integer NOT NULL,
    grupo_id integer NOT NULL,
    turma_aluno_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE grupo_aluno OWNER TO postgres;

--
-- Name: TABLE grupo_aluno; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_aluno IS 'Tabela associativa do aluno com grupo';


--
-- Name: COLUMN grupo_aluno.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.id IS 'Identificação da associativa aluno com o grupo';


--
-- Name: COLUMN grupo_aluno.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.grupo_id IS 'Identificação do grupo';


--
-- Name: COLUMN grupo_aluno.turma_aluno_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.turma_aluno_id IS 'Identificação (matricula) da turma aluno';


--
-- Name: COLUMN grupo_aluno.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN grupo_aluno.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.hora_criacao IS 'Hora de criação da associação';


--
-- Name: COLUMN grupo_aluno.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.data_inativacao IS 'Data da inativação da associação';


--
-- Name: COLUMN grupo_aluno.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: grupo_aluno_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_aluno_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_aluno_id_seq OWNER TO postgres;

--
-- Name: grupo_aluno_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_aluno_id_seq OWNED BY grupo_aluno.id;


--
-- Name: grupo_atendimento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_atendimento (
    id integer NOT NULL,
    grupo_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE grupo_atendimento OWNER TO postgres;

--
-- Name: TABLE grupo_atendimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_atendimento IS 'Tabela associativa do grupo com atendimento';


--
-- Name: COLUMN grupo_atendimento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.id IS 'Identificação da associação';


--
-- Name: COLUMN grupo_atendimento.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.grupo_id IS 'Identificação do grupo';


--
-- Name: COLUMN grupo_atendimento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN grupo_atendimento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.hora_criacao IS 'Hora de criação da associação';


--
-- Name: COLUMN grupo_atendimento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.data_inativacao IS 'Data de inativação da associação';


--
-- Name: COLUMN grupo_atendimento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: grupo_atendimento_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_atendimento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_atendimento_id_seq OWNER TO postgres;

--
-- Name: grupo_atendimento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_atendimento_id_seq OWNED BY grupo_atendimento.id;


--
-- Name: grupo_cv; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_cv (
    id integer NOT NULL,
    grupo_id bigint NOT NULL,
    lider1 integer NOT NULL,
    lider2 integer,
    numero_identificador character varying(64)
);


ALTER TABLE grupo_cv OWNER TO postgres;

--
-- Name: TABLE grupo_cv; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_cv IS 'Tabela associativa com o sistema antigo';


--
-- Name: grupo_cv_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_cv_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_cv_id_seq OWNER TO postgres;

--
-- Name: grupo_cv_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_cv_id_seq OWNED BY grupo_cv.id;


--
-- Name: grupo_evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_evento (
    id integer NOT NULL,
    grupo_id bigint NOT NULL,
    evento_id bigint NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE grupo_evento OWNER TO postgres;

--
-- Name: TABLE grupo_evento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_evento IS 'Tabela de eventos que o grupo participa';


--
-- Name: COLUMN grupo_evento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.id IS 'Identificação dos eventos do grupo';


--
-- Name: COLUMN grupo_evento.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.grupo_id IS 'Identificação do grupo';


--
-- Name: COLUMN grupo_evento.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.evento_id IS 'Identificação do evento';


--
-- Name: COLUMN grupo_evento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN grupo_evento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.hora_criacao IS 'Hora de criação da associação';


--
-- Name: COLUMN grupo_evento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.data_inativacao IS 'Data de inativação da associação';


--
-- Name: COLUMN grupo_evento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: grupo_evento_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_evento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_evento_id_seq OWNER TO postgres;

--
-- Name: grupo_evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_evento_id_seq OWNED BY grupo_evento.id;


--
-- Name: grupo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_id_seq OWNER TO postgres;

--
-- Name: grupo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_id_seq OWNED BY grupo.id;


--
-- Name: grupo_pai_filho; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_pai_filho (
    id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    pai_id bigint NOT NULL,
    filho_id bigint NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE grupo_pai_filho OWNER TO postgres;

--
-- Name: TABLE grupo_pai_filho; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pai_filho IS 'Tabela associativa entre grupos';


--
-- Name: COLUMN grupo_pai_filho.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.id IS 'Identificação da associação entre grupos';


--
-- Name: COLUMN grupo_pai_filho.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN grupo_pai_filho.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.hora_criacao IS 'Hora da criação da associação';


--
-- Name: COLUMN grupo_pai_filho.pai_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.pai_id IS 'Identificação do grupo pai';


--
-- Name: COLUMN grupo_pai_filho.filho_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.filho_id IS 'Identificação do grupo filho';


--
-- Name: COLUMN grupo_pai_filho.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.data_inativacao IS 'Data de inativação da associação';


--
-- Name: COLUMN grupo_pai_filho.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: grupo_pai_filho_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_pai_filho_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_pai_filho_id_seq OWNER TO postgres;

--
-- Name: grupo_pai_filho_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pai_filho_id_seq OWNED BY grupo_pai_filho.id;


--
-- Name: grupo_pessoa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_pessoa (
    id integer NOT NULL,
    grupo_id bigint NOT NULL,
    pessoa_id bigint NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    tipo_id integer NOT NULL,
    transferido enumsn,
    nucleo_perfeito enumcl
);


ALTER TABLE grupo_pessoa OWNER TO postgres;

--
-- Name: TABLE grupo_pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pessoa IS 'Tabela associativa do grupo com as pessoas volateis';


--
-- Name: COLUMN grupo_pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.id IS 'Identificação da associação grupo pessoa volatél';


--
-- Name: COLUMN grupo_pessoa.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.grupo_id IS 'Identificação do grupo';


--
-- Name: COLUMN grupo_pessoa.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.pessoa_id IS 'Identificação da pessoa';


--
-- Name: COLUMN grupo_pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN grupo_pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.hora_criacao IS 'Hora de criação da associativa';


--
-- Name: COLUMN grupo_pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.data_inativacao IS 'Data de inativacao da associação';


--
-- Name: COLUMN grupo_pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: COLUMN grupo_pessoa.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.tipo_id IS 'Identificação do tipo da pessoa volatél';


--
-- Name: COLUMN grupo_pessoa.transferido; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.transferido IS 'Identificação para saber se foi transferido ou não';


--
-- Name: COLUMN grupo_pessoa.nucleo_perfeito; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.nucleo_perfeito IS 'Enumeração para co líder ou líder em treinamento';


--
-- Name: grupo_pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_pessoa_id_seq OWNER TO postgres;

--
-- Name: grupo_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pessoa_id_seq OWNED BY grupo_pessoa.id;


--
-- Name: grupo_pessoa_tipo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_pessoa_tipo (
    id integer NOT NULL,
    nome character varying NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao date
);


ALTER TABLE grupo_pessoa_tipo OWNER TO postgres;

--
-- Name: TABLE grupo_pessoa_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pessoa_tipo IS 'Tabela com o tipo de pessoa volatél';


--
-- Name: COLUMN grupo_pessoa_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.id IS 'Identificação do tipo de pessoa volatél';


--
-- Name: COLUMN grupo_pessoa_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.nome IS 'Nome do tipo de pessoa volatél';


--
-- Name: COLUMN grupo_pessoa_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.data_criacao IS 'Data de criação do tipo da pessoa';


--
-- Name: COLUMN grupo_pessoa_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.hora_criacao IS 'Hora de criação do tipo da pessoa';


--
-- Name: COLUMN grupo_pessoa_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.data_inativacao IS 'Data de inativação do tipo da pessoa';


--
-- Name: COLUMN grupo_pessoa_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.hora_inativacao IS 'Hora da inativação do tipo da pessoa';


--
-- Name: grupo_pessoa_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_pessoa_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_pessoa_tipo_id_seq OWNER TO postgres;

--
-- Name: grupo_pessoa_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pessoa_tipo_id_seq OWNED BY grupo_pessoa_tipo.id;


--
-- Name: grupo_responsavel; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo_responsavel (
    id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    pessoa_id bigint NOT NULL,
    grupo_id bigint NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE grupo_responsavel OWNER TO postgres;

--
-- Name: TABLE grupo_responsavel; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_responsavel IS 'Tabela associativa do grupo com as pessoas';


--
-- Name: COLUMN grupo_responsavel.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.id IS 'Identificação do responsavel pelo grupo';


--
-- Name: COLUMN grupo_responsavel.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.data_criacao IS 'Data de criação da responsabilidade';


--
-- Name: COLUMN grupo_responsavel.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.hora_criacao IS 'Hora que foi criada a responsabilidade';


--
-- Name: COLUMN grupo_responsavel.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.pessoa_id IS 'Identificação da pessoa';


--
-- Name: COLUMN grupo_responsavel.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.grupo_id IS 'Identificação do grupo';


--
-- Name: COLUMN grupo_responsavel.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.data_inativacao IS 'Data de inativação da responsabilidade';


--
-- Name: COLUMN grupo_responsavel.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.hora_inativacao IS 'Hora da inativação da responsabilidade';


--
-- Name: grupo_responsavel_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_responsavel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_responsavel_id_seq OWNER TO postgres;

--
-- Name: grupo_responsavel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_responsavel_id_seq OWNED BY grupo_responsavel.id;


--
-- Name: hierarquia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE hierarquia (
    id integer NOT NULL,
    nome character varying(30) NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE hierarquia OWNER TO postgres;

--
-- Name: TABLE hierarquia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE hierarquia IS 'Tabela com as hierarquia';


--
-- Name: COLUMN hierarquia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.id IS 'Identificador das hierarquia';


--
-- Name: COLUMN hierarquia.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.nome IS 'Nome da hierarquia';


--
-- Name: COLUMN hierarquia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.data_criacao IS 'Data de criação da hierarquia';


--
-- Name: COLUMN hierarquia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.hora_criacao IS 'Hora de criação da hierarquia';


--
-- Name: COLUMN hierarquia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.data_inativacao IS 'Data de inativação da hierarquia';


--
-- Name: COLUMN hierarquia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.hora_inativacao IS 'Hora da inativação';


--
-- Name: hierarquia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE hierarquia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE hierarquia_id_seq OWNER TO postgres;

--
-- Name: hierarquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE hierarquia_id_seq OWNED BY hierarquia.id;


--
-- Name: pessoa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE pessoa (
    id integer NOT NULL,
    nome character varying(150) NOT NULL,
    email character varying(80),
    senha character varying(40),
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    data_inativacao date,
    documento bigint,
    data_nascimento date,
    token character varying(120),
    token_data date,
    token_hora time without time zone,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    hora_inativacao time without time zone,
    telefone bigint,
    foto character varying(40),
    data_revisao date,
    sexo enummf,
    atualizar_dados enumsn DEFAULT 'N'::enumsn NOT NULL
);


ALTER TABLE pessoa OWNER TO postgres;

--
-- Name: TABLE pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa IS 'Tabela pessoa com dados pessoais';


--
-- Name: COLUMN pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.id IS 'Identificação da pessoa';


--
-- Name: COLUMN pessoa.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.nome IS 'Nome Completo da pessoa';


--
-- Name: COLUMN pessoa.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.email IS 'Email de acesso ';


--
-- Name: COLUMN pessoa.senha; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.senha IS 'Senha de acesso em MD5';


--
-- Name: COLUMN pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_criacao IS 'Data de criação da pessoa';


--
-- Name: COLUMN pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_inativacao IS 'Data de inativação da pessoa';


--
-- Name: COLUMN pessoa.documento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.documento IS 'Documento da pessoa, pode ser CPF ou caso estrangeiro um documento aberto';


--
-- Name: COLUMN pessoa.data_nascimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_nascimento IS 'Data de nascimento da pessoa';


--
-- Name: COLUMN pessoa.token; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token IS 'Token de recuperacao de senha';


--
-- Name: COLUMN pessoa.token_data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token_data IS 'Data para validação do token';


--
-- Name: COLUMN pessoa.token_hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token_hora IS 'Hora para validação do token';


--
-- Name: COLUMN pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.hora_criacao IS 'Hora de criação da pessoa';


--
-- Name: COLUMN pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.hora_inativacao IS 'Hora de inativação da pessoa';


--
-- Name: COLUMN pessoa.telefone; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.telefone IS 'Telefone com 9 digitos e DDD';


--
-- Name: COLUMN pessoa.foto; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.foto IS 'Nome do arquivo com a foto da pessoa';


--
-- Name: COLUMN pessoa.data_revisao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_revisao IS 'Data que a pessoa foi ao revisão de vidas';


--
-- Name: COLUMN pessoa.sexo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.sexo IS 'Sexo da pessoa';


--
-- Name: COLUMN pessoa.atualizar_dados; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.atualizar_dados IS 'Variável para verificar se precisa de atualização de dados';


--
-- Name: pessoa_hierarquia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE pessoa_hierarquia (
    id integer NOT NULL,
    pessoa_id integer NOT NULL,
    hierarquia_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE pessoa_hierarquia OWNER TO postgres;

--
-- Name: TABLE pessoa_hierarquia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa_hierarquia IS 'Tabela associativa da pessoa com a hierarquia';


--
-- Name: COLUMN pessoa_hierarquia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.id IS 'Identificação da associação da pessoa com a hierarquia';


--
-- Name: COLUMN pessoa_hierarquia.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.pessoa_id IS 'Identificação da pessoa';


--
-- Name: COLUMN pessoa_hierarquia.hierarquia_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hierarquia_id IS 'Identificação da hierarquia';


--
-- Name: COLUMN pessoa_hierarquia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.data_criacao IS 'Data de criação da associação pessoa com a hierarquia';


--
-- Name: COLUMN pessoa_hierarquia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hora_criacao IS 'Hora de criação da associação pessoa com a hierarquia';


--
-- Name: COLUMN pessoa_hierarquia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.data_inativacao IS 'Data da inativação da associação pessoa com a hierarquia';


--
-- Name: COLUMN pessoa_hierarquia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hora_inativacao IS 'Hora da inativação da associativa pessoa com a hierarquia';


--
-- Name: pessoa_hierarquia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_hierarquia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_hierarquia_id_seq OWNER TO postgres;

--
-- Name: pessoa_hierarquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_hierarquia_id_seq OWNED BY pessoa_hierarquia.id;


--
-- Name: pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_id_seq OWNER TO postgres;

--
-- Name: pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_id_seq OWNED BY pessoa.id;


--
-- Name: situacao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE situacao (
    id integer NOT NULL,
    nome character varying(30),
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE situacao OWNER TO postgres;

--
-- Name: TABLE situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE situacao IS 'Tabela com as situações do aluno';


--
-- Name: COLUMN situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.id IS 'Identificação da situação';


--
-- Name: COLUMN situacao.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.nome IS 'Nome da situação do aluno';


--
-- Name: COLUMN situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.data_criacao IS 'Data de criação da situação';


--
-- Name: COLUMN situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.hora_criacao IS 'Hora da criação da situação';


--
-- Name: COLUMN situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.data_inativacao IS 'Data da inativação da situação';


--
-- Name: COLUMN situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.hora_inativacao IS 'Hora da inativação da situação';


--
-- Name: situacao_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE situacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE situacao_id_seq OWNER TO postgres;

--
-- Name: situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE situacao_id_seq OWNED BY situacao.id;


--
-- Name: turma; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma (
    id integer NOT NULL,
    data_revisao date NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE turma OWNER TO postgres;

--
-- Name: TABLE turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma IS 'Tabela com os revisão de vidas ou turma do instituto de vencedores';


--
-- Name: COLUMN turma.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.id IS 'Identificação da turma';


--
-- Name: COLUMN turma.data_revisao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_revisao IS 'Data de inicio do revisão de vidas';


--
-- Name: COLUMN turma.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_criacao IS 'Data de criação da turma';


--
-- Name: COLUMN turma.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_criacao IS 'Hora de criação da turma';


--
-- Name: COLUMN turma.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_inativacao IS 'Data de inativação da turma';


--
-- Name: COLUMN turma.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_inativacao IS 'Hora de inativação da turma';


--
-- Name: turma_aluno; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma_aluno (
    id integer NOT NULL,
    turma_id integer NOT NULL,
    pessoa_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE turma_aluno OWNER TO postgres;

--
-- Name: TABLE turma_aluno; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_aluno IS 'Associação entre a turma e os alunos';


--
-- Name: COLUMN turma_aluno.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.id IS 'Identificação da associação turma com o aluno';


--
-- Name: COLUMN turma_aluno.turma_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.turma_id IS 'Identificação da turma';


--
-- Name: COLUMN turma_aluno.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.pessoa_id IS 'Identificação da pessoa';


--
-- Name: COLUMN turma_aluno.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.data_criacao IS 'Data de criação da associação';


--
-- Name: COLUMN turma_aluno.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.hora_criacao IS 'Hora da criação da associação';


--
-- Name: COLUMN turma_aluno.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.data_inativacao IS 'Data de inativação da associação';


--
-- Name: COLUMN turma_aluno.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.hora_inativacao IS 'Hora da inativação da associação';


--
-- Name: turma_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE turma_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE turma_id_seq OWNER TO postgres;

--
-- Name: turma_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_id_seq OWNED BY turma.id;


--
-- Name: turma_pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE turma_pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE turma_pessoa_id_seq OWNER TO postgres;

--
-- Name: turma_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_pessoa_id_seq OWNED BY turma_aluno.id;


--
-- Name: aluno_situacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao ALTER COLUMN id SET DEFAULT nextval('aluno_situacao_id_seq'::regclass);


--
-- Name: dimensao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao ALTER COLUMN id SET DEFAULT nextval('dimensao_id_seq'::regclass);


--
-- Name: dimensao_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao_tipo ALTER COLUMN id SET DEFAULT nextval('dimensao_tipo_id_seq'::regclass);


--
-- Name: entidade id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade ALTER COLUMN id SET DEFAULT nextval('entidade_id_seq'::regclass);


--
-- Name: entidade_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade_tipo ALTER COLUMN id SET DEFAULT nextval('entidade_tipo_id_seq'::regclass);


--
-- Name: evento id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento ALTER COLUMN id SET DEFAULT nextval('evento_id_seq'::regclass);


--
-- Name: evento_celula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula ALTER COLUMN id SET DEFAULT nextval('evento_celula_id_seq'::regclass);


--
-- Name: evento_frequencia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia ALTER COLUMN id SET DEFAULT nextval('evento_frequencia_id_seq'::regclass);


--
-- Name: evento_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_tipo ALTER COLUMN id SET DEFAULT nextval('evento_tipo_id_seq'::regclass);


--
-- Name: fato_celula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula ALTER COLUMN id SET DEFAULT nextval('fato_celula_id_seq'::regclass);


--
-- Name: fato_ciclo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ciclo ALTER COLUMN id SET DEFAULT nextval('fato_ciclo_id_seq'::regclass);


--
-- Name: fato_lider id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_lider ALTER COLUMN id SET DEFAULT nextval('fato_lider_id_seq'::regclass);


--
-- Name: grupo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo ALTER COLUMN id SET DEFAULT nextval('grupo_id_seq'::regclass);


--
-- Name: grupo_aluno id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno ALTER COLUMN id SET DEFAULT nextval('grupo_aluno_id_seq'::regclass);


--
-- Name: grupo_atendimento id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento ALTER COLUMN id SET DEFAULT nextval('grupo_atendimento_id_seq'::regclass);


--
-- Name: grupo_cv id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv ALTER COLUMN id SET DEFAULT nextval('grupo_cv_id_seq'::regclass);


--
-- Name: grupo_evento id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento ALTER COLUMN id SET DEFAULT nextval('grupo_evento_id_seq'::regclass);


--
-- Name: grupo_pai_filho id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho ALTER COLUMN id SET DEFAULT nextval('grupo_pai_filho_id_seq'::regclass);


--
-- Name: grupo_pessoa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa ALTER COLUMN id SET DEFAULT nextval('grupo_pessoa_id_seq'::regclass);


--
-- Name: grupo_pessoa_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa_tipo ALTER COLUMN id SET DEFAULT nextval('grupo_pessoa_tipo_id_seq'::regclass);


--
-- Name: grupo_responsavel id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel ALTER COLUMN id SET DEFAULT nextval('grupo_responsavel_id_seq'::regclass);


--
-- Name: hierarquia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hierarquia ALTER COLUMN id SET DEFAULT nextval('hierarquia_id_seq'::regclass);


--
-- Name: pessoa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa ALTER COLUMN id SET DEFAULT nextval('pessoa_id_seq'::regclass);


--
-- Name: pessoa_hierarquia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia ALTER COLUMN id SET DEFAULT nextval('pessoa_hierarquia_id_seq'::regclass);


--
-- Name: situacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY situacao ALTER COLUMN id SET DEFAULT nextval('situacao_id_seq'::regclass);


--
-- Name: turma id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma ALTER COLUMN id SET DEFAULT nextval('turma_id_seq'::regclass);


--
-- Name: turma_aluno id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno ALTER COLUMN id SET DEFAULT nextval('turma_pessoa_id_seq'::regclass);


--
-- Name: aluno_situacao pk_aluno_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT pk_aluno_situacao PRIMARY KEY (id);


--
-- Name: dimensao pk_dimensao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT pk_dimensao PRIMARY KEY (id);


--
-- Name: dimensao_tipo pk_dimensao_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao_tipo
    ADD CONSTRAINT pk_dimensao_tipo PRIMARY KEY (id);


--
-- Name: entidade pk_entidade; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT pk_entidade PRIMARY KEY (id);


--
-- Name: entidade_tipo pk_entidade_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade_tipo
    ADD CONSTRAINT pk_entidade_tipo PRIMARY KEY (id);


--
-- Name: evento pk_evento; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT pk_evento PRIMARY KEY (id);


--
-- Name: evento_celula pk_evento_celula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula
    ADD CONSTRAINT pk_evento_celula PRIMARY KEY (id);


--
-- Name: evento_frequencia pk_evento_frequencia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT pk_evento_frequencia PRIMARY KEY (id);


--
-- Name: fato_celula pk_fato_celula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula
    ADD CONSTRAINT pk_fato_celula PRIMARY KEY (id);


--
-- Name: fato_ciclo pk_fato_ciclo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ciclo
    ADD CONSTRAINT pk_fato_ciclo PRIMARY KEY (id);


--
-- Name: fato_lider pk_fato_lider; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_lider
    ADD CONSTRAINT pk_fato_lider PRIMARY KEY (id);


--
-- Name: grupo pk_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT pk_grupo PRIMARY KEY (id);


--
-- Name: grupo_aluno pk_grupo_aluno; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT pk_grupo_aluno PRIMARY KEY (id);


--
-- Name: grupo_atendimento pk_grupo_atendimento; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento
    ADD CONSTRAINT pk_grupo_atendimento PRIMARY KEY (id);


--
-- Name: grupo_cv pk_grupo_cv; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv
    ADD CONSTRAINT pk_grupo_cv PRIMARY KEY (id);


--
-- Name: grupo_evento pk_grupo_evento; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT pk_grupo_evento PRIMARY KEY (id);


--
-- Name: grupo_pai_filho pk_grupo_pai_filho; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT pk_grupo_pai_filho PRIMARY KEY (id);


--
-- Name: grupo_pessoa pk_grupo_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT pk_grupo_pessoa PRIMARY KEY (id);


--
-- Name: grupo_pessoa_tipo pk_grupo_pessoa_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa_tipo
    ADD CONSTRAINT pk_grupo_pessoa_tipo PRIMARY KEY (id);


--
-- Name: grupo_responsavel pk_grupo_responsavel; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT pk_grupo_responsavel PRIMARY KEY (id);


--
-- Name: hierarquia pk_hierarquia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hierarquia
    ADD CONSTRAINT pk_hierarquia PRIMARY KEY (id);


--
-- Name: pessoa pk_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (id);


--
-- Name: pessoa_hierarquia pk_pessoa_hierarquia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT pk_pessoa_hierarquia PRIMARY KEY (id);


--
-- Name: situacao pk_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY situacao
    ADD CONSTRAINT pk_situacao PRIMARY KEY (id);


--
-- Name: turma pk_turma; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma
    ADD CONSTRAINT pk_turma PRIMARY KEY (id);


--
-- Name: CONSTRAINT pk_turma ON turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma ON turma IS 'Chave primaria da turma';


--
-- Name: turma_aluno pk_turma_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT pk_turma_pessoa PRIMARY KEY (id);


--
-- Name: evento_tipo primary_key_evento_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_tipo
    ADD CONSTRAINT primary_key_evento_tipo PRIMARY KEY (id);


--
-- Name: fki_entidade_grupo_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_entidade_grupo_id ON entidade USING btree (grupo_id);


--
-- Name: fki_entidade_tipo_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_entidade_tipo_id ON entidade USING btree (tipo_id);


--
-- Name: index_fato_ciclo_numero_identificador; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_fato_ciclo_numero_identificador ON fato_ciclo USING btree (numero_identificador);


--
-- Name: index_fato_lider_numero_identificador; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_fato_lider_numero_identificador ON fato_lider USING btree (numero_identificador);


--
-- Name: INDEX index_fato_lider_numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_fato_lider_numero_identificador IS 'Index do número identificador do grupo';


--
-- Name: index_pessoa_data_nascimento; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_pessoa_data_nascimento ON pessoa USING btree (data_nascimento);


--
-- Name: INDEX index_pessoa_data_nascimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_pessoa_data_nascimento IS 'Index para recuperar email de acesso atraves da data de nascimento';


--
-- Name: index_pessoa_documento; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_pessoa_documento ON pessoa USING btree (documento);


--
-- Name: INDEX index_pessoa_documento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_pessoa_documento IS 'Index para recuperar email de acesso atravez do documento';


--
-- Name: aluno_situacao fk_aluno_situacao_situacao; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT fk_aluno_situacao_situacao FOREIGN KEY (situacao_id) REFERENCES situacao(id);


--
-- Name: aluno_situacao fk_aluno_situacao_turma_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT fk_aluno_situacao_turma_aluno FOREIGN KEY (turma_aluno_id) REFERENCES turma_aluno(id);


--
-- Name: dimensao fk_dimensao_dimensao_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT fk_dimensao_dimensao_tipo_id FOREIGN KEY (dimensao_tipo_id) REFERENCES dimensao_tipo(id);


--
-- Name: dimensao fk_dimensao_fato_ciclo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT fk_dimensao_fato_ciclo_id FOREIGN KEY (fato_ciclo_id) REFERENCES fato_ciclo(id);


--
-- Name: entidade fk_entidade_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT fk_entidade_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: CONSTRAINT fk_entidade_grupo_id ON entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_entidade_grupo_id ON entidade IS 'Chave estrangeira da entidade com grupo';


--
-- Name: entidade fk_entidade_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT fk_entidade_tipo_id FOREIGN KEY (tipo_id) REFERENCES entidade_tipo(id);


--
-- Name: CONSTRAINT fk_entidade_tipo_id ON entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_entidade_tipo_id ON entidade IS 'Chave estrangeira de entidade com tipo da entidade';


--
-- Name: evento_celula fk_evento_celula_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula
    ADD CONSTRAINT fk_evento_celula_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- Name: evento fk_evento_evento_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT fk_evento_evento_tipo_id FOREIGN KEY (tipo_id) REFERENCES evento_tipo(id);


--
-- Name: evento_frequencia fk_evento_frequencia_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT fk_evento_frequencia_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- Name: evento_frequencia fk_evento_frequencia_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT fk_evento_frequencia_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fato_celula fk_fato_celula_fato_ciclo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula
    ADD CONSTRAINT fk_fato_celula_fato_ciclo FOREIGN KEY (fato_ciclo_id) REFERENCES fato_ciclo(id);


--
-- Name: grupo_aluno fk_grupo_aluno_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT fk_grupo_aluno_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: grupo_aluno fk_grupo_aluno_turma_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT fk_grupo_aluno_turma_aluno FOREIGN KEY (turma_aluno_id) REFERENCES turma_aluno(id);


--
-- Name: grupo_atendimento fk_grupo_atendimento_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento
    ADD CONSTRAINT fk_grupo_atendimento_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: grupo_cv fk_grupo_cv_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv
    ADD CONSTRAINT fk_grupo_cv_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: grupo_evento fk_grupo_evento_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT fk_grupo_evento_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- Name: grupo_evento fk_grupo_evento_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT fk_grupo_evento_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: grupo_pai_filho fk_grupo_pai_filho_filho_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT fk_grupo_pai_filho_filho_id FOREIGN KEY (filho_id) REFERENCES grupo(id);


--
-- Name: grupo_pai_filho fk_grupo_pai_filho_pai_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT fk_grupo_pai_filho_pai_id FOREIGN KEY (pai_id) REFERENCES grupo(id);


--
-- Name: grupo_pessoa fk_grupo_pessoa_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: grupo_pessoa fk_grupo_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: grupo_pessoa fk_grupo_pessoa_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_tipo_id FOREIGN KEY (tipo_id) REFERENCES grupo_pessoa_tipo(id);


--
-- Name: grupo_responsavel fk_grupo_responsavel_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT fk_grupo_responsavel_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: grupo_responsavel fk_grupo_responsavel_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT fk_grupo_responsavel_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: pessoa_hierarquia fk_pessoa_hierarquia_hierarquia; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT fk_pessoa_hierarquia_hierarquia FOREIGN KEY (hierarquia_id) REFERENCES hierarquia(id);


--
-- Name: pessoa_hierarquia fk_pessoa_hierarquia_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT fk_pessoa_hierarquia_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: turma_aluno fk_turma_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT fk_turma_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: turma_aluno fk_turma_pessoa_turma_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT fk_turma_pessoa_turma_id FOREIGN KEY (turma_id) REFERENCES turma(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

