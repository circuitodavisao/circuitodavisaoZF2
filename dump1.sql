--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.4.0
-- Started on 2017-05-22 10:54:30

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 224 (class 3079 OID 11791)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2375 (class 0 OID 0)
-- Dependencies: 224
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 633 (class 1247 OID 16811)
-- Name: enumcl; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE enumcl AS ENUM (
    'C',
    'L'
);


ALTER TYPE enumcl OWNER TO postgres;

--
-- TOC entry 664 (class 1247 OID 16982)
-- Name: enummf; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE enummf AS ENUM (
    'M',
    'F'
);


ALTER TYPE enummf OWNER TO postgres;

--
-- TOC entry 629 (class 1247 OID 16766)
-- Name: enumsn; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE enumsn AS ENUM (
    'S',
    'N'
);


ALTER TYPE enumsn OWNER TO postgres;

--
-- TOC entry 578 (class 1247 OID 16423)
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
-- TOC entry 202 (class 1259 OID 16874)
-- Name: aluno_situacao; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2376 (class 0 OID 0)
-- Dependencies: 202
-- Name: TABLE aluno_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE aluno_situacao IS 'Associação entre o aluno e sua situacao';


--
-- TOC entry 2377 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.id IS 'Identificação da associação do aluno com a situação';


--
-- TOC entry 2378 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.situacao_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.situacao_id IS 'Identificação da situação';


--
-- TOC entry 2379 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.turma_aluno_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.turma_aluno_id IS 'Identificação (matricula) da turma aluno';


--
-- TOC entry 2380 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.data_criacao IS 'Data de criação da associação da situação com o aluno';


--
-- TOC entry 2381 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.hora_criacao IS 'Hora de criação da associação da situação com o aluno';


--
-- TOC entry 2382 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.data_inativacao IS 'Data de inativação da associação da situação com aluno';


--
-- TOC entry 2383 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN aluno_situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.hora_inativacao IS 'Hora da inativação da associação situação com aluno';


--
-- TOC entry 203 (class 1259 OID 16897)
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
-- TOC entry 2384 (class 0 OID 0)
-- Dependencies: 203
-- Name: aluno_situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE aluno_situacao_id_seq OWNED BY aluno_situacao.id;


--
-- TOC entry 215 (class 1259 OID 17052)
-- Name: dimensao; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2385 (class 0 OID 0)
-- Dependencies: 215
-- Name: TABLE dimensao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE dimensao IS 'Dimensão dos dados';


--
-- TOC entry 2386 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.id IS 'Identificação';


--
-- TOC entry 2387 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.fato_ciclo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.fato_ciclo_id IS 'Identificação do fato ciclo';


--
-- TOC entry 2388 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.dimensao_tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.dimensao_tipo_id IS 'Identificação do tipo dos dados';


--
-- TOC entry 2389 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.visitante; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.visitante IS 'Número de visitantes lançados';


--
-- TOC entry 2390 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.consolidacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.consolidacao IS 'Número de consolidações lançadas';


--
-- TOC entry 2391 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.membro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.membro IS 'Número de membros lançados';


--
-- TOC entry 2392 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.data_criacao IS 'Data de criação da dimensão';


--
-- TOC entry 2393 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.hora_criacao IS 'Hora de criação da dimensão';


--
-- TOC entry 2394 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.data_inativacao IS 'Data de inativação da dimensão';


--
-- TOC entry 2395 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.hora_inativacao IS 'Hora da inativação da dimensão';


--
-- TOC entry 2396 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN dimensao.lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.lider IS 'Número de líderes lançados';


--
-- TOC entry 214 (class 1259 OID 17050)
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
-- TOC entry 2397 (class 0 OID 0)
-- Dependencies: 214
-- Name: dimensao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dimensao_id_seq OWNED BY dimensao.id;


--
-- TOC entry 217 (class 1259 OID 17078)
-- Name: dimensao_tipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2398 (class 0 OID 0)
-- Dependencies: 217
-- Name: TABLE dimensao_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE dimensao_tipo IS 'Tabela com os tipos de dimensões';


--
-- TOC entry 2399 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN dimensao_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.id IS 'Identificação';


--
-- TOC entry 2400 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN dimensao_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.nome IS 'Nome tipo da dimensão';


--
-- TOC entry 2401 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN dimensao_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.data_criacao IS 'Data de criação do tipo da dimensão';


--
-- TOC entry 2402 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN dimensao_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.hora_criacao IS 'Hora de criação do tipo da dimensão';


--
-- TOC entry 2403 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN dimensao_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.data_inativacao IS 'Data de inativação do tipo da dimensão';


--
-- TOC entry 2404 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN dimensao_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.hora_inativacao IS 'Hora de inativação do tipo da dimensão';


--
-- TOC entry 216 (class 1259 OID 17076)
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
-- TOC entry 2405 (class 0 OID 0)
-- Dependencies: 216
-- Name: dimensao_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dimensao_tipo_id_seq OWNED BY dimensao_tipo.id;


--
-- TOC entry 179 (class 1259 OID 16458)
-- Name: entidade; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2406 (class 0 OID 0)
-- Dependencies: 179
-- Name: TABLE entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entidade IS 'Tabela que armazena os dados das diversas entidades com número, nomes, telefone e endereços';


--
-- TOC entry 2407 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.id IS 'Identificação da entidade';


--
-- TOC entry 2408 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.data_criacao IS 'Data de criação da entidade';


--
-- TOC entry 2409 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.hora_criacao IS 'Hora de criação da entidade';


--
-- TOC entry 2410 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.nome IS 'Nome para as entidades: igreja, equipes';


--
-- TOC entry 2411 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.numero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.numero IS 'Número para as entidades: região, coordenação e subs';


--
-- TOC entry 2412 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.data_inativacao IS 'Data de inativação da entidade';


--
-- TOC entry 2413 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.hora_inativacao IS 'Hora da inativação da entidade';


--
-- TOC entry 2414 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.tipo_id IS 'Indetificação do tipo de entidade';


--
-- TOC entry 2415 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN entidade.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 178 (class 1259 OID 16456)
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
-- TOC entry 2416 (class 0 OID 0)
-- Dependencies: 178
-- Name: entidade_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entidade_id_seq OWNED BY entidade.id;


--
-- TOC entry 181 (class 1259 OID 16467)
-- Name: entidade_tipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2417 (class 0 OID 0)
-- Dependencies: 181
-- Name: TABLE entidade_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entidade_tipo IS 'Tabela com os tipo de entidades';


--
-- TOC entry 2418 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN entidade_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.id IS 'Identificação do tipo de entidade';


--
-- TOC entry 2419 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN entidade_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.nome IS 'Nome da entidade';


--
-- TOC entry 2420 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN entidade_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.data_criacao IS 'Data de criação do tipo da entidade';


--
-- TOC entry 2421 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN entidade_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.hora_criacao IS 'Hora de criação do tipo da entidade';


--
-- TOC entry 2422 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN entidade_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.data_inativacao IS 'Data de inativação do tipo da entidade';


--
-- TOC entry 2423 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN entidade_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.hora_inativacao IS 'Hora inativação do tipo da entidade';


--
-- TOC entry 180 (class 1259 OID 16465)
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
-- TOC entry 2424 (class 0 OID 0)
-- Dependencies: 180
-- Name: entidade_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entidade_tipo_id_seq OWNED BY entidade_tipo.id;


--
-- TOC entry 183 (class 1259 OID 16592)
-- Name: evento; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2425 (class 0 OID 0)
-- Dependencies: 183
-- Name: TABLE evento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento IS 'Tabela que armazena dados dos eventos em geral';


--
-- TOC entry 2426 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.id IS 'Identificação do evento';


--
-- TOC entry 2427 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.dia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.dia IS 'Dia da semana que ocorre o evento.
1 - domingo
7 - sabado';


--
-- TOC entry 2428 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora IS 'Hora que ocorre o evento';


--
-- TOC entry 2429 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data_criacao IS 'Data de criação do evento';


--
-- TOC entry 2430 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora_criacao IS 'Hora de criação do evento';


--
-- TOC entry 2431 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data_inativacao IS 'Data de inativação do evento';


--
-- TOC entry 2432 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora_inativacao IS 'Hora de inativação do evento';


--
-- TOC entry 2433 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.tipo_id IS 'Identificação do tipo do evento';


--
-- TOC entry 2434 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.nome IS 'Nome do evento';


--
-- TOC entry 2435 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN evento.data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data IS 'Data do evento';


--
-- TOC entry 187 (class 1259 OID 16640)
-- Name: evento_celula; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2436 (class 0 OID 0)
-- Dependencies: 187
-- Name: TABLE evento_celula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_celula IS 'Tabela para amarzenas dados do evento tipo célula';


--
-- TOC entry 2437 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.id IS 'Identificação do dados do evento tipo célula';


--
-- TOC entry 2438 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.nome_hospedeiro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.nome_hospedeiro IS 'Nome do hospedeiro da célula';


--
-- TOC entry 2439 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.telefone_hospedeiro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.telefone_hospedeiro IS 'Telefone do hospedeiro com 9 digitos com DDD';


--
-- TOC entry 2440 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.logradouro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.logradouro IS 'Logradouro da célula';


--
-- TOC entry 2441 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.complemento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.complemento IS 'Complemento do endereço da célula';


--
-- TOC entry 2442 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.evento_id IS 'Identificação do evento';


--
-- TOC entry 2443 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.bairro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.bairro IS 'Bairro do local da célula';


--
-- TOC entry 2444 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.cidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.cidade IS 'Cidade do local da célula';


--
-- TOC entry 2445 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.cep; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.cep IS 'CEP do local da célula';


--
-- TOC entry 2446 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN evento_celula.uf; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.uf IS 'Unidade Federativa da célula';


--
-- TOC entry 186 (class 1259 OID 16638)
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
-- TOC entry 2447 (class 0 OID 0)
-- Dependencies: 186
-- Name: evento_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_celula_id_seq OWNED BY evento_celula.id;


--
-- TOC entry 195 (class 1259 OID 16747)
-- Name: evento_frequencia; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
    ciclo smallint DEFAULT 1 NOT NULL,
    mes smallint NOT NULL,
    ano smallint NOT NULL
);


ALTER TABLE evento_frequencia OWNER TO postgres;

--
-- TOC entry 2448 (class 0 OID 0)
-- Dependencies: 195
-- Name: TABLE evento_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_frequencia IS 'Tabela associativa da pessoa no evento';


--
-- TOC entry 2449 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.id IS 'Identificação da frequência no evento';


--
-- TOC entry 2450 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.evento_id IS 'Identificação do evento';


--
-- TOC entry 2451 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2452 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2453 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.hora_criacao IS 'Hora de criação da associativa';


--
-- TOC entry 2454 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.data_inativacao IS 'Data da inativação da associativa';


--
-- TOC entry 2455 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 2456 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.frequencia IS 'Frequência da pessoa no evento';


--
-- TOC entry 2457 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.ciclo IS 'Ciclo da frequência do evento';


--
-- TOC entry 2458 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.mes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.mes IS 'Mês que ocorre a frequência do evento';


--
-- TOC entry 2459 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN evento_frequencia.ano; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.ano IS 'Ano que ocorre a frequência do evento';


--
-- TOC entry 194 (class 1259 OID 16745)
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
-- TOC entry 2460 (class 0 OID 0)
-- Dependencies: 194
-- Name: evento_frequencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_frequencia_id_seq OWNED BY evento_frequencia.id;


--
-- TOC entry 182 (class 1259 OID 16590)
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
-- TOC entry 2461 (class 0 OID 0)
-- Dependencies: 182
-- Name: evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_id_seq OWNED BY evento.id;


--
-- TOC entry 185 (class 1259 OID 16620)
-- Name: evento_tipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2462 (class 0 OID 0)
-- Dependencies: 185
-- Name: TABLE evento_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_tipo IS 'Tipo de evento';


--
-- TOC entry 2463 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN evento_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.id IS 'Identificação dos tipos de evento';


--
-- TOC entry 2464 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN evento_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.nome IS 'Nome do tipo de evento';


--
-- TOC entry 2465 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN evento_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.data_criacao IS 'Data de criação do tipo do evento';


--
-- TOC entry 2466 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN evento_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.hora_criacao IS 'Hora de criação do tipo de evento';


--
-- TOC entry 2467 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN evento_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.data_inativacao IS 'Data de inativação do tipo de evento';


--
-- TOC entry 2468 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN evento_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.hora_inativacao IS 'Hora de inativação do tipo de evento';


--
-- TOC entry 184 (class 1259 OID 16618)
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
-- TOC entry 2469 (class 0 OID 0)
-- Dependencies: 184
-- Name: evento_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_tipo_id_seq OWNED BY evento_tipo.id;


--
-- TOC entry 221 (class 1259 OID 17405)
-- Name: fato_celula; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2470 (class 0 OID 0)
-- Dependencies: 221
-- Name: TABLE fato_celula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_celula IS 'Tabela com a quantidade de celulas atuais e realizadas no ciclo';


--
-- TOC entry 2471 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.id IS 'Identificação do relatório';


--
-- TOC entry 2472 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.data_criacao IS 'Data de criação do relatório';


--
-- TOC entry 2473 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.hora_criacao IS 'Hora da criação do relatório';


--
-- TOC entry 2474 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.data_inativacao IS 'Data da inativação do relatório';


--
-- TOC entry 2475 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.hora_inativacao IS 'Hora da inativação do relatório';


--
-- TOC entry 2476 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.realizada; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.realizada IS 'Realizada 1 ou 0';


--
-- TOC entry 2477 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.fato_ciclo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.fato_ciclo_id IS 'Identificação do fato ciclo';


--
-- TOC entry 2478 (class 0 OID 0)
-- Dependencies: 221
-- Name: COLUMN fato_celula.evento_celula_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.evento_celula_id IS 'Identificação do evento célula';


--
-- TOC entry 220 (class 1259 OID 17403)
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
-- TOC entry 2479 (class 0 OID 0)
-- Dependencies: 220
-- Name: fato_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_celula_id_seq OWNED BY fato_celula.id;


--
-- TOC entry 213 (class 1259 OID 17013)
-- Name: fato_ciclo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE fato_ciclo (
    id integer NOT NULL,
    numero_identificador character varying(64) NOT NULL,
    mes integer NOT NULL,
    ano integer NOT NULL,
    ciclo integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL
);


ALTER TABLE fato_ciclo OWNER TO postgres;

--
-- TOC entry 2480 (class 0 OID 0)
-- Dependencies: 213
-- Name: TABLE fato_ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_ciclo IS 'Tabela com os dados consolidados do lançamento de dados das igrejas, equipes e subs';


--
-- TOC entry 2481 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.id IS 'Identificação';


--
-- TOC entry 2482 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.numero_identificador IS 'Número para saber de qual igreja pertenço
8 espaços para cada posição';


--
-- TOC entry 2483 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.mes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.mes IS 'Mês do relatório';


--
-- TOC entry 2484 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.ano; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.ano IS 'Ano do relatório';


--
-- TOC entry 2485 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.ciclo IS 'Ciclo do relatório';


--
-- TOC entry 2486 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.data_criacao IS 'Data de criação do fato';


--
-- TOC entry 2487 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2488 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.hora_inativacao IS 'Hora de inativação do fato';


--
-- TOC entry 2489 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN fato_ciclo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.hora_criacao IS 'Hora de criação do fato';


--
-- TOC entry 212 (class 1259 OID 17011)
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
-- TOC entry 2490 (class 0 OID 0)
-- Dependencies: 212
-- Name: fato_ciclo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_ciclo_id_seq OWNED BY fato_ciclo.id;


--
-- TOC entry 218 (class 1259 OID 17341)
-- Name: fato_lider; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2491 (class 0 OID 0)
-- Dependencies: 218
-- Name: TABLE fato_lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_lider IS 'Tabela com a quantidade de lideres por número identificador';


--
-- TOC entry 2492 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.id IS 'Identificação do fato lider';


--
-- TOC entry 2493 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.numero_identificador IS 'Número identificador do grupo';


--
-- TOC entry 2494 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.lideres; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.lideres IS 'Quantidade de lideres';


--
-- TOC entry 2495 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.data_criacao IS 'Data de criação do relatório';


--
-- TOC entry 2496 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.hora_criacao IS 'Hora de criação do relatório';


--
-- TOC entry 2497 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.data_inativacao IS 'Data de inativação do relatório';


--
-- TOC entry 2498 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN fato_lider.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.hora_inativacao IS 'Hora de inativação do relatório';


--
-- TOC entry 219 (class 1259 OID 17344)
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
-- TOC entry 2499 (class 0 OID 0)
-- Dependencies: 219
-- Name: fato_lider_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_lider_id_seq OWNED BY fato_lider.id;


--
-- TOC entry 177 (class 1259 OID 16450)
-- Name: grupo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2500 (class 0 OID 0)
-- Dependencies: 177
-- Name: TABLE grupo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo IS 'Tabela com os grupos ';


--
-- TOC entry 2501 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN grupo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.id IS 'Identificação do grupo';


--
-- TOC entry 2502 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN grupo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.data_criacao IS 'Data de criação do grupo';


--
-- TOC entry 2503 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN grupo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.hora_criacao IS 'Hora de criação do grupo';


--
-- TOC entry 2504 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN grupo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.data_inativacao IS 'Data de inativação do grupo';


--
-- TOC entry 2505 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN grupo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.hora_inativacao IS 'Hora de inativação do grupo';


--
-- TOC entry 205 (class 1259 OID 16924)
-- Name: grupo_aluno; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2506 (class 0 OID 0)
-- Dependencies: 205
-- Name: TABLE grupo_aluno; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_aluno IS 'Tabela associativa do aluno com grupo';


--
-- TOC entry 2507 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.id IS 'Identificação da associativa aluno com o grupo';


--
-- TOC entry 2508 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2509 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.turma_aluno_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.turma_aluno_id IS 'Identificação (matricula) da turma aluno';


--
-- TOC entry 2510 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2511 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.hora_criacao IS 'Hora de criação da associação';


--
-- TOC entry 2512 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.data_inativacao IS 'Data da inativação da associação';


--
-- TOC entry 2513 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN grupo_aluno.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 204 (class 1259 OID 16922)
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
-- TOC entry 2514 (class 0 OID 0)
-- Dependencies: 204
-- Name: grupo_aluno_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_aluno_id_seq OWNED BY grupo_aluno.id;


--
-- TOC entry 211 (class 1259 OID 16989)
-- Name: grupo_atendimento; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE grupo_atendimento (
    id integer NOT NULL,
    grupo_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    dia date NOT NULL,
    quem integer DEFAULT 1 NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    observacao character varying(120)
);


ALTER TABLE grupo_atendimento OWNER TO postgres;

--
-- TOC entry 2515 (class 0 OID 0)
-- Dependencies: 211
-- Name: TABLE grupo_atendimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_atendimento IS 'Tabela associativa do grupo com atendimento';


--
-- TOC entry 2516 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.id IS 'Identificação da associação';


--
-- TOC entry 2517 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2518 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2519 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.hora_criacao IS 'Hora de criação da associação';


--
-- TOC entry 2520 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.dia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.dia IS 'Data do atendimento';


--
-- TOC entry 2521 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.quem; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.quem IS 'Quem fez o atendimento
1 - Homem ou Responsavel
2 - Mulher
3 - Ambos';


--
-- TOC entry 2522 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2523 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 2524 (class 0 OID 0)
-- Dependencies: 211
-- Name: COLUMN grupo_atendimento.observacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.observacao IS 'Observação do atendimento caso necessário';


--
-- TOC entry 210 (class 1259 OID 16987)
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
-- TOC entry 2525 (class 0 OID 0)
-- Dependencies: 210
-- Name: grupo_atendimento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_atendimento_id_seq OWNED BY grupo_atendimento.id;


--
-- TOC entry 223 (class 1259 OID 17741)
-- Name: grupo_cv; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2526 (class 0 OID 0)
-- Dependencies: 223
-- Name: TABLE grupo_cv; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_cv IS 'Tabela associativa com o sistema antigo';


--
-- TOC entry 222 (class 1259 OID 17739)
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
-- TOC entry 2527 (class 0 OID 0)
-- Dependencies: 222
-- Name: grupo_cv_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_cv_id_seq OWNED BY grupo_cv.id;


--
-- TOC entry 189 (class 1259 OID 16668)
-- Name: grupo_evento; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2528 (class 0 OID 0)
-- Dependencies: 189
-- Name: TABLE grupo_evento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_evento IS 'Tabela de eventos que o grupo participa';


--
-- TOC entry 2529 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.id IS 'Identificação dos eventos do grupo';


--
-- TOC entry 2530 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2531 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.evento_id IS 'Identificação do evento';


--
-- TOC entry 2532 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2533 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.hora_criacao IS 'Hora de criação da associação';


--
-- TOC entry 2534 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2535 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN grupo_evento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 188 (class 1259 OID 16666)
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
-- TOC entry 2536 (class 0 OID 0)
-- Dependencies: 188
-- Name: grupo_evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_evento_id_seq OWNED BY grupo_evento.id;


--
-- TOC entry 176 (class 1259 OID 16448)
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
-- TOC entry 2537 (class 0 OID 0)
-- Dependencies: 176
-- Name: grupo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_id_seq OWNED BY grupo.id;


--
-- TOC entry 175 (class 1259 OID 16440)
-- Name: grupo_pai_filho; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2538 (class 0 OID 0)
-- Dependencies: 175
-- Name: TABLE grupo_pai_filho; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pai_filho IS 'Tabela associativa entre grupos';


--
-- TOC entry 2539 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.id IS 'Identificação da associação entre grupos';


--
-- TOC entry 2540 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2541 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.hora_criacao IS 'Hora da criação da associação';


--
-- TOC entry 2542 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.pai_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.pai_id IS 'Identificação do grupo pai';


--
-- TOC entry 2543 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.filho_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.filho_id IS 'Identificação do grupo filho';


--
-- TOC entry 2544 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2545 (class 0 OID 0)
-- Dependencies: 175
-- Name: COLUMN grupo_pai_filho.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 174 (class 1259 OID 16438)
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
-- TOC entry 2546 (class 0 OID 0)
-- Dependencies: 174
-- Name: grupo_pai_filho_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pai_filho_id_seq OWNED BY grupo_pai_filho.id;


--
-- TOC entry 191 (class 1259 OID 16710)
-- Name: grupo_pessoa; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2547 (class 0 OID 0)
-- Dependencies: 191
-- Name: TABLE grupo_pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pessoa IS 'Tabela associativa do grupo com as pessoas volateis';


--
-- TOC entry 2548 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.id IS 'Identificação da associação grupo pessoa volatél';


--
-- TOC entry 2549 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2550 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2551 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2552 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.hora_criacao IS 'Hora de criação da associativa';


--
-- TOC entry 2553 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.data_inativacao IS 'Data de inativacao da associação';


--
-- TOC entry 2554 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 2555 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.tipo_id IS 'Identificação do tipo da pessoa volatél';


--
-- TOC entry 2556 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.transferido; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.transferido IS 'Identificação para saber se foi transferido ou não';


--
-- TOC entry 2557 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN grupo_pessoa.nucleo_perfeito; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.nucleo_perfeito IS 'Enumeração para co líder ou líder em treinamento';


--
-- TOC entry 190 (class 1259 OID 16708)
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
-- TOC entry 2558 (class 0 OID 0)
-- Dependencies: 190
-- Name: grupo_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pessoa_id_seq OWNED BY grupo_pessoa.id;


--
-- TOC entry 193 (class 1259 OID 16721)
-- Name: grupo_pessoa_tipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2559 (class 0 OID 0)
-- Dependencies: 193
-- Name: TABLE grupo_pessoa_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pessoa_tipo IS 'Tabela com o tipo de pessoa volatél';


--
-- TOC entry 2560 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN grupo_pessoa_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.id IS 'Identificação do tipo de pessoa volatél';


--
-- TOC entry 2561 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN grupo_pessoa_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.nome IS 'Nome do tipo de pessoa volatél';


--
-- TOC entry 2562 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN grupo_pessoa_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.data_criacao IS 'Data de criação do tipo da pessoa';


--
-- TOC entry 2563 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN grupo_pessoa_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.hora_criacao IS 'Hora de criação do tipo da pessoa';


--
-- TOC entry 2564 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN grupo_pessoa_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.data_inativacao IS 'Data de inativação do tipo da pessoa';


--
-- TOC entry 2565 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN grupo_pessoa_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.hora_inativacao IS 'Hora da inativação do tipo da pessoa';


--
-- TOC entry 192 (class 1259 OID 16719)
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
-- TOC entry 2566 (class 0 OID 0)
-- Dependencies: 192
-- Name: grupo_pessoa_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pessoa_tipo_id_seq OWNED BY grupo_pessoa_tipo.id;


--
-- TOC entry 173 (class 1259 OID 16432)
-- Name: grupo_responsavel; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2567 (class 0 OID 0)
-- Dependencies: 173
-- Name: TABLE grupo_responsavel; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_responsavel IS 'Tabela associativa do grupo com as pessoas';


--
-- TOC entry 2568 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.id IS 'Identificação do responsavel pelo grupo';


--
-- TOC entry 2569 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.data_criacao IS 'Data de criação da responsabilidade';


--
-- TOC entry 2570 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.hora_criacao IS 'Hora que foi criada a responsabilidade';


--
-- TOC entry 2571 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2572 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2573 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.data_inativacao IS 'Data de inativação da responsabilidade';


--
-- TOC entry 2574 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN grupo_responsavel.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.hora_inativacao IS 'Hora da inativação da responsabilidade';


--
-- TOC entry 172 (class 1259 OID 16430)
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
-- TOC entry 2575 (class 0 OID 0)
-- Dependencies: 172
-- Name: grupo_responsavel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_responsavel_id_seq OWNED BY grupo_responsavel.id;


--
-- TOC entry 207 (class 1259 OID 16944)
-- Name: hierarquia; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2576 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE hierarquia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE hierarquia IS 'Tabela com as hierarquia';


--
-- TOC entry 2577 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN hierarquia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.id IS 'Identificador das hierarquia';


--
-- TOC entry 2578 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN hierarquia.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.nome IS 'Nome da hierarquia';


--
-- TOC entry 2579 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN hierarquia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.data_criacao IS 'Data de criação da hierarquia';


--
-- TOC entry 2580 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN hierarquia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.hora_criacao IS 'Hora de criação da hierarquia';


--
-- TOC entry 2581 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN hierarquia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.data_inativacao IS 'Data de inativação da hierarquia';


--
-- TOC entry 2582 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN hierarquia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.hora_inativacao IS 'Hora da inativação';


--
-- TOC entry 206 (class 1259 OID 16942)
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
-- TOC entry 2583 (class 0 OID 0)
-- Dependencies: 206
-- Name: hierarquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE hierarquia_id_seq OWNED BY hierarquia.id;


--
-- TOC entry 171 (class 1259 OID 16388)
-- Name: pessoa; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2584 (class 0 OID 0)
-- Dependencies: 171
-- Name: TABLE pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa IS 'Tabela pessoa com dados pessoais';


--
-- TOC entry 2585 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.id IS 'Identificação da pessoa';


--
-- TOC entry 2586 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.nome IS 'Nome Completo da pessoa';


--
-- TOC entry 2587 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.email IS 'Email de acesso ';


--
-- TOC entry 2588 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.senha; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.senha IS 'Senha de acesso em MD5';


--
-- TOC entry 2589 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_criacao IS 'Data de criação da pessoa';


--
-- TOC entry 2590 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_inativacao IS 'Data de inativação da pessoa';


--
-- TOC entry 2591 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.documento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.documento IS 'Documento da pessoa, pode ser CPF ou caso estrangeiro um documento aberto';


--
-- TOC entry 2592 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.data_nascimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_nascimento IS 'Data de nascimento da pessoa';


--
-- TOC entry 2593 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.token; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token IS 'Token de recuperacao de senha';


--
-- TOC entry 2594 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.token_data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token_data IS 'Data para validação do token';


--
-- TOC entry 2595 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.token_hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token_hora IS 'Hora para validação do token';


--
-- TOC entry 2596 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.hora_criacao IS 'Hora de criação da pessoa';


--
-- TOC entry 2597 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.hora_inativacao IS 'Hora de inativação da pessoa';


--
-- TOC entry 2598 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.telefone; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.telefone IS 'Telefone com 9 digitos e DDD';


--
-- TOC entry 2599 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.foto; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.foto IS 'Nome do arquivo com a foto da pessoa';


--
-- TOC entry 2600 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.data_revisao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_revisao IS 'Data que a pessoa foi ao revisão de vidas';


--
-- TOC entry 2601 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.sexo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.sexo IS 'Sexo da pessoa';


--
-- TOC entry 2602 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN pessoa.atualizar_dados; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.atualizar_dados IS 'Variável para verificar se precisa de atualização de dados';


--
-- TOC entry 209 (class 1259 OID 16952)
-- Name: pessoa_hierarquia; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2603 (class 0 OID 0)
-- Dependencies: 209
-- Name: TABLE pessoa_hierarquia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa_hierarquia IS 'Tabela associativa da pessoa com a hierarquia';


--
-- TOC entry 2604 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.id IS 'Identificação da associação da pessoa com a hierarquia';


--
-- TOC entry 2605 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2606 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.hierarquia_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hierarquia_id IS 'Identificação da hierarquia';


--
-- TOC entry 2607 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.data_criacao IS 'Data de criação da associação pessoa com a hierarquia';


--
-- TOC entry 2608 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hora_criacao IS 'Hora de criação da associação pessoa com a hierarquia';


--
-- TOC entry 2609 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.data_inativacao IS 'Data da inativação da associação pessoa com a hierarquia';


--
-- TOC entry 2610 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN pessoa_hierarquia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hora_inativacao IS 'Hora da inativação da associativa pessoa com a hierarquia';


--
-- TOC entry 208 (class 1259 OID 16950)
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
-- TOC entry 2611 (class 0 OID 0)
-- Dependencies: 208
-- Name: pessoa_hierarquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_hierarquia_id_seq OWNED BY pessoa_hierarquia.id;


--
-- TOC entry 170 (class 1259 OID 16386)
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
-- TOC entry 2612 (class 0 OID 0)
-- Dependencies: 170
-- Name: pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_id_seq OWNED BY pessoa.id;


--
-- TOC entry 201 (class 1259 OID 16868)
-- Name: situacao; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2613 (class 0 OID 0)
-- Dependencies: 201
-- Name: TABLE situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE situacao IS 'Tabela com as situações do aluno';


--
-- TOC entry 2614 (class 0 OID 0)
-- Dependencies: 201
-- Name: COLUMN situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.id IS 'Identificação da situação';


--
-- TOC entry 2615 (class 0 OID 0)
-- Dependencies: 201
-- Name: COLUMN situacao.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.nome IS 'Nome da situação do aluno';


--
-- TOC entry 2616 (class 0 OID 0)
-- Dependencies: 201
-- Name: COLUMN situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.data_criacao IS 'Data de criação da situação';


--
-- TOC entry 2617 (class 0 OID 0)
-- Dependencies: 201
-- Name: COLUMN situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.hora_criacao IS 'Hora da criação da situação';


--
-- TOC entry 2618 (class 0 OID 0)
-- Dependencies: 201
-- Name: COLUMN situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.data_inativacao IS 'Data da inativação da situação';


--
-- TOC entry 2619 (class 0 OID 0)
-- Dependencies: 201
-- Name: COLUMN situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.hora_inativacao IS 'Hora da inativação da situação';


--
-- TOC entry 200 (class 1259 OID 16866)
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
-- TOC entry 2620 (class 0 OID 0)
-- Dependencies: 200
-- Name: situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE situacao_id_seq OWNED BY situacao.id;


--
-- TOC entry 197 (class 1259 OID 16817)
-- Name: turma; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2621 (class 0 OID 0)
-- Dependencies: 197
-- Name: TABLE turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma IS 'Tabela com os revisão de vidas ou turma do instituto de vencedores';


--
-- TOC entry 2622 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN turma.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.id IS 'Identificação da turma';


--
-- TOC entry 2623 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN turma.data_revisao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_revisao IS 'Data de inicio do revisão de vidas';


--
-- TOC entry 2624 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN turma.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_criacao IS 'Data de criação da turma';


--
-- TOC entry 2625 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN turma.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_criacao IS 'Hora de criação da turma';


--
-- TOC entry 2626 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN turma.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_inativacao IS 'Data de inativação da turma';


--
-- TOC entry 2627 (class 0 OID 0)
-- Dependencies: 197
-- Name: COLUMN turma.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_inativacao IS 'Hora de inativação da turma';


--
-- TOC entry 199 (class 1259 OID 16835)
-- Name: turma_aluno; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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
-- TOC entry 2628 (class 0 OID 0)
-- Dependencies: 199
-- Name: TABLE turma_aluno; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_aluno IS 'Associação entre a turma e os alunos';


--
-- TOC entry 2629 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.id IS 'Identificação da associação turma com o aluno';


--
-- TOC entry 2630 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.turma_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.turma_id IS 'Identificação da turma';


--
-- TOC entry 2631 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2632 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2633 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.hora_criacao IS 'Hora da criação da associação';


--
-- TOC entry 2634 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2635 (class 0 OID 0)
-- Dependencies: 199
-- Name: COLUMN turma_aluno.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 196 (class 1259 OID 16815)
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
-- TOC entry 2636 (class 0 OID 0)
-- Dependencies: 196
-- Name: turma_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_id_seq OWNED BY turma.id;


--
-- TOC entry 198 (class 1259 OID 16833)
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
-- TOC entry 2637 (class 0 OID 0)
-- Dependencies: 198
-- Name: turma_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_pessoa_id_seq OWNED BY turma_aluno.id;


--
-- TOC entry 2081 (class 2604 OID 16899)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao ALTER COLUMN id SET DEFAULT nextval('aluno_situacao_id_seq'::regclass);


--
-- TOC entry 2100 (class 2604 OID 17055)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao ALTER COLUMN id SET DEFAULT nextval('dimensao_id_seq'::regclass);


--
-- TOC entry 2107 (class 2604 OID 17081)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao_tipo ALTER COLUMN id SET DEFAULT nextval('dimensao_tipo_id_seq'::regclass);


--
-- TOC entry 2045 (class 2604 OID 16461)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade ALTER COLUMN id SET DEFAULT nextval('entidade_id_seq'::regclass);


--
-- TOC entry 2048 (class 2604 OID 16470)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade_tipo ALTER COLUMN id SET DEFAULT nextval('entidade_tipo_id_seq'::regclass);


--
-- TOC entry 2051 (class 2604 OID 16595)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento ALTER COLUMN id SET DEFAULT nextval('evento_id_seq'::regclass);


--
-- TOC entry 2057 (class 2604 OID 16643)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula ALTER COLUMN id SET DEFAULT nextval('evento_celula_id_seq'::regclass);


--
-- TOC entry 2067 (class 2604 OID 16750)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia ALTER COLUMN id SET DEFAULT nextval('evento_frequencia_id_seq'::regclass);


--
-- TOC entry 2054 (class 2604 OID 16623)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_tipo ALTER COLUMN id SET DEFAULT nextval('evento_tipo_id_seq'::regclass);


--
-- TOC entry 2114 (class 2604 OID 17408)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula ALTER COLUMN id SET DEFAULT nextval('fato_celula_id_seq'::regclass);


--
-- TOC entry 2097 (class 2604 OID 17016)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ciclo ALTER COLUMN id SET DEFAULT nextval('fato_ciclo_id_seq'::regclass);


--
-- TOC entry 2110 (class 2604 OID 17346)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_lider ALTER COLUMN id SET DEFAULT nextval('fato_lider_id_seq'::regclass);


--
-- TOC entry 2042 (class 2604 OID 16453)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo ALTER COLUMN id SET DEFAULT nextval('grupo_id_seq'::regclass);


--
-- TOC entry 2084 (class 2604 OID 16927)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno ALTER COLUMN id SET DEFAULT nextval('grupo_aluno_id_seq'::regclass);


--
-- TOC entry 2093 (class 2604 OID 16992)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento ALTER COLUMN id SET DEFAULT nextval('grupo_atendimento_id_seq'::regclass);


--
-- TOC entry 2118 (class 2604 OID 17744)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv ALTER COLUMN id SET DEFAULT nextval('grupo_cv_id_seq'::regclass);


--
-- TOC entry 2058 (class 2604 OID 16671)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento ALTER COLUMN id SET DEFAULT nextval('grupo_evento_id_seq'::regclass);


--
-- TOC entry 2039 (class 2604 OID 16443)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho ALTER COLUMN id SET DEFAULT nextval('grupo_pai_filho_id_seq'::regclass);


--
-- TOC entry 2061 (class 2604 OID 16713)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa ALTER COLUMN id SET DEFAULT nextval('grupo_pessoa_id_seq'::regclass);


--
-- TOC entry 2064 (class 2604 OID 16724)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa_tipo ALTER COLUMN id SET DEFAULT nextval('grupo_pessoa_tipo_id_seq'::regclass);


--
-- TOC entry 2036 (class 2604 OID 16435)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel ALTER COLUMN id SET DEFAULT nextval('grupo_responsavel_id_seq'::regclass);


--
-- TOC entry 2087 (class 2604 OID 16947)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hierarquia ALTER COLUMN id SET DEFAULT nextval('hierarquia_id_seq'::regclass);


--
-- TOC entry 2032 (class 2604 OID 16391)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa ALTER COLUMN id SET DEFAULT nextval('pessoa_id_seq'::regclass);


--
-- TOC entry 2092 (class 2604 OID 16970)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia ALTER COLUMN id SET DEFAULT nextval('pessoa_hierarquia_id_seq'::regclass);


--
-- TOC entry 2078 (class 2604 OID 16871)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY situacao ALTER COLUMN id SET DEFAULT nextval('situacao_id_seq'::regclass);


--
-- TOC entry 2072 (class 2604 OID 16820)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma ALTER COLUMN id SET DEFAULT nextval('turma_id_seq'::regclass);


--
-- TOC entry 2075 (class 2604 OID 16838)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno ALTER COLUMN id SET DEFAULT nextval('turma_pessoa_id_seq'::regclass);


--
-- TOC entry 2346 (class 0 OID 16874)
-- Dependencies: 202
-- Data for Name: aluno_situacao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY aluno_situacao (id, situacao_id, turma_aluno_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	1	1	2017-01-18	11:15:28.531563	\N	\N
2	1	2	2017-01-20	13:58:52.782916	\N	\N
\.


--
-- TOC entry 2638 (class 0 OID 0)
-- Dependencies: 203
-- Name: aluno_situacao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('aluno_situacao_id_seq', 1, true);


--
-- TOC entry 2359 (class 0 OID 17052)
-- Dependencies: 215
-- Data for Name: dimensao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY dimensao (id, fato_ciclo_id, dimensao_tipo_id, visitante, consolidacao, membro, data_criacao, hora_criacao, data_inativacao, hora_inativacao, lider) FROM stdin;
4122	1032	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4123	1032	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4124	1032	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4125	1032	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4126	1033	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4127	1033	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4128	1033	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4129	1033	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4130	1034	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4131	1034	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4132	1034	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4133	1034	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4134	1035	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4135	1035	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4136	1035	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4137	1035	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4138	1036	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4139	1036	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4140	1036	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4141	1036	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4142	1037	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4143	1037	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4144	1037	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4145	1037	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4146	1038	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4147	1038	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4148	1038	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4149	1038	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4150	1039	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4151	1039	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4152	1039	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4153	1039	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4154	1040	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4155	1040	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4156	1040	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4157	1040	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4158	1041	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4159	1041	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4160	1041	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4161	1041	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4162	1042	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4163	1042	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4164	1042	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4165	1042	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4166	1043	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4167	1043	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4168	1043	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4169	1043	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4170	1044	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4171	1044	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4172	1044	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4173	1044	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4174	1045	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4175	1045	2	0	0	0	2017-05-08	15:42:20	\N	\N	0
4176	1045	3	0	0	0	2017-05-08	15:42:20	\N	\N	0
4177	1045	4	0	0	0	2017-05-08	15:42:20	\N	\N	0
4178	1046	1	0	0	0	2017-05-08	15:42:20	\N	\N	0
4179	1046	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4180	1046	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4181	1046	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4182	1047	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4183	1047	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4184	1047	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4185	1047	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4186	1048	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4187	1048	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4188	1048	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4189	1048	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4190	1049	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4191	1049	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4192	1049	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4193	1049	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4194	1050	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4195	1050	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4196	1050	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4197	1050	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4198	1051	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4199	1051	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4200	1051	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4201	1051	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4202	1052	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4203	1052	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4204	1052	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4205	1052	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4206	1053	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4208	1053	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4209	1053	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4210	1054	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4211	1054	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4212	1054	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4213	1054	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4214	1055	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4215	1055	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4216	1055	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4217	1055	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4218	1056	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4219	1056	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4220	1056	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4221	1056	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4222	1057	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4223	1057	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4224	1057	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4225	1057	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4226	1058	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4227	1058	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4228	1058	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4229	1058	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4230	1059	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4231	1059	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4232	1059	3	0	0	0	2017-05-08	15:43:20	\N	\N	0
4233	1059	4	0	0	0	2017-05-08	15:43:20	\N	\N	0
4234	1060	1	0	0	0	2017-05-08	15:43:20	\N	\N	0
4235	1060	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4236	1060	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4237	1060	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4238	1061	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4239	1061	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4240	1061	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4241	1061	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4242	1062	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4243	1062	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4244	1062	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4245	1062	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4246	1063	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4247	1063	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4248	1063	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4249	1063	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4250	1064	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4251	1064	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4252	1064	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4253	1064	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4254	1065	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4255	1065	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4256	1065	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4257	1065	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4258	1066	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4259	1066	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4260	1066	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4261	1066	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4262	1067	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4263	1067	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4264	1067	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4265	1067	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4266	1068	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4267	1068	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4268	1068	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4269	1068	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4270	1069	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4271	1069	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4272	1069	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4273	1069	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4274	1070	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4275	1070	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4276	1070	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4277	1070	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4278	1071	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4279	1071	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4280	1071	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4281	1071	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4282	1072	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4283	1072	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4284	1072	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4285	1072	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4286	1073	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4287	1073	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4288	1073	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4289	1073	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4290	1074	1	0	0	0	2017-05-08	15:44:20	\N	\N	0
4291	1074	2	0	0	0	2017-05-08	15:44:20	\N	\N	0
4292	1074	3	0	0	0	2017-05-08	15:44:20	\N	\N	0
4293	1074	4	0	0	0	2017-05-08	15:44:20	\N	\N	0
4294	1075	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4295	1075	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4296	1075	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4297	1075	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4298	1076	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4299	1076	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4300	1076	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4301	1076	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4302	1077	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4303	1077	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4304	1077	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4305	1077	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4306	1078	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4307	1078	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4308	1078	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4309	1078	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4310	1079	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4311	1079	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4312	1079	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4313	1079	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4314	1080	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4315	1080	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4316	1080	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4317	1080	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4318	1081	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4319	1081	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4320	1081	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4321	1081	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4322	1082	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4323	1082	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4324	1082	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4325	1082	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4326	1083	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4327	1083	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4328	1083	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4329	1083	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4330	1084	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4331	1084	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4332	1084	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4333	1084	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4334	1085	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4335	1085	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4336	1085	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4337	1085	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4338	1086	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4339	1086	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4340	1086	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4341	1086	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4342	1087	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4343	1087	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4344	1087	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4345	1087	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4346	1088	1	0	0	0	2017-05-08	15:45:20	\N	\N	0
4347	1088	2	0	0	0	2017-05-08	15:45:20	\N	\N	0
4348	1088	3	0	0	0	2017-05-08	15:45:20	\N	\N	0
4349	1088	4	0	0	0	2017-05-08	15:45:20	\N	\N	0
4350	1089	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4351	1089	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4352	1089	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4353	1089	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4354	1090	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4355	1090	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4356	1090	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4357	1090	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4358	1091	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4359	1091	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4360	1091	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4361	1091	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4366	1093	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4367	1093	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4368	1093	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4369	1093	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4370	1094	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4371	1094	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4372	1094	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4373	1094	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4374	1095	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4375	1095	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4376	1095	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4377	1095	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4378	1096	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4379	1096	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4380	1096	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4381	1096	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4382	1097	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4383	1097	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4384	1097	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4385	1097	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4386	1098	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4387	1098	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4388	1098	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4389	1098	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4390	1099	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4391	1099	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4392	1099	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4393	1099	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4394	1100	1	0	0	0	2017-05-08	15:46:20	\N	\N	0
4395	1100	2	0	0	0	2017-05-08	15:46:20	\N	\N	0
4396	1100	3	0	0	0	2017-05-08	15:46:20	\N	\N	0
4397	1100	4	0	0	0	2017-05-08	15:46:20	\N	\N	0
4398	1101	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4399	1101	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4400	1101	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4401	1101	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4402	1102	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4403	1102	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4404	1102	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4405	1102	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4406	1103	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4407	1103	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4408	1103	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4409	1103	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4410	1104	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4411	1104	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4412	1104	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4363	1092	2	0	0	0	2017-05-08	15:46:20	\N	\N	2
4365	1092	4	0	2	1	2017-05-08	15:46:20	\N	\N	2
4364	1092	3	0	2	0	2017-05-08	15:46:20	\N	\N	2
4413	1104	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4414	1105	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4415	1105	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4416	1105	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4417	1105	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4418	1106	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4419	1106	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4420	1106	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4421	1106	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4422	1107	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4423	1107	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4424	1107	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4425	1107	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4426	1108	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4427	1108	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4428	1108	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4429	1108	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4430	1109	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4431	1109	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4432	1109	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4433	1109	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4434	1110	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4435	1110	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4436	1110	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4437	1110	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4438	1111	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4439	1111	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4440	1111	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4441	1111	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4442	1112	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4443	1112	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4444	1112	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4445	1112	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4446	1113	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4447	1113	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4448	1113	3	0	0	0	2017-05-08	15:47:20	\N	\N	0
4449	1113	4	0	0	0	2017-05-08	15:47:20	\N	\N	0
4450	1114	1	0	0	0	2017-05-08	15:47:20	\N	\N	0
4451	1114	2	0	0	0	2017-05-08	15:47:20	\N	\N	0
4452	1114	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4453	1114	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4454	1115	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4455	1115	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4456	1115	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4457	1115	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4458	1116	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4459	1116	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4460	1116	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4461	1116	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4462	1117	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4463	1117	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4464	1117	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4465	1117	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4466	1118	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4467	1118	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4468	1118	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4469	1118	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4470	1119	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4471	1119	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4472	1119	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4473	1119	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4474	1120	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4475	1120	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4476	1120	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4477	1120	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4478	1121	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4479	1121	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4480	1121	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4481	1121	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4482	1122	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4483	1122	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4484	1122	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4485	1122	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4486	1123	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4487	1123	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4488	1123	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4489	1123	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4490	1124	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4491	1124	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4492	1124	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4493	1124	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4494	1125	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4495	1125	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4496	1125	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4497	1125	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4498	1126	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4499	1126	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4500	1126	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4501	1126	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4502	1127	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4503	1127	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4504	1127	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4505	1127	4	0	0	0	2017-05-08	15:48:20	\N	\N	0
4506	1128	1	0	0	0	2017-05-08	15:48:20	\N	\N	0
4507	1128	2	0	0	0	2017-05-08	15:48:20	\N	\N	0
4508	1128	3	0	0	0	2017-05-08	15:48:20	\N	\N	0
4509	1128	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4510	1129	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4511	1129	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4512	1129	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4513	1129	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4514	1130	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4515	1130	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4516	1130	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4517	1130	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4518	1131	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4519	1131	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4520	1131	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4521	1131	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4522	1132	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4523	1132	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4524	1132	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4525	1132	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4526	1133	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4527	1133	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4528	1133	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4529	1133	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4530	1134	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4531	1134	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4532	1134	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4533	1134	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4534	1135	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4535	1135	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4536	1135	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4537	1135	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4538	1136	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4539	1136	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4540	1136	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4541	1136	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4542	1137	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4543	1137	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4544	1137	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4545	1137	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4546	1138	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4547	1138	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4548	1138	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4549	1138	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4550	1139	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4551	1139	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4552	1139	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4553	1139	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4554	1140	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4555	1140	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4556	1140	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4557	1140	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4558	1141	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4559	1141	2	0	0	0	2017-05-08	15:49:20	\N	\N	0
4560	1141	3	0	0	0	2017-05-08	15:49:20	\N	\N	0
4561	1141	4	0	0	0	2017-05-08	15:49:20	\N	\N	0
4562	1142	1	0	0	0	2017-05-08	15:49:20	\N	\N	0
4563	1142	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4564	1142	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4565	1142	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4566	1143	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4567	1143	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4568	1143	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4569	1143	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4570	1144	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4571	1144	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4572	1144	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4573	1144	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4574	1145	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4575	1145	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4576	1145	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4577	1145	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4578	1146	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4579	1146	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4580	1146	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4581	1146	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4582	1147	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4583	1147	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4584	1147	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4585	1147	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4586	1148	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4587	1148	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4588	1148	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4589	1148	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4590	1149	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4591	1149	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4592	1149	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4593	1149	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4594	1150	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4595	1150	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4596	1150	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4597	1150	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4598	1151	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4599	1151	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4600	1151	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4601	1151	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4602	1152	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4603	1152	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4604	1152	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4605	1152	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4606	1153	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4607	1153	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4608	1153	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4609	1153	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4610	1154	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4611	1154	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4612	1154	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4613	1154	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4614	1155	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4615	1155	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4616	1155	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4617	1155	4	0	0	0	2017-05-08	15:50:20	\N	\N	0
4618	1156	1	0	0	0	2017-05-08	15:50:20	\N	\N	0
4619	1156	2	0	0	0	2017-05-08	15:50:20	\N	\N	0
4620	1156	3	0	0	0	2017-05-08	15:50:20	\N	\N	0
4621	1156	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4622	1157	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4623	1157	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4624	1157	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4625	1157	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4626	1158	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4627	1158	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4628	1158	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4629	1158	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4630	1159	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4631	1159	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4632	1159	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4633	1159	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4634	1160	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4635	1160	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4636	1160	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4637	1160	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4638	1161	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4639	1161	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4640	1161	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4641	1161	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4642	1162	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4643	1162	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4644	1162	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4645	1162	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4646	1163	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4647	1163	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4648	1163	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4649	1163	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4650	1164	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4651	1164	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4652	1164	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4653	1164	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4654	1165	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4655	1165	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4656	1165	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4657	1165	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4658	1166	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4659	1166	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4660	1166	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4661	1166	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4662	1167	1	0	0	0	2017-05-08	15:51:20	\N	\N	0
4663	1167	2	0	0	0	2017-05-08	15:51:20	\N	\N	0
4664	1167	3	0	0	0	2017-05-08	15:51:20	\N	\N	0
4665	1167	4	0	0	0	2017-05-08	15:51:20	\N	\N	0
4666	1168	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4667	1168	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4668	1168	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4669	1168	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4670	1169	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4671	1169	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4672	1169	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4673	1169	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4674	1170	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4675	1170	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4676	1170	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4677	1170	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4678	1171	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4679	1171	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4680	1171	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4681	1171	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4682	1172	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4683	1172	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4684	1172	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4685	1172	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4686	1173	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4687	1173	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4688	1173	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4689	1173	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4690	1174	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4691	1174	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4692	1174	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4693	1174	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4694	1175	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4695	1175	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4696	1175	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4697	1175	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4698	1176	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4699	1176	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4700	1176	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4701	1176	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4702	1177	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4703	1177	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4704	1177	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4705	1177	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4706	1178	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4707	1178	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4708	1178	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4709	1178	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4710	1179	1	0	0	0	2017-05-08	15:52:20	\N	\N	0
4711	1179	2	0	0	0	2017-05-08	15:52:20	\N	\N	0
4712	1179	3	0	0	0	2017-05-08	15:52:20	\N	\N	0
4713	1179	4	0	0	0	2017-05-08	15:52:20	\N	\N	0
4714	1180	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4715	1180	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4716	1180	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4717	1180	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4718	1181	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4719	1181	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4720	1181	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4721	1181	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4722	1182	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4723	1182	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4724	1182	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4725	1182	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4726	1183	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4727	1183	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4728	1183	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4729	1183	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4730	1184	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4731	1184	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4732	1184	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4733	1184	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4738	1186	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4739	1186	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4740	1186	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4741	1186	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4742	1187	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4743	1187	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4744	1187	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4745	1187	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4746	1188	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4747	1188	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4748	1188	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4749	1188	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4750	1189	1	0	0	0	2017-05-08	15:53:20	\N	\N	0
4751	1189	2	0	0	0	2017-05-08	15:53:20	\N	\N	0
4752	1189	3	0	0	0	2017-05-08	15:53:20	\N	\N	0
4753	1189	4	0	0	0	2017-05-08	15:53:20	\N	\N	0
4754	1190	1	0	0	0	2017-05-08	15:19:21	\N	\N	0
4755	1190	2	0	0	0	2017-05-08	15:19:21	\N	\N	0
4756	1190	3	0	0	0	2017-05-08	15:19:21	\N	\N	0
4757	1190	4	0	0	0	2017-05-08	15:19:21	\N	\N	0
4758	1191	1	0	0	0	2017-05-08	15:19:21	\N	\N	0
4759	1191	2	0	0	0	2017-05-08	15:19:21	\N	\N	0
4760	1191	3	0	0	0	2017-05-08	15:19:21	\N	\N	0
4761	1191	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4762	1192	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4763	1192	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4764	1192	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4765	1192	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4766	1193	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4767	1193	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4768	1193	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4769	1193	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4770	1194	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4771	1194	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4772	1194	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4773	1194	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4774	1195	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4775	1195	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4776	1195	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4777	1195	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4778	1196	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4779	1196	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4780	1196	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4781	1196	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4782	1197	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4783	1197	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4784	1197	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4785	1197	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4786	1198	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4787	1198	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4788	1198	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4789	1198	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4790	1199	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4791	1199	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4792	1199	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4793	1199	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4794	1200	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4795	1200	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4796	1200	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4797	1200	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4798	1201	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4799	1201	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4800	1201	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4736	1185	3	0	1	0	2017-05-08	15:53:20	\N	\N	2
4737	1185	4	0	1	0	2017-05-08	15:53:20	\N	\N	2
4734	1185	1	0	2	0	2017-05-08	15:53:20	\N	\N	4
4801	1201	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4802	1202	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4803	1202	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4804	1202	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4805	1202	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4806	1203	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4807	1203	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4808	1203	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4809	1203	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4810	1204	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4811	1204	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4812	1204	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4813	1204	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4814	1205	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4815	1205	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4816	1205	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4817	1205	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4818	1206	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4819	1206	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4820	1206	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4821	1206	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4822	1207	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4823	1207	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4824	1207	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4825	1207	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4826	1208	1	0	0	0	2017-05-08	15:20:21	\N	\N	0
4827	1208	2	0	0	0	2017-05-08	15:20:21	\N	\N	0
4828	1208	3	0	0	0	2017-05-08	15:20:21	\N	\N	0
4829	1208	4	0	0	0	2017-05-08	15:20:21	\N	\N	0
4830	1209	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4831	1209	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4832	1209	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4833	1209	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4834	1210	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4835	1210	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4836	1210	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4837	1210	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4842	1212	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4843	1212	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4844	1212	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4845	1212	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4846	1213	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4847	1213	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4848	1213	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4849	1213	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4850	1214	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4851	1214	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4852	1214	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4853	1214	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4854	1215	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4855	1215	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4856	1215	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4857	1215	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4858	1216	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4859	1216	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4860	1216	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4861	1216	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4862	1217	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4863	1217	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4864	1217	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4865	1217	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4866	1218	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4867	1218	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4868	1218	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4869	1218	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4870	1219	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4871	1219	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4872	1219	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4873	1219	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4874	1220	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4875	1220	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4876	1220	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4877	1220	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4878	1221	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4879	1221	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4880	1221	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4881	1221	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4882	1222	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4883	1222	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4884	1222	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4885	1222	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4886	1223	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4887	1223	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4888	1223	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4889	1223	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4890	1224	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4891	1224	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4892	1224	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4893	1224	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4894	1225	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4895	1225	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4896	1225	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4897	1225	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4840	1211	3	0	0	2	2017-05-08	15:21:21	\N	\N	0
4838	1211	1	0	0	2	2017-05-08	15:21:21	\N	\N	0
4841	1211	4	0	0	2	2017-05-08	15:21:21	\N	\N	0
4898	1226	1	0	0	0	2017-05-08	15:21:21	\N	\N	0
4899	1226	2	0	0	0	2017-05-08	15:21:21	\N	\N	0
4900	1226	3	0	0	0	2017-05-08	15:21:21	\N	\N	0
4901	1226	4	0	0	0	2017-05-08	15:21:21	\N	\N	0
4902	1227	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4903	1227	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4904	1227	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4905	1227	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4906	1228	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4907	1228	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4908	1228	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4909	1228	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4910	1229	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4911	1229	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4912	1229	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4913	1229	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4914	1230	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4915	1230	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4916	1230	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4917	1230	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4918	1231	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4919	1231	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4920	1231	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4921	1231	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4922	1232	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4923	1232	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4924	1232	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4925	1232	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4926	1233	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4927	1233	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4928	1233	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4929	1233	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4930	1234	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4931	1234	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4932	1234	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4933	1234	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4934	1235	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4935	1235	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4936	1235	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4937	1235	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4938	1236	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4939	1236	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4940	1236	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4941	1236	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4942	1237	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4943	1237	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4944	1237	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4945	1237	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4946	1238	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4947	1238	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4948	1238	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4949	1238	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4950	1239	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4951	1239	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4952	1239	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4953	1239	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4954	1240	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4955	1240	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4956	1240	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4957	1240	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4958	1241	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4959	1241	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4960	1241	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4961	1241	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4962	1242	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4963	1242	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4964	1242	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4965	1242	4	0	0	0	2017-05-08	15:22:21	\N	\N	0
4966	1243	1	0	0	0	2017-05-08	15:22:21	\N	\N	0
4967	1243	2	0	0	0	2017-05-08	15:22:21	\N	\N	0
4968	1243	3	0	0	0	2017-05-08	15:22:21	\N	\N	0
4969	1243	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4970	1244	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4971	1244	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
4972	1244	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
4973	1244	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4974	1245	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4975	1245	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
4976	1245	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
4977	1245	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4978	1246	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4979	1246	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
4980	1246	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
4981	1246	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4982	1247	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4983	1247	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
4984	1247	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
4985	1247	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4986	1248	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4987	1248	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
4988	1248	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
4989	1248	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4990	1249	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4991	1249	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
4992	1249	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
4993	1249	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
4998	1251	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
4999	1251	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5000	1251	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5001	1251	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5002	1252	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5003	1252	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5004	1252	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5005	1252	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5006	1253	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5007	1253	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5008	1253	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5009	1253	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5010	1254	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5011	1254	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5012	1254	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5013	1254	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5014	1255	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5015	1255	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5016	1255	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5017	1255	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5018	1256	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5019	1256	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5020	1256	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5021	1256	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5022	1257	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5023	1257	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5024	1257	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5025	1257	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5026	1258	1	0	0	0	2017-05-08	15:23:21	\N	\N	0
5027	1258	2	0	0	0	2017-05-08	15:23:21	\N	\N	0
5028	1258	3	0	0	0	2017-05-08	15:23:21	\N	\N	0
5029	1258	4	0	0	0	2017-05-08	15:23:21	\N	\N	0
5030	1259	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5031	1259	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5032	1259	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5033	1259	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5034	1260	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5035	1260	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5036	1260	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5037	1260	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5038	1261	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5039	1261	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5040	1261	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5041	1261	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5042	1262	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5043	1262	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5044	1262	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5045	1262	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5046	1263	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5047	1263	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5048	1263	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5049	1263	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5050	1264	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5051	1264	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5052	1264	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5053	1264	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5054	1265	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5055	1265	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5056	1265	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5057	1265	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5058	1266	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5059	1266	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5060	1266	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5061	1266	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5062	1267	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5063	1267	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5064	1267	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5065	1267	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5066	1268	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5067	1268	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5068	1268	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5069	1268	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5070	1269	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5071	1269	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5072	1269	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5073	1269	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5074	1270	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5075	1270	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5076	1270	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5077	1270	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5078	1271	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5079	1271	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5080	1271	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5081	1271	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5082	1272	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5083	1272	2	0	0	0	2017-05-08	15:24:21	\N	\N	0
5084	1272	3	0	0	0	2017-05-08	15:24:21	\N	\N	0
5085	1272	4	0	0	0	2017-05-08	15:24:21	\N	\N	0
5086	1273	1	0	0	0	2017-05-08	15:24:21	\N	\N	0
5087	1273	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5088	1273	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5089	1273	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5090	1274	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5091	1274	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
4996	1250	3	0	1	0	2017-05-08	15:23:21	\N	\N	2
4997	1250	4	0	0	1	2017-05-08	15:23:21	\N	\N	2
5092	1274	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5093	1274	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5094	1275	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5095	1275	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5096	1275	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5097	1275	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5098	1276	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5099	1276	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5100	1276	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5101	1276	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5102	1277	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5103	1277	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5104	1277	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5105	1277	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5106	1278	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5107	1278	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5108	1278	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5109	1278	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5110	1279	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5111	1279	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5112	1279	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5113	1279	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5114	1280	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5115	1280	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5116	1280	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5117	1280	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5118	1281	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5119	1281	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5120	1281	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5121	1281	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5122	1282	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5123	1282	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5124	1282	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5125	1282	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5126	1283	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5127	1283	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5128	1283	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5129	1283	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5130	1284	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5131	1284	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5132	1284	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5133	1284	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5134	1285	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5135	1285	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5136	1285	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5137	1285	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5138	1286	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5139	1286	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5140	1286	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5141	1286	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5142	1287	1	0	0	0	2017-05-08	15:25:21	\N	\N	0
5143	1287	2	0	0	0	2017-05-08	15:25:21	\N	\N	0
5144	1287	3	0	0	0	2017-05-08	15:25:21	\N	\N	0
5145	1287	4	0	0	0	2017-05-08	15:25:21	\N	\N	0
5146	1288	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5147	1288	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5148	1288	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5149	1288	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5150	1289	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5151	1289	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5152	1289	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5153	1289	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5154	1290	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5155	1290	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5156	1290	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5157	1290	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5158	1291	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5159	1291	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5160	1291	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5161	1291	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5162	1292	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5163	1292	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5164	1292	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5165	1292	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5166	1293	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5167	1293	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5168	1293	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5169	1293	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5170	1294	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5171	1294	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5172	1294	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5173	1294	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5174	1295	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5175	1295	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5176	1295	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5177	1295	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5178	1296	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5179	1296	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5180	1296	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5181	1296	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5182	1297	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5183	1297	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5184	1297	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5185	1297	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5186	1298	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5187	1298	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5188	1298	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5189	1298	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5190	1299	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5191	1299	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5192	1299	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5193	1299	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5194	1300	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5195	1300	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5196	1300	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5197	1300	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5198	1301	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5199	1301	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5200	1301	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5201	1301	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5202	1302	1	0	0	0	2017-05-08	15:26:21	\N	\N	0
5203	1302	2	0	0	0	2017-05-08	15:26:21	\N	\N	0
5204	1302	3	0	0	0	2017-05-08	15:26:21	\N	\N	0
5205	1302	4	0	0	0	2017-05-08	15:26:21	\N	\N	0
5206	1303	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5207	1303	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5208	1303	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5209	1303	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5210	1304	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5211	1304	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5212	1304	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5213	1304	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5214	1305	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5215	1305	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5216	1305	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5217	1305	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5218	1306	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5219	1306	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5220	1306	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5221	1306	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5222	1307	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5223	1307	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5224	1307	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5225	1307	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5226	1308	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5227	1308	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5228	1308	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5229	1308	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5230	1309	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5231	1309	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5232	1309	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5233	1309	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5234	1310	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5235	1310	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5236	1310	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5237	1310	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5238	1311	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5239	1311	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5240	1311	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5241	1311	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5242	1312	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5243	1312	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5244	1312	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5245	1312	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5246	1313	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5247	1313	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5248	1313	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5249	1313	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5250	1314	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5251	1314	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5252	1314	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5253	1314	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5254	1315	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5255	1315	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5256	1315	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5257	1315	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5258	1316	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5259	1316	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5260	1316	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5261	1316	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5262	1317	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5263	1317	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5264	1317	3	0	0	0	2017-05-08	15:27:21	\N	\N	0
5265	1317	4	0	0	0	2017-05-08	15:27:21	\N	\N	0
5266	1318	1	0	0	0	2017-05-08	15:27:21	\N	\N	0
5267	1318	2	0	0	0	2017-05-08	15:27:21	\N	\N	0
5268	1318	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5269	1318	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5270	1319	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5271	1319	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5272	1319	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5273	1319	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5274	1320	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5275	1320	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5276	1320	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5277	1320	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5278	1321	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5279	1321	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5280	1321	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5281	1321	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5282	1322	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5283	1322	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5284	1322	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5285	1322	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5286	1323	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5287	1323	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5288	1323	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5289	1323	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5290	1324	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5291	1324	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5292	1324	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5293	1324	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5294	1325	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5295	1325	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5296	1325	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5297	1325	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5298	1326	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5299	1326	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5300	1326	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5301	1326	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5302	1327	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5303	1327	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5304	1327	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5305	1327	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5306	1328	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5307	1328	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5308	1328	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5309	1328	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5310	1329	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5311	1329	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5312	1329	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5313	1329	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5314	1330	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5315	1330	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5316	1330	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5317	1330	4	0	0	0	2017-05-08	15:28:21	\N	\N	0
5318	1331	1	0	0	0	2017-05-08	15:28:21	\N	\N	0
5319	1331	2	0	0	0	2017-05-08	15:28:21	\N	\N	0
5320	1331	3	0	0	0	2017-05-08	15:28:21	\N	\N	0
5321	1331	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5322	1332	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5323	1332	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5324	1332	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5325	1332	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5326	1333	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5327	1333	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5328	1333	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5329	1333	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5330	1334	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5331	1334	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5332	1334	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5333	1334	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5334	1335	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5335	1335	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5336	1335	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5337	1335	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5338	1336	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5339	1336	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5340	1336	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5341	1336	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5342	1337	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5343	1337	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5344	1337	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5345	1337	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5346	1338	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5347	1338	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5348	1338	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5349	1338	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5350	1339	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5351	1339	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5352	1339	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5353	1339	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5354	1340	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5355	1340	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5356	1340	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5357	1340	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5358	1341	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5359	1341	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5360	1341	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5361	1341	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5362	1342	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5363	1342	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5364	1342	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5365	1342	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5370	1344	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5371	1344	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5372	1344	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5373	1344	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5374	1345	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5375	1345	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5376	1345	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5377	1345	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5378	1346	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5379	1346	2	0	0	0	2017-05-08	15:29:21	\N	\N	0
5380	1346	3	0	0	0	2017-05-08	15:29:21	\N	\N	0
5381	1346	4	0	0	0	2017-05-08	15:29:21	\N	\N	0
5382	1347	1	0	0	0	2017-05-08	15:29:21	\N	\N	0
5368	1343	3	0	1	0	2017-05-08	15:29:21	\N	\N	2
5366	1343	1	0	2	0	2017-05-08	15:29:21	\N	\N	4
5369	1343	4	0	1	0	2017-05-08	15:29:21	\N	\N	2
5383	1347	2	0	0	0	2017-05-08	15:30:21	\N	\N	0
5384	1347	3	0	0	0	2017-05-08	15:30:21	\N	\N	0
5385	1347	4	0	0	0	2017-05-08	15:30:21	\N	\N	0
4839	1211	2	0	0	3	2017-05-08	15:21:21	\N	\N	0
5386	1348	1	0	0	0	2017-05-16	08:14:59	\N	\N	0
5388	1348	3	0	0	0	2017-05-16	08:14:59	\N	\N	0
5389	1348	4	0	0	0	2017-05-16	08:14:59	\N	\N	0
5387	1348	2	0	49	0	2017-05-16	08:14:59	\N	\N	2
5394	1350	1	0	0	0	2017-05-16	18:49:55	\N	\N	0
5396	1350	3	0	0	0	2017-05-16	18:49:55	\N	\N	0
5397	1350	4	0	0	0	2017-05-16	18:49:55	\N	\N	0
5395	1350	2	0	1	1	2017-05-16	18:49:55	\N	\N	2
5390	1349	1	0	0	0	2017-05-16	10:14:28	\N	\N	0
5392	1349	3	0	0	0	2017-05-16	10:14:28	\N	\N	0
5393	1349	4	0	0	0	2017-05-16	10:14:28	\N	\N	0
4362	1092	1	0	2	0	2017-05-08	15:46:20	\N	\N	2
5391	1349	2	0	0	0	2017-05-16	10:14:28	\N	\N	0
4735	1185	2	0	1	0	2017-05-08	15:53:20	\N	\N	2
5367	1343	2	0	4	0	2017-05-08	15:29:21	\N	\N	2
4995	1250	2	0	0	0	2017-05-08	15:23:21	\N	\N	2
4207	1053	2	0	0	0	2017-05-08	15:43:20	\N	\N	0
4994	1250	1	1	1	0	2017-05-08	15:23:21	\N	\N	2
\.


--
-- TOC entry 2639 (class 0 OID 0)
-- Dependencies: 214
-- Name: dimensao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('dimensao_id_seq', 5397, true);


--
-- TOC entry 2361 (class 0 OID 17078)
-- Dependencies: 217
-- Data for Name: dimensao_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY dimensao_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	CELULA	2017-01-31	14:26:06.077929	\N	\N
2	CULTO	2017-01-31	14:26:12.678174	\N	\N
3	ARENA	2017-01-31	14:26:17.535224	\N	\N
4	DOMINGO	2017-01-31	14:26:21.580287	\N	\N
\.


--
-- TOC entry 2640 (class 0 OID 0)
-- Dependencies: 216
-- Name: dimensao_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('dimensao_tipo_id_seq', 1, false);


--
-- TOC entry 2323 (class 0 OID 16458)
-- Dependencies: 179
-- Data for Name: entidade; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY entidade (id, data_criacao, hora_criacao, nome, numero, data_inativacao, hora_inativacao, tipo_id, grupo_id) FROM stdin;
1	2017-01-17	10:47:47.767227	PRESIDENTE	\N	\N	\N	1	1
2	2017-01-17	16:23:26.312022	NACIONAL	\N	\N	\N	2	2
3	2017-01-17	16:23:44.309776	REGIONAL	\N	\N	\N	3	3
4	2017-01-17	16:23:58.033194	COORDENADOR	\N	\N	\N	4	4
5	2017-01-17	16:24:10.785414	IGREJA CEI	\N	\N	\N	5	5
6	2017-01-17	16:24:20.842215	EQUIPE I	\N	\N	\N	6	6
7	2017-01-17	16:24:30.393427	SUB	5	\N	\N	7	7
515	2017-05-01	15:34:04	CEILANDIA CENTRO	\N	\N	\N	5	515
516	2017-05-01	15:34:04	TIME DAS 18	\N	\N	\N	6	516
517	2017-05-01	15:34:04	\N	1	\N	\N	7	517
518	2017-05-01	15:34:04	\N	5	\N	\N	7	518
519	2017-05-01	15:34:04	\N	-1	\N	\N	7	519
520	2017-05-01	15:34:04	\N	-4	\N	\N	7	520
521	2017-05-01	15:34:04	\N	-2	\N	\N	7	521
522	2017-05-01	15:34:04	\N	2	\N	\N	7	522
523	2017-05-01	15:35:04	\N	-3	\N	\N	7	523
524	2017-05-01	15:35:04	\N	-4	\N	\N	7	524
525	2017-05-01	15:35:04	\N	5	\N	\N	7	525
526	2017-05-01	15:35:04	\N	3	\N	\N	7	526
527	2017-05-01	15:35:04	\N	-7	\N	\N	7	527
528	2017-05-01	15:35:04	\N	11	\N	\N	7	528
529	2017-05-01	15:35:04	\N	12	\N	\N	7	529
530	2017-05-01	15:36:04	\N	-2	\N	\N	7	530
531	2017-05-01	15:36:04	\N	-1	\N	\N	7	531
532	2017-05-01	15:36:04	\N	-3	\N	\N	7	532
533	2017-05-01	15:36:04	\N	-4	\N	\N	7	533
534	2017-05-01	15:36:04	\N	-6	\N	\N	7	534
535	2017-05-01	15:36:04	\N	-5	\N	\N	7	535
536	2017-05-01	15:36:04	\N	16	\N	\N	7	536
537	2017-05-01	15:36:04	\N	4	\N	\N	7	537
538	2017-05-01	15:36:04	\N	1	\N	\N	7	538
539	2017-05-01	15:36:04	\N	3	\N	\N	7	539
540	2017-05-01	15:36:04	\N	2	\N	\N	7	540
541	2017-05-01	15:36:04	\N	8	\N	\N	7	541
542	2017-05-01	15:36:04	\N	-9	\N	\N	7	542
543	2017-05-01	15:37:04	\N	6	\N	\N	7	543
544	2017-05-01	15:37:04	\N	-2	\N	\N	7	544
545	2017-05-01	15:37:04	\N	10	\N	\N	7	545
546	2017-05-01	15:37:04	\N	7	\N	\N	7	546
547	2017-05-01	15:37:04	\N	9	\N	\N	7	547
548	2017-05-01	15:37:04	\N	2	\N	\N	7	548
549	2017-05-01	15:37:04	\N	13	\N	\N	7	549
550	2017-05-01	15:37:04	\N	14	\N	\N	7	550
551	2017-05-01	15:38:04	\N	5	\N	\N	7	551
552	2017-05-01	15:38:04	\N	10	\N	\N	7	552
553	2017-05-01	15:38:04	\N	12	\N	\N	7	553
554	2017-05-01	15:38:04	\N	1	\N	\N	7	554
555	2017-05-01	15:38:04	\N	4	\N	\N	7	555
556	2017-05-01	15:38:04	\N	7	\N	\N	7	556
557	2017-05-01	15:38:04	\N	1	\N	\N	7	557
558	2017-05-01	15:39:04	\N	2	\N	\N	7	558
559	2017-05-01	15:39:04	\N	3	\N	\N	7	559
560	2017-05-01	15:39:04	\N	4	\N	\N	7	560
561	2017-05-01	15:39:04	\N	9	\N	\N	7	561
562	2017-05-01	15:39:04	\N	12	\N	\N	7	562
563	2017-05-01	15:39:04	\N	14	\N	\N	7	563
564	2017-05-01	15:40:04	\N	2	\N	\N	7	564
565	2017-05-01	15:40:04	\N	1	\N	\N	7	565
566	2017-05-01	15:40:04	\N	15	\N	\N	7	566
567	2017-05-01	15:40:04	\N	-1	\N	\N	7	567
568	2017-05-01	15:40:04	\N	-2	\N	\N	7	568
569	2017-05-01	15:40:04	\N	1	\N	\N	7	569
570	2017-05-01	15:40:04	\N	2	\N	\N	7	570
571	2017-05-01	15:40:04	\N	13	\N	\N	7	571
572	2017-05-01	15:40:04	\N	8	\N	\N	7	572
573	2017-05-01	15:41:04	\N	-1	\N	\N	7	573
574	2017-05-01	15:41:04	\N	-1	\N	\N	7	574
575	2017-05-01	15:41:04	\N	-5	\N	\N	7	575
576	2017-05-01	15:41:04	\N	-3	\N	\N	7	576
577	2017-05-01	15:41:04	\N	-2	\N	\N	7	577
578	2017-05-01	15:41:04	\N	10	\N	\N	7	578
579	2017-05-01	15:41:04	\N	4	\N	\N	7	579
580	2017-05-01	15:41:04	\N	3	\N	\N	7	580
581	2017-05-01	15:42:04	\N	1	\N	\N	7	581
582	2017-05-01	15:42:04	\N	2	\N	\N	7	582
583	2017-05-01	15:42:04	\N	3	\N	\N	7	583
584	2017-05-01	15:42:04	\N	4	\N	\N	7	584
585	2017-05-01	15:42:04	\N	5	\N	\N	7	585
586	2017-05-01	15:42:04	\N	5	\N	\N	7	586
587	2017-05-01	15:42:04	\N	9	\N	\N	7	587
588	2017-05-01	15:42:04	\N	1	\N	\N	7	588
589	2017-05-01	15:42:04	\N	1	\N	\N	7	589
590	2017-05-01	15:43:04	\N	2	\N	\N	7	590
591	2017-05-01	15:43:04	\N	1	\N	\N	7	591
592	2017-05-01	15:43:04	\N	10	\N	\N	7	592
593	2017-05-01	15:43:04	\N	-1	\N	\N	7	593
594	2017-05-01	15:43:04	\N	1	\N	\N	7	594
595	2017-05-01	15:43:04	\N	-2	\N	\N	7	595
596	2017-05-01	15:43:04	\N	3	\N	\N	7	596
597	2017-05-01	15:43:04	\N	-2	\N	\N	7	597
598	2017-05-01	15:44:04	\N	2	\N	\N	7	598
599	2017-05-01	15:44:04	\N	1	\N	\N	7	599
600	2017-05-01	15:44:04	\N	-8	\N	\N	7	600
601	2017-05-01	15:44:04	\N	1	\N	\N	7	601
602	2017-05-01	15:44:04	\N	-1	\N	\N	7	602
603	2017-05-01	15:44:04	\N	-2	\N	\N	7	603
604	2017-05-01	15:44:04	\N	-3	\N	\N	7	604
605	2017-05-01	15:44:04	\N	2	\N	\N	7	605
606	2017-05-01	15:44:04	\N	3	\N	\N	7	606
607	2017-05-01	15:45:04	\N	4	\N	\N	7	607
608	2017-05-01	15:45:04	\N	5	\N	\N	7	608
609	2017-05-01	15:45:04	\N	6	\N	\N	7	609
610	2017-05-01	15:45:04	\N	7	\N	\N	7	610
611	2017-05-01	15:45:04	\N	-10	\N	\N	7	611
612	2017-05-01	15:45:04	\N	1	\N	\N	7	612
613	2017-05-01	15:45:04	\N	6	\N	\N	7	613
614	2017-05-01	15:45:04	\N	10	\N	\N	7	614
615	2017-05-01	15:45:04	\N	18	\N	\N	7	615
616	2017-05-01	15:46:04	\N	2	\N	\N	7	616
617	2017-05-01	15:46:04	\N	3	\N	\N	7	617
618	2017-05-01	15:46:04	\N	-1	\N	\N	7	618
619	2017-05-01	15:46:04	\N	4	\N	\N	7	619
620	2017-05-01	15:46:04	\N	2	\N	\N	7	620
621	2017-05-01	15:46:04	\N	1	\N	\N	7	621
622	2017-05-01	15:46:04	\N	7	\N	\N	7	622
623	2017-05-01	15:46:04	\N	18	\N	\N	7	623
624	2017-05-01	15:46:04	\N	-1	\N	\N	7	624
625	2017-05-01	15:47:04	\N	-3	\N	\N	7	625
626	2017-05-01	15:47:04	\N	5	\N	\N	7	626
627	2017-05-01	15:47:04	\N	1	\N	\N	7	627
628	2017-05-01	15:47:04	\N	9	\N	\N	7	628
629	2017-05-01	15:47:04	\N	3	\N	\N	7	629
630	2017-05-01	15:47:04	\N	2	\N	\N	7	630
631	2017-05-01	15:48:04	\N	8	\N	\N	7	631
632	2017-05-01	15:48:04	\N	10	\N	\N	7	632
633	2017-05-01	15:48:04	\N	1	\N	\N	7	633
634	2017-05-01	15:48:04	\N	-2	\N	\N	7	634
635	2017-05-01	15:48:04	\N	6	\N	\N	7	635
636	2017-05-01	15:48:04	\N	4	\N	\N	7	636
637	2017-05-01	15:48:04	\N	4	\N	\N	7	637
638	2017-05-01	15:48:04	\N	6	\N	\N	7	638
639	2017-05-01	15:49:04	\N	1	\N	\N	7	639
640	2017-05-01	15:49:04	\N	-4	\N	\N	7	640
641	2017-05-01	15:49:04	\N	-6	\N	\N	7	641
642	2017-05-01	15:49:04	\N	-13	\N	\N	7	642
643	2017-05-01	15:49:04	\N	3	\N	\N	7	643
644	2017-05-01	15:49:04	\N	6	\N	\N	7	644
645	2017-05-01	15:49:04	\N	2	\N	\N	7	645
646	2017-05-01	15:49:04	\N	-11	\N	\N	7	646
647	2017-05-01	15:49:04	\N	1	\N	\N	7	647
648	2017-05-01	15:49:04	\N	-7	\N	\N	7	648
649	2017-05-01	15:50:04	\N	2	\N	\N	7	649
650	2017-05-01	15:50:04	\N	3	\N	\N	7	650
651	2017-05-01	15:50:04	\N	4	\N	\N	7	651
652	2017-05-01	15:50:04	\N	5	\N	\N	7	652
653	2017-05-01	15:50:04	\N	6	\N	\N	7	653
654	2017-05-01	15:50:04	\N	7	\N	\N	7	654
655	2017-05-01	15:50:04	\N	-17	\N	\N	7	655
656	2017-05-01	15:50:04	\N	-6	\N	\N	7	656
657	2017-05-01	15:50:04	\N	3	\N	\N	7	657
658	2017-05-01	15:50:04	\N	2	\N	\N	7	658
659	2017-05-01	15:51:04	\N	-7	\N	\N	7	659
660	2017-05-01	15:51:04	\N	-4	\N	\N	7	660
661	2017-05-01	15:51:04	\N	-6	\N	\N	7	661
662	2017-05-01	15:51:04	\N	2	\N	\N	7	662
663	2017-05-01	15:51:04	\N	5	\N	\N	7	663
664	2017-05-01	15:51:04	\N	3	\N	\N	7	664
665	2017-05-01	15:51:04	\N	4	\N	\N	7	665
666	2017-05-01	15:51:04	\N	1	\N	\N	7	666
667	2017-05-01	15:51:04	\N	2	\N	\N	7	667
668	2017-05-01	15:51:04	\N	-3	\N	\N	7	668
669	2017-05-01	15:52:04	\N	-12	\N	\N	7	669
670	2017-05-01	15:52:04	\N	1	\N	\N	7	670
671	2017-05-01	15:52:04	\N	3	\N	\N	7	671
672	2017-05-01	15:52:04	\N	4	\N	\N	7	672
\.


--
-- TOC entry 2641 (class 0 OID 0)
-- Dependencies: 178
-- Name: entidade_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('entidade_id_seq', 672, true);


--
-- TOC entry 2325 (class 0 OID 16467)
-- Dependencies: 181
-- Data for Name: entidade_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY entidade_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	PRESIDENCIAL	2016-04-08	13:55:25.563914	\N	\N
4	COORDENAÇÃO	2016-04-08	13:56:03.885107	\N	\N
2	NACIONAL	2016-04-08	13:55:44.659838	\N	\N
3	REGIÃO	2016-04-08	13:55:53.47643	\N	\N
5	IGREJA	2016-04-08	13:56:28.421487	\N	\N
6	EQUIPE	2016-04-08	13:57:02.622016	\N	\N
7	SUBEQUIPE	2016-04-08	13:57:15.798285	\N	\N
\.


--
-- TOC entry 2642 (class 0 OID 0)
-- Dependencies: 180
-- Name: entidade_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('entidade_tipo_id_seq', 1, true);


--
-- TOC entry 2327 (class 0 OID 16592)
-- Dependencies: 183
-- Data for Name: evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento (id, dia, hora, data_criacao, hora_criacao, data_inativacao, hora_inativacao, tipo_id, nome, data) FROM stdin;
214	1	01:00:00	2017-01-19	13:22:55	2017-01-19	13:55:22	2	\N	\N
215	1	20:00:00	2017-01-20	10:42:47	\N	\N	1	FAMíLIA	\N
216	3	20:00:00	2017-01-20	10:39:59	2017-01-20	10:59:39	1	FéE	\N
217	3	02:03:00	2017-01-20	11:03:54	2017-01-20	11:54:03	2	\N	\N
218	1	01:03:00	2017-01-20	11:31:54	2017-01-20	11:54:31	2	\N	\N
219	4	08:30:00	2017-01-24	13:56:02	2017-01-24	13:02:56	2	\N	\N
213	5	01:02:00	2017-01-30	16:48:16	2017-01-30	16:16:48	1	QUEBRANDO AS MALDICOES	\N
226	1	20:00:00	2017-01-01	16:54:20	\N	\N	1	CULTO PROFéTICO 	\N
223	5	20:00:00	2017-01-01	16:08:19	\N	\N	1	CONEXãO	\N
224	7	18:00:00	2017-01-01	16:43:19	\N	\N	1	ARENA JOVEM	\N
220	3	20:00:00	2017-01-01	16:09:12	\N	\N	1	CULTO DE TERçA 	\N
225	1	18:00:00	2017-01-01	16:29:20	\N	\N	1	CULTO DA FAMíLIA	\N
229	2	20:30:00	2017-01-01	16:14:42	\N	\N	2	\N	\N
221	3	20:00:00	2017-01-01	16:30:16	\N	\N	1	QUEBRA DE MALDIçõES	\N
222	7	20:00:00	2017-01-01	16:05:19	\N	\N	1	ARENA JOVEM	\N
230	3	20:00:00	2017-01-31	11:29:48	\N	\N	2	\N	\N
231	5	20:30:00	2017-02-01	09:17:51	\N	\N	2	\N	\N
232	4	20:00:00	2017-02-01	09:06:52	\N	\N	1	CULTO DE QUARTA	\N
233	5	20:30:00	2017-02-02	10:23:58	\N	\N	1	CONEXAO	\N
240	7	00:00:00	2017-02-08	14:38:35	\N	\N	1	QUEBRA DE MALDIçõES	\N
443	1	10:00:00	2017-05-01	15:34:04	\N	\N	1	DOMINGO 10H	\N
444	1	18:00:00	2017-05-01	15:34:04	\N	\N	1	DOMINGO 18H	\N
445	1	20:00:00	2017-05-01	15:34:04	\N	\N	1	DOMINGO - 20H	\N
446	2	20:00:00	2017-05-01	15:34:04	\N	\N	1	#SEGUNDACHANCE	\N
447	3	20:00:00	2017-05-01	15:34:04	\N	\N	1	TERÇA FEIRA	\N
448	3	20:00:00	2017-05-01	15:34:04	\N	\N	1	TERÇA-FEIRA	\N
449	4	20:00:00	2017-05-01	15:34:04	\N	\N	1	QUARTA DA VITÓRIA	\N
450	5	19:30:00	2017-05-01	15:34:04	\N	\N	1	QUEBRA DE MALDIÇÃO	\N
451	5	20:00:00	2017-05-01	15:34:04	\N	\N	1	CONEXAO	\N
452	6	20:00:00	2017-05-01	15:34:04	\N	\N	1	SEXTA MAIS	\N
453	7	18:00:00	2017-05-01	15:34:04	\N	\N	1	SáBADO 18H	\N
454	7	20:00:00	2017-05-01	15:34:04	\N	\N	1	SÁBADO 20H	\N
455	7	17:00:00	2017-05-01	15:34:04	\N	\N	2	\N	\N
456	7	15:00:00	2017-05-01	15:34:04	\N	\N	2	\N	\N
457	6	16:00:00	2017-05-01	15:34:04	\N	\N	2	\N	\N
458	7	18:00:00	2017-05-01	15:34:04	\N	\N	2	\N	\N
459	4	19:30:00	2017-05-01	15:35:04	\N	\N	2	\N	\N
460	7	17:30:00	2017-05-01	15:35:04	\N	\N	2	\N	\N
461	7	17:30:00	2017-05-01	15:35:04	\N	\N	2	\N	\N
462	7	17:30:00	2017-05-01	15:36:04	\N	\N	2	\N	\N
463	7	17:00:00	2017-05-01	15:36:04	\N	\N	2	\N	\N
464	7	17:00:00	2017-05-01	15:36:04	\N	\N	2	\N	\N
465	7	16:00:00	2017-05-01	15:37:04	\N	\N	2	\N	\N
466	5	20:00:00	2017-05-01	15:37:04	\N	\N	2	\N	\N
467	5	20:00:00	2017-05-01	15:37:04	\N	\N	2	\N	\N
468	6	17:00:00	2017-05-01	15:37:04	\N	\N	2	\N	\N
469	6	19:00:00	2017-05-01	15:37:04	\N	\N	2	\N	\N
470	7	19:00:00	2017-05-01	15:37:04	\N	\N	2	\N	\N
471	7	18:00:00	2017-05-01	15:38:04	\N	\N	2	\N	\N
472	7	16:30:00	2017-05-01	15:38:04	\N	\N	2	\N	\N
473	5	20:00:00	2017-05-01	15:38:04	\N	\N	2	\N	\N
474	7	16:00:00	2017-05-01	15:38:04	\N	\N	2	\N	\N
475	7	16:00:00	2017-05-01	15:38:04	\N	\N	2	\N	\N
476	7	18:00:00	2017-05-01	15:38:04	\N	\N	2	\N	\N
477	7	17:00:00	2017-05-01	15:39:04	\N	\N	2	\N	\N
478	7	15:00:00	2017-05-01	15:39:04	\N	\N	2	\N	\N
479	7	15:30:00	2017-05-01	15:39:04	\N	\N	2	\N	\N
480	7	16:30:00	2017-05-01	15:39:04	\N	\N	2	\N	\N
481	7	16:30:00	2017-05-01	15:39:04	\N	\N	2	\N	\N
482	7	16:00:00	2017-05-01	15:39:04	\N	\N	2	\N	\N
483	7	20:00:00	2017-05-01	15:40:04	\N	\N	2	\N	\N
484	4	15:00:00	2017-05-01	15:40:04	\N	\N	2	\N	\N
485	2	19:30:00	2017-05-01	15:40:04	\N	\N	2	\N	\N
486	7	18:30:00	2017-05-01	15:40:04	\N	\N	2	\N	\N
487	5	19:30:00	2017-05-01	15:40:04	\N	\N	2	\N	\N
488	7	17:00:00	2017-05-01	15:40:04	\N	\N	2	\N	\N
489	1	09:00:00	2017-05-01	15:41:04	\N	\N	2	\N	\N
490	7	17:00:00	2017-05-01	15:41:04	\N	\N	2	\N	\N
491	7	16:00:00	2017-05-01	15:41:04	\N	\N	2	\N	\N
492	7	18:00:00	2017-05-01	15:41:04	\N	\N	2	\N	\N
493	7	16:00:00	2017-05-01	15:42:04	\N	\N	2	\N	\N
494	7	17:00:00	2017-05-01	15:42:04	\N	\N	2	\N	\N
495	7	18:00:00	2017-05-01	15:42:04	\N	\N	2	\N	\N
496	7	16:30:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
497	7	16:30:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
498	4	19:00:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
499	7	16:20:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
500	5	20:30:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
501	7	13:00:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
502	4	20:00:00	2017-05-01	15:43:04	\N	\N	2	\N	\N
503	7	17:30:00	2017-05-01	15:44:04	\N	\N	2	\N	\N
504	5	20:00:00	2017-05-01	15:44:04	\N	\N	2	\N	\N
505	7	20:30:00	2017-05-01	15:45:04	\N	\N	2	\N	\N
506	7	15:00:00	2017-05-01	15:45:04	\N	\N	2	\N	\N
507	7	17:30:00	2017-05-01	15:45:04	\N	\N	2	\N	\N
508	7	18:00:00	2017-05-01	15:46:04	\N	\N	2	\N	\N
509	5	20:00:00	2017-05-01	15:46:04	\N	\N	2	\N	\N
510	5	20:00:00	2017-05-01	15:46:04	\N	\N	2	\N	\N
511	7	18:00:00	2017-05-01	15:46:04	\N	\N	2	\N	\N
512	7	18:00:00	2017-05-01	15:47:04	\N	\N	2	\N	\N
513	7	18:00:00	2017-05-01	15:47:04	\N	\N	2	\N	\N
514	4	18:30:00	2017-05-01	15:47:04	\N	\N	2	\N	\N
515	4	19:00:00	2017-05-01	15:47:04	\N	\N	2	\N	\N
516	7	18:00:00	2017-05-01	15:48:04	\N	\N	2	\N	\N
517	7	18:00:00	2017-05-01	15:48:04	\N	\N	2	\N	\N
518	4	19:00:00	2017-05-01	15:48:04	\N	\N	2	\N	\N
519	7	16:00:00	2017-05-01	15:48:04	\N	\N	2	\N	\N
520	7	18:30:00	2017-05-01	15:49:04	\N	\N	2	\N	\N
521	7	18:30:00	2017-05-01	15:49:04	\N	\N	2	\N	\N
522	7	18:30:00	2017-05-01	15:49:04	\N	\N	2	\N	\N
523	7	20:30:00	2017-05-01	15:49:04	\N	\N	2	\N	\N
524	7	15:30:00	2017-05-01	15:49:04	\N	\N	2	\N	\N
525	5	18:30:00	2017-05-01	15:50:04	\N	\N	2	\N	\N
526	7	16:00:00	2017-05-01	15:50:04	\N	\N	2	\N	\N
527	7	15:00:00	2017-05-01	15:50:04	\N	\N	2	\N	\N
528	5	19:00:00	2017-05-01	15:50:04	\N	\N	2	\N	\N
529	4	19:00:00	2017-05-01	15:51:04	\N	\N	2	\N	\N
530	7	17:00:00	2017-05-01	15:51:04	\N	\N	2	\N	\N
531	7	18:00:00	2017-05-01	15:51:04	\N	\N	2	\N	\N
532	7	20:30:00	2017-05-01	15:52:04	\N	\N	2	\N	\N
533	7	17:00:00	2017-05-01	15:52:04	\N	\N	2	\N	\N
534	7	17:30:00	2017-05-01	15:52:04	\N	\N	2	\N	\N
239	6	21:00:00	2017-02-08	14:23:22	\N	\N	3	Revisão do Chile 	2017-05-26
244	6	21:00:00	2017-02-09	16:12:23	\N	\N	3	Quebra de Maldições	2017-02-24
238	6	21:00:00	2017-02-08	14:32:12	\N	\N	3	Revisão do Acre	2017-02-24
234	6	21:00:00	2017-02-07	15:54:00	\N	\N	3	TESTE LEONARDO 	2017-02-24
236	6	21:00:00	2017-02-08	13:59:14	\N	\N	3	\N	2017-02-24
237	6	21:00:00	2017-02-08	13:21:40	\N	\N	3	\N	2017-02-24
242	6	21:00:00	2017-02-09	16:32:16	\N	\N	3	\N	2017-02-24
235	6	21:00:00	2017-02-07	15:17:14	\N	\N	3	\N	2017-02-24
241	6	21:00:00	2017-02-09	16:25:15	\N	\N	3	\N	2017-02-24
243	6	21:00:00	2017-02-09	16:48:17	\N	\N	3	\N	2017-02-24
\.


--
-- TOC entry 2331 (class 0 OID 16640)
-- Dependencies: 187
-- Data for Name: evento_celula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento_celula (id, nome_hospedeiro, telefone_hospedeiro, logradouro, complemento, evento_id, bairro, cidade, cep, uf) FROM stdin;
307	VINICIUS	6191567465	CONDOMINIO RESIDENCIAL SÃO FRANCISCO		455	1238	1778	0	DF
308	IGREJA	6186236553	IGREJA SARA NOSSA TERRA DA CEILANDIA	40	456	1185	1781	0	DF
309	BRENO DE LUCENA	610	Escola	partamento 310	457	0	1796	0	DF
310	THIAGO 	61991407337			458	1185	1778	0	DF
311	Ana Luiza	6185862962	Qnp 24 conjunto C casa 25	casa 25	459	0	1781	0	DF
312	FLAVIA TAILANE	61986321731	qno 09 conjunto c casa 53	setor o	460	0	1778	0	DF
313	FERNANDA	6184306351	QNP 18 Conjunto F	15 A	461	1186	1781	0	DF
314	THALITA	6184623163	QNM 36 Conjunto E		462	1182	1796	0	DF
315	DIEGO	6181709470	QI 02 BLOCO O	APT 103	463	1193	1784	0	DF
316	MEL	6991198101			464	1185	1778	0	DF
317	LEONARDO	61995994949		Quadra	465	1186	1778	0	DF
318	URIEL RAMON 	61984544756		Feira permanente 	466	1185	1778	0	DF
319	MELISSA	61991190181			467	1185	1778	0	DF
320	PEDRO HENRIQUE LIMA	61992405425			468	1185	1778	0	DF
321	NATHALIA GOMES 	61984801728		Rua da distribuidora 	469	1186	1778	0	DF
322	LUCAS	6135816292			470	0	1778	0	DF
323	ISAAC DIAS 	6186564287	QNP 10 Conjunto P	casa 03	471	1186	1781	0	DF
324	GIL	6133717389	QNM 4 Conjunto N	casa 25	472	1185	1781	0	DF
325	JOÃO	6193315645	QNP10 CONJUNTO P		473	1186	1781	0	DF
326	WESLLEM	61998510703	AO LADO DO MONTE DO PSUL		474	1186	1781	0	DF
327	VIVIANE 	6199860703			475	1185	1778	0	DF
328	IGREJA	6184855240	Qnm 28 Conjunto O		476	1185	1781	0	DF
329	GUILHERME	6195568837			477	55045	2055	0	GO
330	GEANDERSON	6192505003			478	55045	2055	0	GO
331	LAIS BARBOSA DE SOUZA	6192172873			479	55046	2055	0	GO
332	VITóRIA	6195927365	QR 103 Conj 3 Cs 03		480	1229	1792	0	DF
333	LUCAS 	6133582911	QR 606 Conjunto 4 LT 02		481	1228	1792	0	DF
334	VINICIUS	6192560601	eqnm 7/9, bloco B, 	lote 3	482	1185	1781	0	DF
335	LARISSA 	6186761686	AV  Ponte ALta Qd 605 lt 26 casa 03		483	1238	1790	0	DF
336	SARAH NAYARA 	61993468747			484	1238	1778	0	DF
337	VICTóRIA FREITAS	610	Qnm 18 Corpo de Bombeiro 	Em frente a qnm 20, Snt Ceilândia 	485	0	1778	0	DF
338	RAQUEL RODRIGUES DA COSTA	61983786183		23 A 33	486	1185	1778	0	DF
339	RAQUEL 	6197760			487	0	1778	0	DF
340	ARMANDO	6193668550		casa 44	488	1229	1778	0	DF
341	WILKSON WELKER MONTEIRO ALVES DOS SANTOS 	6184840551	Parque Três Meninas		489	1186	1781	0	DF
342	IGREJA 	61981438222			490	1185	1778	0	DF
343	PEDRO 	6185390106	qnm 24 conjunto k	CS 09	491	1185	1781	0	DF
344	SAMUEL	6185035795	QNP 10 CJ R CS 48	P SUL	492	1186	1781	0	DF
345	GUSTAVO	6199536814	Qno 03 conj f casa 45		493	1185	1781	0	DF
346	MARIA MARLENE	61984839105	QNM 4 CJ K LT 01		494	0	1778	0	DF
347	LUCAS SANTOS DE MOURA	61994236236	QI 25	atrás da star moveis	495	0	1778	0	DF
348	ROGER	61981469931	QN 12		496	1243	1778	0	DF
349	MARIA DA GLORIA	6133570654		26	497	1229	1778	0	DF
350	LURDES	61996680620			498	1185	1778	0	DF
351	PAULO	61986479949		QNN 23 CONJUNTO N CASA 46	499	0	1778	0	DF
352	SUZELLY 	61984101909		15	500	1185	1778	0	DF
353	MARCOS VINICIUS	61996908284		CASA 01	501	51982	1778	0	DF
354	THIEGO 	61984413079		Casa 02	502	51982	1778	0	DF
355	JULIANA BATISTA	6196936168	QNP 12 Conjunto D	casa 26	503	1186	1781	0	DF
356	MARIA DEIZIANE	6195291454	QNP 10 CONJUNTO  F	26	504	1186	1781	0	DF
357	Ana Cristina	6183270512	Expansão setor ó	COND PRIVÊ	505	1185	1781	0	DF
358	CRIIS	61984997259			506	1228	1778	0	DF
359	WESLLEY OLIVEIRA	6182457671	Qnl 09 Bloco E  Casa 04		507	1182	1796	0	DF
360	MATHEUS MARQUES	61885381923	QNL 20 Via 1		508	1182	1796	0	DF
361	FELIPE MARQUES 	6185757768	QNL 20 Via 1		509	1182	1796	0	DF
362	WESLEY SANTOS 	61995648736			510	1182	1778	0	DF
363	MARCOS	6122222222		Lote 03 apto 201	511	1182	1778	0	DF
364	MARIA LUíSA	6199684016		Cnb 02 lote 03 apto 301	512	1182	1778	0	DF
365	DAVID	6191846788		CNB 02 LOTE 09 APTO 101	513	1182	1778	0	DF
366	Nicoly	61984146843	QNL 19		514	1182	1796	0	DF
367	HOSANA CORTE MEIRELLES	6195964919	qnj 37		515	1182	1796	0	DF
368	TALITA FERRAZ 	6186094096	QNM 04 CONJUNTO K CASA 30 		516	1185	1778	0	DF
369	THAYNARA	6185159465	QNL 4 Conjunto H	Casa 05	517	1182	1796	0	DF
370	FERNANDA GOMES ALBUQUERQUE	6196455419	QN 14A Conjunto 2	casa 01	518	1243	1791	0	DF
371	RAYNARA	6122222222		Lote 04	519	1182	1778	0	DF
372	ESTHER	61984049505	QNM 20 CONJUNTO B CASA 	22	520	1185	1781	0	DF
373	PRAçA DA 404 	6192779379	qr 404		521	0	1790	0	DF
374	GILVAN	6192779379	qnm 22 conjunto m casa 45		522	1185	1781	0	DF
375	ROBERTO	6184387877	QR 425 CONJUNTO 04 CASA 17		523	1228	1792	0	DF
376	KAREN	6192033203			524	0	1778	0	DF
377	ESCADINHAS DA FARMACIA	61985353200	Qnm 22 Conjunto B	Escadinhas da Farmacia	525	0	1781	0	DF
378	ANA PAULA	61995650710	Qnm 22, conjunto O casa 38		526	1185	1781	0	DF
379	LEANDRO	6199556633	QC 4 Conjunto 18		527	1243	1791	0	DF
380	LETICIA SANTOS BEZERRA	61985824250			528	1243	1778	0	DF
381	ICARO SANTANA DUQUE	61985824250			529	1186	1778	0	DF
382	VANESSA	61986451535	Qn 12a conjunto 4  casa 8 ap 302	Condo 10	530	0	1791	0	DF
383	EVA MARIA	61983466604	SHNS CHACARA RODRIGUES 186 	CASA 28	531	1185	1778	0	DF
384	GLEVISSON DE JESUS SANTOS  MORAES	6185929322	QNO 13 CONJUNTO F CASA 27		532	1185	1781	0	DF
385	CYNTHIA 	6191086035	Rua 9 módulo 2  		533	1185	1781	0	DF
386	JONATAN IGOR	61983202461			534	0	1778	0	DF
\.


--
-- TOC entry 2643 (class 0 OID 0)
-- Dependencies: 186
-- Name: evento_celula_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_celula_id_seq', 386, true);


--
-- TOC entry 2339 (class 0 OID 16747)
-- Dependencies: 195
-- Data for Name: evento_frequencia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento_frequencia (id, evento_id, pessoa_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao, frequencia, ciclo, mes, ano) FROM stdin;
105	525	9550	2017-05-08	15:17:09	\N	\N	N	1	5	2017
104	525	9549	2017-05-08	15:17:09	\N	\N	N	1	5	2017
103	525	9548	2017-05-08	15:19:09	\N	\N	N	1	5	2017
102	448	9548	2017-05-08	15:19:09	\N	\N	N	1	5	2017
101	448	9549	2017-05-08	15:20:09	\N	\N	N	1	5	2017
109	445	9549	2017-05-08	15:26:09	\N	\N	N	1	5	2017
108	445	9548	2017-05-08	15:27:09	\N	\N	N	1	5	2017
110	445	9550	2017-05-08	15:27:09	\N	\N	N	1	5	2017
106	453	9549	2017-05-08	15:28:09	\N	\N	N	1	5	2017
107	453	9548	2017-05-08	15:28:09	\N	\N	N	1	5	2017
113	448	7812	2017-05-08	19:16:40	\N	\N	S	1	5	2017
127	448	9520	2017-05-09	12:55:23	\N	\N	S	1	5	2017
116	445	7812	2017-05-08	19:19:40	\N	\N	S	1	5	2017
117	445	7813	2017-05-08	19:20:40	\N	\N	S	1	5	2017
119	448	7813	2017-05-08	19:21:40	\N	\N	S	1	5	2017
121	453	7819	2017-05-08	19:29:40	\N	\N	S	1	5	2017
122	445	7819	2017-05-08	19:30:40	\N	\N	S	1	5	2017
124	453	7821	2017-05-08	19:33:40	\N	\N	S	1	5	2017
125	445	7821	2017-05-08	19:34:40	\N	\N	S	1	5	2017
126	445	7816	2017-05-08	19:37:40	\N	\N	S	1	5	2017
112	474	7813	2017-05-09	11:08:41	\N	\N	N	1	5	2017
128	448	9521	2017-05-09	12:56:23	\N	\N	S	1	5	2017
118	453	7813	2017-05-08	19:37:44	\N	\N	S	1	5	2017
115	453	7812	2017-05-08	19:37:44	\N	\N	S	1	5	2017
163	448	7113	2017-05-10	18:18:39	\N	\N	N	1	5	2017
140	526	9524	2017-05-09	11:44:41	\N	\N	N	1	5	2017
129	448	9523	2017-05-09	12:56:23	\N	\N	S	1	5	2017
143	525	9520	2017-05-09	12:57:23	\N	\N	S	1	5	2017
132	525	9521	2017-05-09	12:58:23	\N	\N	S	1	5	2017
131	525	9523	2017-05-09	12:58:23	\N	\N	S	1	5	2017
149	526	9520	2017-05-09	12:59:23	\N	\N	S	1	5	2017
150	526	9521	2017-05-09	12:00:24	\N	\N	S	1	5	2017
139	526	9523	2017-05-09	12:00:24	\N	\N	S	1	5	2017
111	474	7812	2017-05-09	11:59:41	\N	\N	S	1	5	2017
130	448	9524	2017-05-09	09:16:10	\N	\N	N	1	5	2017
151	453	9520	2017-05-09	12:01:24	\N	\N	S	1	5	2017
152	453	9521	2017-05-09	12:02:24	\N	\N	S	1	5	2017
153	453	9523	2017-05-09	12:02:24	\N	\N	S	1	5	2017
154	445	9520	2017-05-09	12:03:24	\N	\N	S	1	5	2017
155	445	9521	2017-05-09	12:03:24	\N	\N	S	1	5	2017
156	445	9523	2017-05-09	12:04:24	\N	\N	S	1	5	2017
114	475	7812	2017-05-09	11:15:42	\N	\N	N	1	5	2017
144	475	7813	2017-05-09	11:16:42	\N	\N	S	1	5	2017
157	448	9520	2017-05-09	12:14:30	\N	\N	S	2	5	2017
141	448	9521	2017-05-09	12:15:30	\N	\N	S	2	5	2017
120	474	7819	2017-05-09	11:19:42	\N	\N	S	1	5	2017
123	474	7821	2017-05-09	11:21:42	\N	\N	S	1	5	2017
142	448	9523	2017-05-09	12:15:30	\N	\N	S	2	5	2017
135	448	9540	2017-05-09	12:31:30	\N	\N	N	1	5	2017
134	448	9539	2017-05-09	12:31:30	\N	\N	N	1	5	2017
147	474	7818	2017-05-09	11:28:40	\N	\N	N	1	5	2017
145	474	7817	2017-05-09	11:28:40	\N	\N	N	1	5	2017
133	448	9538	2017-05-09	12:32:30	\N	\N	N	1	5	2017
148	475	7818	2017-05-09	11:30:40	\N	\N	N	1	5	2017
138	526	9539	2017-05-09	12:33:30	\N	\N	N	1	5	2017
137	526	9538	2017-05-09	12:34:30	\N	\N	N	1	5	2017
146	475	7817	2017-05-09	11:30:40	\N	\N	N	1	5	2017
136	526	9537	2017-05-09	12:34:30	\N	\N	N	1	5	2017
158	448	9524	2017-05-09	12:44:35	\N	\N	S	2	5	2017
159	448	9525	2017-05-09	12:45:35	\N	\N	S	2	5	2017
160	448	9526	2017-05-09	12:46:35	\N	\N	S	2	5	2017
161	448	7812	2017-05-09	19:50:18	\N	\N	S	2	5	2017
162	448	7813	2017-05-09	19:52:18	\N	\N	S	2	5	2017
168	448	7107	2017-05-15	20:44:29	\N	\N	S	2	5	2017
169	448	7108	2017-05-15	20:45:29	\N	\N	S	2	5	2017
170	448	7109	2017-05-11	17:30:11	\N	\N	N	2	5	2017
171	448	7110	2017-05-11	17:32:11	\N	\N	N	2	5	2017
172	448	7111	2017-05-11	17:32:11	\N	\N	N	2	5	2017
173	448	7112	2017-05-11	17:33:11	\N	\N	N	2	5	2017
174	448	7113	2017-05-11	17:33:11	\N	\N	N	2	5	2017
175	448	7115	2017-05-11	17:35:11	\N	\N	N	2	5	2017
176	234	9903	2017-05-12	12:18:20	\N	\N	N	0	5	2017
177	234	9904	2017-05-12	14:26:29	\N	\N	N	0	5	2017
180	453	7812	2017-05-13	17:19:12	\N	\N	S	2	5	2017
181	453	7813	2017-05-13	17:21:12	\N	\N	S	2	5	2017
183	453	7819	2017-05-13	17:32:12	\N	\N	S	2	5	2017
182	453	7820	2017-05-13	17:38:12	\N	\N	N	2	5	2017
184	475	7819	2017-05-13	17:15:13	\N	\N	S	2	5	2017
178	474	7812	2017-05-14	11:29:38	\N	\N	N	2	5	2017
185	474	7813	2017-05-14	11:26:38	\N	\N	S	2	5	2017
186	445	7812	2017-05-14	11:37:38	\N	\N	S	2	5	2017
179	475	7812	2017-05-14	11:29:38	\N	\N	S	2	5	2017
166	525	9523	2017-05-16	08:28:59	\N	\N	S	2	5	2017
165	525	9521	2017-05-16	08:28:59	\N	\N	S	2	5	2017
164	525	9520	2017-05-16	08:27:59	\N	\N	S	2	5	2017
187	445	7813	2017-05-14	11:37:38	\N	\N	S	2	5	2017
188	474	9907	2017-05-14	11:03:40	\N	\N	S	2	5	2017
189	244	9910	2017-05-15	17:38:07	\N	\N	N	0	1	2017
190	244	9911	2017-05-15	17:09:10	\N	\N	N	0	1	2017
167	448	7106	2017-05-15	20:44:29	\N	\N	S	2	5	2017
191	455	7106	2017-05-15	20:44:29	\N	\N	S	2	5	2017
192	453	7106	2017-05-15	20:44:29	\N	\N	S	2	5	2017
193	455	7107	2017-05-15	20:44:29	\N	\N	S	2	5	2017
194	453	7107	2017-05-15	20:44:29	\N	\N	S	2	5	2017
195	445	7106	2017-05-15	20:44:29	\N	\N	S	2	5	2017
196	445	7107	2017-05-15	20:45:29	\N	\N	S	2	5	2017
266	445	7816	2017-05-16	18:21:55	\N	\N	S	2	5	2017
197	448	9572	2017-05-16	08:17:59	\N	\N	N	3	5	2017
199	525	9524	2017-05-16	08:37:59	\N	\N	N	2	5	2017
249	448	9554	2017-05-16	10:39:52	\N	\N	S	3	5	2017
250	448	9555	2017-05-16	10:39:52	\N	\N	S	3	5	2017
251	448	9556	2017-05-16	10:41:52	\N	\N	S	3	5	2017
203	448	9912	2017-05-16	09:22:44	\N	\N	N	3	5	2017
204	244	9911	2017-05-16	09:25:46	\N	\N	N	0	2	2017
205	244	9911	2017-05-16	10:57:02	\N	\N	N	0	2	2017
252	448	9557	2017-05-16	10:41:52	\N	\N	S	3	5	2017
253	448	9558	2017-05-16	10:41:52	\N	\N	S	3	5	2017
254	448	9559	2017-05-16	10:42:52	\N	\N	S	3	5	2017
255	448	9560	2017-05-16	10:42:52	\N	\N	S	3	5	2017
208	448	7106	2017-05-16	10:26:28	\N	\N	N	3	5	2017
207	448	7105	2017-05-16	10:27:28	\N	\N	N	3	5	2017
206	448	7104	2017-05-16	10:27:28	\N	\N	N	3	5	2017
209	244	9911	2017-05-16	10:37:28	\N	\N	N	0	2	2017
200	448	9520	2017-05-16	10:43:29	\N	\N	S	3	5	2017
201	448	9521	2017-05-16	10:43:29	\N	\N	S	3	5	2017
202	448	9523	2017-05-16	10:44:29	\N	\N	S	3	5	2017
210	448	9524	2017-05-16	10:44:29	\N	\N	S	3	5	2017
211	448	9525	2017-05-16	10:44:29	\N	\N	S	3	5	2017
212	526	9520	2017-05-16	10:01:41	\N	\N	S	2	5	2017
213	526	9521	2017-05-16	10:02:41	\N	\N	S	2	5	2017
214	526	9523	2017-05-16	10:03:41	\N	\N	S	2	5	2017
215	453	9520	2017-05-16	10:04:41	\N	\N	S	2	5	2017
216	453	9521	2017-05-16	10:05:41	\N	\N	S	2	5	2017
217	453	9523	2017-05-16	10:05:41	\N	\N	S	2	5	2017
218	445	9520	2017-05-16	10:08:41	\N	\N	S	2	5	2017
219	445	9521	2017-05-16	10:08:41	\N	\N	S	2	5	2017
220	445	9523	2017-05-16	10:09:41	\N	\N	S	2	5	2017
221	448	9526	2017-05-16	10:20:52	\N	\N	S	3	5	2017
222	448	9527	2017-05-16	10:21:52	\N	\N	S	3	5	2017
223	448	9528	2017-05-16	10:23:52	\N	\N	S	3	5	2017
224	448	9529	2017-05-16	10:23:52	\N	\N	S	3	5	2017
225	448	9530	2017-05-16	10:24:52	\N	\N	S	3	5	2017
226	448	9531	2017-05-16	10:24:52	\N	\N	S	3	5	2017
227	448	9532	2017-05-16	10:25:52	\N	\N	S	3	5	2017
228	448	9533	2017-05-16	10:25:52	\N	\N	S	3	5	2017
229	448	9534	2017-05-16	10:26:52	\N	\N	S	3	5	2017
230	448	9535	2017-05-16	10:27:52	\N	\N	S	3	5	2017
231	448	9536	2017-05-16	10:27:52	\N	\N	S	3	5	2017
232	448	9537	2017-05-16	10:29:52	\N	\N	S	3	5	2017
233	448	9538	2017-05-16	10:29:52	\N	\N	S	3	5	2017
234	448	9539	2017-05-16	10:29:52	\N	\N	S	3	5	2017
235	448	9540	2017-05-16	10:30:52	\N	\N	S	3	5	2017
236	448	9541	2017-05-16	10:30:52	\N	\N	S	3	5	2017
237	448	9542	2017-05-16	10:31:52	\N	\N	S	3	5	2017
238	448	9543	2017-05-16	10:32:52	\N	\N	S	3	5	2017
239	448	9544	2017-05-16	10:32:52	\N	\N	S	3	5	2017
240	448	9545	2017-05-16	10:33:52	\N	\N	S	3	5	2017
241	448	9546	2017-05-16	10:33:52	\N	\N	S	3	5	2017
242	448	9547	2017-05-16	10:34:52	\N	\N	S	3	5	2017
243	448	9548	2017-05-16	10:35:52	\N	\N	S	3	5	2017
244	448	9549	2017-05-16	10:36:52	\N	\N	S	3	5	2017
245	448	9550	2017-05-16	10:36:52	\N	\N	S	3	5	2017
246	448	9551	2017-05-16	10:37:52	\N	\N	S	3	5	2017
247	448	9552	2017-05-16	10:37:52	\N	\N	S	3	5	2017
248	448	9553	2017-05-16	10:39:52	\N	\N	S	3	5	2017
256	448	9561	2017-05-16	10:44:52	\N	\N	S	3	5	2017
257	448	9562	2017-05-16	10:44:52	\N	\N	S	3	5	2017
258	448	9563	2017-05-16	10:45:52	\N	\N	S	3	5	2017
259	448	9564	2017-05-16	10:46:52	\N	\N	S	3	5	2017
260	448	9565	2017-05-16	10:46:52	\N	\N	S	3	5	2017
261	448	9566	2017-05-16	10:47:52	\N	\N	S	3	5	2017
262	448	9567	2017-05-16	10:48:52	\N	\N	S	3	5	2017
263	448	9568	2017-05-16	10:48:52	\N	\N	S	3	5	2017
264	448	9569	2017-05-16	10:49:52	\N	\N	S	3	5	2017
265	448	9570	2017-05-16	10:50:52	\N	\N	S	3	5	2017
198	448	9571	2017-05-16	10:50:52	\N	\N	S	3	5	2017
267	448	7812	2017-05-16	18:49:55	\N	\N	S	3	5	2017
268	448	7813	2017-05-16	18:50:55	\N	\N	S	3	5	2017
269	448	7814	2017-05-16	18:54:55	\N	\N	S	3	5	2017
270	448	9913	2017-05-16	19:28:43	\N	\N	S	3	5	2017
271	244	9911	2017-05-18	14:05:58	\N	\N	N	0	4	2017
272	239	9524	2017-05-19	14:38:39	\N	\N	N	0	5	2017
273	239	8894	2017-05-19	14:04:45	\N	\N	N	0	5	2017
\.


--
-- TOC entry 2644 (class 0 OID 0)
-- Dependencies: 194
-- Name: evento_frequencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_frequencia_id_seq', 276, true);


--
-- TOC entry 2645 (class 0 OID 0)
-- Dependencies: 182
-- Name: evento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_id_seq', 534, true);


--
-- TOC entry 2329 (class 0 OID 16620)
-- Dependencies: 185
-- Data for Name: evento_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	CELULA	2016-04-27	16:21:13.863352	\N	\N
2	CULTO	2016-04-27	16:21:19.935154	\N	\N
3	REVISAO	2017-01-05	13:48:27.604821	\N	\N
\.


--
-- TOC entry 2646 (class 0 OID 0)
-- Dependencies: 184
-- Name: evento_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_tipo_id_seq', 1, false);


--
-- TOC entry 2365 (class 0 OID 17405)
-- Dependencies: 221
-- Data for Name: fato_celula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY fato_celula (id, data_criacao, hora_criacao, data_inativacao, hora_inativacao, realizada, fato_ciclo_id, evento_celula_id) FROM stdin;
522	2017-05-08	15:42:20	\N	\N	0	1035	379
523	2017-05-08	15:42:20	\N	\N	0	1037	380
524	2017-05-08	15:42:20	\N	\N	0	1038	381
525	2017-05-08	15:42:20	\N	\N	0	1041	382
526	2017-05-08	15:43:20	\N	\N	0	1046	383
527	2017-05-08	15:43:20	\N	\N	0	1048	384
528	2017-05-08	15:43:20	\N	\N	0	1049	385
529	2017-05-08	15:43:20	\N	\N	0	1050	386
530	2017-05-08	15:43:20	\N	\N	0	1053	307
531	2017-05-08	15:43:20	\N	\N	0	1054	308
532	2017-05-08	15:43:20	\N	\N	0	1055	309
533	2017-05-08	15:43:20	\N	\N	0	1056	310
534	2017-05-08	15:44:20	\N	\N	0	1063	311
535	2017-05-08	15:44:20	\N	\N	0	1065	312
536	2017-05-08	15:44:20	\N	\N	0	1066	313
537	2017-05-08	15:44:20	\N	\N	0	1073	314
538	2017-05-08	15:44:20	\N	\N	0	1074	315
539	2017-05-08	15:45:20	\N	\N	0	1078	316
540	2017-05-08	15:45:20	\N	\N	0	1080	317
541	2017-05-08	15:45:20	\N	\N	0	1082	318
542	2017-05-08	15:45:20	\N	\N	0	1083	319
543	2017-05-08	15:45:20	\N	\N	0	1084	320
544	2017-05-08	15:45:20	\N	\N	0	1086	321
545	2017-05-08	15:45:20	\N	\N	0	1087	322
546	2017-05-08	15:45:20	\N	\N	0	1088	324
547	2017-05-08	15:45:20	\N	\N	0	1088	323
548	2017-05-08	15:46:20	\N	\N	0	1089	325
551	2017-05-08	15:46:20	\N	\N	0	1093	328
552	2017-05-08	15:46:20	\N	\N	0	1094	329
553	2017-05-08	15:46:20	\N	\N	0	1095	330
554	2017-05-08	15:46:20	\N	\N	0	1096	331
555	2017-05-08	15:46:20	\N	\N	0	1098	332
556	2017-05-08	15:46:20	\N	\N	0	1099	333
557	2017-05-08	15:47:20	\N	\N	0	1100	334
558	2017-05-08	15:47:20	\N	\N	0	1101	335
559	2017-05-08	15:47:20	\N	\N	0	1102	336
560	2017-05-08	15:47:20	\N	\N	0	1103	337
561	2017-05-08	15:47:20	\N	\N	0	1103	338
562	2017-05-08	15:47:20	\N	\N	0	1105	339
563	2017-05-08	15:47:20	\N	\N	0	1108	340
564	2017-05-08	15:47:20	\N	\N	0	1109	341
565	2017-05-08	15:48:20	\N	\N	0	1115	343
566	2017-05-08	15:48:20	\N	\N	0	1115	342
567	2017-05-08	15:48:20	\N	\N	0	1116	344
568	2017-05-08	15:48:20	\N	\N	0	1117	345
569	2017-05-08	15:48:20	\N	\N	0	1123	346
570	2017-05-08	15:48:20	\N	\N	0	1124	347
571	2017-05-08	15:48:20	\N	\N	0	1126	348
572	2017-05-08	15:49:20	\N	\N	0	1128	349
573	2017-05-08	15:49:20	\N	\N	0	1129	350
574	2017-05-08	15:49:20	\N	\N	0	1130	351
575	2017-05-08	15:49:20	\N	\N	0	1131	352
576	2017-05-08	15:49:20	\N	\N	0	1132	353
577	2017-05-08	15:49:20	\N	\N	0	1133	354
578	2017-05-08	15:49:20	\N	\N	0	1134	355
579	2017-05-08	15:49:20	\N	\N	0	1137	356
580	2017-05-08	15:50:20	\N	\N	0	1148	358
581	2017-05-08	15:50:20	\N	\N	0	1148	357
582	2017-05-08	15:50:20	\N	\N	0	1152	359
583	2017-05-08	15:50:20	\N	\N	0	1153	360
584	2017-05-08	15:50:20	\N	\N	0	1154	361
585	2017-05-08	15:51:20	\N	\N	0	1160	362
586	2017-05-08	15:51:20	\N	\N	0	1161	363
587	2017-05-08	15:51:20	\N	\N	0	1162	364
588	2017-05-08	15:51:20	\N	\N	0	1163	365
589	2017-05-08	15:51:20	\N	\N	0	1164	366
590	2017-05-08	15:51:20	\N	\N	0	1165	367
591	2017-05-08	15:52:20	\N	\N	0	1168	368
592	2017-05-08	15:52:20	\N	\N	0	1171	369
593	2017-05-08	15:52:20	\N	\N	0	1174	370
594	2017-05-08	15:52:20	\N	\N	0	1175	371
595	2017-05-08	15:53:20	\N	\N	0	1179	372
596	2017-05-08	15:53:20	\N	\N	0	1179	373
597	2017-05-08	15:53:20	\N	\N	0	1180	374
598	2017-05-08	15:53:20	\N	\N	0	1182	375
599	2017-05-08	15:53:20	\N	\N	0	1183	376
602	2017-05-08	15:20:21	\N	\N	0	1193	379
603	2017-05-08	15:20:21	\N	\N	0	1195	380
604	2017-05-08	15:20:21	\N	\N	0	1196	381
605	2017-05-08	15:20:21	\N	\N	0	1199	382
606	2017-05-08	15:20:21	\N	\N	0	1204	383
607	2017-05-08	15:20:21	\N	\N	0	1206	384
608	2017-05-08	15:20:21	\N	\N	0	1207	385
609	2017-05-08	15:21:21	\N	\N	0	1208	386
611	2017-05-08	15:21:21	\N	\N	0	1212	308
612	2017-05-08	15:21:21	\N	\N	0	1213	309
613	2017-05-08	15:21:21	\N	\N	0	1214	310
614	2017-05-08	15:21:21	\N	\N	0	1221	311
615	2017-05-08	15:21:21	\N	\N	0	1223	312
616	2017-05-08	15:21:21	\N	\N	0	1224	313
617	2017-05-08	15:22:21	\N	\N	0	1231	314
618	2017-05-08	15:22:21	\N	\N	0	1232	315
619	2017-05-08	15:22:21	\N	\N	0	1236	316
620	2017-05-08	15:22:21	\N	\N	0	1238	317
621	2017-05-08	15:22:21	\N	\N	0	1240	318
622	2017-05-08	15:22:21	\N	\N	0	1241	319
623	2017-05-08	15:22:21	\N	\N	0	1242	320
624	2017-05-08	15:23:21	\N	\N	0	1244	321
625	2017-05-08	15:23:21	\N	\N	0	1245	322
626	2017-05-08	15:23:21	\N	\N	0	1246	324
627	2017-05-08	15:23:21	\N	\N	0	1246	323
628	2017-05-08	15:23:21	\N	\N	0	1247	325
631	2017-05-08	15:23:21	\N	\N	0	1251	328
632	2017-05-08	15:23:21	\N	\N	0	1252	329
633	2017-05-08	15:23:21	\N	\N	0	1253	330
634	2017-05-08	15:23:21	\N	\N	0	1254	331
635	2017-05-08	15:23:21	\N	\N	0	1256	332
636	2017-05-08	15:23:21	\N	\N	0	1257	333
637	2017-05-08	15:23:21	\N	\N	0	1258	334
638	2017-05-08	15:24:21	\N	\N	0	1259	335
639	2017-05-08	15:24:21	\N	\N	0	1260	336
640	2017-05-08	15:24:21	\N	\N	0	1261	337
641	2017-05-08	15:24:21	\N	\N	0	1261	338
601	2017-05-08	15:53:20	\N	\N	1	1185	378
630	2017-05-08	15:23:21	\N	\N	1	1250	327
610	2017-05-08	15:21:21	\N	\N	1	1211	307
600	2017-05-08	15:53:20	\N	\N	1	1185	377
550	2017-05-08	15:46:20	\N	\N	1	1092	327
642	2017-05-08	15:24:21	\N	\N	0	1263	339
643	2017-05-08	15:24:21	\N	\N	0	1266	340
644	2017-05-08	15:24:21	\N	\N	0	1267	341
645	2017-05-08	15:25:21	\N	\N	0	1273	343
646	2017-05-08	15:25:21	\N	\N	0	1273	342
647	2017-05-08	15:25:21	\N	\N	0	1274	344
648	2017-05-08	15:25:21	\N	\N	0	1275	345
649	2017-05-08	15:25:21	\N	\N	0	1281	346
650	2017-05-08	15:25:21	\N	\N	0	1282	347
651	2017-05-08	15:25:21	\N	\N	0	1284	348
652	2017-05-08	15:25:21	\N	\N	0	1286	349
653	2017-05-08	15:25:21	\N	\N	0	1287	350
654	2017-05-08	15:26:21	\N	\N	0	1288	351
655	2017-05-08	15:26:21	\N	\N	0	1289	352
656	2017-05-08	15:26:21	\N	\N	0	1290	353
657	2017-05-08	15:26:21	\N	\N	0	1291	354
658	2017-05-08	15:26:21	\N	\N	0	1292	355
659	2017-05-08	15:26:21	\N	\N	0	1295	356
660	2017-05-08	15:27:21	\N	\N	0	1306	358
661	2017-05-08	15:27:21	\N	\N	0	1306	357
662	2017-05-08	15:27:21	\N	\N	0	1310	359
663	2017-05-08	15:27:21	\N	\N	0	1311	360
664	2017-05-08	15:27:21	\N	\N	0	1312	361
665	2017-05-08	15:28:21	\N	\N	0	1318	362
666	2017-05-08	15:28:21	\N	\N	0	1319	363
667	2017-05-08	15:28:21	\N	\N	0	1320	364
668	2017-05-08	15:28:21	\N	\N	0	1321	365
669	2017-05-08	15:28:21	\N	\N	0	1322	366
670	2017-05-08	15:28:21	\N	\N	0	1323	367
671	2017-05-08	15:28:21	\N	\N	0	1326	368
672	2017-05-08	15:28:21	\N	\N	0	1329	369
673	2017-05-08	15:29:21	\N	\N	0	1332	370
674	2017-05-08	15:29:21	\N	\N	0	1333	371
675	2017-05-08	15:29:21	\N	\N	0	1337	372
676	2017-05-08	15:29:21	\N	\N	0	1337	373
677	2017-05-08	15:29:21	\N	\N	0	1338	374
678	2017-05-08	15:29:21	\N	\N	0	1340	375
679	2017-05-08	15:29:21	\N	\N	0	1341	376
549	2017-05-08	15:46:20	\N	\N	1	1092	326
629	2017-05-08	15:23:21	\N	\N	1	1250	326
680	2017-05-08	15:29:21	\N	\N	1	1343	377
681	2017-05-08	15:29:21	\N	\N	1	1343	378
\.


--
-- TOC entry 2647 (class 0 OID 0)
-- Dependencies: 220
-- Name: fato_celula_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('fato_celula_id_seq', 681, true);


--
-- TOC entry 2357 (class 0 OID 17013)
-- Dependencies: 213
-- Data for Name: fato_ciclo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY fato_ciclo (id, numero_identificador, mes, ano, ciclo, data_criacao, data_inativacao, hora_inativacao, hora_criacao) FROM stdin;
1032	00000515000005160000064800000652	5	2017	1	2017-05-08	\N	\N	15:41:20
1033	00000515000005160000064800000653	5	2017	1	2017-05-08	\N	\N	15:42:20
1034	00000515000005160000064800000654	5	2017	1	2017-05-08	\N	\N	15:42:20
1035	000005150000051600000655	5	2017	1	2017-05-08	\N	\N	15:42:20
1036	00000515000005160000065500000656	5	2017	1	2017-05-08	\N	\N	15:42:20
1037	00000515000005160000065500000657	5	2017	1	2017-05-08	\N	\N	15:42:20
1038	00000515000005160000065500000658	5	2017	1	2017-05-08	\N	\N	15:42:20
1039	00000515000005160000065500000659	5	2017	1	2017-05-08	\N	\N	15:42:20
1040	00000515000005160000065500000660	5	2017	1	2017-05-08	\N	\N	15:42:20
1041	000005150000051600000661	5	2017	1	2017-05-08	\N	\N	15:42:20
1042	00000515000005160000066100000662	5	2017	1	2017-05-08	\N	\N	15:42:20
1043	00000515000005160000066100000663	5	2017	1	2017-05-08	\N	\N	15:42:20
1044	00000515000005160000066100000664	5	2017	1	2017-05-08	\N	\N	15:42:20
1045	00000515000005160000066100000665	5	2017	1	2017-05-08	\N	\N	15:42:20
1046	00000515000005160000066100000666	5	2017	1	2017-05-08	\N	\N	15:42:20
1047	0000051500000516000006610000066600000667	5	2017	1	2017-05-08	\N	\N	15:43:20
1048	000005150000051600000668	5	2017	1	2017-05-08	\N	\N	15:43:20
1049	000005150000051600000669	5	2017	1	2017-05-08	\N	\N	15:43:20
1050	00000515000005160000066900000670	5	2017	1	2017-05-08	\N	\N	15:43:20
1051	00000515000005160000066900000671	5	2017	1	2017-05-08	\N	\N	15:43:20
1052	00000515000005160000066900000672	5	2017	1	2017-05-08	\N	\N	15:43:20
1053	0000051500000516	5	2017	1	2017-05-08	\N	\N	15:43:20
1054	000005150000051600000517	5	2017	1	2017-05-08	\N	\N	15:43:20
1055	00000515000005160000051700000518	5	2017	1	2017-05-08	\N	\N	15:43:20
1056	0000051500000516000005170000051800000519	5	2017	1	2017-05-08	\N	\N	15:43:20
1057	0000051500000516000005170000051800000520	5	2017	1	2017-05-08	\N	\N	15:43:20
1058	0000051500000516000005170000051800000521	5	2017	1	2017-05-08	\N	\N	15:43:20
1059	00000515000005160000051700000522	5	2017	1	2017-05-08	\N	\N	15:43:20
1060	0000051500000516000005170000052200000523	5	2017	1	2017-05-08	\N	\N	15:43:20
1061	0000051500000516000005170000052200000524	5	2017	1	2017-05-08	\N	\N	15:44:20
1062	0000051500000516000005170000052200000525	5	2017	1	2017-05-08	\N	\N	15:44:20
1063	00000515000005160000051700000526	5	2017	1	2017-05-08	\N	\N	15:44:20
1064	00000515000005160000051700000527	5	2017	1	2017-05-08	\N	\N	15:44:20
1065	00000515000005160000051700000528	5	2017	1	2017-05-08	\N	\N	15:44:20
1066	00000515000005160000051700000529	5	2017	1	2017-05-08	\N	\N	15:44:20
1067	0000051500000516000005170000052900000530	5	2017	1	2017-05-08	\N	\N	15:44:20
1068	0000051500000516000005170000052900000531	5	2017	1	2017-05-08	\N	\N	15:44:20
1069	0000051500000516000005170000052900000532	5	2017	1	2017-05-08	\N	\N	15:44:20
1070	0000051500000516000005170000052900000533	5	2017	1	2017-05-08	\N	\N	15:44:20
1071	0000051500000516000005170000052900000534	5	2017	1	2017-05-08	\N	\N	15:44:20
1072	0000051500000516000005170000052900000535	5	2017	1	2017-05-08	\N	\N	15:44:20
1073	00000515000005160000051700000536	5	2017	1	2017-05-08	\N	\N	15:44:20
1074	00000515000005160000051700000537	5	2017	1	2017-05-08	\N	\N	15:44:20
1075	0000051500000516000005170000053700000538	5	2017	1	2017-05-08	\N	\N	15:45:20
1076	0000051500000516000005170000053700000539	5	2017	1	2017-05-08	\N	\N	15:45:20
1077	0000051500000516000005170000053700000540	5	2017	1	2017-05-08	\N	\N	15:45:20
1078	00000515000005160000051700000541	5	2017	1	2017-05-08	\N	\N	15:45:20
1079	00000515000005160000051700000542	5	2017	1	2017-05-08	\N	\N	15:45:20
1080	00000515000005160000051700000543	5	2017	1	2017-05-08	\N	\N	15:45:20
1081	00000515000005160000051700000544	5	2017	1	2017-05-08	\N	\N	15:45:20
1082	00000515000005160000051700000545	5	2017	1	2017-05-08	\N	\N	15:45:20
1083	00000515000005160000051700000546	5	2017	1	2017-05-08	\N	\N	15:45:20
1084	00000515000005160000051700000547	5	2017	1	2017-05-08	\N	\N	15:45:20
1085	0000051500000516000005170000054700000548	5	2017	1	2017-05-08	\N	\N	15:45:20
1086	00000515000005160000051700000549	5	2017	1	2017-05-08	\N	\N	15:45:20
1087	00000515000005160000051700000550	5	2017	1	2017-05-08	\N	\N	15:45:20
1088	000005150000051600000551	5	2017	1	2017-05-08	\N	\N	15:45:20
1089	00000515000005160000055100000552	5	2017	1	2017-05-08	\N	\N	15:46:20
1090	0000051500000516000005510000055200000553	5	2017	1	2017-05-08	\N	\N	15:46:20
1091	0000051500000516000005510000055200000554	5	2017	1	2017-05-08	\N	\N	15:46:20
1092	00000515000005160000055100000555	5	2017	1	2017-05-08	\N	\N	15:46:20
1093	00000515000005160000055100000556	5	2017	1	2017-05-08	\N	\N	15:46:20
1094	0000051500000516000005510000055600000557	5	2017	1	2017-05-08	\N	\N	15:46:20
1095	0000051500000516000005510000055600000558	5	2017	1	2017-05-08	\N	\N	15:46:20
1096	0000051500000516000005510000055600000559	5	2017	1	2017-05-08	\N	\N	15:46:20
1097	0000051500000516000005510000055600000560	5	2017	1	2017-05-08	\N	\N	15:46:20
1098	00000515000005160000055100000561	5	2017	1	2017-05-08	\N	\N	15:46:20
1099	00000515000005160000055100000562	5	2017	1	2017-05-08	\N	\N	15:46:20
1100	00000515000005160000055100000563	5	2017	1	2017-05-08	\N	\N	15:46:20
1101	00000515000005160000055100000564	5	2017	1	2017-05-08	\N	\N	15:47:20
1102	0000051500000516000005510000056400000565	5	2017	1	2017-05-08	\N	\N	15:47:20
1103	00000515000005160000055100000566	5	2017	1	2017-05-08	\N	\N	15:47:20
1104	0000051500000516000005510000056600000567	5	2017	1	2017-05-08	\N	\N	15:47:20
1105	0000051500000516000005510000056600000568	5	2017	1	2017-05-08	\N	\N	15:47:20
1106	0000051500000516000005510000056600000569	5	2017	1	2017-05-08	\N	\N	15:47:20
1107	0000051500000516000005510000056600000570	5	2017	1	2017-05-08	\N	\N	15:47:20
1108	00000515000005160000055100000571	5	2017	1	2017-05-08	\N	\N	15:47:20
1109	00000515000005160000055100000572	5	2017	1	2017-05-08	\N	\N	15:47:20
1110	0000051500000516000005510000057200000573	5	2017	1	2017-05-08	\N	\N	15:47:20
1191	00000515000005160000064800000653	5	2017	2	2017-05-08	\N	\N	15:19:21
1111	0000051500000516000005510000057200000574	5	2017	1	2017-05-08	\N	\N	15:47:20
1112	0000051500000516000005510000057200000575	5	2017	1	2017-05-08	\N	\N	15:47:20
1113	0000051500000516000005510000057200000576	5	2017	1	2017-05-08	\N	\N	15:47:20
1114	0000051500000516000005510000057200000577	5	2017	1	2017-05-08	\N	\N	15:47:20
1115	000005150000051600000578	5	2017	1	2017-05-08	\N	\N	15:48:20
1116	00000515000005160000057800000579	5	2017	1	2017-05-08	\N	\N	15:48:20
1117	00000515000005160000057800000580	5	2017	1	2017-05-08	\N	\N	15:48:20
1118	0000051500000516000005780000058000000581	5	2017	1	2017-05-08	\N	\N	15:48:20
1119	0000051500000516000005780000058000000582	5	2017	1	2017-05-08	\N	\N	15:48:20
1120	0000051500000516000005780000058000000583	5	2017	1	2017-05-08	\N	\N	15:48:20
1121	0000051500000516000005780000058000000584	5	2017	1	2017-05-08	\N	\N	15:48:20
1122	0000051500000516000005780000058000000585	5	2017	1	2017-05-08	\N	\N	15:48:20
1123	00000515000005160000057800000586	5	2017	1	2017-05-08	\N	\N	15:48:20
1124	00000515000005160000057800000587	5	2017	1	2017-05-08	\N	\N	15:48:20
1125	0000051500000516000005780000058700000588	5	2017	1	2017-05-08	\N	\N	15:48:20
1126	00000515000005160000057800000589	5	2017	1	2017-05-08	\N	\N	15:48:20
1127	0000051500000516000005780000058900000590	5	2017	1	2017-05-08	\N	\N	15:48:20
1128	0000051500000516000005780000058900000591	5	2017	1	2017-05-08	\N	\N	15:48:20
1129	00000515000005160000057800000592	5	2017	1	2017-05-08	\N	\N	15:49:20
1130	000005150000051600000593	5	2017	1	2017-05-08	\N	\N	15:49:20
1131	00000515000005160000059300000594	5	2017	1	2017-05-08	\N	\N	15:49:20
1132	00000515000005160000059300000595	5	2017	1	2017-05-08	\N	\N	15:49:20
1133	00000515000005160000059300000596	5	2017	1	2017-05-08	\N	\N	15:49:20
1134	000005150000051600000597	5	2017	1	2017-05-08	\N	\N	15:49:20
1135	00000515000005160000059700000598	5	2017	1	2017-05-08	\N	\N	15:49:20
1136	00000515000005160000059700000599	5	2017	1	2017-05-08	\N	\N	15:49:20
1137	000005150000051600000600	5	2017	1	2017-05-08	\N	\N	15:49:20
1138	00000515000005160000060000000601	5	2017	1	2017-05-08	\N	\N	15:49:20
1139	0000051500000516000006000000060100000602	5	2017	1	2017-05-08	\N	\N	15:49:20
1140	0000051500000516000006000000060100000603	5	2017	1	2017-05-08	\N	\N	15:49:20
1141	0000051500000516000006000000060100000604	5	2017	1	2017-05-08	\N	\N	15:49:20
1142	00000515000005160000060000000605	5	2017	1	2017-05-08	\N	\N	15:49:20
1143	00000515000005160000060000000606	5	2017	1	2017-05-08	\N	\N	15:50:20
1144	00000515000005160000060000000607	5	2017	1	2017-05-08	\N	\N	15:50:20
1145	00000515000005160000060000000608	5	2017	1	2017-05-08	\N	\N	15:50:20
1146	00000515000005160000060000000609	5	2017	1	2017-05-08	\N	\N	15:50:20
1147	00000515000005160000060000000610	5	2017	1	2017-05-08	\N	\N	15:50:20
1148	000005150000051600000611	5	2017	1	2017-05-08	\N	\N	15:50:20
1149	00000515000005160000061100000612	5	2017	1	2017-05-08	\N	\N	15:50:20
1150	00000515000005160000061100000613	5	2017	1	2017-05-08	\N	\N	15:50:20
1151	00000515000005160000061100000614	5	2017	1	2017-05-08	\N	\N	15:50:20
1152	000005150000051600000615	5	2017	1	2017-05-08	\N	\N	15:50:20
1153	00000515000005160000061500000616	5	2017	1	2017-05-08	\N	\N	15:50:20
1154	00000515000005160000061500000617	5	2017	1	2017-05-08	\N	\N	15:50:20
1155	0000051500000516000006150000061700000618	5	2017	1	2017-05-08	\N	\N	15:50:20
1156	0000051500000516000006150000061700000619	5	2017	1	2017-05-08	\N	\N	15:50:20
1157	0000051500000516000006150000061700000620	5	2017	1	2017-05-08	\N	\N	15:51:20
1158	0000051500000516000006150000061700000621	5	2017	1	2017-05-08	\N	\N	15:51:20
1159	0000051500000516000006150000061700000622	5	2017	1	2017-05-08	\N	\N	15:51:20
1160	00000515000005160000061500000623	5	2017	1	2017-05-08	\N	\N	15:51:20
1161	00000515000005160000061500000624	5	2017	1	2017-05-08	\N	\N	15:51:20
1162	00000515000005160000061500000625	5	2017	1	2017-05-08	\N	\N	15:51:20
1163	00000515000005160000061500000626	5	2017	1	2017-05-08	\N	\N	15:51:20
1164	00000515000005160000061500000627	5	2017	1	2017-05-08	\N	\N	15:51:20
1165	00000515000005160000061500000628	5	2017	1	2017-05-08	\N	\N	15:51:20
1166	0000051500000516000006150000062800000629	5	2017	1	2017-05-08	\N	\N	15:51:20
1167	0000051500000516000006150000062800000630	5	2017	1	2017-05-08	\N	\N	15:51:20
1168	00000515000005160000061500000631	5	2017	1	2017-05-08	\N	\N	15:51:20
1169	0000051500000516000006150000063100000632	5	2017	1	2017-05-08	\N	\N	15:52:20
1170	0000051500000516000006150000063100000633	5	2017	1	2017-05-08	\N	\N	15:52:20
1171	00000515000005160000061500000634	5	2017	1	2017-05-08	\N	\N	15:52:20
1172	0000051500000516000006150000063400000635	5	2017	1	2017-05-08	\N	\N	15:52:20
1173	0000051500000516000006150000063400000636	5	2017	1	2017-05-08	\N	\N	15:52:20
1174	00000515000005160000061500000637	5	2017	1	2017-05-08	\N	\N	15:52:20
1175	00000515000005160000061500000638	5	2017	1	2017-05-08	\N	\N	15:52:20
1176	0000051500000516000006150000063800000639	5	2017	1	2017-05-08	\N	\N	15:52:20
1177	00000515000005160000061500000640	5	2017	1	2017-05-08	\N	\N	15:52:20
1178	00000515000005160000061500000641	5	2017	1	2017-05-08	\N	\N	15:52:20
1179	000005150000051600000642	5	2017	1	2017-05-08	\N	\N	15:52:20
1180	00000515000005160000064200000643	5	2017	1	2017-05-08	\N	\N	15:53:20
1181	00000515000005160000064200000644	5	2017	1	2017-05-08	\N	\N	15:53:20
1182	000005150000051600000645	5	2017	1	2017-05-08	\N	\N	15:53:20
1183	000005150000051600000646	5	2017	1	2017-05-08	\N	\N	15:53:20
1184	00000515000005160000064600000647	5	2017	1	2017-05-08	\N	\N	15:53:20
1185	000005150000051600000648	5	2017	1	2017-05-08	\N	\N	15:53:20
1186	00000515000005160000064800000649	5	2017	1	2017-05-08	\N	\N	15:53:20
1187	00000515000005160000064800000650	5	2017	1	2017-05-08	\N	\N	15:53:20
1188	00000515000005160000064800000651	5	2017	1	2017-05-08	\N	\N	15:53:20
1189	00000515	5	2017	1	2017-05-08	\N	\N	15:53:20
1190	00000515000005160000064800000652	5	2017	2	2017-05-08	\N	\N	15:19:21
1192	00000515000005160000064800000654	5	2017	2	2017-05-08	\N	\N	15:20:21
1193	000005150000051600000655	5	2017	2	2017-05-08	\N	\N	15:20:21
1194	00000515000005160000065500000656	5	2017	2	2017-05-08	\N	\N	15:20:21
1195	00000515000005160000065500000657	5	2017	2	2017-05-08	\N	\N	15:20:21
1196	00000515000005160000065500000658	5	2017	2	2017-05-08	\N	\N	15:20:21
1197	00000515000005160000065500000659	5	2017	2	2017-05-08	\N	\N	15:20:21
1198	00000515000005160000065500000660	5	2017	2	2017-05-08	\N	\N	15:20:21
1199	000005150000051600000661	5	2017	2	2017-05-08	\N	\N	15:20:21
1200	00000515000005160000066100000662	5	2017	2	2017-05-08	\N	\N	15:20:21
1201	00000515000005160000066100000663	5	2017	2	2017-05-08	\N	\N	15:20:21
1202	00000515000005160000066100000664	5	2017	2	2017-05-08	\N	\N	15:20:21
1203	00000515000005160000066100000665	5	2017	2	2017-05-08	\N	\N	15:20:21
1204	00000515000005160000066100000666	5	2017	2	2017-05-08	\N	\N	15:20:21
1205	0000051500000516000006610000066600000667	5	2017	2	2017-05-08	\N	\N	15:20:21
1206	000005150000051600000668	5	2017	2	2017-05-08	\N	\N	15:20:21
1207	000005150000051600000669	5	2017	2	2017-05-08	\N	\N	15:20:21
1208	00000515000005160000066900000670	5	2017	2	2017-05-08	\N	\N	15:20:21
1209	00000515000005160000066900000671	5	2017	2	2017-05-08	\N	\N	15:21:21
1210	00000515000005160000066900000672	5	2017	2	2017-05-08	\N	\N	15:21:21
1211	0000051500000516	5	2017	2	2017-05-08	\N	\N	15:21:21
1212	000005150000051600000517	5	2017	2	2017-05-08	\N	\N	15:21:21
1213	00000515000005160000051700000518	5	2017	2	2017-05-08	\N	\N	15:21:21
1214	0000051500000516000005170000051800000519	5	2017	2	2017-05-08	\N	\N	15:21:21
1215	0000051500000516000005170000051800000520	5	2017	2	2017-05-08	\N	\N	15:21:21
1216	0000051500000516000005170000051800000521	5	2017	2	2017-05-08	\N	\N	15:21:21
1217	00000515000005160000051700000522	5	2017	2	2017-05-08	\N	\N	15:21:21
1218	0000051500000516000005170000052200000523	5	2017	2	2017-05-08	\N	\N	15:21:21
1219	0000051500000516000005170000052200000524	5	2017	2	2017-05-08	\N	\N	15:21:21
1220	0000051500000516000005170000052200000525	5	2017	2	2017-05-08	\N	\N	15:21:21
1221	00000515000005160000051700000526	5	2017	2	2017-05-08	\N	\N	15:21:21
1222	00000515000005160000051700000527	5	2017	2	2017-05-08	\N	\N	15:21:21
1223	00000515000005160000051700000528	5	2017	2	2017-05-08	\N	\N	15:21:21
1224	00000515000005160000051700000529	5	2017	2	2017-05-08	\N	\N	15:21:21
1225	0000051500000516000005170000052900000530	5	2017	2	2017-05-08	\N	\N	15:21:21
1226	0000051500000516000005170000052900000531	5	2017	2	2017-05-08	\N	\N	15:21:21
1227	0000051500000516000005170000052900000532	5	2017	2	2017-05-08	\N	\N	15:21:21
1228	0000051500000516000005170000052900000533	5	2017	2	2017-05-08	\N	\N	15:22:21
1229	0000051500000516000005170000052900000534	5	2017	2	2017-05-08	\N	\N	15:22:21
1230	0000051500000516000005170000052900000535	5	2017	2	2017-05-08	\N	\N	15:22:21
1231	00000515000005160000051700000536	5	2017	2	2017-05-08	\N	\N	15:22:21
1232	00000515000005160000051700000537	5	2017	2	2017-05-08	\N	\N	15:22:21
1233	0000051500000516000005170000053700000538	5	2017	2	2017-05-08	\N	\N	15:22:21
1234	0000051500000516000005170000053700000539	5	2017	2	2017-05-08	\N	\N	15:22:21
1235	0000051500000516000005170000053700000540	5	2017	2	2017-05-08	\N	\N	15:22:21
1236	00000515000005160000051700000541	5	2017	2	2017-05-08	\N	\N	15:22:21
1237	00000515000005160000051700000542	5	2017	2	2017-05-08	\N	\N	15:22:21
1238	00000515000005160000051700000543	5	2017	2	2017-05-08	\N	\N	15:22:21
1239	00000515000005160000051700000544	5	2017	2	2017-05-08	\N	\N	15:22:21
1240	00000515000005160000051700000545	5	2017	2	2017-05-08	\N	\N	15:22:21
1241	00000515000005160000051700000546	5	2017	2	2017-05-08	\N	\N	15:22:21
1242	00000515000005160000051700000547	5	2017	2	2017-05-08	\N	\N	15:22:21
1243	0000051500000516000005170000054700000548	5	2017	2	2017-05-08	\N	\N	15:22:21
1244	00000515000005160000051700000549	5	2017	2	2017-05-08	\N	\N	15:23:21
1245	00000515000005160000051700000550	5	2017	2	2017-05-08	\N	\N	15:23:21
1246	000005150000051600000551	5	2017	2	2017-05-08	\N	\N	15:23:21
1247	00000515000005160000055100000552	5	2017	2	2017-05-08	\N	\N	15:23:21
1248	0000051500000516000005510000055200000553	5	2017	2	2017-05-08	\N	\N	15:23:21
1249	0000051500000516000005510000055200000554	5	2017	2	2017-05-08	\N	\N	15:23:21
1250	00000515000005160000055100000555	5	2017	2	2017-05-08	\N	\N	15:23:21
1251	00000515000005160000055100000556	5	2017	2	2017-05-08	\N	\N	15:23:21
1252	0000051500000516000005510000055600000557	5	2017	2	2017-05-08	\N	\N	15:23:21
1253	0000051500000516000005510000055600000558	5	2017	2	2017-05-08	\N	\N	15:23:21
1254	0000051500000516000005510000055600000559	5	2017	2	2017-05-08	\N	\N	15:23:21
1255	0000051500000516000005510000055600000560	5	2017	2	2017-05-08	\N	\N	15:23:21
1256	00000515000005160000055100000561	5	2017	2	2017-05-08	\N	\N	15:23:21
1257	00000515000005160000055100000562	5	2017	2	2017-05-08	\N	\N	15:23:21
1258	00000515000005160000055100000563	5	2017	2	2017-05-08	\N	\N	15:23:21
1259	00000515000005160000055100000564	5	2017	2	2017-05-08	\N	\N	15:24:21
1260	0000051500000516000005510000056400000565	5	2017	2	2017-05-08	\N	\N	15:24:21
1261	00000515000005160000055100000566	5	2017	2	2017-05-08	\N	\N	15:24:21
1262	0000051500000516000005510000056600000567	5	2017	2	2017-05-08	\N	\N	15:24:21
1263	0000051500000516000005510000056600000568	5	2017	2	2017-05-08	\N	\N	15:24:21
1264	0000051500000516000005510000056600000569	5	2017	2	2017-05-08	\N	\N	15:24:21
1265	0000051500000516000005510000056600000570	5	2017	2	2017-05-08	\N	\N	15:24:21
1266	00000515000005160000055100000571	5	2017	2	2017-05-08	\N	\N	15:24:21
1267	00000515000005160000055100000572	5	2017	2	2017-05-08	\N	\N	15:24:21
1268	0000051500000516000005510000057200000573	5	2017	2	2017-05-08	\N	\N	15:24:21
1269	0000051500000516000005510000057200000574	5	2017	2	2017-05-08	\N	\N	15:24:21
1270	0000051500000516000005510000057200000575	5	2017	2	2017-05-08	\N	\N	15:24:21
1271	0000051500000516000005510000057200000576	5	2017	2	2017-05-08	\N	\N	15:24:21
1272	0000051500000516000005510000057200000577	5	2017	2	2017-05-08	\N	\N	15:24:21
1273	000005150000051600000578	5	2017	2	2017-05-08	\N	\N	15:24:21
1274	00000515000005160000057800000579	5	2017	2	2017-05-08	\N	\N	15:25:21
1275	00000515000005160000057800000580	5	2017	2	2017-05-08	\N	\N	15:25:21
1276	0000051500000516000005780000058000000581	5	2017	2	2017-05-08	\N	\N	15:25:21
1277	0000051500000516000005780000058000000582	5	2017	2	2017-05-08	\N	\N	15:25:21
1278	0000051500000516000005780000058000000583	5	2017	2	2017-05-08	\N	\N	15:25:21
1279	0000051500000516000005780000058000000584	5	2017	2	2017-05-08	\N	\N	15:25:21
1280	0000051500000516000005780000058000000585	5	2017	2	2017-05-08	\N	\N	15:25:21
1281	00000515000005160000057800000586	5	2017	2	2017-05-08	\N	\N	15:25:21
1282	00000515000005160000057800000587	5	2017	2	2017-05-08	\N	\N	15:25:21
1283	0000051500000516000005780000058700000588	5	2017	2	2017-05-08	\N	\N	15:25:21
1284	00000515000005160000057800000589	5	2017	2	2017-05-08	\N	\N	15:25:21
1285	0000051500000516000005780000058900000590	5	2017	2	2017-05-08	\N	\N	15:25:21
1286	0000051500000516000005780000058900000591	5	2017	2	2017-05-08	\N	\N	15:25:21
1287	00000515000005160000057800000592	5	2017	2	2017-05-08	\N	\N	15:25:21
1288	000005150000051600000593	5	2017	2	2017-05-08	\N	\N	15:25:21
1289	00000515000005160000059300000594	5	2017	2	2017-05-08	\N	\N	15:26:21
1290	00000515000005160000059300000595	5	2017	2	2017-05-08	\N	\N	15:26:21
1291	00000515000005160000059300000596	5	2017	2	2017-05-08	\N	\N	15:26:21
1292	000005150000051600000597	5	2017	2	2017-05-08	\N	\N	15:26:21
1293	00000515000005160000059700000598	5	2017	2	2017-05-08	\N	\N	15:26:21
1294	00000515000005160000059700000599	5	2017	2	2017-05-08	\N	\N	15:26:21
1295	000005150000051600000600	5	2017	2	2017-05-08	\N	\N	15:26:21
1296	00000515000005160000060000000601	5	2017	2	2017-05-08	\N	\N	15:26:21
1297	0000051500000516000006000000060100000602	5	2017	2	2017-05-08	\N	\N	15:26:21
1298	0000051500000516000006000000060100000603	5	2017	2	2017-05-08	\N	\N	15:26:21
1299	0000051500000516000006000000060100000604	5	2017	2	2017-05-08	\N	\N	15:26:21
1300	00000515000005160000060000000605	5	2017	2	2017-05-08	\N	\N	15:26:21
1301	00000515000005160000060000000606	5	2017	2	2017-05-08	\N	\N	15:26:21
1302	00000515000005160000060000000607	5	2017	2	2017-05-08	\N	\N	15:26:21
1303	00000515000005160000060000000608	5	2017	2	2017-05-08	\N	\N	15:27:21
1304	00000515000005160000060000000609	5	2017	2	2017-05-08	\N	\N	15:27:21
1305	00000515000005160000060000000610	5	2017	2	2017-05-08	\N	\N	15:27:21
1306	000005150000051600000611	5	2017	2	2017-05-08	\N	\N	15:27:21
1307	00000515000005160000061100000612	5	2017	2	2017-05-08	\N	\N	15:27:21
1308	00000515000005160000061100000613	5	2017	2	2017-05-08	\N	\N	15:27:21
1309	00000515000005160000061100000614	5	2017	2	2017-05-08	\N	\N	15:27:21
1310	000005150000051600000615	5	2017	2	2017-05-08	\N	\N	15:27:21
1311	00000515000005160000061500000616	5	2017	2	2017-05-08	\N	\N	15:27:21
1312	00000515000005160000061500000617	5	2017	2	2017-05-08	\N	\N	15:27:21
1313	0000051500000516000006150000061700000618	5	2017	2	2017-05-08	\N	\N	15:27:21
1314	0000051500000516000006150000061700000619	5	2017	2	2017-05-08	\N	\N	15:27:21
1315	0000051500000516000006150000061700000620	5	2017	2	2017-05-08	\N	\N	15:27:21
1316	0000051500000516000006150000061700000621	5	2017	2	2017-05-08	\N	\N	15:27:21
1317	0000051500000516000006150000061700000622	5	2017	2	2017-05-08	\N	\N	15:27:21
1318	00000515000005160000061500000623	5	2017	2	2017-05-08	\N	\N	15:27:21
1319	00000515000005160000061500000624	5	2017	2	2017-05-08	\N	\N	15:28:21
1320	00000515000005160000061500000625	5	2017	2	2017-05-08	\N	\N	15:28:21
1321	00000515000005160000061500000626	5	2017	2	2017-05-08	\N	\N	15:28:21
1322	00000515000005160000061500000627	5	2017	2	2017-05-08	\N	\N	15:28:21
1323	00000515000005160000061500000628	5	2017	2	2017-05-08	\N	\N	15:28:21
1324	0000051500000516000006150000062800000629	5	2017	2	2017-05-08	\N	\N	15:28:21
1325	0000051500000516000006150000062800000630	5	2017	2	2017-05-08	\N	\N	15:28:21
1326	00000515000005160000061500000631	5	2017	2	2017-05-08	\N	\N	15:28:21
1327	0000051500000516000006150000063100000632	5	2017	2	2017-05-08	\N	\N	15:28:21
1328	0000051500000516000006150000063100000633	5	2017	2	2017-05-08	\N	\N	15:28:21
1329	00000515000005160000061500000634	5	2017	2	2017-05-08	\N	\N	15:28:21
1330	0000051500000516000006150000063400000635	5	2017	2	2017-05-08	\N	\N	15:28:21
1331	0000051500000516000006150000063400000636	5	2017	2	2017-05-08	\N	\N	15:28:21
1332	00000515000005160000061500000637	5	2017	2	2017-05-08	\N	\N	15:29:21
1333	00000515000005160000061500000638	5	2017	2	2017-05-08	\N	\N	15:29:21
1334	0000051500000516000006150000063800000639	5	2017	2	2017-05-08	\N	\N	15:29:21
1335	00000515000005160000061500000640	5	2017	2	2017-05-08	\N	\N	15:29:21
1336	00000515000005160000061500000641	5	2017	2	2017-05-08	\N	\N	15:29:21
1337	000005150000051600000642	5	2017	2	2017-05-08	\N	\N	15:29:21
1338	00000515000005160000064200000643	5	2017	2	2017-05-08	\N	\N	15:29:21
1339	00000515000005160000064200000644	5	2017	2	2017-05-08	\N	\N	15:29:21
1340	000005150000051600000645	5	2017	2	2017-05-08	\N	\N	15:29:21
1341	000005150000051600000646	5	2017	2	2017-05-08	\N	\N	15:29:21
1342	00000515000005160000064600000647	5	2017	2	2017-05-08	\N	\N	15:29:21
1343	000005150000051600000648	5	2017	2	2017-05-08	\N	\N	15:29:21
1344	00000515000005160000064800000649	5	2017	2	2017-05-08	\N	\N	15:29:21
1345	00000515000005160000064800000650	5	2017	2	2017-05-08	\N	\N	15:29:21
1346	00000515000005160000064800000651	5	2017	2	2017-05-08	\N	\N	15:29:21
1347	00000515	5	2017	2	2017-05-08	\N	\N	15:29:21
1348	000005150000051600000648	5	2017	3	2017-05-16	\N	\N	08:14:59
1349	0000051500000516	5	2017	3	2017-05-16	\N	\N	10:14:28
1350	00000515000005160000055100000555	5	2017	3	2017-05-16	\N	\N	18:49:55
\.


--
-- TOC entry 2648 (class 0 OID 0)
-- Dependencies: 212
-- Name: fato_ciclo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('fato_ciclo_id_seq', 1350, true);


--
-- TOC entry 2362 (class 0 OID 17341)
-- Dependencies: 218
-- Data for Name: fato_lider; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY fato_lider (id, numero_identificador, lideres, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1093	00000515000005160000055100000556	2	2017-05-08	15:46:20	\N	\N
1094	0000051500000516000005510000055600000557	1	2017-05-08	15:46:20	\N	\N
1095	0000051500000516000005510000055600000558	1	2017-05-08	15:46:20	\N	\N
1096	0000051500000516000005510000055600000559	1	2017-05-08	15:46:20	\N	\N
1097	0000051500000516000005510000055600000560	0	2017-05-08	15:46:20	\N	\N
1098	00000515000005160000055100000561	1	2017-05-08	15:46:20	\N	\N
1099	00000515000005160000055100000562	1	2017-05-08	15:46:20	\N	\N
1100	00000515000005160000055100000563	1	2017-05-08	15:47:20	\N	\N
1101	00000515000005160000055100000564	1	2017-05-08	15:47:20	\N	\N
1102	0000051500000516000005510000056400000565	1	2017-05-08	15:47:20	\N	\N
1103	00000515000005160000055100000566	1	2017-05-08	15:47:20	\N	\N
1104	0000051500000516000005510000056600000567	0	2017-05-08	15:47:20	\N	\N
1105	0000051500000516000005510000056600000568	1	2017-05-08	15:47:20	\N	\N
1106	0000051500000516000005510000056600000569	0	2017-05-08	15:47:20	\N	\N
1107	0000051500000516000005510000056600000570	0	2017-05-08	15:47:20	\N	\N
1108	00000515000005160000055100000571	1	2017-05-08	15:47:20	\N	\N
1109	00000515000005160000055100000572	2	2017-05-08	15:47:20	\N	\N
1110	0000051500000516000005510000057200000573	0	2017-05-08	15:47:20	\N	\N
1111	0000051500000516000005510000057200000574	0	2017-05-08	15:47:20	\N	\N
1112	0000051500000516000005510000057200000575	0	2017-05-08	15:47:20	\N	\N
1113	0000051500000516000005510000057200000576	0	2017-05-08	15:47:20	\N	\N
1114	0000051500000516000005510000057200000577	0	2017-05-08	15:48:20	\N	\N
1115	000005150000051600000578	2	2017-05-08	15:48:20	\N	\N
1116	00000515000005160000057800000579	1	2017-05-08	15:48:20	\N	\N
1117	00000515000005160000057800000580	1	2017-05-08	15:48:20	\N	\N
1118	0000051500000516000005780000058000000581	0	2017-05-08	15:48:20	\N	\N
1119	0000051500000516000005780000058000000582	0	2017-05-08	15:48:20	\N	\N
1120	0000051500000516000005780000058000000583	0	2017-05-08	15:48:20	\N	\N
1121	0000051500000516000005780000058000000584	0	2017-05-08	15:48:20	\N	\N
1122	0000051500000516000005780000058000000585	0	2017-05-08	15:48:20	\N	\N
1123	00000515000005160000057800000586	1	2017-05-08	15:48:20	\N	\N
1124	00000515000005160000057800000587	1	2017-05-08	15:48:20	\N	\N
1125	0000051500000516000005780000058700000588	0	2017-05-08	15:48:20	\N	\N
1126	00000515000005160000057800000589	1	2017-05-08	15:48:20	\N	\N
1127	0000051500000516000005780000058900000590	0	2017-05-08	15:48:20	\N	\N
1128	0000051500000516000005780000058900000591	1	2017-05-08	15:49:20	\N	\N
1129	00000515000005160000057800000592	2	2017-05-08	15:49:20	\N	\N
1130	000005150000051600000593	2	2017-05-08	15:49:20	\N	\N
1131	00000515000005160000059300000594	1	2017-05-08	15:49:20	\N	\N
1132	00000515000005160000059300000595	1	2017-05-08	15:49:20	\N	\N
1133	00000515000005160000059300000596	2	2017-05-08	15:49:20	\N	\N
1134	000005150000051600000597	1	2017-05-08	15:49:20	\N	\N
1135	00000515000005160000059700000598	0	2017-05-08	15:49:20	\N	\N
1136	00000515000005160000059700000599	0	2017-05-08	15:49:20	\N	\N
1137	000005150000051600000600	1	2017-05-08	15:49:20	\N	\N
1138	00000515000005160000060000000601	0	2017-05-08	15:49:20	\N	\N
1139	0000051500000516000006000000060100000602	0	2017-05-08	15:49:20	\N	\N
1140	0000051500000516000006000000060100000603	0	2017-05-08	15:49:20	\N	\N
1141	0000051500000516000006000000060100000604	0	2017-05-08	15:49:20	\N	\N
1142	00000515000005160000060000000605	0	2017-05-08	15:50:20	\N	\N
1143	00000515000005160000060000000606	0	2017-05-08	15:50:20	\N	\N
1144	00000515000005160000060000000607	0	2017-05-08	15:50:20	\N	\N
1145	00000515000005160000060000000608	0	2017-05-08	15:50:20	\N	\N
1146	00000515000005160000060000000609	0	2017-05-08	15:50:20	\N	\N
1147	00000515000005160000060000000610	0	2017-05-08	15:50:20	\N	\N
1148	000005150000051600000611	2	2017-05-08	15:50:20	\N	\N
1149	00000515000005160000061100000612	0	2017-05-08	15:50:20	\N	\N
1150	00000515000005160000061100000613	0	2017-05-08	15:50:20	\N	\N
1151	00000515000005160000061100000614	0	2017-05-08	15:50:20	\N	\N
1152	000005150000051600000615	2	2017-05-08	15:50:20	\N	\N
1153	00000515000005160000061500000616	1	2017-05-08	15:50:20	\N	\N
1154	00000515000005160000061500000617	1	2017-05-08	15:50:20	\N	\N
1155	0000051500000516000006150000061700000618	0	2017-05-08	15:50:20	\N	\N
1156	0000051500000516000006150000061700000619	0	2017-05-08	15:51:20	\N	\N
1157	0000051500000516000006150000061700000620	0	2017-05-08	15:51:20	\N	\N
1158	0000051500000516000006150000061700000621	0	2017-05-08	15:51:20	\N	\N
1159	0000051500000516000006150000061700000622	0	2017-05-08	15:51:20	\N	\N
1160	00000515000005160000061500000623	1	2017-05-08	15:51:20	\N	\N
1161	00000515000005160000061500000624	1	2017-05-08	15:51:20	\N	\N
1162	00000515000005160000061500000625	1	2017-05-08	15:51:20	\N	\N
1163	00000515000005160000061500000626	1	2017-05-08	15:51:20	\N	\N
1164	00000515000005160000061500000627	1	2017-05-08	15:51:20	\N	\N
1165	00000515000005160000061500000628	1	2017-05-08	15:51:20	\N	\N
1166	0000051500000516000006150000062800000629	0	2017-05-08	15:51:20	\N	\N
1167	0000051500000516000006150000062800000630	0	2017-05-08	15:51:20	\N	\N
1168	00000515000005160000061500000631	1	2017-05-08	15:52:20	\N	\N
1169	0000051500000516000006150000063100000632	0	2017-05-08	15:52:20	\N	\N
1170	0000051500000516000006150000063100000633	0	2017-05-08	15:52:20	\N	\N
1171	00000515000005160000061500000634	1	2017-05-08	15:52:20	\N	\N
1172	0000051500000516000006150000063400000635	0	2017-05-08	15:52:20	\N	\N
1173	0000051500000516000006150000063400000636	0	2017-05-08	15:52:20	\N	\N
1174	00000515000005160000061500000637	1	2017-05-08	15:52:20	\N	\N
1175	00000515000005160000061500000638	1	2017-05-08	15:52:20	\N	\N
1176	0000051500000516000006150000063800000639	0	2017-05-08	15:52:20	\N	\N
1177	00000515000005160000061500000640	0	2017-05-08	15:52:20	\N	\N
1178	00000515000005160000061500000641	0	2017-05-08	15:52:20	\N	\N
1179	000005150000051600000642	2	2017-05-08	15:53:20	\N	\N
1180	00000515000005160000064200000643	1	2017-05-08	15:53:20	\N	\N
1181	00000515000005160000064200000644	0	2017-05-08	15:53:20	\N	\N
1032	00000515000005160000064800000652	0	2017-05-08	15:42:20	\N	\N
1033	00000515000005160000064800000653	0	2017-05-08	15:42:20	\N	\N
1034	00000515000005160000064800000654	0	2017-05-08	15:42:20	\N	\N
1035	000005150000051600000655	1	2017-05-08	15:42:20	\N	\N
1036	00000515000005160000065500000656	0	2017-05-08	15:42:20	\N	\N
1037	00000515000005160000065500000657	1	2017-05-08	15:42:20	\N	\N
1038	00000515000005160000065500000658	1	2017-05-08	15:42:20	\N	\N
1039	00000515000005160000065500000659	0	2017-05-08	15:42:20	\N	\N
1040	00000515000005160000065500000660	0	2017-05-08	15:42:20	\N	\N
1041	000005150000051600000661	1	2017-05-08	15:42:20	\N	\N
1042	00000515000005160000066100000662	0	2017-05-08	15:42:20	\N	\N
1043	00000515000005160000066100000663	0	2017-05-08	15:42:20	\N	\N
1044	00000515000005160000066100000664	0	2017-05-08	15:42:20	\N	\N
1045	00000515000005160000066100000665	0	2017-05-08	15:42:20	\N	\N
1046	00000515000005160000066100000666	1	2017-05-08	15:43:20	\N	\N
1047	0000051500000516000006610000066600000667	0	2017-05-08	15:43:20	\N	\N
1048	000005150000051600000668	1	2017-05-08	15:43:20	\N	\N
1049	000005150000051600000669	2	2017-05-08	15:43:20	\N	\N
1050	00000515000005160000066900000670	1	2017-05-08	15:43:20	\N	\N
1051	00000515000005160000066900000671	0	2017-05-08	15:43:20	\N	\N
1052	00000515000005160000066900000672	0	2017-05-08	15:43:20	\N	\N
1053	0000051500000516	2	2017-05-08	15:43:20	\N	\N
1054	000005150000051600000517	2	2017-05-08	15:43:20	\N	\N
1055	00000515000005160000051700000518	1	2017-05-08	15:43:20	\N	\N
1056	0000051500000516000005170000051800000519	1	2017-05-08	15:43:20	\N	\N
1057	0000051500000516000005170000051800000520	0	2017-05-08	15:43:20	\N	\N
1058	0000051500000516000005170000051800000521	0	2017-05-08	15:43:20	\N	\N
1059	00000515000005160000051700000522	0	2017-05-08	15:43:20	\N	\N
1060	0000051500000516000005170000052200000523	0	2017-05-08	15:44:20	\N	\N
1061	0000051500000516000005170000052200000524	0	2017-05-08	15:44:20	\N	\N
1062	0000051500000516000005170000052200000525	0	2017-05-08	15:44:20	\N	\N
1063	00000515000005160000051700000526	1	2017-05-08	15:44:20	\N	\N
1064	00000515000005160000051700000527	0	2017-05-08	15:44:20	\N	\N
1065	00000515000005160000051700000528	2	2017-05-08	15:44:20	\N	\N
1066	00000515000005160000051700000529	1	2017-05-08	15:44:20	\N	\N
1067	0000051500000516000005170000052900000530	0	2017-05-08	15:44:20	\N	\N
1068	0000051500000516000005170000052900000531	0	2017-05-08	15:44:20	\N	\N
1069	0000051500000516000005170000052900000532	0	2017-05-08	15:44:20	\N	\N
1070	0000051500000516000005170000052900000533	0	2017-05-08	15:44:20	\N	\N
1071	0000051500000516000005170000052900000534	0	2017-05-08	15:44:20	\N	\N
1072	0000051500000516000005170000052900000535	0	2017-05-08	15:44:20	\N	\N
1073	00000515000005160000051700000536	2	2017-05-08	15:44:20	\N	\N
1074	00000515000005160000051700000537	1	2017-05-08	15:44:20	\N	\N
1075	0000051500000516000005170000053700000538	0	2017-05-08	15:45:20	\N	\N
1076	0000051500000516000005170000053700000539	0	2017-05-08	15:45:20	\N	\N
1077	0000051500000516000005170000053700000540	0	2017-05-08	15:45:20	\N	\N
1078	00000515000005160000051700000541	1	2017-05-08	15:45:20	\N	\N
1079	00000515000005160000051700000542	0	2017-05-08	15:45:20	\N	\N
1080	00000515000005160000051700000543	1	2017-05-08	15:45:20	\N	\N
1081	00000515000005160000051700000544	0	2017-05-08	15:45:20	\N	\N
1082	00000515000005160000051700000545	1	2017-05-08	15:45:20	\N	\N
1083	00000515000005160000051700000546	1	2017-05-08	15:45:20	\N	\N
1084	00000515000005160000051700000547	1	2017-05-08	15:45:20	\N	\N
1085	0000051500000516000005170000054700000548	0	2017-05-08	15:45:20	\N	\N
1086	00000515000005160000051700000549	1	2017-05-08	15:45:20	\N	\N
1087	00000515000005160000051700000550	1	2017-05-08	15:45:20	\N	\N
1088	000005150000051600000551	2	2017-05-08	15:46:20	\N	\N
1089	00000515000005160000055100000552	1	2017-05-08	15:46:20	\N	\N
1090	0000051500000516000005510000055200000553	0	2017-05-08	15:46:20	\N	\N
1091	0000051500000516000005510000055200000554	0	2017-05-08	15:46:20	\N	\N
1092	00000515000005160000055100000555	2	2017-05-08	15:46:20	\N	\N
1182	000005150000051600000645	2	2017-05-08	15:53:20	\N	\N
1183	000005150000051600000646	1	2017-05-08	15:53:20	\N	\N
1184	00000515000005160000064600000647	0	2017-05-08	15:53:20	\N	\N
1185	000005150000051600000648	2	2017-05-08	15:53:20	\N	\N
1186	00000515000005160000064800000649	0	2017-05-08	15:53:20	\N	\N
1187	00000515000005160000064800000650	0	2017-05-08	15:53:20	\N	\N
1188	00000515000005160000064800000651	0	2017-05-08	15:53:20	\N	\N
1189	00000515	0	2017-05-08	15:53:20	\N	\N
\.


--
-- TOC entry 2649 (class 0 OID 0)
-- Dependencies: 219
-- Name: fato_lider_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('fato_lider_id_seq', 1189, true);


--
-- TOC entry 2321 (class 0 OID 16450)
-- Dependencies: 177
-- Data for Name: grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo (id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
3	2017-01-17	10:45:37.033678	\N	\N
4	2017-01-17	10:45:44.457613	\N	\N
5	2017-01-17	10:45:49.353742	\N	\N
6	2017-01-17	10:45:53.585684	\N	\N
2	2017-01-20	10:06:51	\N	\N
1	2017-01-31	11:09:34	\N	\N
7	2017-02-02	11:09:25	\N	\N
515	2017-05-01	15:34:04	\N	\N
516	2017-05-01	15:34:04	\N	\N
517	2017-05-01	15:34:04	\N	\N
518	2017-05-01	15:34:04	\N	\N
519	2017-05-01	15:34:04	\N	\N
520	2017-05-01	15:34:04	\N	\N
521	2017-05-01	15:34:04	\N	\N
522	2017-05-01	15:34:04	\N	\N
523	2017-05-01	15:35:04	\N	\N
524	2017-05-01	15:35:04	\N	\N
525	2017-05-01	15:35:04	\N	\N
526	2017-05-01	15:35:04	\N	\N
527	2017-05-01	15:35:04	\N	\N
528	2017-05-01	15:35:04	\N	\N
529	2017-05-01	15:35:04	\N	\N
530	2017-05-01	15:36:04	\N	\N
531	2017-05-01	15:36:04	\N	\N
532	2017-05-01	15:36:04	\N	\N
533	2017-05-01	15:36:04	\N	\N
534	2017-05-01	15:36:04	\N	\N
535	2017-05-01	15:36:04	\N	\N
536	2017-05-01	15:36:04	\N	\N
537	2017-05-01	15:36:04	\N	\N
538	2017-05-01	15:36:04	\N	\N
539	2017-05-01	15:36:04	\N	\N
540	2017-05-01	15:36:04	\N	\N
541	2017-05-01	15:36:04	\N	\N
542	2017-05-01	15:36:04	\N	\N
543	2017-05-01	15:37:04	\N	\N
544	2017-05-01	15:37:04	\N	\N
545	2017-05-01	15:37:04	\N	\N
546	2017-05-01	15:37:04	\N	\N
547	2017-05-01	15:37:04	\N	\N
548	2017-05-01	15:37:04	\N	\N
549	2017-05-01	15:37:04	\N	\N
550	2017-05-01	15:37:04	\N	\N
551	2017-05-01	15:38:04	\N	\N
552	2017-05-01	15:38:04	\N	\N
553	2017-05-01	15:38:04	\N	\N
554	2017-05-01	15:38:04	\N	\N
555	2017-05-01	15:38:04	\N	\N
556	2017-05-01	15:38:04	\N	\N
557	2017-05-01	15:38:04	\N	\N
558	2017-05-01	15:39:04	\N	\N
559	2017-05-01	15:39:04	\N	\N
560	2017-05-01	15:39:04	\N	\N
561	2017-05-01	15:39:04	\N	\N
562	2017-05-01	15:39:04	\N	\N
563	2017-05-01	15:39:04	\N	\N
564	2017-05-01	15:40:04	\N	\N
565	2017-05-01	15:40:04	\N	\N
566	2017-05-01	15:40:04	\N	\N
567	2017-05-01	15:40:04	\N	\N
568	2017-05-01	15:40:04	\N	\N
569	2017-05-01	15:40:04	\N	\N
570	2017-05-01	15:40:04	\N	\N
571	2017-05-01	15:40:04	\N	\N
572	2017-05-01	15:40:04	\N	\N
573	2017-05-01	15:41:04	\N	\N
574	2017-05-01	15:41:04	\N	\N
575	2017-05-01	15:41:04	\N	\N
576	2017-05-01	15:41:04	\N	\N
577	2017-05-01	15:41:04	\N	\N
578	2017-05-01	15:41:04	\N	\N
579	2017-05-01	15:41:04	\N	\N
580	2017-05-01	15:41:04	\N	\N
581	2017-05-01	15:42:04	\N	\N
582	2017-05-01	15:42:04	\N	\N
583	2017-05-01	15:42:04	\N	\N
584	2017-05-01	15:42:04	\N	\N
585	2017-05-01	15:42:04	\N	\N
586	2017-05-01	15:42:04	\N	\N
587	2017-05-01	15:42:04	\N	\N
588	2017-05-01	15:42:04	\N	\N
589	2017-05-01	15:42:04	\N	\N
590	2017-05-01	15:43:04	\N	\N
591	2017-05-01	15:43:04	\N	\N
592	2017-05-01	15:43:04	\N	\N
593	2017-05-01	15:43:04	\N	\N
594	2017-05-01	15:43:04	\N	\N
595	2017-05-01	15:43:04	\N	\N
596	2017-05-01	15:43:04	\N	\N
597	2017-05-01	15:43:04	\N	\N
598	2017-05-01	15:44:04	\N	\N
599	2017-05-01	15:44:04	\N	\N
600	2017-05-01	15:44:04	\N	\N
601	2017-05-01	15:44:04	\N	\N
602	2017-05-01	15:44:04	\N	\N
603	2017-05-01	15:44:04	\N	\N
604	2017-05-01	15:44:04	\N	\N
605	2017-05-01	15:44:04	\N	\N
606	2017-05-01	15:44:04	\N	\N
607	2017-05-01	15:45:04	\N	\N
608	2017-05-01	15:45:04	\N	\N
609	2017-05-01	15:45:04	\N	\N
610	2017-05-01	15:45:04	\N	\N
611	2017-05-01	15:45:04	\N	\N
612	2017-05-01	15:45:04	\N	\N
613	2017-05-01	15:45:04	\N	\N
614	2017-05-01	15:45:04	\N	\N
615	2017-05-01	15:45:04	\N	\N
616	2017-05-01	15:46:04	\N	\N
617	2017-05-01	15:46:04	\N	\N
618	2017-05-01	15:46:04	\N	\N
619	2017-05-01	15:46:04	\N	\N
620	2017-05-01	15:46:04	\N	\N
621	2017-05-01	15:46:04	\N	\N
622	2017-05-01	15:46:04	\N	\N
623	2017-05-01	15:46:04	\N	\N
624	2017-05-01	15:46:04	\N	\N
625	2017-05-01	15:47:04	\N	\N
626	2017-05-01	15:47:04	\N	\N
627	2017-05-01	15:47:04	\N	\N
628	2017-05-01	15:47:04	\N	\N
629	2017-05-01	15:47:04	\N	\N
630	2017-05-01	15:47:04	\N	\N
631	2017-05-01	15:48:04	\N	\N
632	2017-05-01	15:48:04	\N	\N
633	2017-05-01	15:48:04	\N	\N
634	2017-05-01	15:48:04	\N	\N
635	2017-05-01	15:48:04	\N	\N
636	2017-05-01	15:48:04	\N	\N
637	2017-05-01	15:48:04	\N	\N
638	2017-05-01	15:48:04	\N	\N
639	2017-05-01	15:49:04	\N	\N
640	2017-05-01	15:49:04	\N	\N
641	2017-05-01	15:49:04	\N	\N
642	2017-05-01	15:49:04	\N	\N
643	2017-05-01	15:49:04	\N	\N
644	2017-05-01	15:49:04	\N	\N
645	2017-05-01	15:49:04	\N	\N
646	2017-05-01	15:49:04	\N	\N
647	2017-05-01	15:49:04	\N	\N
648	2017-05-01	15:49:04	\N	\N
649	2017-05-01	15:50:04	\N	\N
650	2017-05-01	15:50:04	\N	\N
651	2017-05-01	15:50:04	\N	\N
652	2017-05-01	15:50:04	\N	\N
653	2017-05-01	15:50:04	\N	\N
654	2017-05-01	15:50:04	\N	\N
655	2017-05-01	15:50:04	\N	\N
656	2017-05-01	15:50:04	\N	\N
657	2017-05-01	15:50:04	\N	\N
658	2017-05-01	15:50:04	\N	\N
659	2017-05-01	15:51:04	\N	\N
660	2017-05-01	15:51:04	\N	\N
661	2017-05-01	15:51:04	\N	\N
662	2017-05-01	15:51:04	\N	\N
663	2017-05-01	15:51:04	\N	\N
664	2017-05-01	15:51:04	\N	\N
665	2017-05-01	15:51:04	\N	\N
666	2017-05-01	15:51:04	\N	\N
667	2017-05-01	15:51:04	\N	\N
668	2017-05-01	15:51:04	\N	\N
669	2017-05-01	15:52:04	\N	\N
670	2017-05-01	15:52:04	\N	\N
671	2017-05-01	15:52:04	\N	\N
672	2017-05-01	15:52:04	\N	\N
\.


--
-- TOC entry 2349 (class 0 OID 16924)
-- Dependencies: 205
-- Data for Name: grupo_aluno; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_aluno (id, grupo_id, turma_aluno_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
\.


--
-- TOC entry 2650 (class 0 OID 0)
-- Dependencies: 204
-- Name: grupo_aluno_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_aluno_id_seq', 6, true);


--
-- TOC entry 2355 (class 0 OID 16989)
-- Dependencies: 211
-- Data for Name: grupo_atendimento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_atendimento (id, grupo_id, data_criacao, hora_criacao, dia, quem, data_inativacao, hora_inativacao, observacao) FROM stdin;
140	551	2017-05-15	15:11:47	2017-05-15	7104	2017-05-15	15:18:47	\N
141	652	2017-05-15	16:55:01	2017-05-15	9520	2017-05-15	16:05:02	\N
142	652	2017-05-15	16:55:02	2017-05-15	9520	2017-05-15	16:02:03	\N
143	652	2017-05-15	16:23:04	2017-05-15	9520	2017-05-15	16:40:06	\N
144	652	2017-05-15	16:37:04	2017-05-15	9520	2017-05-15	16:41:06	\N
145	652	2017-05-15	16:45:04	2017-05-15	9520	2017-05-15	16:42:06	\N
146	652	2017-05-15	16:15:05	2017-05-15	9520	2017-05-15	16:43:06	\N
147	652	2017-05-15	16:13:08	2017-05-15	9520	2017-05-15	16:23:08	\N
148	652	2017-05-15	16:33:08	2017-05-15	9520	2017-05-15	16:51:08	\N
149	652	2017-05-15	16:59:09	2017-05-15	9520	2017-05-15	16:51:12	\N
150	652	2017-05-15	16:19:11	2017-05-15	9520	2017-05-15	16:52:12	\N
110	652	2017-05-09	12:46:51	2017-05-09	9520	2017-05-15	16:53:12	\N
111	652	2017-05-09	12:47:51	2017-05-09	9520	2017-05-15	16:54:12	\N
152	652	2017-05-15	16:57:13	2017-05-15	9520	\N	\N	\N
112	651	2017-05-09	12:48:51	2017-05-09	9520	2017-05-15	16:12:14	\N
153	651	2017-05-15	16:14:14	2017-05-15	9520	\N	\N	\N
128	655	2017-05-11	20:11:46	2017-05-11	7104	2017-05-15	18:29:59	\N
129	661	2017-05-11	20:16:46	2017-05-11	7104	2017-05-15	18:32:59	\N
131	669	2017-05-11	20:20:46	2017-05-11	7104	2017-05-15	18:33:59	\N
130	668	2017-05-11	20:18:46	2017-05-11	7104	2017-05-15	18:34:59	\N
132	517	2017-05-11	20:23:46	2017-05-11	7104	2017-05-15	18:37:59	\N
133	593	2017-05-11	20:27:46	2017-05-11	7104	2017-05-15	18:39:59	\N
134	597	2017-05-11	20:29:46	2017-05-11	7104	2017-05-15	18:41:59	\N
135	600	2017-05-11	20:33:46	2017-05-11	7104	2017-05-15	18:42:59	\N
136	615	2017-05-11	20:38:46	2017-05-11	7104	2017-05-15	18:48:59	\N
137	642	2017-05-11	20:40:46	2017-05-11	7104	2017-05-15	18:50:59	\N
138	648	2017-05-11	20:43:46	2017-05-11	7104	2017-05-15	18:55:59	\N
101	652	2017-05-09	12:20:51	2017-04-08	9520	\N	\N	\N
103	653	2017-05-09	12:23:51	2017-04-08	9520	\N	\N	\N
106	649	2017-05-09	12:28:51	2017-04-08	9520	\N	\N	\N
102	653	2017-05-09	12:22:51	2017-04-08	9520	2017-05-09	12:30:51	\N
104	654	2017-05-09	12:25:51	2017-04-08	9520	2017-05-09	12:32:51	\N
107	654	2017-05-09	12:34:51	2017-04-08	9520	\N	\N	\N
105	649	2017-05-09	12:26:51	2017-04-08	9520	2017-05-09	12:37:51	\N
108	650	2017-05-09	12:38:51	2017-04-08	9520	\N	\N	\N
109	651	2017-05-09	12:40:51	2017-04-08	9520	\N	\N	\N
113	651	2017-05-09	12:49:51	2017-05-09	9520	\N	\N	\N
114	653	2017-05-09	13:03:03	2017-05-09	9520	2017-05-09	13:10:03	\N
115	652	2017-05-09	13:47:03	2017-04-08	9520	\N	\N	\N
116	653	2017-05-09	13:48:03	2017-04-08	9520	\N	\N	\N
117	654	2017-05-09	13:49:03	2017-04-08	9520	\N	\N	\N
118	649	2017-05-09	13:50:03	2017-04-08	9520	\N	\N	\N
119	650	2017-05-09	13:51:03	2017-04-08	9520	\N	\N	\N
120	651	2017-05-09	13:52:03	2017-04-08	9520	\N	\N	\N
156	661	2017-05-15	20:05:29	2017-05-15	7104	2017-05-15	20:09:29	\N
157	661	2017-05-15	20:06:29	2017-05-15	7104	2017-05-15	20:10:29	\N
158	661	2017-05-15	20:07:29	2017-05-15	7104	2017-05-15	20:11:29	\N
154	655	2017-05-15	20:02:29	2017-05-15	7104	2017-05-15	20:12:29	\N
155	655	2017-05-15	20:03:29	2017-05-15	7104	2017-05-15	20:13:29	\N
151	652	2017-05-15	16:56:13	2017-05-15	9520	2017-05-16	09:09:53	\N
159	652	2017-05-16	09:11:53	2017-05-16	9520	\N	\N	\N
160	655	2017-05-16	10:00:25	2017-05-16	7104	2017-05-16	10:02:25	\N
161	649	2017-05-19	13:44:56	2017-05-19	9520	\N	\N	\N
123	661	2017-05-10	18:12:38	2017-05-10	7104	2017-05-10	18:21:38	\N
121	655	2017-05-10	18:59:37	2017-05-10	7104	2017-05-10	18:22:38	\N
122	655	2017-05-10	18:08:38	2017-05-10	7104	2017-05-10	18:30:38	\N
127	669	2017-05-11	17:49:10	2017-05-11	7104	2017-05-11	17:50:10	\N
126	668	2017-05-11	17:48:10	2017-05-11	7104	2017-05-11	17:52:10	\N
125	661	2017-05-11	17:46:10	2017-05-11	7104	2017-05-11	17:53:10	\N
124	655	2017-05-11	17:44:10	2017-05-11	7104	2017-05-11	17:54:10	\N
139	551	2017-05-15	15:39:46	2017-05-15	7104	2017-05-15	15:46:46	\N
\.


--
-- TOC entry 2651 (class 0 OID 0)
-- Dependencies: 210
-- Name: grupo_atendimento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_atendimento_id_seq', 161, true);


--
-- TOC entry 2367 (class 0 OID 17741)
-- Dependencies: 223
-- Data for Name: grupo_cv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_cv (id, grupo_id, lider1, lider2, numero_identificador) FROM stdin;
416	515	1	2	0010080001
417	516	12	13	0010080001000001
418	517	5313	5530	001008000100000100000015
419	518	18494	\N	00100800010000010000001500004474
420	519	2892286	\N	0010080001000001000000150000447400029483
421	520	2421684	\N	0010080001000001000000150000447400038087
422	521	2900788	\N	0010080001000001000000150000447400038088
423	522	8215	\N	00100800010000010000001500008129
424	523	1624803	\N	0010080001000001000000150000812900018537
425	524	2082819	\N	0010080001000001000000150000812900025543
426	525	2082822	\N	0010080001000001000000150000812900025544
427	526	2617	\N	00100800010000010000001500008130
428	527	713201	\N	00100800010000010000001500013762
429	528	2934842	2935004	00100800010000010000001500039775
430	529	1952008	\N	00100800010000010000001500039776
431	530	2059469	\N	0010080001000001000000150003977600039777
432	531	3720494	\N	0010080001000001000000150003977600039778
433	532	3964626	\N	0010080001000001000000150003977600039779
434	533	4207884	\N	0010080001000001000000150003977600040453
435	534	4393538	\N	0010080001000001000000150003977600041849
436	535	4846110	\N	0010080001000001000000150003977600045101
437	536	1933188	1933193	00100800010000010000001500039794
438	537	18316	\N	00100800010000010000001500046464
439	538	5082462	\N	0010080001000001000000150004646400049720
440	539	5081729	\N	0010080001000001000000150004646400049721
441	540	5082458	\N	0010080001000001000000150004646400049726
442	541	2083990	\N	00100800010000010000001500047956
443	542	4601849	\N	00100800010000010000001500049044
444	543	755524	\N	00100800010000010000001500052103
445	544	5913436	\N	00100800010000010000001500052116
446	545	4214066	\N	00100800010000010000001500052814
447	546	1862156	\N	00100800010000010000001500052815
448	547	957666	\N	00100800010000010000001500057097
449	548	2977912	\N	0010080001000001000000150005709700057098
450	549	1610062	\N	00100800010000010000001500058480
451	550	4192072	\N	00100800010000010000001500058564
452	551	2144	5621	001008000100000100000016
453	552	159369	\N	00100800010000010000001600009633
454	553	1281336	\N	0010080001000001000000160000963300021901
455	554	6510209	\N	0010080001000001000000160000963300060041
456	555	14	1930562	00100800010000010000001600011888
457	556	160099	22435	00100800010000010000001600015029
458	557	1624605	\N	0010080001000001000000160001502900018512
459	558	2204279	\N	0010080001000001000000160001502900035662
460	559	1931182	\N	0010080001000001000000160001502900035664
461	560	2204271	\N	0010080001000001000000160001502900035728
462	561	1276599	\N	00100800010000010000001600016666
463	562	1410707	\N	00100800010000010000001600019937
464	563	2452	\N	00100800010000010000001600022731
465	564	2206295	\N	00100800010000010000001600022886
466	565	5115079	\N	0010080001000001000000160002288600047944
467	566	3169078	\N	00100800010000010000001600032483
468	567	2655000	\N	0010080001000001000000160003248300043623
469	568	3181591	\N	0010080001000001000000160003248300043674
470	569	6532815	\N	0010080001000001000000160003248300059344
471	570	6532807	\N	0010080001000001000000160003248300059345
472	571	2079247	\N	00100800010000010000001600035636
473	572	1116251	2887706	00100800010000010000001600039785
474	573	4188029	\N	0010080001000001000000160003978500040194
475	574	4188108	\N	0010080001000001000000160003978500040202
476	575	4209186	\N	0010080001000001000000160003978500040478
477	576	1125589	\N	0010080001000001000000160003978500040480
478	577	2221798	\N	0010080001000001000000160003978500042942
479	578	5578	5579	001008000100000100000072
480	579	957665	\N	00100800010000010000007200010464
481	580	1624261	\N	00100800010000010000007200020882
482	581	2205042	\N	0010080001000001000000720002088200023349
483	582	2205045	\N	0010080001000001000000720002088200023446
484	583	5080664	\N	0010080001000001000000720002088200047939
485	584	5083170	\N	0010080001000001000000720002088200047945
486	585	5115087	\N	0010080001000001000000720002088200047946
487	586	3954253	\N	00100800010000010000007200039786
488	587	2648529	\N	00100800010000010000007200041155
489	588	5115077	\N	0010080001000001000000720004115500047943
490	589	4389431	\N	00100800010000010000007200043155
491	590	5083480	\N	0010080001000001000000720004315500047941
492	591	4209524	\N	0010080001000001000000720004315500047951
493	592	1274786	1274812	00100800010000010000007200057347
494	593	5517	14308	001008000100000100002825
495	594	3706420	\N	00100800010000010000282500036904
496	595	4388936	\N	00100800010000010000282500049602
497	596	5454	8246	00100800010000010000282500054817
498	597	8641	\N	001008000100000100007968
499	598	5338665	\N	00100800010000010000796800048582
500	599	4621368	\N	00100800010000010000796800048583
501	600	2115	\N	001008000100000100007972
502	601	158038	\N	00100800010000010000797200009508
503	602	1933326	\N	0010080001000001000079720000950800024572
504	603	1114137	\N	0010080001000001000079720000950800024579
505	604	2231669	\N	0010080001000001000079720000950800024582
506	605	5635140	\N	00100800010000010000797200051838
507	606	5644572	\N	00100800010000010000797200051839
508	607	5091876	\N	00100800010000010000797200051840
509	608	5083220	\N	00100800010000010000797200051841
510	609	5670828	\N	00100800010000010000797200051842
511	610	5670835	\N	00100800010000010000797200051843
512	611	15145	2591080	001008000100000100008661
513	612	4390465	\N	00100800010000010000866100043197
514	613	4390471	\N	00100800010000010000866100043201
515	614	4835825	\N	00100800010000010000866100047542
516	615	435275	7840	001008000100000100013773
517	616	1411621	\N	00100800010000010001377300016700
518	617	1818590	\N	00100800010000010001377300016706
519	618	1121903	\N	0010080001000001000137730001670600048589
520	619	5396963	\N	0010080001000001000137730001670600050584
521	620	5400266	\N	0010080001000001000137730001670600050776
522	621	6213407	\N	0010080001000001000137730001670600054412
523	622	6531668	\N	0010080001000001000137730001670600056866
524	623	4623186	\N	00100800010000010001377300046564
525	624	3405223	\N	00100800010000010001377300046566
526	625	4391713	\N	00100800010000010001377300052407
527	626	3718767	\N	00100800010000010001377300052408
528	627	15116	\N	00100800010000010001377300052409
529	628	9066	\N	00100800010000010001377300052410
530	629	5092420	\N	0010080001000001000137730005241000052411
531	630	5921931	\N	0010080001000001000137730005241000052629
532	631	1932186	\N	00100800010000010001377300052412
533	632	2397187	\N	0010080001000001000137730005241200052413
534	633	4613286	\N	0010080001000001000137730005241200052414
535	634	1277214	\N	00100800010000010001377300052415
536	635	4926334	\N	0010080001000001000137730005241500052416
537	636	4926336	\N	0010080001000001000137730005241500052417
538	637	1276283	\N	00100800010000010001377300052418
539	638	2981966	\N	00100800010000010001377300052419
540	639	5635664	\N	0010080001000001000137730005241900059808
541	640	5635662	\N	00100800010000010001377300052420
542	641	5091879	\N	00100800010000010001377300057414
543	642	20726	2056	001008000100000100014009
544	643	1410595	\N	00100800010000010001400900016120
545	644	4593265	\N	00100800010000010001400900043911
546	645	7752	7804	001008000100000100016109
547	646	2410514	\N	001008000100000100026414
548	647	6549204	\N	00100800010000010002641400057523
549	648	18587	18586	001008000100000100036764
550	649	3199203	\N	00100800010000010003676400036767
551	650	3199216	\N	00100800010000010003676400036768
552	651	4113215	\N	00100800010000010003676400043755
553	652	4113213	\N	00100800010000010003676400043756
554	653	2204779	\N	00100800010000010003676400043757
555	654	5355837	\N	00100800010000010003676400049526
556	655	1726302	\N	001008000100000100036773
557	656	2056326	\N	00100800010000010003677300036775
558	657	5091882	\N	00100800010000010003677300049899
559	658	5949744	\N	00100800010000010003677300053529
560	659	10863	\N	00100800010000010003677300059025
561	660	4390387	\N	00100800010000010003677300059028
562	661	711164	\N	001008000100000100044274
563	662	5066936	\N	00100800010000010004427400047710
564	663	5119601	\N	00100800010000010004427400051239
565	664	1119941	\N	00100800010000010004427400051286
566	665	5916057	\N	00100800010000010004427400053503
567	666	1412790	\N	00100800010000010004427400053838
568	667	1932757	\N	0010080001000001000442740005383800053839
569	668	2057	\N	001008000100000100056632
570	669	1274967	1277472	001008000100000100059512
571	670	2654983	\N	00100800010000010005951200059513
572	671	1932429	\N	00100800010000010005951200059514
573	672	2061944	\N	00100800010000010005951200059515
\.


--
-- TOC entry 2652 (class 0 OID 0)
-- Dependencies: 222
-- Name: grupo_cv_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_cv_id_seq', 573, true);


--
-- TOC entry 2333 (class 0 OID 16668)
-- Dependencies: 189
-- Data for Name: grupo_evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_evento (id, grupo_id, evento_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
366	4	234	2017-02-07	15:54:00	\N	\N
367	5	234	2017-02-07	15:54:00	\N	\N
368	4	235	2017-02-07	15:17:14	\N	\N
369	5	235	2017-02-07	15:17:14	\N	\N
370	4	236	2017-02-08	13:59:14	\N	\N
371	5	236	2017-02-08	13:59:14	\N	\N
372	4	237	2017-02-08	13:21:40	\N	\N
373	5	237	2017-02-08	13:21:40	\N	\N
374	4	238	2017-02-08	14:32:12	\N	\N
375	5	238	2017-02-08	14:32:12	\N	\N
377	5	239	2017-02-08	14:23:22	\N	\N
378	4	240	2017-02-08	14:38:35	\N	\N
379	5	240	2017-02-08	14:38:35	\N	\N
380	4	241	2017-02-09	16:25:15	\N	\N
381	5	241	2017-02-09	16:25:15	\N	\N
382	4	242	2017-02-09	16:32:16	\N	\N
383	5	242	2017-02-09	16:32:16	\N	\N
384	4	243	2017-02-09	16:48:17	\N	\N
385	5	243	2017-02-09	16:48:17	\N	\N
386	4	244	2017-02-09	16:12:23	\N	\N
387	5	244	2017-02-09	16:12:23	\N	\N
376	515	239	2017-02-08	14:23:22	\N	\N
401	515	443	2017-05-01	15:34:04	\N	\N
402	515	444	2017-05-01	15:34:04	\N	\N
403	515	445	2017-05-01	15:34:04	\N	\N
404	515	446	2017-05-01	15:34:04	\N	\N
405	515	447	2017-05-01	15:34:04	\N	\N
406	515	448	2017-05-01	15:34:04	\N	\N
407	515	449	2017-05-01	15:34:04	\N	\N
408	515	450	2017-05-01	15:34:04	\N	\N
409	515	451	2017-05-01	15:34:04	\N	\N
410	515	452	2017-05-01	15:34:04	\N	\N
411	515	453	2017-05-01	15:34:04	\N	\N
412	515	454	2017-05-01	15:34:04	\N	\N
413	516	445	2017-05-01	15:34:04	\N	\N
414	516	448	2017-05-01	15:34:04	\N	\N
415	516	453	2017-05-01	15:34:04	\N	\N
416	516	455	2017-05-01	15:34:04	\N	\N
417	517	456	2017-05-01	15:34:04	\N	\N
418	518	457	2017-05-01	15:34:04	\N	\N
419	519	458	2017-05-01	15:34:04	\N	\N
420	526	459	2017-05-01	15:35:04	\N	\N
421	528	460	2017-05-01	15:35:04	\N	\N
422	529	461	2017-05-01	15:35:04	\N	\N
423	536	462	2017-05-01	15:36:04	\N	\N
424	537	463	2017-05-01	15:36:04	\N	\N
425	541	464	2017-05-01	15:36:04	\N	\N
426	543	465	2017-05-01	15:37:04	\N	\N
427	545	466	2017-05-01	15:37:04	\N	\N
428	546	467	2017-05-01	15:37:04	\N	\N
429	547	468	2017-05-01	15:37:04	\N	\N
430	549	469	2017-05-01	15:37:04	\N	\N
431	550	470	2017-05-01	15:37:04	\N	\N
432	551	471	2017-05-01	15:38:04	\N	\N
433	551	472	2017-05-01	15:38:04	\N	\N
434	552	473	2017-05-01	15:38:04	\N	\N
435	555	474	2017-05-01	15:38:04	\N	\N
436	555	475	2017-05-01	15:38:04	\N	\N
437	556	476	2017-05-01	15:38:04	\N	\N
438	557	477	2017-05-01	15:39:04	\N	\N
439	558	478	2017-05-01	15:39:04	\N	\N
440	559	479	2017-05-01	15:39:04	\N	\N
441	561	480	2017-05-01	15:39:04	\N	\N
442	562	481	2017-05-01	15:39:04	\N	\N
443	563	482	2017-05-01	15:39:04	\N	\N
444	564	483	2017-05-01	15:40:04	\N	\N
445	565	484	2017-05-01	15:40:04	\N	\N
446	566	485	2017-05-01	15:40:04	\N	\N
447	566	486	2017-05-01	15:40:04	\N	\N
448	568	487	2017-05-01	15:40:04	\N	\N
449	571	488	2017-05-01	15:40:04	\N	\N
450	572	489	2017-05-01	15:41:04	\N	\N
451	578	490	2017-05-01	15:41:04	\N	\N
452	578	491	2017-05-01	15:41:04	\N	\N
453	579	492	2017-05-01	15:41:04	\N	\N
454	580	493	2017-05-01	15:42:04	\N	\N
455	586	494	2017-05-01	15:42:04	\N	\N
456	587	495	2017-05-01	15:42:04	\N	\N
457	589	496	2017-05-01	15:43:04	\N	\N
458	591	497	2017-05-01	15:43:04	\N	\N
459	592	498	2017-05-01	15:43:04	\N	\N
460	593	499	2017-05-01	15:43:04	\N	\N
461	594	500	2017-05-01	15:43:04	\N	\N
462	595	501	2017-05-01	15:43:04	\N	\N
463	596	502	2017-05-01	15:43:04	\N	\N
464	597	503	2017-05-01	15:44:04	\N	\N
465	600	504	2017-05-01	15:44:04	\N	\N
466	611	505	2017-05-01	15:45:04	\N	\N
467	611	506	2017-05-01	15:45:04	\N	\N
468	615	507	2017-05-01	15:45:04	\N	\N
469	616	508	2017-05-01	15:46:04	\N	\N
470	617	509	2017-05-01	15:46:04	\N	\N
471	623	510	2017-05-01	15:46:04	\N	\N
472	624	511	2017-05-01	15:46:04	\N	\N
473	625	512	2017-05-01	15:47:04	\N	\N
474	626	513	2017-05-01	15:47:04	\N	\N
475	627	514	2017-05-01	15:47:04	\N	\N
476	628	515	2017-05-01	15:47:04	\N	\N
477	631	516	2017-05-01	15:48:04	\N	\N
478	634	517	2017-05-01	15:48:04	\N	\N
479	637	518	2017-05-01	15:48:04	\N	\N
480	638	519	2017-05-01	15:48:04	\N	\N
481	642	520	2017-05-01	15:49:04	\N	\N
482	642	521	2017-05-01	15:49:04	\N	\N
483	643	522	2017-05-01	15:49:04	\N	\N
484	645	523	2017-05-01	15:49:04	\N	\N
485	646	524	2017-05-01	15:49:04	\N	\N
486	648	525	2017-05-01	15:50:04	\N	\N
487	648	526	2017-05-01	15:50:04	\N	\N
488	655	527	2017-05-01	15:50:04	\N	\N
489	657	528	2017-05-01	15:50:04	\N	\N
490	658	529	2017-05-01	15:51:04	\N	\N
491	661	530	2017-05-01	15:51:04	\N	\N
492	666	531	2017-05-01	15:51:04	\N	\N
493	668	532	2017-05-01	15:52:04	\N	\N
494	669	533	2017-05-01	15:52:04	\N	\N
495	670	534	2017-05-01	15:52:04	\N	\N
\.


--
-- TOC entry 2653 (class 0 OID 0)
-- Dependencies: 188
-- Name: grupo_evento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_evento_id_seq', 495, true);


--
-- TOC entry 2654 (class 0 OID 0)
-- Dependencies: 176
-- Name: grupo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_id_seq', 672, true);


--
-- TOC entry 2319 (class 0 OID 16440)
-- Dependencies: 175
-- Data for Name: grupo_pai_filho; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_pai_filho (id, data_criacao, hora_criacao, pai_id, filho_id, data_inativacao, hora_inativacao) FROM stdin;
646	2017-05-01	15:50:04	648	652	\N	\N
647	2017-05-01	15:50:04	648	653	\N	\N
648	2017-05-01	15:50:04	648	654	\N	\N
649	2017-05-01	15:50:04	516	655	\N	\N
650	2017-05-01	15:50:04	655	656	\N	\N
651	2017-05-01	15:50:04	655	657	\N	\N
652	2017-05-01	15:50:04	655	658	\N	\N
653	2017-05-01	15:51:04	655	659	\N	\N
654	2017-05-01	15:51:04	655	660	\N	\N
655	2017-05-01	15:51:04	516	661	\N	\N
656	2017-05-01	15:51:04	661	662	\N	\N
657	2017-05-01	15:51:04	661	663	\N	\N
658	2017-05-01	15:51:04	661	664	\N	\N
659	2017-05-01	15:51:04	661	665	\N	\N
660	2017-05-01	15:51:04	661	666	\N	\N
661	2017-05-01	15:51:04	666	667	\N	\N
662	2017-05-01	15:52:04	516	668	\N	\N
663	2017-05-01	15:52:04	516	669	\N	\N
664	2017-05-01	15:52:04	669	670	\N	\N
665	2017-05-01	15:52:04	669	671	\N	\N
666	2017-05-01	15:52:04	669	672	\N	\N
510	2017-05-01	15:34:04	515	516	\N	\N
511	2017-05-01	15:34:04	516	517	\N	\N
512	2017-05-01	15:34:04	517	518	\N	\N
513	2017-05-01	15:34:04	518	519	\N	\N
514	2017-05-01	15:34:04	518	520	\N	\N
515	2017-05-01	15:34:04	518	521	\N	\N
516	2017-05-01	15:34:04	517	522	\N	\N
517	2017-05-01	15:35:04	522	523	\N	\N
518	2017-05-01	15:35:04	522	524	\N	\N
519	2017-05-01	15:35:04	522	525	\N	\N
520	2017-05-01	15:35:04	517	526	\N	\N
521	2017-05-01	15:35:04	517	527	\N	\N
522	2017-05-01	15:35:04	517	528	\N	\N
523	2017-05-01	15:35:04	517	529	\N	\N
524	2017-05-01	15:36:04	529	530	\N	\N
525	2017-05-01	15:36:04	529	531	\N	\N
526	2017-05-01	15:36:04	529	532	\N	\N
527	2017-05-01	15:36:04	529	533	\N	\N
528	2017-05-01	15:36:04	529	534	\N	\N
529	2017-05-01	15:36:04	529	535	\N	\N
530	2017-05-01	15:36:04	517	536	\N	\N
531	2017-05-01	15:36:04	517	537	\N	\N
532	2017-05-01	15:36:04	537	538	\N	\N
533	2017-05-01	15:36:04	537	539	\N	\N
534	2017-05-01	15:36:04	537	540	\N	\N
535	2017-05-01	15:36:04	517	541	\N	\N
536	2017-05-01	15:36:04	517	542	\N	\N
537	2017-05-01	15:37:04	517	543	\N	\N
538	2017-05-01	15:37:04	517	544	\N	\N
539	2017-05-01	15:37:04	517	545	\N	\N
540	2017-05-01	15:37:04	517	546	\N	\N
541	2017-05-01	15:37:04	517	547	\N	\N
542	2017-05-01	15:37:04	547	548	\N	\N
543	2017-05-01	15:37:04	517	549	\N	\N
544	2017-05-01	15:37:04	517	550	\N	\N
545	2017-05-01	15:38:04	516	551	\N	\N
546	2017-05-01	15:38:04	551	552	\N	\N
547	2017-05-01	15:38:04	552	553	\N	\N
548	2017-05-01	15:38:04	552	554	\N	\N
549	2017-05-01	15:38:04	551	555	\N	\N
550	2017-05-01	15:38:04	551	556	\N	\N
551	2017-05-01	15:38:04	556	557	\N	\N
552	2017-05-01	15:39:04	556	558	\N	\N
553	2017-05-01	15:39:04	556	559	\N	\N
554	2017-05-01	15:39:04	556	560	\N	\N
555	2017-05-01	15:39:04	551	561	\N	\N
556	2017-05-01	15:39:04	551	562	\N	\N
557	2017-05-01	15:39:04	551	563	\N	\N
558	2017-05-01	15:40:04	551	564	\N	\N
559	2017-05-01	15:40:04	564	565	\N	\N
560	2017-05-01	15:40:04	551	566	\N	\N
561	2017-05-01	15:40:04	566	567	\N	\N
562	2017-05-01	15:40:04	566	568	\N	\N
563	2017-05-01	15:40:04	566	569	\N	\N
564	2017-05-01	15:40:04	566	570	\N	\N
565	2017-05-01	15:40:04	551	571	\N	\N
566	2017-05-01	15:40:04	551	572	\N	\N
567	2017-05-01	15:41:04	572	573	\N	\N
568	2017-05-01	15:41:04	572	574	\N	\N
569	2017-05-01	15:41:04	572	575	\N	\N
570	2017-05-01	15:41:04	572	576	\N	\N
571	2017-05-01	15:41:04	572	577	\N	\N
572	2017-05-01	15:41:04	516	578	\N	\N
573	2017-05-01	15:41:04	578	579	\N	\N
574	2017-05-01	15:41:04	578	580	\N	\N
575	2017-05-01	15:42:04	580	581	\N	\N
576	2017-05-01	15:42:04	580	582	\N	\N
577	2017-05-01	15:42:04	580	583	\N	\N
578	2017-05-01	15:42:04	580	584	\N	\N
579	2017-05-01	15:42:04	580	585	\N	\N
580	2017-05-01	15:42:04	578	586	\N	\N
581	2017-05-01	15:42:04	578	587	\N	\N
582	2017-05-01	15:42:04	587	588	\N	\N
583	2017-05-01	15:42:04	578	589	\N	\N
584	2017-05-01	15:43:04	589	590	\N	\N
585	2017-05-01	15:43:04	589	591	\N	\N
586	2017-05-01	15:43:04	578	592	\N	\N
587	2017-05-01	15:43:04	516	593	\N	\N
588	2017-05-01	15:43:04	593	594	\N	\N
589	2017-05-01	15:43:04	593	595	\N	\N
590	2017-05-01	15:43:04	593	596	\N	\N
591	2017-05-01	15:43:04	516	597	\N	\N
592	2017-05-01	15:44:04	597	598	\N	\N
593	2017-05-01	15:44:04	597	599	\N	\N
594	2017-05-01	15:44:04	516	600	\N	\N
595	2017-05-01	15:44:04	600	601	\N	\N
596	2017-05-01	15:44:04	601	602	\N	\N
597	2017-05-01	15:44:04	601	603	\N	\N
598	2017-05-01	15:44:04	601	604	\N	\N
599	2017-05-01	15:44:04	600	605	\N	\N
600	2017-05-01	15:44:04	600	606	\N	\N
601	2017-05-01	15:45:04	600	607	\N	\N
602	2017-05-01	15:45:04	600	608	\N	\N
603	2017-05-01	15:45:04	600	609	\N	\N
604	2017-05-01	15:45:04	600	610	\N	\N
605	2017-05-01	15:45:04	516	611	\N	\N
606	2017-05-01	15:45:04	611	612	\N	\N
607	2017-05-01	15:45:04	611	613	\N	\N
608	2017-05-01	15:45:04	611	614	\N	\N
609	2017-05-01	15:45:04	516	615	\N	\N
610	2017-05-01	15:46:04	615	616	\N	\N
611	2017-05-01	15:46:04	615	617	\N	\N
612	2017-05-01	15:46:04	617	618	\N	\N
613	2017-05-01	15:46:04	617	619	\N	\N
614	2017-05-01	15:46:04	617	620	\N	\N
615	2017-05-01	15:46:04	617	621	\N	\N
616	2017-05-01	15:46:04	617	622	\N	\N
617	2017-05-01	15:46:04	615	623	\N	\N
618	2017-05-01	15:46:04	615	624	\N	\N
619	2017-05-01	15:47:04	615	625	\N	\N
620	2017-05-01	15:47:04	615	626	\N	\N
621	2017-05-01	15:47:04	615	627	\N	\N
622	2017-05-01	15:47:04	615	628	\N	\N
623	2017-05-01	15:47:04	628	629	\N	\N
624	2017-05-01	15:47:04	628	630	\N	\N
625	2017-05-01	15:48:04	615	631	\N	\N
626	2017-05-01	15:48:04	631	632	\N	\N
627	2017-05-01	15:48:04	631	633	\N	\N
628	2017-05-01	15:48:04	615	634	\N	\N
629	2017-05-01	15:48:04	634	635	\N	\N
630	2017-05-01	15:48:04	634	636	\N	\N
631	2017-05-01	15:48:04	615	637	\N	\N
632	2017-05-01	15:48:04	615	638	\N	\N
633	2017-05-01	15:49:04	638	639	\N	\N
634	2017-05-01	15:49:04	615	640	\N	\N
635	2017-05-01	15:49:04	615	641	\N	\N
636	2017-05-01	15:49:04	516	642	\N	\N
637	2017-05-01	15:49:04	642	643	\N	\N
638	2017-05-01	15:49:04	642	644	\N	\N
639	2017-05-01	15:49:04	516	645	\N	\N
640	2017-05-01	15:49:04	516	646	\N	\N
641	2017-05-01	15:49:04	646	647	\N	\N
642	2017-05-01	15:49:04	516	648	\N	\N
643	2017-05-01	15:50:04	648	649	\N	\N
644	2017-05-01	15:50:04	648	650	\N	\N
645	2017-05-01	15:50:04	648	651	\N	\N
128792	2017-02-20	12:00:00	4	5	\N	\N
\.


--
-- TOC entry 2655 (class 0 OID 0)
-- Dependencies: 174
-- Name: grupo_pai_filho_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_pai_filho_id_seq', 666, true);


--
-- TOC entry 2335 (class 0 OID 16710)
-- Dependencies: 191
-- Data for Name: grupo_pessoa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_pessoa (id, grupo_id, pessoa_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao, tipo_id, transferido, nucleo_perfeito) FROM stdin;
9204	4	9901	2017-05-11	13:38:34	\N	\N	2	\N	\N
9205	648	9902	2017-05-11	15:44:41	\N	\N	1	\N	\N
9206	4	9903	2017-05-17	11:05:09	2017-05-17	11:05:09	3	\N	\N
9207	4	9904	2017-05-17	11:00:09	2017-05-17	11:00:09	3	\N	\N
9208	4	9905	2017-05-17	11:09:09	2017-05-17	11:09:09	3	\N	\N
9210	555	9906	2017-05-12	15:12:45	2017-05-12	15:12:45	1	\N	\N
9211	555	9907	2017-05-14	11:49:39	\N	\N	1	\N	\N
7249	555	7817	2017-05-14	16:10:35	2017-05-14	16:10:35	2	\N	\N
7247	555	7815	2017-05-14	16:39:35	2017-05-14	16:39:35	3	\N	\N
9212	555	9908	2017-05-15	10:16:37	2017-05-15	10:16:37	1	\N	\N
9213	555	9909	2017-05-15	14:44:00	2017-05-15	14:44:00	1	\N	\N
9214	4	9910	2017-05-15	16:09:32	\N	\N	1	\N	\N
9215	4	9911	2017-05-15	16:44:32	2017-05-18	14:05:58	2	\N	\N
9216	4	9910	2017-05-17	11:14:09	2017-05-17	11:14:09	3	\N	\N
9217	4	9911	2017-05-15	17:08:10	\N	\N	3	\N	\N
9218	648	9912	2017-05-16	09:11:44	2017-05-16	09:11:44	3	\N	\N
9219	4	9911	2017-05-16	09:25:46	\N	\N	3	\N	\N
9220	4	9911	2017-05-16	10:57:02	\N	\N	3	\N	\N
9221	4	9911	2017-05-17	11:18:09	2017-05-17	11:18:09	3	\N	\N
9222	555	9913	2017-05-16	19:20:43	\N	\N	2	\N	\N
9223	4	9914	2017-05-17	11:00:10	\N	\N	1	\N	\N
9224	4	9915	2017-05-17	11:23:10	\N	\N	1	\N	\N
9225	4	9911	2017-05-18	14:05:58	\N	\N	3	\N	\N
9226	648	9916	2017-05-19	14:43:33	\N	\N	1	\N	\N
6582	516	7106	2017-05-01	15:34:04	\N	\N	3	\N	\N
6583	516	7107	2017-05-01	15:34:04	\N	\N	3	\N	\N
6584	516	7108	2017-05-01	15:34:04	\N	\N	3	\N	\N
6585	516	7109	2017-05-01	15:34:04	\N	\N	3	\N	\N
6586	516	7110	2017-05-01	15:34:04	\N	\N	3	\N	\N
6587	516	7111	2017-05-01	15:34:04	\N	\N	3	\N	\N
6588	516	7112	2017-05-01	15:34:04	\N	\N	3	\N	\N
6589	516	7113	2017-05-01	15:34:04	\N	\N	3	\N	\N
6590	516	7114	2017-05-01	15:34:04	\N	\N	3	\N	\N
6591	516	7115	2017-05-01	15:34:04	\N	\N	3	\N	\N
6592	516	7116	2017-05-01	15:34:04	\N	\N	3	\N	\N
6593	517	7119	2017-05-01	15:34:04	\N	\N	3	\N	\N
6594	517	7120	2017-05-01	15:34:04	\N	\N	3	\N	\N
6595	517	7121	2017-05-01	15:34:04	\N	\N	3	\N	\N
6596	517	7122	2017-05-01	15:34:04	\N	\N	3	\N	\N
6597	517	7123	2017-05-01	15:34:04	\N	\N	3	\N	\N
6598	517	7124	2017-05-01	15:34:04	\N	\N	3	\N	\N
6599	517	7125	2017-05-01	15:34:04	\N	\N	3	\N	\N
6600	517	7126	2017-05-01	15:34:04	\N	\N	3	\N	\N
6601	517	7127	2017-05-01	15:34:04	\N	\N	3	\N	\N
6602	517	7128	2017-05-01	15:34:04	\N	\N	3	\N	\N
6603	517	7129	2017-05-01	15:34:04	\N	\N	3	\N	\N
6604	517	7130	2017-05-01	15:34:04	\N	\N	3	\N	\N
6605	517	7131	2017-05-01	15:34:04	\N	\N	3	\N	\N
6606	517	7132	2017-05-01	15:34:04	\N	\N	2	\N	\N
6607	517	7133	2017-05-01	15:34:04	\N	\N	2	\N	\N
6608	517	7134	2017-05-01	15:34:04	\N	\N	2	\N	\N
6609	517	7135	2017-05-01	15:34:04	\N	\N	2	\N	\N
6610	517	7136	2017-05-01	15:34:04	\N	\N	2	\N	\N
6611	517	7137	2017-05-01	15:34:04	\N	\N	2	\N	\N
6612	517	7138	2017-05-01	15:34:04	\N	\N	2	\N	\N
6613	517	7139	2017-05-01	15:34:04	\N	\N	2	\N	\N
6614	517	7140	2017-05-01	15:34:04	\N	\N	2	\N	\N
6615	517	7141	2017-05-01	15:34:04	\N	\N	2	\N	\N
6616	517	7142	2017-05-01	15:34:04	\N	\N	2	\N	\N
6617	517	7143	2017-05-01	15:34:04	\N	\N	2	\N	\N
6618	517	7144	2017-05-01	15:34:04	\N	\N	2	\N	\N
6619	517	7145	2017-05-01	15:34:04	\N	\N	2	\N	\N
6620	517	7146	2017-05-01	15:34:04	\N	\N	2	\N	\N
6621	517	7147	2017-05-01	15:34:04	\N	\N	2	\N	\N
6622	517	7148	2017-05-01	15:34:04	\N	\N	2	\N	\N
6623	517	7149	2017-05-01	15:34:04	\N	\N	2	\N	\N
6624	517	7150	2017-05-01	15:34:04	\N	\N	2	\N	\N
6625	517	7151	2017-05-01	15:34:04	\N	\N	2	\N	\N
6626	517	7152	2017-05-01	15:34:04	\N	\N	2	\N	\N
6627	517	7153	2017-05-01	15:34:04	\N	\N	2	\N	\N
6628	517	7154	2017-05-01	15:34:04	\N	\N	2	\N	\N
6629	517	7155	2017-05-01	15:34:04	\N	\N	2	\N	\N
6630	517	7156	2017-05-01	15:34:04	\N	\N	2	\N	\N
6631	517	7157	2017-05-01	15:34:04	\N	\N	2	\N	\N
6632	517	7158	2017-05-01	15:34:04	\N	\N	2	\N	\N
6633	517	7159	2017-05-01	15:34:04	\N	\N	2	\N	\N
6634	517	7160	2017-05-01	15:34:04	\N	\N	2	\N	\N
6635	517	7161	2017-05-01	15:34:04	\N	\N	2	\N	\N
6636	517	7162	2017-05-01	15:34:04	\N	\N	2	\N	\N
6637	517	7163	2017-05-01	15:34:04	\N	\N	2	\N	\N
6638	517	7164	2017-05-01	15:34:04	\N	\N	2	\N	\N
6639	517	7165	2017-05-01	15:34:04	\N	\N	2	\N	\N
6640	517	7166	2017-05-01	15:34:04	\N	\N	2	\N	\N
6641	517	7167	2017-05-01	15:34:04	\N	\N	2	\N	\N
6642	517	7168	2017-05-01	15:34:04	\N	\N	2	\N	\N
6643	517	7169	2017-05-01	15:34:04	\N	\N	2	\N	\N
6644	517	7170	2017-05-01	15:34:04	\N	\N	2	\N	\N
6645	517	7171	2017-05-01	15:34:04	\N	\N	2	\N	\N
6646	517	7172	2017-05-01	15:34:04	\N	\N	2	\N	\N
6647	517	7173	2017-05-01	15:34:04	\N	\N	2	\N	\N
6648	517	7174	2017-05-01	15:34:04	\N	\N	2	\N	\N
6649	517	7175	2017-05-01	15:34:04	\N	\N	2	\N	\N
6650	517	7176	2017-05-01	15:34:04	\N	\N	2	\N	\N
6651	517	7177	2017-05-01	15:34:04	\N	\N	2	\N	\N
6652	517	7178	2017-05-01	15:34:04	\N	\N	2	\N	\N
6653	517	7179	2017-05-01	15:34:04	\N	\N	2	\N	\N
6654	517	7180	2017-05-01	15:34:04	\N	\N	2	\N	\N
6655	517	7181	2017-05-01	15:34:04	\N	\N	2	\N	\N
6656	517	7182	2017-05-01	15:34:04	\N	\N	2	\N	\N
6657	517	7183	2017-05-01	15:34:04	\N	\N	2	\N	\N
6658	517	7184	2017-05-01	15:34:04	\N	\N	2	\N	\N
6659	517	7185	2017-05-01	15:34:04	\N	\N	2	\N	\N
6660	517	7186	2017-05-01	15:34:04	\N	\N	2	\N	\N
6661	517	7187	2017-05-01	15:34:04	\N	\N	2	\N	\N
6662	517	7188	2017-05-01	15:34:04	\N	\N	2	\N	\N
6663	517	7189	2017-05-01	15:34:04	\N	\N	2	\N	\N
6664	517	7190	2017-05-01	15:34:04	\N	\N	2	\N	\N
6665	517	7191	2017-05-01	15:34:04	\N	\N	2	\N	\N
6666	517	7192	2017-05-01	15:34:04	\N	\N	2	\N	\N
6667	517	7193	2017-05-01	15:34:04	\N	\N	2	\N	\N
6668	517	7194	2017-05-01	15:34:04	\N	\N	2	\N	\N
6669	517	7195	2017-05-01	15:34:04	\N	\N	2	\N	\N
6670	517	7196	2017-05-01	15:34:04	\N	\N	2	\N	\N
6671	517	7197	2017-05-01	15:34:04	\N	\N	2	\N	\N
6672	517	7198	2017-05-01	15:34:04	\N	\N	2	\N	\N
6673	517	7199	2017-05-01	15:34:04	\N	\N	2	\N	\N
6674	517	7200	2017-05-01	15:34:04	\N	\N	2	\N	\N
6675	517	7201	2017-05-01	15:34:04	\N	\N	2	\N	\N
6676	517	7202	2017-05-01	15:34:04	\N	\N	2	\N	\N
6677	517	7203	2017-05-01	15:34:04	\N	\N	2	\N	\N
6678	517	7204	2017-05-01	15:34:04	\N	\N	2	\N	\N
6679	517	7205	2017-05-01	15:34:04	\N	\N	1	\N	\N
6680	517	7206	2017-05-01	15:34:04	\N	\N	1	\N	\N
6681	517	7207	2017-05-01	15:34:04	\N	\N	1	\N	\N
6682	518	7209	2017-05-01	15:34:04	\N	\N	3	\N	\N
6683	518	7210	2017-05-01	15:34:04	\N	\N	2	\N	\N
6684	518	7211	2017-05-01	15:34:04	\N	\N	2	\N	\N
6685	518	7212	2017-05-01	15:34:04	\N	\N	2	\N	\N
6686	518	7213	2017-05-01	15:34:04	\N	\N	2	\N	\N
6687	518	7214	2017-05-01	15:34:04	\N	\N	2	\N	\N
6688	518	7215	2017-05-01	15:34:04	\N	\N	2	\N	\N
6689	518	7216	2017-05-01	15:34:04	\N	\N	2	\N	\N
6690	518	7217	2017-05-01	15:34:04	\N	\N	2	\N	\N
6691	518	7218	2017-05-01	15:34:04	\N	\N	2	\N	\N
6692	518	7219	2017-05-01	15:34:04	\N	\N	2	\N	\N
6693	518	7220	2017-05-01	15:34:04	\N	\N	2	\N	\N
6694	518	7221	2017-05-01	15:34:04	\N	\N	2	\N	\N
6695	518	7222	2017-05-01	15:34:04	\N	\N	2	\N	\N
6696	518	7223	2017-05-01	15:34:04	\N	\N	2	\N	\N
6697	518	7224	2017-05-01	15:34:04	\N	\N	2	\N	\N
6698	518	7225	2017-05-01	15:34:04	\N	\N	2	\N	\N
6699	518	7226	2017-05-01	15:34:04	\N	\N	2	\N	\N
6700	518	7227	2017-05-01	15:34:04	\N	\N	2	\N	\N
6701	518	7228	2017-05-01	15:34:04	\N	\N	2	\N	\N
6702	518	7229	2017-05-01	15:34:04	\N	\N	2	\N	\N
6703	518	7230	2017-05-01	15:34:04	\N	\N	2	\N	\N
6704	518	7231	2017-05-01	15:34:04	\N	\N	2	\N	\N
6705	518	7232	2017-05-01	15:34:04	\N	\N	2	\N	\N
6706	518	7233	2017-05-01	15:34:04	\N	\N	2	\N	\N
6707	518	7234	2017-05-01	15:34:04	\N	\N	2	\N	\N
6708	518	7235	2017-05-01	15:34:04	\N	\N	2	\N	\N
6709	518	7236	2017-05-01	15:34:04	\N	\N	2	\N	\N
6710	518	7237	2017-05-01	15:34:04	\N	\N	2	\N	\N
6711	518	7238	2017-05-01	15:34:04	\N	\N	2	\N	\N
6712	518	7239	2017-05-01	15:34:04	\N	\N	2	\N	\N
6713	518	7240	2017-05-01	15:34:04	\N	\N	2	\N	\N
6714	518	7241	2017-05-01	15:34:04	\N	\N	2	\N	\N
6715	518	7242	2017-05-01	15:34:04	\N	\N	2	\N	\N
6716	518	7243	2017-05-01	15:34:04	\N	\N	2	\N	\N
6717	518	7244	2017-05-01	15:34:04	\N	\N	2	\N	\N
6718	518	7245	2017-05-01	15:34:04	\N	\N	2	\N	\N
6719	518	7246	2017-05-01	15:34:04	\N	\N	2	\N	\N
6720	518	7247	2017-05-01	15:34:04	\N	\N	2	\N	\N
6721	518	7248	2017-05-01	15:34:04	\N	\N	2	\N	\N
6722	518	7249	2017-05-01	15:34:04	\N	\N	2	\N	\N
6723	518	7250	2017-05-01	15:34:04	\N	\N	2	\N	\N
6724	518	7251	2017-05-01	15:34:04	\N	\N	2	\N	\N
6725	518	7252	2017-05-01	15:34:04	\N	\N	2	\N	\N
6726	518	7253	2017-05-01	15:34:04	\N	\N	2	\N	\N
6727	518	7254	2017-05-01	15:34:04	\N	\N	2	\N	\N
6728	518	7255	2017-05-01	15:34:04	\N	\N	2	\N	\N
6729	518	7256	2017-05-01	15:34:04	\N	\N	2	\N	\N
6730	518	7257	2017-05-01	15:34:04	\N	\N	2	\N	\N
6731	518	7258	2017-05-01	15:34:04	\N	\N	2	\N	\N
6732	518	7259	2017-05-01	15:34:04	\N	\N	2	\N	\N
6733	518	7260	2017-05-01	15:34:04	\N	\N	2	\N	\N
6734	518	7261	2017-05-01	15:34:04	\N	\N	2	\N	\N
6735	518	7262	2017-05-01	15:34:04	\N	\N	2	\N	\N
6736	518	7263	2017-05-01	15:34:04	\N	\N	2	\N	\N
6737	518	7264	2017-05-01	15:34:04	\N	\N	2	\N	\N
6738	518	7265	2017-05-01	15:34:04	\N	\N	2	\N	\N
6739	518	7266	2017-05-01	15:34:04	\N	\N	2	\N	\N
6740	518	7267	2017-05-01	15:34:04	\N	\N	2	\N	\N
6741	518	7268	2017-05-01	15:34:04	\N	\N	2	\N	\N
6742	519	7270	2017-05-01	15:34:04	\N	\N	2	\N	\N
6743	519	7271	2017-05-01	15:34:04	\N	\N	2	\N	\N
6744	519	7272	2017-05-01	15:34:04	\N	\N	2	\N	\N
6745	519	7273	2017-05-01	15:34:04	\N	\N	2	\N	\N
6746	519	7274	2017-05-01	15:34:04	\N	\N	2	\N	\N
6747	519	7275	2017-05-01	15:34:04	\N	\N	2	\N	\N
6748	519	7276	2017-05-01	15:34:04	\N	\N	2	\N	\N
6749	519	7277	2017-05-01	15:34:04	\N	\N	2	\N	\N
6750	519	7278	2017-05-01	15:34:04	\N	\N	2	\N	\N
6751	519	7279	2017-05-01	15:34:04	\N	\N	2	\N	\N
6752	519	7280	2017-05-01	15:34:04	\N	\N	2	\N	\N
6753	519	7281	2017-05-01	15:34:04	\N	\N	2	\N	\N
6754	519	7282	2017-05-01	15:34:04	\N	\N	2	\N	\N
6755	519	7283	2017-05-01	15:34:04	\N	\N	1	\N	\N
6756	519	7284	2017-05-01	15:34:04	\N	\N	1	\N	\N
6757	519	7285	2017-05-01	15:34:04	\N	\N	1	\N	\N
6758	521	7288	2017-05-01	15:34:04	\N	\N	3	\N	\N
6759	521	7289	2017-05-01	15:34:04	\N	\N	2	\N	\N
6760	522	7291	2017-05-01	15:34:04	\N	\N	3	\N	\N
6761	522	7292	2017-05-01	15:34:04	\N	\N	3	\N	\N
6762	522	7293	2017-05-01	15:34:04	\N	\N	3	\N	\N
6763	522	7294	2017-05-01	15:34:04	\N	\N	3	\N	\N
6764	522	7295	2017-05-01	15:34:04	\N	\N	2	\N	\N
6765	522	7296	2017-05-01	15:34:04	\N	\N	2	\N	\N
6766	522	7297	2017-05-01	15:34:04	\N	\N	2	\N	\N
6767	522	7298	2017-05-01	15:34:04	\N	\N	2	\N	\N
6768	522	7299	2017-05-01	15:34:04	\N	\N	2	\N	\N
6769	522	7300	2017-05-01	15:34:04	\N	\N	2	\N	\N
6770	522	7301	2017-05-01	15:34:04	\N	\N	2	\N	\N
6771	522	7302	2017-05-01	15:34:04	\N	\N	2	\N	\N
6772	522	7303	2017-05-01	15:34:04	\N	\N	2	\N	\N
6773	522	7304	2017-05-01	15:34:04	\N	\N	2	\N	\N
6774	522	7305	2017-05-01	15:34:04	\N	\N	2	\N	\N
6775	522	7306	2017-05-01	15:34:04	\N	\N	2	\N	\N
6776	522	7307	2017-05-01	15:34:04	\N	\N	2	\N	\N
6777	524	7310	2017-05-01	15:35:04	\N	\N	2	\N	\N
6778	524	7311	2017-05-01	15:35:04	\N	\N	2	\N	\N
6779	524	7312	2017-05-01	15:35:04	\N	\N	2	\N	\N
6780	524	7313	2017-05-01	15:35:04	\N	\N	2	\N	\N
6781	524	7314	2017-05-01	15:35:04	\N	\N	2	\N	\N
6782	524	7315	2017-05-01	15:35:04	\N	\N	2	\N	\N
6783	524	7316	2017-05-01	15:35:04	\N	\N	2	\N	\N
6784	524	7317	2017-05-01	15:35:04	\N	\N	2	\N	\N
6785	524	7318	2017-05-01	15:35:04	\N	\N	2	\N	\N
6786	524	7319	2017-05-01	15:35:04	\N	\N	2	\N	\N
6787	525	7321	2017-05-01	15:35:04	\N	\N	2	\N	\N
6788	525	7322	2017-05-01	15:35:04	\N	\N	2	\N	\N
6789	525	7323	2017-05-01	15:35:04	\N	\N	2	\N	\N
6790	525	7324	2017-05-01	15:35:04	\N	\N	2	\N	\N
6791	525	7325	2017-05-01	15:35:04	\N	\N	2	\N	\N
6792	525	7326	2017-05-01	15:35:04	\N	\N	2	\N	\N
6793	525	7327	2017-05-01	15:35:04	\N	\N	2	\N	\N
6794	525	7328	2017-05-01	15:35:04	\N	\N	2	\N	\N
6795	525	7329	2017-05-01	15:35:04	\N	\N	2	\N	\N
6796	525	7330	2017-05-01	15:35:04	\N	\N	2	\N	\N
6797	525	7331	2017-05-01	15:35:04	\N	\N	2	\N	\N
6798	525	7332	2017-05-01	15:35:04	\N	\N	2	\N	\N
6799	525	7333	2017-05-01	15:35:04	\N	\N	2	\N	\N
6800	526	7335	2017-05-01	15:35:04	\N	\N	2	\N	\N
6801	526	7336	2017-05-01	15:35:04	\N	\N	2	\N	\N
6802	526	7337	2017-05-01	15:35:04	\N	\N	2	\N	\N
6803	526	7338	2017-05-01	15:35:04	\N	\N	2	\N	\N
6804	526	7339	2017-05-01	15:35:04	\N	\N	2	\N	\N
6805	526	7340	2017-05-01	15:35:04	\N	\N	2	\N	\N
6806	526	7341	2017-05-01	15:35:04	\N	\N	2	\N	\N
6807	526	7342	2017-05-01	15:35:04	\N	\N	2	\N	\N
6808	526	7343	2017-05-01	15:35:04	\N	\N	2	\N	\N
6809	526	7344	2017-05-01	15:35:04	\N	\N	2	\N	\N
6810	526	7345	2017-05-01	15:35:04	\N	\N	2	\N	\N
6811	526	7346	2017-05-01	15:35:04	\N	\N	2	\N	\N
6812	526	7347	2017-05-01	15:35:04	\N	\N	2	\N	\N
6813	526	7348	2017-05-01	15:35:04	\N	\N	2	\N	\N
6814	526	7349	2017-05-01	15:35:04	\N	\N	2	\N	\N
6815	526	7350	2017-05-01	15:35:04	\N	\N	2	\N	\N
6816	526	7351	2017-05-01	15:35:04	\N	\N	2	\N	\N
6817	526	7352	2017-05-01	15:35:04	\N	\N	2	\N	\N
6818	526	7353	2017-05-01	15:35:04	\N	\N	2	\N	\N
6819	526	7354	2017-05-01	15:35:04	\N	\N	2	\N	\N
6820	526	7355	2017-05-01	15:35:04	\N	\N	2	\N	\N
6821	526	7356	2017-05-01	15:35:04	\N	\N	2	\N	\N
6822	526	7357	2017-05-01	15:35:04	\N	\N	2	\N	\N
6823	526	7358	2017-05-01	15:35:04	\N	\N	2	\N	\N
6824	526	7359	2017-05-01	15:35:04	\N	\N	2	\N	\N
6825	526	7360	2017-05-01	15:35:04	\N	\N	2	\N	\N
6826	526	7361	2017-05-01	15:35:04	\N	\N	2	\N	\N
6827	526	7362	2017-05-01	15:35:04	\N	\N	2	\N	\N
6828	526	7363	2017-05-01	15:35:04	\N	\N	2	\N	\N
6829	526	7364	2017-05-01	15:35:04	\N	\N	2	\N	\N
6830	526	7365	2017-05-01	15:35:04	\N	\N	2	\N	\N
6831	526	7366	2017-05-01	15:35:04	\N	\N	2	\N	\N
6832	526	7367	2017-05-01	15:35:04	\N	\N	2	\N	\N
6833	526	7368	2017-05-01	15:35:04	\N	\N	2	\N	\N
6834	526	7369	2017-05-01	15:35:04	\N	\N	2	\N	\N
6835	526	7370	2017-05-01	15:35:04	\N	\N	2	\N	\N
6836	526	7371	2017-05-01	15:35:04	\N	\N	2	\N	\N
6837	526	7372	2017-05-01	15:35:04	\N	\N	2	\N	\N
6838	526	7373	2017-05-01	15:35:04	\N	\N	2	\N	\N
6839	526	7374	2017-05-01	15:35:04	\N	\N	2	\N	\N
6840	526	7375	2017-05-01	15:35:04	\N	\N	2	\N	\N
6841	526	7376	2017-05-01	15:35:04	\N	\N	2	\N	\N
6842	526	7377	2017-05-01	15:35:04	\N	\N	2	\N	\N
6843	526	7378	2017-05-01	15:35:04	\N	\N	2	\N	\N
6844	526	7379	2017-05-01	15:35:04	\N	\N	2	\N	\N
6845	526	7380	2017-05-01	15:35:04	\N	\N	2	\N	\N
6846	526	7381	2017-05-01	15:35:04	\N	\N	2	\N	\N
6847	526	7382	2017-05-01	15:35:04	\N	\N	2	\N	\N
6848	526	7383	2017-05-01	15:35:04	\N	\N	1	\N	\N
6849	526	7384	2017-05-01	15:35:04	\N	\N	1	\N	\N
6850	526	7385	2017-05-01	15:35:04	\N	\N	1	\N	\N
6851	526	7386	2017-05-01	15:35:04	\N	\N	1	\N	\N
6852	526	7387	2017-05-01	15:35:04	\N	\N	1	\N	\N
6853	526	7388	2017-05-01	15:35:04	\N	\N	1	\N	\N
6854	526	7389	2017-05-01	15:35:04	\N	\N	1	\N	\N
6855	526	7390	2017-05-01	15:35:04	\N	\N	1	\N	\N
6856	526	7391	2017-05-01	15:35:04	\N	\N	1	\N	\N
6857	526	7392	2017-05-01	15:35:04	\N	\N	1	\N	\N
6858	526	7393	2017-05-01	15:35:04	\N	\N	1	\N	\N
6859	527	7395	2017-05-01	15:35:04	\N	\N	2	\N	\N
6860	527	7396	2017-05-01	15:35:04	\N	\N	2	\N	\N
6861	527	7397	2017-05-01	15:35:04	\N	\N	2	\N	\N
6862	527	7398	2017-05-01	15:35:04	\N	\N	2	\N	\N
6863	527	7399	2017-05-01	15:35:04	\N	\N	2	\N	\N
6864	527	7400	2017-05-01	15:35:04	\N	\N	2	\N	\N
6865	527	7401	2017-05-01	15:35:04	\N	\N	2	\N	\N
6866	527	7402	2017-05-01	15:35:04	\N	\N	2	\N	\N
6867	527	7403	2017-05-01	15:35:04	\N	\N	2	\N	\N
6868	527	7404	2017-05-01	15:35:04	\N	\N	2	\N	\N
6869	527	7405	2017-05-01	15:35:04	\N	\N	2	\N	\N
6870	527	7406	2017-05-01	15:35:04	\N	\N	2	\N	\N
6871	527	7407	2017-05-01	15:35:04	\N	\N	2	\N	\N
6872	527	7408	2017-05-01	15:35:04	\N	\N	2	\N	\N
6873	527	7409	2017-05-01	15:35:04	\N	\N	2	\N	\N
6874	527	7410	2017-05-01	15:35:04	\N	\N	2	\N	\N
6875	527	7411	2017-05-01	15:35:04	\N	\N	2	\N	\N
6876	528	7414	2017-05-01	15:35:04	\N	\N	3	\N	\N
6877	528	7415	2017-05-01	15:35:04	\N	\N	2	\N	\N
6878	528	7416	2017-05-01	15:35:04	\N	\N	2	\N	\N
6879	528	7417	2017-05-01	15:35:04	\N	\N	2	\N	\N
6880	528	7418	2017-05-01	15:35:04	\N	\N	2	\N	\N
6881	528	7419	2017-05-01	15:35:04	\N	\N	2	\N	\N
6882	528	7420	2017-05-01	15:35:04	\N	\N	2	\N	\N
6883	528	7421	2017-05-01	15:35:04	\N	\N	2	\N	\N
6884	528	7422	2017-05-01	15:35:04	\N	\N	2	\N	\N
6885	528	7423	2017-05-01	15:35:04	\N	\N	2	\N	\N
6886	528	7424	2017-05-01	15:35:04	\N	\N	2	\N	\N
6887	528	7425	2017-05-01	15:35:04	\N	\N	2	\N	\N
6888	528	7426	2017-05-01	15:35:04	\N	\N	2	\N	\N
6889	528	7427	2017-05-01	15:35:04	\N	\N	2	\N	\N
6890	529	7429	2017-05-01	15:35:04	\N	\N	3	\N	\N
6891	529	7430	2017-05-01	15:35:04	\N	\N	3	\N	\N
6892	529	7431	2017-05-01	15:35:04	\N	\N	3	\N	\N
6893	529	7432	2017-05-01	15:35:04	\N	\N	2	\N	\N
6894	529	7433	2017-05-01	15:35:04	\N	\N	2	\N	\N
6895	529	7434	2017-05-01	15:35:04	\N	\N	2	\N	\N
6896	529	7435	2017-05-01	15:35:04	\N	\N	3	\N	\N
6897	529	7436	2017-05-01	15:35:04	\N	\N	2	\N	\N
6898	529	7437	2017-05-01	15:35:04	\N	\N	2	\N	\N
6899	529	7438	2017-05-01	15:35:04	\N	\N	2	\N	\N
6900	529	7439	2017-05-01	15:35:04	\N	\N	2	\N	\N
6901	529	7440	2017-05-01	15:35:04	\N	\N	2	\N	\N
6902	529	7441	2017-05-01	15:35:04	\N	\N	2	\N	\N
6903	529	7442	2017-05-01	15:35:04	\N	\N	2	\N	\N
6904	529	7443	2017-05-01	15:35:04	\N	\N	2	\N	\N
6905	529	7444	2017-05-01	15:35:04	\N	\N	2	\N	\N
6906	529	7445	2017-05-01	15:35:04	\N	\N	2	\N	\N
6907	529	7446	2017-05-01	15:35:04	\N	\N	2	\N	\N
6908	529	7447	2017-05-01	15:35:04	\N	\N	2	\N	\N
6909	529	7448	2017-05-01	15:35:04	\N	\N	3	\N	\N
6910	529	7449	2017-05-01	15:35:04	\N	\N	2	\N	\N
6911	529	7450	2017-05-01	15:35:04	\N	\N	2	\N	\N
6912	529	7451	2017-05-01	15:35:04	\N	\N	2	\N	\N
6913	529	7452	2017-05-01	15:35:04	\N	\N	2	\N	\N
6914	529	7453	2017-05-01	15:35:04	\N	\N	2	\N	\N
6915	529	7454	2017-05-01	15:35:04	\N	\N	2	\N	\N
6916	529	7455	2017-05-01	15:35:04	\N	\N	2	\N	\N
6917	529	7456	2017-05-01	15:35:04	\N	\N	2	\N	\N
6918	529	7457	2017-05-01	15:35:04	\N	\N	2	\N	\N
6919	529	7458	2017-05-01	15:35:04	\N	\N	2	\N	\N
6920	529	7459	2017-05-01	15:35:04	\N	\N	2	\N	\N
6921	529	7460	2017-05-01	15:35:04	\N	\N	2	\N	\N
6922	529	7461	2017-05-01	15:35:04	\N	\N	2	\N	\N
6923	529	7462	2017-05-01	15:35:04	\N	\N	2	\N	\N
6924	529	7463	2017-05-01	15:35:04	\N	\N	2	\N	\N
6925	529	7464	2017-05-01	15:35:04	\N	\N	2	\N	\N
6926	529	7465	2017-05-01	15:35:04	\N	\N	2	\N	\N
6927	529	7466	2017-05-01	15:35:04	\N	\N	2	\N	\N
6928	529	7467	2017-05-01	15:35:04	\N	\N	2	\N	\N
6929	529	7468	2017-05-01	15:35:04	\N	\N	2	\N	\N
6930	529	7469	2017-05-01	15:35:04	\N	\N	2	\N	\N
6931	529	7470	2017-05-01	15:35:04	\N	\N	2	\N	\N
6932	529	7471	2017-05-01	15:35:04	\N	\N	2	\N	\N
6933	529	7472	2017-05-01	15:35:04	\N	\N	2	\N	\N
6934	529	7473	2017-05-01	15:35:04	\N	\N	2	\N	\N
6935	529	7474	2017-05-01	15:35:04	\N	\N	2	\N	\N
6936	529	7475	2017-05-01	15:35:04	\N	\N	2	\N	\N
6937	529	7476	2017-05-01	15:35:04	\N	\N	2	\N	\N
6938	529	7477	2017-05-01	15:35:04	\N	\N	2	\N	\N
6939	529	7478	2017-05-01	15:35:04	\N	\N	2	\N	\N
6940	529	7479	2017-05-01	15:35:04	\N	\N	2	\N	\N
6941	529	7480	2017-05-01	15:35:04	\N	\N	2	\N	\N
6942	529	7481	2017-05-01	15:35:04	\N	\N	2	\N	\N
6943	529	7482	2017-05-01	15:35:04	\N	\N	2	\N	\N
6944	529	7483	2017-05-01	15:35:04	\N	\N	2	\N	\N
6945	529	7484	2017-05-01	15:35:04	\N	\N	2	\N	\N
6946	529	7485	2017-05-01	15:35:04	\N	\N	2	\N	\N
6947	529	7486	2017-05-01	15:35:04	\N	\N	2	\N	\N
6948	529	7487	2017-05-01	15:35:04	\N	\N	2	\N	\N
6949	529	7488	2017-05-01	15:35:04	\N	\N	2	\N	\N
6950	529	7489	2017-05-01	15:35:04	\N	\N	2	\N	\N
6951	531	7492	2017-05-01	15:36:04	\N	\N	2	\N	\N
6952	531	7493	2017-05-01	15:36:04	\N	\N	2	\N	\N
6953	535	7498	2017-05-01	15:36:04	\N	\N	2	\N	\N
6954	535	7499	2017-05-01	15:36:04	\N	\N	2	\N	\N
6955	535	7500	2017-05-01	15:36:04	\N	\N	2	\N	\N
6956	535	7501	2017-05-01	15:36:04	\N	\N	2	\N	\N
6957	535	7502	2017-05-01	15:36:04	\N	\N	2	\N	\N
6958	535	7503	2017-05-01	15:36:04	\N	\N	2	\N	\N
6959	535	7504	2017-05-01	15:36:04	\N	\N	2	\N	\N
6960	535	7505	2017-05-01	15:36:04	\N	\N	2	\N	\N
6961	535	7506	2017-05-01	15:36:04	\N	\N	2	\N	\N
6962	536	7509	2017-05-01	15:36:04	\N	\N	3	\N	\N
6963	536	7510	2017-05-01	15:36:04	\N	\N	3	\N	\N
6964	536	7511	2017-05-01	15:36:04	\N	\N	3	\N	\N
6965	536	7512	2017-05-01	15:36:04	\N	\N	2	\N	\N
6966	536	7513	2017-05-01	15:36:04	\N	\N	2	\N	\N
6967	536	7514	2017-05-01	15:36:04	\N	\N	2	\N	\N
6968	536	7515	2017-05-01	15:36:04	\N	\N	2	\N	\N
6969	536	7516	2017-05-01	15:36:04	\N	\N	2	\N	\N
6970	536	7517	2017-05-01	15:36:04	\N	\N	2	\N	\N
6971	536	7518	2017-05-01	15:36:04	\N	\N	2	\N	\N
6972	536	7519	2017-05-01	15:36:04	\N	\N	2	\N	\N
6973	536	7520	2017-05-01	15:36:04	\N	\N	2	\N	\N
6974	536	7521	2017-05-01	15:36:04	\N	\N	2	\N	\N
6975	536	7522	2017-05-01	15:36:04	\N	\N	2	\N	\N
6976	536	7523	2017-05-01	15:36:04	\N	\N	2	\N	\N
6977	536	7524	2017-05-01	15:36:04	\N	\N	2	\N	\N
6978	536	7525	2017-05-01	15:36:04	\N	\N	2	\N	\N
6979	536	7526	2017-05-01	15:36:04	\N	\N	2	\N	\N
6980	536	7527	2017-05-01	15:36:04	\N	\N	2	\N	\N
6981	536	7528	2017-05-01	15:36:04	\N	\N	2	\N	\N
6982	536	7529	2017-05-01	15:36:04	\N	\N	2	\N	\N
6983	536	7530	2017-05-01	15:36:04	\N	\N	2	\N	\N
6984	537	7532	2017-05-01	15:36:04	\N	\N	3	\N	\N
6985	537	7533	2017-05-01	15:36:04	\N	\N	3	\N	\N
6986	537	7534	2017-05-01	15:36:04	\N	\N	3	\N	\N
6987	537	7535	2017-05-01	15:36:04	\N	\N	3	\N	\N
6988	537	7536	2017-05-01	15:36:04	\N	\N	2	\N	\N
6989	537	7537	2017-05-01	15:36:04	\N	\N	2	\N	\N
6990	537	7538	2017-05-01	15:36:04	\N	\N	2	\N	\N
6991	537	7539	2017-05-01	15:36:04	\N	\N	2	\N	\N
6992	537	7540	2017-05-01	15:36:04	\N	\N	2	\N	\N
6993	541	7545	2017-05-01	15:36:04	\N	\N	3	\N	\N
6994	541	7546	2017-05-01	15:36:04	\N	\N	3	\N	\N
6995	541	7547	2017-05-01	15:36:04	\N	\N	2	\N	\N
6996	541	7548	2017-05-01	15:36:04	\N	\N	2	\N	\N
6997	541	7549	2017-05-01	15:36:04	\N	\N	2	\N	\N
6998	541	7550	2017-05-01	15:36:04	\N	\N	2	\N	\N
6999	541	7551	2017-05-01	15:36:04	\N	\N	2	\N	\N
7000	541	7552	2017-05-01	15:36:04	\N	\N	2	\N	\N
7001	541	7553	2017-05-01	15:36:04	\N	\N	2	\N	\N
7002	541	7554	2017-05-01	15:36:04	\N	\N	2	\N	\N
7003	541	7555	2017-05-01	15:36:04	\N	\N	2	\N	\N
7004	541	7556	2017-05-01	15:36:04	\N	\N	2	\N	\N
7005	541	7557	2017-05-01	15:36:04	\N	\N	2	\N	\N
7006	541	7558	2017-05-01	15:36:04	\N	\N	2	\N	\N
7007	541	7559	2017-05-01	15:36:04	\N	\N	2	\N	\N
7008	541	7560	2017-05-01	15:36:04	\N	\N	2	\N	\N
7009	541	7561	2017-05-01	15:36:04	\N	\N	2	\N	\N
7010	541	7562	2017-05-01	15:36:04	\N	\N	2	\N	\N
7011	541	7563	2017-05-01	15:36:04	\N	\N	2	\N	\N
7012	541	7564	2017-05-01	15:36:04	\N	\N	2	\N	\N
7013	541	7565	2017-05-01	15:36:04	\N	\N	2	\N	\N
7014	541	7566	2017-05-01	15:36:04	\N	\N	2	\N	\N
7015	543	7569	2017-05-01	15:37:04	\N	\N	2	\N	\N
7016	543	7570	2017-05-01	15:37:04	\N	\N	2	\N	\N
7017	543	7571	2017-05-01	15:37:04	\N	\N	2	\N	\N
7018	543	7572	2017-05-01	15:37:04	\N	\N	2	\N	\N
7019	543	7573	2017-05-01	15:37:04	\N	\N	2	\N	\N
7020	543	7574	2017-05-01	15:37:04	\N	\N	2	\N	\N
7021	543	7575	2017-05-01	15:37:04	\N	\N	2	\N	\N
7022	543	7576	2017-05-01	15:37:04	\N	\N	2	\N	\N
7023	543	7577	2017-05-01	15:37:04	\N	\N	2	\N	\N
7024	543	7578	2017-05-01	15:37:04	\N	\N	2	\N	\N
7025	543	7579	2017-05-01	15:37:04	\N	\N	2	\N	\N
7026	543	7580	2017-05-01	15:37:04	\N	\N	2	\N	\N
7027	543	7581	2017-05-01	15:37:04	\N	\N	2	\N	\N
7028	543	7582	2017-05-01	15:37:04	\N	\N	2	\N	\N
7029	543	7583	2017-05-01	15:37:04	\N	\N	2	\N	\N
7030	543	7584	2017-05-01	15:37:04	\N	\N	2	\N	\N
7031	543	7585	2017-05-01	15:37:04	\N	\N	2	\N	\N
7032	543	7586	2017-05-01	15:37:04	\N	\N	2	\N	\N
7033	543	7587	2017-05-01	15:37:04	\N	\N	2	\N	\N
7034	543	7588	2017-05-01	15:37:04	\N	\N	2	\N	\N
7035	543	7589	2017-05-01	15:37:04	\N	\N	2	\N	\N
7036	543	7590	2017-05-01	15:37:04	\N	\N	2	\N	\N
7037	543	7591	2017-05-01	15:37:04	\N	\N	2	\N	\N
7038	543	7592	2017-05-01	15:37:04	\N	\N	2	\N	\N
7039	543	7593	2017-05-01	15:37:04	\N	\N	2	\N	\N
7040	543	7594	2017-05-01	15:37:04	\N	\N	2	\N	\N
7041	543	7595	2017-05-01	15:37:04	\N	\N	2	\N	\N
7042	543	7596	2017-05-01	15:37:04	\N	\N	2	\N	\N
7043	543	7597	2017-05-01	15:37:04	\N	\N	2	\N	\N
7044	543	7598	2017-05-01	15:37:04	\N	\N	2	\N	\N
7045	544	7600	2017-05-01	15:37:04	\N	\N	3	\N	\N
7046	544	7601	2017-05-01	15:37:04	\N	\N	2	\N	\N
7047	544	7602	2017-05-01	15:37:04	\N	\N	2	\N	\N
7048	544	7603	2017-05-01	15:37:04	\N	\N	2	\N	\N
7049	544	7604	2017-05-01	15:37:04	\N	\N	2	\N	\N
7050	544	7605	2017-05-01	15:37:04	\N	\N	2	\N	\N
7051	544	7606	2017-05-01	15:37:04	\N	\N	2	\N	\N
7052	544	7607	2017-05-01	15:37:04	\N	\N	2	\N	\N
7053	544	7608	2017-05-01	15:37:04	\N	\N	2	\N	\N
7054	545	7610	2017-05-01	15:37:04	\N	\N	3	\N	\N
7055	545	7611	2017-05-01	15:37:04	\N	\N	3	\N	\N
7056	545	7612	2017-05-01	15:37:04	\N	\N	3	\N	\N
7057	545	7613	2017-05-01	15:37:04	\N	\N	3	\N	\N
7058	545	7614	2017-05-01	15:37:04	\N	\N	2	\N	\N
7059	545	7615	2017-05-01	15:37:04	\N	\N	2	\N	\N
7060	545	7616	2017-05-01	15:37:04	\N	\N	2	\N	\N
7061	545	7617	2017-05-01	15:37:04	\N	\N	2	\N	\N
7062	545	7618	2017-05-01	15:37:04	\N	\N	2	\N	\N
7063	545	7619	2017-05-01	15:37:04	\N	\N	2	\N	\N
7064	545	7620	2017-05-01	15:37:04	\N	\N	2	\N	\N
7065	545	7621	2017-05-01	15:37:04	\N	\N	2	\N	\N
7066	545	7622	2017-05-01	15:37:04	\N	\N	2	\N	\N
7067	545	7623	2017-05-01	15:37:04	\N	\N	2	\N	\N
7068	545	7624	2017-05-01	15:37:04	\N	\N	2	\N	\N
7069	545	7625	2017-05-01	15:37:04	\N	\N	2	\N	\N
7070	545	7626	2017-05-01	15:37:04	\N	\N	2	\N	\N
7071	545	7627	2017-05-01	15:37:04	\N	\N	2	\N	\N
7072	545	7628	2017-05-01	15:37:04	\N	\N	2	\N	\N
7073	545	7629	2017-05-01	15:37:04	\N	\N	2	\N	\N
7074	545	7630	2017-05-01	15:37:04	\N	\N	2	\N	\N
7075	545	7631	2017-05-01	15:37:04	\N	\N	2	\N	\N
7076	546	7633	2017-05-01	15:37:04	\N	\N	3	\N	\N
7077	546	7634	2017-05-01	15:37:04	\N	\N	3	\N	\N
7078	546	7635	2017-05-01	15:37:04	\N	\N	2	\N	\N
7079	546	7636	2017-05-01	15:37:04	\N	\N	2	\N	\N
7080	546	7637	2017-05-01	15:37:04	\N	\N	2	\N	\N
7081	546	7638	2017-05-01	15:37:04	\N	\N	2	\N	\N
7082	546	7639	2017-05-01	15:37:04	\N	\N	2	\N	\N
7083	546	7640	2017-05-01	15:37:04	\N	\N	1	\N	\N
7084	547	7642	2017-05-01	15:37:04	\N	\N	3	\N	\N
7085	547	7643	2017-05-01	15:37:04	\N	\N	3	\N	\N
7086	547	7644	2017-05-01	15:37:04	\N	\N	3	\N	\N
7087	547	7645	2017-05-01	15:37:04	\N	\N	2	\N	\N
7088	547	7646	2017-05-01	15:37:04	\N	\N	2	\N	\N
7089	547	7647	2017-05-01	15:37:04	\N	\N	2	\N	\N
7090	547	7648	2017-05-01	15:37:04	\N	\N	2	\N	\N
7091	547	7649	2017-05-01	15:37:04	\N	\N	2	\N	\N
7092	547	7650	2017-05-01	15:37:04	\N	\N	2	\N	\N
7093	547	7651	2017-05-01	15:37:04	\N	\N	2	\N	\N
7094	547	7652	2017-05-01	15:37:04	\N	\N	2	\N	\N
7095	547	7653	2017-05-01	15:37:04	\N	\N	2	\N	\N
7096	547	7654	2017-05-01	15:37:04	\N	\N	2	\N	\N
7097	547	7655	2017-05-01	15:37:04	\N	\N	2	\N	\N
7098	547	7656	2017-05-01	15:37:04	\N	\N	2	\N	\N
7099	547	7657	2017-05-01	15:37:04	\N	\N	2	\N	\N
7100	547	7658	2017-05-01	15:37:04	\N	\N	2	\N	\N
7101	547	7659	2017-05-01	15:37:04	\N	\N	2	\N	\N
7102	549	7662	2017-05-01	15:37:04	\N	\N	3	\N	\N
7103	549	7663	2017-05-01	15:37:04	\N	\N	3	\N	\N
7104	549	7664	2017-05-01	15:37:04	\N	\N	3	\N	\N
7105	549	7665	2017-05-01	15:37:04	\N	\N	2	\N	\N
7106	549	7666	2017-05-01	15:37:04	\N	\N	2	\N	\N
7107	549	7667	2017-05-01	15:37:04	\N	\N	2	\N	\N
7108	549	7668	2017-05-01	15:37:04	\N	\N	2	\N	\N
7109	549	7669	2017-05-01	15:37:04	\N	\N	2	\N	\N
7110	549	7670	2017-05-01	15:37:04	\N	\N	2	\N	\N
7111	549	7671	2017-05-01	15:37:04	\N	\N	2	\N	\N
7112	549	7672	2017-05-01	15:37:04	\N	\N	2	\N	\N
7113	549	7673	2017-05-01	15:37:04	\N	\N	2	\N	\N
7114	549	7674	2017-05-01	15:37:04	\N	\N	2	\N	\N
7115	549	7675	2017-05-01	15:37:04	\N	\N	2	\N	\N
7116	549	7676	2017-05-01	15:37:04	\N	\N	2	\N	\N
7117	549	7677	2017-05-01	15:37:04	\N	\N	2	\N	\N
7118	549	7678	2017-05-01	15:37:04	\N	\N	2	\N	\N
7119	549	7679	2017-05-01	15:37:04	\N	\N	2	\N	\N
7120	549	7680	2017-05-01	15:37:04	\N	\N	2	\N	\N
7121	549	7681	2017-05-01	15:37:04	\N	\N	2	\N	\N
7122	549	7682	2017-05-01	15:37:04	\N	\N	2	\N	\N
7123	550	7684	2017-05-01	15:37:04	\N	\N	3	\N	\N
7124	550	7685	2017-05-01	15:37:04	\N	\N	2	\N	\N
7125	550	7686	2017-05-01	15:37:04	\N	\N	2	\N	\N
7126	550	7687	2017-05-01	15:37:04	\N	\N	2	\N	\N
7127	550	7688	2017-05-01	15:37:04	\N	\N	2	\N	\N
7128	550	7689	2017-05-01	15:37:04	\N	\N	2	\N	\N
7129	550	7690	2017-05-01	15:37:04	\N	\N	2	\N	\N
7130	550	7691	2017-05-01	15:37:04	\N	\N	2	\N	\N
7131	550	7692	2017-05-01	15:37:04	\N	\N	2	\N	\N
7132	550	7693	2017-05-01	15:37:04	\N	\N	2	\N	\N
7133	550	7694	2017-05-01	15:37:04	\N	\N	2	\N	\N
7134	550	7695	2017-05-01	15:37:04	\N	\N	2	\N	\N
7135	550	7696	2017-05-01	15:37:04	\N	\N	2	\N	\N
7136	550	7697	2017-05-01	15:37:04	\N	\N	2	\N	\N
7137	550	7698	2017-05-01	15:37:04	\N	\N	2	\N	\N
7138	550	7699	2017-05-01	15:37:04	\N	\N	2	\N	\N
7139	550	7700	2017-05-01	15:37:04	\N	\N	2	\N	\N
7140	550	7701	2017-05-01	15:37:04	\N	\N	2	\N	\N
7141	550	7702	2017-05-01	15:37:04	\N	\N	2	\N	\N
7142	550	7703	2017-05-01	15:37:04	\N	\N	2	\N	\N
7143	550	7704	2017-05-01	15:37:04	\N	\N	2	\N	\N
7144	550	7705	2017-05-01	15:37:04	\N	\N	2	\N	\N
7145	550	7706	2017-05-01	15:37:04	\N	\N	2	\N	\N
7146	550	7707	2017-05-01	15:37:04	\N	\N	2	\N	\N
7147	550	7708	2017-05-01	15:37:04	\N	\N	2	\N	\N
7148	550	7709	2017-05-01	15:37:04	\N	\N	2	\N	\N
7149	550	7710	2017-05-01	15:37:04	\N	\N	2	\N	\N
7150	551	7713	2017-05-01	15:38:04	\N	\N	3	\N	\N
7151	551	7714	2017-05-01	15:38:04	\N	\N	3	\N	\N
7152	551	7715	2017-05-01	15:38:04	\N	\N	3	\N	\N
7153	551	7716	2017-05-01	15:38:04	\N	\N	3	\N	\N
7154	551	7717	2017-05-01	15:38:04	\N	\N	2	\N	\N
7155	551	7718	2017-05-01	15:38:04	\N	\N	2	\N	\N
7156	551	7719	2017-05-01	15:38:04	\N	\N	2	\N	\N
7157	551	7720	2017-05-01	15:38:04	\N	\N	2	\N	\N
7158	551	7721	2017-05-01	15:38:04	\N	\N	2	\N	\N
7159	551	7722	2017-05-01	15:38:04	\N	\N	2	\N	\N
7160	551	7723	2017-05-01	15:38:04	\N	\N	2	\N	\N
7161	551	7724	2017-05-01	15:38:04	\N	\N	2	\N	\N
7162	551	7725	2017-05-01	15:38:04	\N	\N	2	\N	\N
7163	551	7726	2017-05-01	15:38:04	\N	\N	2	\N	\N
7164	551	7727	2017-05-01	15:38:04	\N	\N	2	\N	\N
7165	551	7728	2017-05-01	15:38:04	\N	\N	2	\N	\N
7166	551	7729	2017-05-01	15:38:04	\N	\N	2	\N	\N
7167	551	7730	2017-05-01	15:38:04	\N	\N	2	\N	\N
7168	551	7731	2017-05-01	15:38:04	\N	\N	2	\N	\N
7169	551	7732	2017-05-01	15:38:04	\N	\N	2	\N	\N
7170	551	7733	2017-05-01	15:38:04	\N	\N	2	\N	\N
7171	551	7734	2017-05-01	15:38:04	\N	\N	2	\N	\N
7172	551	7735	2017-05-01	15:38:04	\N	\N	2	\N	\N
7173	551	7736	2017-05-01	15:38:04	\N	\N	2	\N	\N
7174	551	7737	2017-05-01	15:38:04	\N	\N	2	\N	\N
7175	551	7738	2017-05-01	15:38:04	\N	\N	2	\N	\N
7176	551	7739	2017-05-01	15:38:04	\N	\N	2	\N	\N
7177	551	7740	2017-05-01	15:38:04	\N	\N	2	\N	\N
7178	551	7741	2017-05-01	15:38:04	\N	\N	2	\N	\N
7179	551	7742	2017-05-01	15:38:04	\N	\N	2	\N	\N
7180	551	7743	2017-05-01	15:38:04	\N	\N	2	\N	\N
7181	552	7745	2017-05-01	15:38:04	\N	\N	3	\N	\N
7182	552	7746	2017-05-01	15:38:04	\N	\N	3	\N	\N
7183	552	7747	2017-05-01	15:38:04	\N	\N	3	\N	\N
7184	552	7748	2017-05-01	15:38:04	\N	\N	3	\N	\N
7185	552	7749	2017-05-01	15:38:04	\N	\N	3	\N	\N
7186	552	7750	2017-05-01	15:38:04	\N	\N	2	\N	\N
7187	552	7751	2017-05-01	15:38:04	\N	\N	2	\N	\N
7188	552	7752	2017-05-01	15:38:04	\N	\N	2	\N	\N
7189	552	7753	2017-05-01	15:38:04	\N	\N	2	\N	\N
7190	552	7754	2017-05-01	15:38:04	\N	\N	2	\N	\N
7191	552	7755	2017-05-01	15:38:04	\N	\N	2	\N	\N
7192	552	7756	2017-05-01	15:38:04	\N	\N	2	\N	\N
7193	552	7757	2017-05-01	15:38:04	\N	\N	2	\N	\N
7194	552	7758	2017-05-01	15:38:04	\N	\N	2	\N	\N
7195	552	7759	2017-05-01	15:38:04	\N	\N	2	\N	\N
7196	552	7760	2017-05-01	15:38:04	\N	\N	2	\N	\N
7197	552	7761	2017-05-01	15:38:04	\N	\N	2	\N	\N
7198	552	7762	2017-05-01	15:38:04	\N	\N	2	\N	\N
7199	552	7763	2017-05-01	15:38:04	\N	\N	2	\N	\N
7200	552	7764	2017-05-01	15:38:04	\N	\N	2	\N	\N
7201	552	7765	2017-05-01	15:38:04	\N	\N	2	\N	\N
7202	552	7766	2017-05-01	15:38:04	\N	\N	2	\N	\N
7203	552	7767	2017-05-01	15:38:04	\N	\N	2	\N	\N
7204	552	7768	2017-05-01	15:38:04	\N	\N	2	\N	\N
7205	552	7769	2017-05-01	15:38:04	\N	\N	2	\N	\N
7206	552	7770	2017-05-01	15:38:04	\N	\N	2	\N	\N
7207	552	7771	2017-05-01	15:38:04	\N	\N	2	\N	\N
7208	552	7772	2017-05-01	15:38:04	\N	\N	2	\N	\N
7209	552	7773	2017-05-01	15:38:04	\N	\N	2	\N	\N
7210	552	7774	2017-05-01	15:38:04	\N	\N	2	\N	\N
7211	552	7775	2017-05-01	15:38:04	\N	\N	2	\N	\N
7212	552	7776	2017-05-01	15:38:04	\N	\N	2	\N	\N
7213	552	7777	2017-05-01	15:38:04	\N	\N	2	\N	\N
7214	552	7778	2017-05-01	15:38:04	\N	\N	2	\N	\N
7215	552	7779	2017-05-01	15:38:04	\N	\N	2	\N	\N
7216	552	7780	2017-05-01	15:38:04	\N	\N	2	\N	\N
7217	552	7781	2017-05-01	15:38:04	\N	\N	2	\N	\N
7218	552	7782	2017-05-01	15:38:04	\N	\N	2	\N	\N
7219	552	7783	2017-05-01	15:38:04	\N	\N	2	\N	\N
7220	552	7784	2017-05-01	15:38:04	\N	\N	2	\N	\N
7221	552	7785	2017-05-01	15:38:04	\N	\N	2	\N	\N
7222	552	7786	2017-05-01	15:38:04	\N	\N	2	\N	\N
7223	552	7787	2017-05-01	15:38:04	\N	\N	2	\N	\N
7224	552	7788	2017-05-01	15:38:04	\N	\N	2	\N	\N
7225	552	7789	2017-05-01	15:38:04	\N	\N	2	\N	\N
7226	552	7790	2017-05-01	15:38:04	\N	\N	2	\N	\N
7227	552	7791	2017-05-01	15:38:04	\N	\N	2	\N	\N
7228	552	7792	2017-05-01	15:38:04	\N	\N	2	\N	\N
7229	552	7793	2017-05-01	15:38:04	\N	\N	2	\N	\N
7230	552	7794	2017-05-01	15:38:04	\N	\N	2	\N	\N
7231	552	7795	2017-05-01	15:38:04	\N	\N	2	\N	\N
7232	552	7796	2017-05-01	15:38:04	\N	\N	2	\N	\N
7233	552	7797	2017-05-01	15:38:04	\N	\N	2	\N	\N
7234	552	7798	2017-05-01	15:38:04	\N	\N	2	\N	\N
7235	552	7799	2017-05-01	15:38:04	\N	\N	2	\N	\N
7236	552	7800	2017-05-01	15:38:04	\N	\N	2	\N	\N
7237	552	7801	2017-05-01	15:38:04	\N	\N	2	\N	\N
7238	552	7802	2017-05-01	15:38:04	\N	\N	2	\N	\N
7239	552	7803	2017-05-01	15:38:04	\N	\N	2	\N	\N
7240	552	7804	2017-05-01	15:38:04	\N	\N	1	\N	\N
7241	553	7806	2017-05-01	15:38:04	\N	\N	2	\N	\N
7242	553	7807	2017-05-01	15:38:04	\N	\N	2	\N	\N
7243	553	7808	2017-05-01	15:38:04	\N	\N	2	\N	\N
7244	553	7809	2017-05-01	15:38:04	\N	\N	2	\N	\N
7245	553	7810	2017-05-01	15:38:04	\N	\N	2	\N	\N
7246	555	7814	2017-05-01	15:38:04	\N	\N	3	\N	\N
7248	555	7816	2017-05-01	15:38:04	\N	\N	3	\N	\N
7250	555	7818	2017-05-01	15:38:04	\N	\N	2	\N	\N
7251	555	7819	2017-05-01	15:38:04	\N	\N	2	\N	\N
7253	555	7821	2017-05-01	15:38:04	\N	\N	2	\N	\N
7254	556	7824	2017-05-01	15:38:04	\N	\N	3	\N	\N
7255	556	7825	2017-05-01	15:38:04	\N	\N	3	\N	\N
7256	556	7826	2017-05-01	15:38:04	\N	\N	2	\N	\N
7257	556	7827	2017-05-01	15:38:04	\N	\N	2	\N	\N
7258	556	7828	2017-05-01	15:38:04	\N	\N	2	\N	\N
7259	556	7829	2017-05-01	15:38:04	\N	\N	2	\N	\N
7252	555	7820	2017-05-14	16:25:35	2017-05-14	16:25:35	2	\N	\N
7260	556	7830	2017-05-01	15:38:04	\N	\N	2	\N	\N
7261	556	7831	2017-05-01	15:38:04	\N	\N	2	\N	\N
7262	556	7832	2017-05-01	15:38:04	\N	\N	2	\N	\N
7263	556	7833	2017-05-01	15:38:04	\N	\N	2	\N	\N
7264	556	7834	2017-05-01	15:38:04	\N	\N	2	\N	\N
7265	556	7835	2017-05-01	15:38:04	\N	\N	2	\N	\N
7266	556	7836	2017-05-01	15:38:04	\N	\N	2	\N	\N
7267	556	7837	2017-05-01	15:38:04	\N	\N	2	\N	\N
7268	556	7838	2017-05-01	15:38:04	\N	\N	2	\N	\N
7269	556	7839	2017-05-01	15:38:04	\N	\N	2	\N	\N
7270	556	7840	2017-05-01	15:38:04	\N	\N	2	\N	\N
7271	556	7841	2017-05-01	15:38:04	\N	\N	2	\N	\N
7272	556	7842	2017-05-01	15:38:04	\N	\N	2	\N	\N
7273	556	7843	2017-05-01	15:38:04	\N	\N	2	\N	\N
7274	556	7844	2017-05-01	15:38:04	\N	\N	2	\N	\N
7275	556	7845	2017-05-01	15:38:04	\N	\N	2	\N	\N
7276	556	7846	2017-05-01	15:38:04	\N	\N	2	\N	\N
7277	556	7847	2017-05-01	15:38:04	\N	\N	2	\N	\N
7278	556	7848	2017-05-01	15:38:04	\N	\N	2	\N	\N
7279	556	7849	2017-05-01	15:38:04	\N	\N	2	\N	\N
7280	556	7850	2017-05-01	15:38:04	\N	\N	2	\N	\N
7281	556	7851	2017-05-01	15:38:04	\N	\N	2	\N	\N
7282	556	7852	2017-05-01	15:38:04	\N	\N	2	\N	\N
7283	556	7853	2017-05-01	15:38:04	\N	\N	2	\N	\N
7284	556	7854	2017-05-01	15:38:04	\N	\N	2	\N	\N
7285	556	7855	2017-05-01	15:38:04	\N	\N	2	\N	\N
7286	556	7856	2017-05-01	15:38:04	\N	\N	2	\N	\N
7287	556	7857	2017-05-01	15:38:04	\N	\N	2	\N	\N
7288	556	7858	2017-05-01	15:38:04	\N	\N	2	\N	\N
7289	556	7859	2017-05-01	15:38:04	\N	\N	2	\N	\N
7290	556	7860	2017-05-01	15:38:04	\N	\N	2	\N	\N
7291	556	7861	2017-05-01	15:38:04	\N	\N	2	\N	\N
7292	556	7862	2017-05-01	15:38:04	\N	\N	2	\N	\N
7293	556	7863	2017-05-01	15:38:04	\N	\N	2	\N	\N
7294	556	7864	2017-05-01	15:38:04	\N	\N	2	\N	\N
7295	556	7865	2017-05-01	15:38:04	\N	\N	2	\N	\N
7296	556	7866	2017-05-01	15:38:04	\N	\N	2	\N	\N
7297	556	7867	2017-05-01	15:38:04	\N	\N	2	\N	\N
7298	556	7868	2017-05-01	15:38:04	\N	\N	2	\N	\N
7299	556	7869	2017-05-01	15:38:04	\N	\N	2	\N	\N
7300	557	7871	2017-05-01	15:39:04	\N	\N	2	\N	\N
7301	557	7872	2017-05-01	15:39:04	\N	\N	2	\N	\N
7302	557	7873	2017-05-01	15:39:04	\N	\N	2	\N	\N
7303	557	7874	2017-05-01	15:39:04	\N	\N	2	\N	\N
7304	557	7875	2017-05-01	15:39:04	\N	\N	2	\N	\N
7305	557	7876	2017-05-01	15:39:04	\N	\N	2	\N	\N
7306	557	7877	2017-05-01	15:39:04	\N	\N	2	\N	\N
7307	557	7878	2017-05-01	15:39:04	\N	\N	2	\N	\N
7308	557	7879	2017-05-01	15:39:04	\N	\N	2	\N	\N
7309	557	7880	2017-05-01	15:39:04	\N	\N	2	\N	\N
7310	557	7881	2017-05-01	15:39:04	\N	\N	2	\N	\N
7311	557	7882	2017-05-01	15:39:04	\N	\N	2	\N	\N
7312	557	7883	2017-05-01	15:39:04	\N	\N	2	\N	\N
7313	557	7884	2017-05-01	15:39:04	\N	\N	2	\N	\N
7314	557	7885	2017-05-01	15:39:04	\N	\N	2	\N	\N
7315	557	7886	2017-05-01	15:39:04	\N	\N	2	\N	\N
7316	557	7887	2017-05-01	15:39:04	\N	\N	2	\N	\N
7317	557	7888	2017-05-01	15:39:04	\N	\N	2	\N	\N
7318	557	7889	2017-05-01	15:39:04	\N	\N	2	\N	\N
7319	557	7890	2017-05-01	15:39:04	\N	\N	2	\N	\N
7320	557	7891	2017-05-01	15:39:04	\N	\N	2	\N	\N
7321	557	7892	2017-05-01	15:39:04	\N	\N	2	\N	\N
7322	557	7893	2017-05-01	15:39:04	\N	\N	2	\N	\N
7323	557	7894	2017-05-01	15:39:04	\N	\N	2	\N	\N
7324	557	7895	2017-05-01	15:39:04	\N	\N	2	\N	\N
7325	557	7896	2017-05-01	15:39:04	\N	\N	2	\N	\N
7326	557	7897	2017-05-01	15:39:04	\N	\N	2	\N	\N
7327	557	7898	2017-05-01	15:39:04	\N	\N	2	\N	\N
7328	557	7899	2017-05-01	15:39:04	\N	\N	2	\N	\N
7329	557	7900	2017-05-01	15:39:04	\N	\N	2	\N	\N
7330	557	7901	2017-05-01	15:39:04	\N	\N	2	\N	\N
7331	557	7902	2017-05-01	15:39:04	\N	\N	2	\N	\N
7332	557	7903	2017-05-01	15:39:04	\N	\N	2	\N	\N
7333	557	7904	2017-05-01	15:39:04	\N	\N	2	\N	\N
7334	557	7905	2017-05-01	15:39:04	\N	\N	2	\N	\N
7335	557	7906	2017-05-01	15:39:04	\N	\N	2	\N	\N
7336	557	7907	2017-05-01	15:39:04	\N	\N	2	\N	\N
7337	557	7908	2017-05-01	15:39:04	\N	\N	2	\N	\N
7338	557	7909	2017-05-01	15:39:04	\N	\N	2	\N	\N
7339	557	7910	2017-05-01	15:39:04	\N	\N	2	\N	\N
7340	557	7911	2017-05-01	15:39:04	\N	\N	2	\N	\N
7341	557	7912	2017-05-01	15:39:04	\N	\N	2	\N	\N
7342	557	7913	2017-05-01	15:39:04	\N	\N	2	\N	\N
7343	557	7914	2017-05-01	15:39:04	\N	\N	2	\N	\N
7344	557	7915	2017-05-01	15:39:04	\N	\N	2	\N	\N
7345	557	7916	2017-05-01	15:39:04	\N	\N	2	\N	\N
7346	557	7917	2017-05-01	15:39:04	\N	\N	1	\N	\N
7347	558	7919	2017-05-01	15:39:04	\N	\N	2	\N	\N
7348	558	7920	2017-05-01	15:39:04	\N	\N	2	\N	\N
7349	558	7921	2017-05-01	15:39:04	\N	\N	2	\N	\N
7350	558	7922	2017-05-01	15:39:04	\N	\N	2	\N	\N
7351	558	7923	2017-05-01	15:39:04	\N	\N	2	\N	\N
7352	558	7924	2017-05-01	15:39:04	\N	\N	2	\N	\N
7353	558	7925	2017-05-01	15:39:04	\N	\N	2	\N	\N
7354	558	7926	2017-05-01	15:39:04	\N	\N	2	\N	\N
7355	558	7927	2017-05-01	15:39:04	\N	\N	2	\N	\N
7356	558	7928	2017-05-01	15:39:04	\N	\N	2	\N	\N
7357	558	7929	2017-05-01	15:39:04	\N	\N	2	\N	\N
7358	558	7930	2017-05-01	15:39:04	\N	\N	2	\N	\N
7359	558	7931	2017-05-01	15:39:04	\N	\N	2	\N	\N
7360	558	7932	2017-05-01	15:39:04	\N	\N	2	\N	\N
7361	558	7933	2017-05-01	15:39:04	\N	\N	2	\N	\N
7362	558	7934	2017-05-01	15:39:04	\N	\N	2	\N	\N
7363	558	7935	2017-05-01	15:39:04	\N	\N	2	\N	\N
7364	558	7936	2017-05-01	15:39:04	\N	\N	2	\N	\N
7365	558	7937	2017-05-01	15:39:04	\N	\N	2	\N	\N
7366	558	7938	2017-05-01	15:39:04	\N	\N	2	\N	\N
7367	558	7939	2017-05-01	15:39:04	\N	\N	2	\N	\N
7368	558	7940	2017-05-01	15:39:04	\N	\N	2	\N	\N
7369	558	7941	2017-05-01	15:39:04	\N	\N	2	\N	\N
7370	558	7942	2017-05-01	15:39:04	\N	\N	2	\N	\N
7371	558	7943	2017-05-01	15:39:04	\N	\N	2	\N	\N
7372	558	7944	2017-05-01	15:39:04	\N	\N	2	\N	\N
7373	558	7945	2017-05-01	15:39:04	\N	\N	2	\N	\N
7374	558	7946	2017-05-01	15:39:04	\N	\N	2	\N	\N
7375	558	7947	2017-05-01	15:39:04	\N	\N	2	\N	\N
7376	558	7948	2017-05-01	15:39:04	\N	\N	2	\N	\N
7377	558	7949	2017-05-01	15:39:04	\N	\N	2	\N	\N
7378	558	7950	2017-05-01	15:39:04	\N	\N	2	\N	\N
7379	558	7951	2017-05-01	15:39:04	\N	\N	2	\N	\N
7380	559	7953	2017-05-01	15:39:04	\N	\N	3	\N	\N
7381	559	7954	2017-05-01	15:39:04	\N	\N	2	\N	\N
7382	559	7955	2017-05-01	15:39:04	\N	\N	2	\N	\N
7383	559	7956	2017-05-01	15:39:04	\N	\N	2	\N	\N
7384	559	7957	2017-05-01	15:39:04	\N	\N	2	\N	\N
7385	559	7958	2017-05-01	15:39:04	\N	\N	2	\N	\N
7386	559	7959	2017-05-01	15:39:04	\N	\N	2	\N	\N
7387	559	7960	2017-05-01	15:39:04	\N	\N	2	\N	\N
7388	559	7961	2017-05-01	15:39:04	\N	\N	2	\N	\N
7389	559	7962	2017-05-01	15:39:04	\N	\N	2	\N	\N
7390	559	7963	2017-05-01	15:39:04	\N	\N	2	\N	\N
7391	559	7964	2017-05-01	15:39:04	\N	\N	2	\N	\N
7392	559	7965	2017-05-01	15:39:04	\N	\N	2	\N	\N
7393	559	7966	2017-05-01	15:39:04	\N	\N	2	\N	\N
7394	559	7967	2017-05-01	15:39:04	\N	\N	2	\N	\N
7395	559	7968	2017-05-01	15:39:04	\N	\N	2	\N	\N
7396	559	7969	2017-05-01	15:39:04	\N	\N	2	\N	\N
7397	559	7970	2017-05-01	15:39:04	\N	\N	2	\N	\N
7398	559	7971	2017-05-01	15:39:04	\N	\N	2	\N	\N
7399	559	7972	2017-05-01	15:39:04	\N	\N	2	\N	\N
7400	559	7973	2017-05-01	15:39:04	\N	\N	2	\N	\N
7401	559	7974	2017-05-01	15:39:04	\N	\N	2	\N	\N
7402	559	7975	2017-05-01	15:39:04	\N	\N	2	\N	\N
7403	559	7976	2017-05-01	15:39:04	\N	\N	2	\N	\N
7404	559	7977	2017-05-01	15:39:04	\N	\N	2	\N	\N
7405	559	7978	2017-05-01	15:39:04	\N	\N	2	\N	\N
7406	559	7979	2017-05-01	15:39:04	\N	\N	2	\N	\N
7407	559	7980	2017-05-01	15:39:04	\N	\N	2	\N	\N
7408	559	7981	2017-05-01	15:39:04	\N	\N	2	\N	\N
7409	559	7982	2017-05-01	15:39:04	\N	\N	2	\N	\N
7410	559	7983	2017-05-01	15:39:04	\N	\N	3	\N	\N
7411	559	7984	2017-05-01	15:39:04	\N	\N	2	\N	\N
7412	559	7985	2017-05-01	15:39:04	\N	\N	2	\N	\N
7413	559	7986	2017-05-01	15:39:04	\N	\N	2	\N	\N
7414	559	7987	2017-05-01	15:39:04	\N	\N	2	\N	\N
7415	559	7988	2017-05-01	15:39:04	\N	\N	2	\N	\N
7416	559	7989	2017-05-01	15:39:04	\N	\N	2	\N	\N
7417	559	7990	2017-05-01	15:39:04	\N	\N	2	\N	\N
7418	559	7991	2017-05-01	15:39:04	\N	\N	2	\N	\N
7419	559	7992	2017-05-01	15:39:04	\N	\N	2	\N	\N
7420	559	7993	2017-05-01	15:39:04	\N	\N	2	\N	\N
7421	559	7994	2017-05-01	15:39:04	\N	\N	2	\N	\N
7422	559	7995	2017-05-01	15:39:04	\N	\N	2	\N	\N
7423	559	7996	2017-05-01	15:39:04	\N	\N	2	\N	\N
7424	559	7997	2017-05-01	15:39:04	\N	\N	2	\N	\N
7425	559	7998	2017-05-01	15:39:04	\N	\N	2	\N	\N
7426	559	7999	2017-05-01	15:39:04	\N	\N	2	\N	\N
7427	559	8000	2017-05-01	15:39:04	\N	\N	3	\N	\N
7428	559	8001	2017-05-01	15:39:04	\N	\N	2	\N	\N
7429	559	8002	2017-05-01	15:39:04	\N	\N	2	\N	\N
7430	559	8003	2017-05-01	15:39:04	\N	\N	2	\N	\N
7431	559	8004	2017-05-01	15:39:04	\N	\N	3	\N	\N
7432	559	8005	2017-05-01	15:39:04	\N	\N	2	\N	\N
7433	559	8006	2017-05-01	15:39:04	\N	\N	2	\N	\N
7434	559	8007	2017-05-01	15:39:04	\N	\N	2	\N	\N
7435	559	8008	2017-05-01	15:39:04	\N	\N	2	\N	\N
7436	559	8009	2017-05-01	15:39:04	\N	\N	2	\N	\N
7437	559	8010	2017-05-01	15:39:04	\N	\N	2	\N	\N
7438	559	8011	2017-05-01	15:39:04	\N	\N	2	\N	\N
7439	559	8012	2017-05-01	15:39:04	\N	\N	2	\N	\N
7440	559	8013	2017-05-01	15:39:04	\N	\N	2	\N	\N
7441	559	8014	2017-05-01	15:39:04	\N	\N	2	\N	\N
7442	559	8015	2017-05-01	15:39:04	\N	\N	2	\N	\N
7443	561	8018	2017-05-01	15:39:04	\N	\N	3	\N	\N
7444	561	8019	2017-05-01	15:39:04	\N	\N	2	\N	\N
7445	561	8020	2017-05-01	15:39:04	\N	\N	2	\N	\N
7446	561	8021	2017-05-01	15:39:04	\N	\N	2	\N	\N
7447	561	8022	2017-05-01	15:39:04	\N	\N	2	\N	\N
7448	561	8023	2017-05-01	15:39:04	\N	\N	2	\N	\N
7449	561	8024	2017-05-01	15:39:04	\N	\N	2	\N	\N
7450	561	8025	2017-05-01	15:39:04	\N	\N	2	\N	\N
7451	561	8026	2017-05-01	15:39:04	\N	\N	2	\N	\N
7452	561	8027	2017-05-01	15:39:04	\N	\N	2	\N	\N
7453	561	8028	2017-05-01	15:39:04	\N	\N	2	\N	\N
7454	561	8029	2017-05-01	15:39:04	\N	\N	2	\N	\N
7455	561	8030	2017-05-01	15:39:04	\N	\N	2	\N	\N
7456	561	8031	2017-05-01	15:39:04	\N	\N	2	\N	\N
7457	561	8032	2017-05-01	15:39:04	\N	\N	2	\N	\N
7458	561	8033	2017-05-01	15:39:04	\N	\N	2	\N	\N
7459	561	8034	2017-05-01	15:39:04	\N	\N	2	\N	\N
7460	561	8035	2017-05-01	15:39:04	\N	\N	2	\N	\N
7461	561	8036	2017-05-01	15:39:04	\N	\N	2	\N	\N
7462	561	8037	2017-05-01	15:39:04	\N	\N	2	\N	\N
7463	561	8038	2017-05-01	15:39:04	\N	\N	2	\N	\N
7464	561	8039	2017-05-01	15:39:04	\N	\N	2	\N	\N
7465	561	8040	2017-05-01	15:39:04	\N	\N	2	\N	\N
7466	561	8041	2017-05-01	15:39:04	\N	\N	2	\N	\N
7467	561	8042	2017-05-01	15:39:04	\N	\N	1	\N	\N
7468	562	8044	2017-05-01	15:39:04	\N	\N	2	\N	\N
7469	562	8045	2017-05-01	15:39:04	\N	\N	2	\N	\N
7470	562	8046	2017-05-01	15:39:04	\N	\N	2	\N	\N
7471	562	8047	2017-05-01	15:39:04	\N	\N	2	\N	\N
7472	562	8048	2017-05-01	15:39:04	\N	\N	2	\N	\N
7473	562	8049	2017-05-01	15:39:04	\N	\N	2	\N	\N
7474	562	8050	2017-05-01	15:39:04	\N	\N	2	\N	\N
7475	562	8051	2017-05-01	15:39:04	\N	\N	2	\N	\N
7476	562	8052	2017-05-01	15:39:04	\N	\N	2	\N	\N
7477	562	8053	2017-05-01	15:39:04	\N	\N	2	\N	\N
7478	562	8054	2017-05-01	15:39:04	\N	\N	2	\N	\N
7479	562	8055	2017-05-01	15:39:04	\N	\N	2	\N	\N
7480	562	8056	2017-05-01	15:39:04	\N	\N	2	\N	\N
7481	562	8057	2017-05-01	15:39:04	\N	\N	2	\N	\N
7482	562	8058	2017-05-01	15:39:04	\N	\N	2	\N	\N
7483	562	8059	2017-05-01	15:39:04	\N	\N	2	\N	\N
7484	562	8060	2017-05-01	15:39:04	\N	\N	2	\N	\N
7485	563	8062	2017-05-01	15:39:04	\N	\N	2	\N	\N
7486	563	8063	2017-05-01	15:39:04	\N	\N	2	\N	\N
7487	563	8064	2017-05-01	15:39:04	\N	\N	2	\N	\N
7488	563	8065	2017-05-01	15:39:04	\N	\N	2	\N	\N
7489	563	8066	2017-05-01	15:39:04	\N	\N	2	\N	\N
7490	563	8067	2017-05-01	15:39:04	\N	\N	2	\N	\N
7491	563	8068	2017-05-01	15:39:04	\N	\N	2	\N	\N
7492	563	8069	2017-05-01	15:39:04	\N	\N	2	\N	\N
7493	563	8070	2017-05-01	15:39:04	\N	\N	2	\N	\N
7494	563	8071	2017-05-01	15:39:04	\N	\N	2	\N	\N
7495	563	8072	2017-05-01	15:39:04	\N	\N	2	\N	\N
7496	563	8073	2017-05-01	15:39:04	\N	\N	2	\N	\N
7497	563	8074	2017-05-01	15:39:04	\N	\N	2	\N	\N
7498	563	8075	2017-05-01	15:39:04	\N	\N	2	\N	\N
7499	563	8076	2017-05-01	15:39:04	\N	\N	2	\N	\N
7500	563	8077	2017-05-01	15:39:04	\N	\N	2	\N	\N
7501	563	8078	2017-05-01	15:39:04	\N	\N	2	\N	\N
7502	563	8079	2017-05-01	15:39:04	\N	\N	2	\N	\N
7503	563	8080	2017-05-01	15:39:04	\N	\N	2	\N	\N
7504	564	8082	2017-05-01	15:40:04	\N	\N	3	\N	\N
7505	564	8083	2017-05-01	15:40:04	\N	\N	2	\N	\N
7506	564	8084	2017-05-01	15:40:04	\N	\N	2	\N	\N
7507	564	8085	2017-05-01	15:40:04	\N	\N	2	\N	\N
7508	564	8086	2017-05-01	15:40:04	\N	\N	2	\N	\N
7509	565	8088	2017-05-01	15:40:04	\N	\N	2	\N	\N
7510	565	8089	2017-05-01	15:40:04	\N	\N	2	\N	\N
7511	565	8090	2017-05-01	15:40:04	\N	\N	2	\N	\N
7512	565	8091	2017-05-01	15:40:04	\N	\N	2	\N	\N
7513	565	8092	2017-05-01	15:40:04	\N	\N	2	\N	\N
7514	565	8093	2017-05-01	15:40:04	\N	\N	2	\N	\N
7515	565	8094	2017-05-01	15:40:04	\N	\N	2	\N	\N
7516	565	8095	2017-05-01	15:40:04	\N	\N	2	\N	\N
7517	565	8096	2017-05-01	15:40:04	\N	\N	2	\N	\N
7518	565	8097	2017-05-01	15:40:04	\N	\N	2	\N	\N
7519	565	8098	2017-05-01	15:40:04	\N	\N	2	\N	\N
7520	566	8100	2017-05-01	15:40:04	\N	\N	3	\N	\N
7521	566	8101	2017-05-01	15:40:04	\N	\N	3	\N	\N
7522	566	8102	2017-05-01	15:40:04	\N	\N	2	\N	\N
7523	566	8103	2017-05-01	15:40:04	\N	\N	2	\N	\N
7524	566	8104	2017-05-01	15:40:04	\N	\N	2	\N	\N
7525	566	8105	2017-05-01	15:40:04	\N	\N	2	\N	\N
7526	566	8106	2017-05-01	15:40:04	\N	\N	2	\N	\N
7527	566	8107	2017-05-01	15:40:04	\N	\N	2	\N	\N
7528	566	8108	2017-05-01	15:40:04	\N	\N	2	\N	\N
7529	566	8109	2017-05-01	15:40:04	\N	\N	2	\N	\N
7530	566	8110	2017-05-01	15:40:04	\N	\N	2	\N	\N
7531	566	8111	2017-05-01	15:40:04	\N	\N	2	\N	\N
7532	566	8112	2017-05-01	15:40:04	\N	\N	2	\N	\N
7533	566	8113	2017-05-01	15:40:04	\N	\N	2	\N	\N
7534	566	8114	2017-05-01	15:40:04	\N	\N	2	\N	\N
7535	566	8115	2017-05-01	15:40:04	\N	\N	2	\N	\N
7536	566	8116	2017-05-01	15:40:04	\N	\N	2	\N	\N
7537	566	8117	2017-05-01	15:40:04	\N	\N	2	\N	\N
7538	566	8118	2017-05-01	15:40:04	\N	\N	2	\N	\N
7539	566	8119	2017-05-01	15:40:04	\N	\N	2	\N	\N
7540	566	8120	2017-05-01	15:40:04	\N	\N	2	\N	\N
7541	566	8121	2017-05-01	15:40:04	\N	\N	2	\N	\N
7542	566	8122	2017-05-01	15:40:04	\N	\N	2	\N	\N
7543	566	8123	2017-05-01	15:40:04	\N	\N	2	\N	\N
7544	566	8124	2017-05-01	15:40:04	\N	\N	2	\N	\N
7545	566	8125	2017-05-01	15:40:04	\N	\N	2	\N	\N
7546	566	8126	2017-05-01	15:40:04	\N	\N	2	\N	\N
7547	566	8127	2017-05-01	15:40:04	\N	\N	2	\N	\N
7548	566	8128	2017-05-01	15:40:04	\N	\N	2	\N	\N
7549	566	8129	2017-05-01	15:40:04	\N	\N	2	\N	\N
7550	566	8130	2017-05-01	15:40:04	\N	\N	2	\N	\N
7551	566	8131	2017-05-01	15:40:04	\N	\N	2	\N	\N
7552	566	8132	2017-05-01	15:40:04	\N	\N	2	\N	\N
7553	566	8133	2017-05-01	15:40:04	\N	\N	2	\N	\N
7554	566	8134	2017-05-01	15:40:04	\N	\N	2	\N	\N
7555	566	8135	2017-05-01	15:40:04	\N	\N	2	\N	\N
7556	566	8136	2017-05-01	15:40:04	\N	\N	2	\N	\N
7557	566	8137	2017-05-01	15:40:04	\N	\N	2	\N	\N
7558	566	8138	2017-05-01	15:40:04	\N	\N	2	\N	\N
7559	566	8139	2017-05-01	15:40:04	\N	\N	2	\N	\N
7560	566	8140	2017-05-01	15:40:04	\N	\N	2	\N	\N
7561	566	8141	2017-05-01	15:40:04	\N	\N	2	\N	\N
7562	566	8142	2017-05-01	15:40:04	\N	\N	2	\N	\N
7563	566	8143	2017-05-01	15:40:04	\N	\N	2	\N	\N
7564	566	8144	2017-05-01	15:40:04	\N	\N	2	\N	\N
7565	566	8145	2017-05-01	15:40:04	\N	\N	2	\N	\N
7566	566	8146	2017-05-01	15:40:04	\N	\N	3	\N	\N
7567	566	8147	2017-05-01	15:40:04	\N	\N	2	\N	\N
7568	566	8148	2017-05-01	15:40:04	\N	\N	2	\N	\N
7569	566	8149	2017-05-01	15:40:04	\N	\N	2	\N	\N
7570	566	8150	2017-05-01	15:40:04	\N	\N	2	\N	\N
7571	566	8151	2017-05-01	15:40:04	\N	\N	2	\N	\N
7572	566	8152	2017-05-01	15:40:04	\N	\N	2	\N	\N
7573	566	8153	2017-05-01	15:40:04	\N	\N	2	\N	\N
7574	566	8154	2017-05-01	15:40:04	\N	\N	2	\N	\N
7575	566	8155	2017-05-01	15:40:04	\N	\N	2	\N	\N
7576	566	8156	2017-05-01	15:40:04	\N	\N	2	\N	\N
7577	566	8157	2017-05-01	15:40:04	\N	\N	3	\N	\N
7578	568	8160	2017-05-01	15:40:04	\N	\N	3	\N	\N
7579	568	8161	2017-05-01	15:40:04	\N	\N	2	\N	\N
7580	568	8162	2017-05-01	15:40:04	\N	\N	2	\N	\N
7581	568	8163	2017-05-01	15:40:04	\N	\N	2	\N	\N
7582	568	8164	2017-05-01	15:40:04	\N	\N	2	\N	\N
7583	568	8165	2017-05-01	15:40:04	\N	\N	2	\N	\N
7584	568	8166	2017-05-01	15:40:04	\N	\N	2	\N	\N
7585	568	8167	2017-05-01	15:40:04	\N	\N	2	\N	\N
7586	568	8168	2017-05-01	15:40:04	\N	\N	2	\N	\N
7587	568	8169	2017-05-01	15:40:04	\N	\N	2	\N	\N
7588	568	8170	2017-05-01	15:40:04	\N	\N	2	\N	\N
7589	568	8171	2017-05-01	15:40:04	\N	\N	2	\N	\N
7590	568	8172	2017-05-01	15:40:04	\N	\N	2	\N	\N
7591	568	8173	2017-05-01	15:40:04	\N	\N	2	\N	\N
7592	568	8174	2017-05-01	15:40:04	\N	\N	2	\N	\N
7593	568	8175	2017-05-01	15:40:04	\N	\N	2	\N	\N
7594	568	8176	2017-05-01	15:40:04	\N	\N	2	\N	\N
7595	568	8177	2017-05-01	15:40:04	\N	\N	2	\N	\N
7596	568	8178	2017-05-01	15:40:04	\N	\N	2	\N	\N
7597	568	8179	2017-05-01	15:40:04	\N	\N	2	\N	\N
7598	568	8180	2017-05-01	15:40:04	\N	\N	2	\N	\N
7599	568	8181	2017-05-01	15:40:04	\N	\N	2	\N	\N
7600	568	8182	2017-05-01	15:40:04	\N	\N	2	\N	\N
7601	568	8183	2017-05-01	15:40:04	\N	\N	2	\N	\N
7602	568	8184	2017-05-01	15:40:04	\N	\N	2	\N	\N
7603	568	8185	2017-05-01	15:40:04	\N	\N	2	\N	\N
7604	568	8186	2017-05-01	15:40:04	\N	\N	2	\N	\N
7605	571	8190	2017-05-01	15:40:04	\N	\N	2	\N	\N
7606	571	8191	2017-05-01	15:40:04	\N	\N	2	\N	\N
7607	571	8192	2017-05-01	15:40:04	\N	\N	2	\N	\N
7608	571	8193	2017-05-01	15:40:04	\N	\N	2	\N	\N
7609	571	8194	2017-05-01	15:40:04	\N	\N	2	\N	\N
7610	571	8195	2017-05-01	15:40:04	\N	\N	2	\N	\N
7611	571	8196	2017-05-01	15:40:04	\N	\N	2	\N	\N
7612	571	8197	2017-05-01	15:40:04	\N	\N	2	\N	\N
7613	571	8198	2017-05-01	15:40:04	\N	\N	2	\N	\N
7614	571	8199	2017-05-01	15:40:04	\N	\N	2	\N	\N
7615	571	8200	2017-05-01	15:40:04	\N	\N	2	\N	\N
7616	571	8201	2017-05-01	15:40:04	\N	\N	2	\N	\N
7617	571	8202	2017-05-01	15:40:04	\N	\N	2	\N	\N
7618	571	8203	2017-05-01	15:40:04	\N	\N	2	\N	\N
7619	571	8204	2017-05-01	15:40:04	\N	\N	2	\N	\N
7620	571	8205	2017-05-01	15:40:04	\N	\N	2	\N	\N
7621	571	8206	2017-05-01	15:40:04	\N	\N	2	\N	\N
7622	571	8207	2017-05-01	15:40:04	\N	\N	2	\N	\N
7623	571	8208	2017-05-01	15:40:04	\N	\N	2	\N	\N
7624	571	8209	2017-05-01	15:40:04	\N	\N	2	\N	\N
7625	571	8210	2017-05-01	15:40:04	\N	\N	2	\N	\N
7626	571	8211	2017-05-01	15:40:04	\N	\N	2	\N	\N
7627	571	8212	2017-05-01	15:40:04	\N	\N	2	\N	\N
7628	572	8215	2017-05-01	15:40:04	\N	\N	3	\N	\N
7629	572	8216	2017-05-01	15:40:04	\N	\N	3	\N	\N
7630	572	8217	2017-05-01	15:40:04	\N	\N	2	\N	\N
7631	572	8218	2017-05-01	15:40:04	\N	\N	2	\N	\N
7632	572	8219	2017-05-01	15:40:04	\N	\N	2	\N	\N
7633	572	8220	2017-05-01	15:40:04	\N	\N	2	\N	\N
7634	572	8221	2017-05-01	15:40:04	\N	\N	2	\N	\N
7635	572	8222	2017-05-01	15:40:04	\N	\N	2	\N	\N
7636	572	8223	2017-05-01	15:40:04	\N	\N	2	\N	\N
7637	572	8224	2017-05-01	15:40:04	\N	\N	2	\N	\N
7638	572	8225	2017-05-01	15:40:04	\N	\N	2	\N	\N
7639	572	8226	2017-05-01	15:40:04	\N	\N	2	\N	\N
7640	572	8227	2017-05-01	15:40:04	\N	\N	2	\N	\N
7641	572	8228	2017-05-01	15:40:04	\N	\N	2	\N	\N
7642	572	8229	2017-05-01	15:40:04	\N	\N	2	\N	\N
7643	572	8230	2017-05-01	15:40:04	\N	\N	2	\N	\N
7644	572	8231	2017-05-01	15:40:04	\N	\N	2	\N	\N
7645	572	8232	2017-05-01	15:40:04	\N	\N	2	\N	\N
7646	572	8233	2017-05-01	15:40:04	\N	\N	2	\N	\N
7647	572	8234	2017-05-01	15:40:04	\N	\N	2	\N	\N
7648	572	8235	2017-05-01	15:40:04	\N	\N	2	\N	\N
7649	572	8236	2017-05-01	15:40:04	\N	\N	2	\N	\N
7650	572	8237	2017-05-01	15:40:04	\N	\N	2	\N	\N
7651	572	8238	2017-05-01	15:41:04	\N	\N	2	\N	\N
7652	572	8239	2017-05-01	15:41:04	\N	\N	2	\N	\N
7653	572	8240	2017-05-01	15:41:04	\N	\N	2	\N	\N
7654	572	8241	2017-05-01	15:41:04	\N	\N	2	\N	\N
7655	572	8242	2017-05-01	15:41:04	\N	\N	2	\N	\N
7656	572	8243	2017-05-01	15:41:04	\N	\N	2	\N	\N
7657	572	8244	2017-05-01	15:41:04	\N	\N	3	\N	\N
7658	572	8245	2017-05-01	15:41:04	\N	\N	2	\N	\N
7659	572	8246	2017-05-01	15:41:04	\N	\N	3	\N	\N
7660	572	8247	2017-05-01	15:41:04	\N	\N	2	\N	\N
7661	572	8248	2017-05-01	15:41:04	\N	\N	2	\N	\N
7662	572	8249	2017-05-01	15:41:04	\N	\N	2	\N	\N
7663	572	8250	2017-05-01	15:41:04	\N	\N	2	\N	\N
7664	572	8251	2017-05-01	15:41:04	\N	\N	2	\N	\N
7665	572	8252	2017-05-01	15:41:04	\N	\N	2	\N	\N
7666	572	8253	2017-05-01	15:41:04	\N	\N	2	\N	\N
7667	572	8254	2017-05-01	15:41:04	\N	\N	2	\N	\N
7668	572	8255	2017-05-01	15:41:04	\N	\N	2	\N	\N
7669	572	8256	2017-05-01	15:41:04	\N	\N	2	\N	\N
7670	572	8257	2017-05-01	15:41:04	\N	\N	2	\N	\N
7671	572	8258	2017-05-01	15:41:04	\N	\N	2	\N	\N
7672	572	8259	2017-05-01	15:41:04	\N	\N	2	\N	\N
7673	572	8260	2017-05-01	15:41:04	\N	\N	2	\N	\N
7674	572	8261	2017-05-01	15:41:04	\N	\N	2	\N	\N
7675	572	8262	2017-05-01	15:41:04	\N	\N	2	\N	\N
7676	572	8263	2017-05-01	15:41:04	\N	\N	2	\N	\N
7677	572	8264	2017-05-01	15:41:04	\N	\N	2	\N	\N
7678	572	8265	2017-05-01	15:41:04	\N	\N	3	\N	\N
7679	572	8266	2017-05-01	15:41:04	\N	\N	2	\N	\N
7680	572	8267	2017-05-01	15:41:04	\N	\N	2	\N	\N
7681	572	8268	2017-05-01	15:41:04	\N	\N	2	\N	\N
7682	572	8269	2017-05-01	15:41:04	\N	\N	3	\N	\N
7683	572	8270	2017-05-01	15:41:04	\N	\N	2	\N	\N
7684	572	8271	2017-05-01	15:41:04	\N	\N	2	\N	\N
7685	572	8272	2017-05-01	15:41:04	\N	\N	2	\N	\N
7686	572	8273	2017-05-01	15:41:04	\N	\N	2	\N	\N
7687	572	8274	2017-05-01	15:41:04	\N	\N	3	\N	\N
7688	572	8275	2017-05-01	15:41:04	\N	\N	2	\N	\N
7689	572	8276	2017-05-01	15:41:04	\N	\N	2	\N	\N
7690	572	8277	2017-05-01	15:41:04	\N	\N	2	\N	\N
7691	572	8278	2017-05-01	15:41:04	\N	\N	2	\N	\N
7692	572	8279	2017-05-01	15:41:04	\N	\N	2	\N	\N
7693	575	8283	2017-05-01	15:41:04	\N	\N	2	\N	\N
7694	575	8284	2017-05-01	15:41:04	\N	\N	2	\N	\N
7695	575	8285	2017-05-01	15:41:04	\N	\N	2	\N	\N
7696	575	8286	2017-05-01	15:41:04	\N	\N	2	\N	\N
7697	575	8287	2017-05-01	15:41:04	\N	\N	2	\N	\N
7698	575	8288	2017-05-01	15:41:04	\N	\N	2	\N	\N
7699	575	8289	2017-05-01	15:41:04	\N	\N	2	\N	\N
7700	575	8290	2017-05-01	15:41:04	\N	\N	2	\N	\N
7701	575	8291	2017-05-01	15:41:04	\N	\N	2	\N	\N
7702	575	8292	2017-05-01	15:41:04	\N	\N	2	\N	\N
7703	575	8293	2017-05-01	15:41:04	\N	\N	2	\N	\N
7704	576	8295	2017-05-01	15:41:04	\N	\N	2	\N	\N
7705	576	8296	2017-05-01	15:41:04	\N	\N	2	\N	\N
7706	576	8297	2017-05-01	15:41:04	\N	\N	2	\N	\N
7707	576	8298	2017-05-01	15:41:04	\N	\N	2	\N	\N
7708	576	8299	2017-05-01	15:41:04	\N	\N	2	\N	\N
7709	576	8300	2017-05-01	15:41:04	\N	\N	2	\N	\N
7710	576	8301	2017-05-01	15:41:04	\N	\N	2	\N	\N
7711	576	8302	2017-05-01	15:41:04	\N	\N	2	\N	\N
7712	576	8303	2017-05-01	15:41:04	\N	\N	2	\N	\N
7713	576	8304	2017-05-01	15:41:04	\N	\N	2	\N	\N
7714	576	8305	2017-05-01	15:41:04	\N	\N	2	\N	\N
7715	577	8307	2017-05-01	15:41:04	\N	\N	2	\N	\N
7716	577	8308	2017-05-01	15:41:04	\N	\N	2	\N	\N
7717	577	8309	2017-05-01	15:41:04	\N	\N	2	\N	\N
7718	577	8310	2017-05-01	15:41:04	\N	\N	2	\N	\N
7719	577	8311	2017-05-01	15:41:04	\N	\N	2	\N	\N
7720	577	8312	2017-05-01	15:41:04	\N	\N	2	\N	\N
7721	577	8313	2017-05-01	15:41:04	\N	\N	2	\N	\N
7722	577	8314	2017-05-01	15:41:04	\N	\N	2	\N	\N
7723	577	8315	2017-05-01	15:41:04	\N	\N	2	\N	\N
7724	577	8316	2017-05-01	15:41:04	\N	\N	2	\N	\N
7725	577	8317	2017-05-01	15:41:04	\N	\N	2	\N	\N
7726	577	8318	2017-05-01	15:41:04	\N	\N	2	\N	\N
7727	577	8319	2017-05-01	15:41:04	\N	\N	2	\N	\N
7728	578	8322	2017-05-01	15:41:04	\N	\N	3	\N	\N
7729	578	8323	2017-05-01	15:41:04	\N	\N	3	\N	\N
7730	578	8324	2017-05-01	15:41:04	\N	\N	3	\N	\N
7731	578	8325	2017-05-01	15:41:04	\N	\N	3	\N	\N
7732	578	8326	2017-05-01	15:41:04	\N	\N	3	\N	\N
7733	578	8327	2017-05-01	15:41:04	\N	\N	3	\N	\N
7734	578	8328	2017-05-01	15:41:04	\N	\N	3	\N	\N
7735	578	8329	2017-05-01	15:41:04	\N	\N	3	\N	\N
7736	578	8330	2017-05-01	15:41:04	\N	\N	3	\N	\N
7737	578	8331	2017-05-01	15:41:04	\N	\N	3	\N	\N
7738	578	8332	2017-05-01	15:41:04	\N	\N	3	\N	\N
7739	578	8333	2017-05-01	15:41:04	\N	\N	3	\N	\N
7740	578	8334	2017-05-01	15:41:04	\N	\N	3	\N	\N
7741	578	8335	2017-05-01	15:41:04	\N	\N	3	\N	\N
7742	578	8336	2017-05-01	15:41:04	\N	\N	3	\N	\N
7743	578	8337	2017-05-01	15:41:04	\N	\N	2	\N	\N
7744	578	8338	2017-05-01	15:41:04	\N	\N	2	\N	\N
7745	578	8339	2017-05-01	15:41:04	\N	\N	2	\N	\N
7746	578	8340	2017-05-01	15:41:04	\N	\N	2	\N	\N
7747	578	8341	2017-05-01	15:41:04	\N	\N	2	\N	\N
7748	578	8342	2017-05-01	15:41:04	\N	\N	2	\N	\N
7749	578	8343	2017-05-01	15:41:04	\N	\N	2	\N	\N
7750	578	8344	2017-05-01	15:41:04	\N	\N	2	\N	\N
7751	578	8345	2017-05-01	15:41:04	\N	\N	2	\N	\N
7752	578	8346	2017-05-01	15:41:04	\N	\N	2	\N	\N
7753	578	8347	2017-05-01	15:41:04	\N	\N	2	\N	\N
7754	578	8348	2017-05-01	15:41:04	\N	\N	2	\N	\N
7755	578	8349	2017-05-01	15:41:04	\N	\N	2	\N	\N
7756	578	8350	2017-05-01	15:41:04	\N	\N	2	\N	\N
7757	578	8351	2017-05-01	15:41:04	\N	\N	2	\N	\N
7758	578	8352	2017-05-01	15:41:04	\N	\N	2	\N	\N
7759	578	8353	2017-05-01	15:41:04	\N	\N	2	\N	\N
7760	578	8354	2017-05-01	15:41:04	\N	\N	2	\N	\N
7761	578	8355	2017-05-01	15:41:04	\N	\N	2	\N	\N
7762	578	8356	2017-05-01	15:41:04	\N	\N	2	\N	\N
7763	578	8357	2017-05-01	15:41:04	\N	\N	2	\N	\N
7764	578	8358	2017-05-01	15:41:04	\N	\N	2	\N	\N
7765	578	8359	2017-05-01	15:41:04	\N	\N	2	\N	\N
7766	578	8360	2017-05-01	15:41:04	\N	\N	2	\N	\N
7767	578	8361	2017-05-01	15:41:04	\N	\N	2	\N	\N
7768	578	8362	2017-05-01	15:41:04	\N	\N	2	\N	\N
7769	578	8363	2017-05-01	15:41:04	\N	\N	2	\N	\N
7770	578	8364	2017-05-01	15:41:04	\N	\N	2	\N	\N
7771	578	8365	2017-05-01	15:41:04	\N	\N	2	\N	\N
7772	578	8366	2017-05-01	15:41:04	\N	\N	2	\N	\N
7773	578	8367	2017-05-01	15:41:04	\N	\N	2	\N	\N
7774	578	8368	2017-05-01	15:41:04	\N	\N	2	\N	\N
7775	578	8369	2017-05-01	15:41:04	\N	\N	2	\N	\N
7776	578	8370	2017-05-01	15:41:04	\N	\N	1	\N	\N
7777	578	8371	2017-05-01	15:41:04	\N	\N	1	\N	\N
7778	578	8372	2017-05-01	15:41:04	\N	\N	1	\N	\N
7779	578	8373	2017-05-01	15:41:04	\N	\N	2	\N	\N
7780	578	8374	2017-05-01	15:41:04	\N	\N	1	\N	\N
7781	578	8375	2017-05-01	15:41:04	\N	\N	2	\N	\N
7782	578	8376	2017-05-01	15:41:04	\N	\N	3	\N	\N
7783	578	8377	2017-05-01	15:41:04	\N	\N	2	\N	\N
7784	578	8378	2017-05-01	15:41:04	\N	\N	1	\N	\N
7785	578	8379	2017-05-01	15:41:04	\N	\N	1	\N	\N
7786	579	8381	2017-05-01	15:41:04	\N	\N	2	\N	\N
7787	579	8382	2017-05-01	15:41:04	\N	\N	2	\N	\N
7788	579	8383	2017-05-01	15:41:04	\N	\N	2	\N	\N
7789	579	8384	2017-05-01	15:41:04	\N	\N	2	\N	\N
7790	579	8385	2017-05-01	15:41:04	\N	\N	2	\N	\N
7791	579	8386	2017-05-01	15:41:04	\N	\N	2	\N	\N
7792	579	8387	2017-05-01	15:41:04	\N	\N	2	\N	\N
7793	579	8388	2017-05-01	15:41:04	\N	\N	2	\N	\N
7794	579	8389	2017-05-01	15:41:04	\N	\N	2	\N	\N
7795	579	8390	2017-05-01	15:41:04	\N	\N	2	\N	\N
7796	579	8391	2017-05-01	15:41:04	\N	\N	2	\N	\N
7797	579	8392	2017-05-01	15:41:04	\N	\N	2	\N	\N
7798	579	8393	2017-05-01	15:41:04	\N	\N	2	\N	\N
7799	579	8394	2017-05-01	15:41:04	\N	\N	2	\N	\N
7800	579	8395	2017-05-01	15:41:04	\N	\N	2	\N	\N
7801	579	8396	2017-05-01	15:41:04	\N	\N	2	\N	\N
7802	580	8398	2017-05-01	15:41:04	\N	\N	2	\N	\N
7803	580	8399	2017-05-01	15:41:04	\N	\N	2	\N	\N
7804	580	8400	2017-05-01	15:41:04	\N	\N	2	\N	\N
7805	580	8401	2017-05-01	15:41:04	\N	\N	2	\N	\N
7806	580	8402	2017-05-01	15:41:04	\N	\N	2	\N	\N
7807	580	8403	2017-05-01	15:41:04	\N	\N	2	\N	\N
7808	580	8404	2017-05-01	15:41:04	\N	\N	2	\N	\N
7809	580	8405	2017-05-01	15:41:04	\N	\N	2	\N	\N
7810	580	8406	2017-05-01	15:41:04	\N	\N	2	\N	\N
7811	580	8407	2017-05-01	15:41:04	\N	\N	2	\N	\N
7812	580	8408	2017-05-01	15:41:04	\N	\N	2	\N	\N
7813	580	8409	2017-05-01	15:41:04	\N	\N	2	\N	\N
7814	580	8410	2017-05-01	15:41:04	\N	\N	2	\N	\N
7815	580	8411	2017-05-01	15:42:04	\N	\N	2	\N	\N
7816	580	8412	2017-05-01	15:42:04	\N	\N	2	\N	\N
7817	580	8413	2017-05-01	15:42:04	\N	\N	2	\N	\N
7818	580	8414	2017-05-01	15:42:04	\N	\N	2	\N	\N
7819	580	8415	2017-05-01	15:42:04	\N	\N	2	\N	\N
7820	580	8416	2017-05-01	15:42:04	\N	\N	2	\N	\N
7821	580	8417	2017-05-01	15:42:04	\N	\N	2	\N	\N
7822	580	8418	2017-05-01	15:42:04	\N	\N	2	\N	\N
7823	580	8419	2017-05-01	15:42:04	\N	\N	2	\N	\N
7824	580	8420	2017-05-01	15:42:04	\N	\N	2	\N	\N
7825	580	8421	2017-05-01	15:42:04	\N	\N	2	\N	\N
7826	580	8422	2017-05-01	15:42:04	\N	\N	2	\N	\N
7827	580	8423	2017-05-01	15:42:04	\N	\N	2	\N	\N
7828	580	8424	2017-05-01	15:42:04	\N	\N	2	\N	\N
7829	580	8425	2017-05-01	15:42:04	\N	\N	2	\N	\N
7830	580	8426	2017-05-01	15:42:04	\N	\N	2	\N	\N
7831	580	8427	2017-05-01	15:42:04	\N	\N	2	\N	\N
7832	580	8428	2017-05-01	15:42:04	\N	\N	2	\N	\N
7833	580	8429	2017-05-01	15:42:04	\N	\N	2	\N	\N
7834	580	8430	2017-05-01	15:42:04	\N	\N	2	\N	\N
7835	580	8431	2017-05-01	15:42:04	\N	\N	2	\N	\N
7836	580	8432	2017-05-01	15:42:04	\N	\N	2	\N	\N
7837	580	8433	2017-05-01	15:42:04	\N	\N	2	\N	\N
7838	580	8434	2017-05-01	15:42:04	\N	\N	2	\N	\N
7839	580	8435	2017-05-01	15:42:04	\N	\N	2	\N	\N
7840	580	8436	2017-05-01	15:42:04	\N	\N	2	\N	\N
7841	580	8437	2017-05-01	15:42:04	\N	\N	2	\N	\N
7842	580	8438	2017-05-01	15:42:04	\N	\N	2	\N	\N
7843	580	8439	2017-05-01	15:42:04	\N	\N	2	\N	\N
7844	580	8440	2017-05-01	15:42:04	\N	\N	2	\N	\N
7845	580	8441	2017-05-01	15:42:04	\N	\N	2	\N	\N
7846	580	8442	2017-05-01	15:42:04	\N	\N	2	\N	\N
7847	580	8443	2017-05-01	15:42:04	\N	\N	2	\N	\N
7848	580	8444	2017-05-01	15:42:04	\N	\N	2	\N	\N
7849	580	8445	2017-05-01	15:42:04	\N	\N	2	\N	\N
7850	580	8446	2017-05-01	15:42:04	\N	\N	2	\N	\N
7851	580	8447	2017-05-01	15:42:04	\N	\N	2	\N	\N
7852	580	8448	2017-05-01	15:42:04	\N	\N	2	\N	\N
7853	580	8449	2017-05-01	15:42:04	\N	\N	2	\N	\N
7854	580	8450	2017-05-01	15:42:04	\N	\N	1	\N	\N
7855	580	8451	2017-05-01	15:42:04	\N	\N	2	\N	\N
7856	586	8458	2017-05-01	15:42:04	\N	\N	3	\N	\N
7857	586	8459	2017-05-01	15:42:04	\N	\N	3	\N	\N
7858	586	8460	2017-05-01	15:42:04	\N	\N	2	\N	\N
7859	586	8461	2017-05-01	15:42:04	\N	\N	2	\N	\N
7860	586	8462	2017-05-01	15:42:04	\N	\N	2	\N	\N
7861	586	8463	2017-05-01	15:42:04	\N	\N	2	\N	\N
7862	586	8464	2017-05-01	15:42:04	\N	\N	2	\N	\N
7863	586	8465	2017-05-01	15:42:04	\N	\N	2	\N	\N
7864	586	8466	2017-05-01	15:42:04	\N	\N	2	\N	\N
7865	586	8467	2017-05-01	15:42:04	\N	\N	2	\N	\N
7866	586	8468	2017-05-01	15:42:04	\N	\N	2	\N	\N
7867	586	8469	2017-05-01	15:42:04	\N	\N	2	\N	\N
7868	586	8470	2017-05-01	15:42:04	\N	\N	2	\N	\N
7869	586	8471	2017-05-01	15:42:04	\N	\N	2	\N	\N
7870	586	8472	2017-05-01	15:42:04	\N	\N	2	\N	\N
7871	586	8473	2017-05-01	15:42:04	\N	\N	2	\N	\N
7872	586	8474	2017-05-01	15:42:04	\N	\N	2	\N	\N
7873	586	8475	2017-05-01	15:42:04	\N	\N	2	\N	\N
7874	586	8476	2017-05-01	15:42:04	\N	\N	2	\N	\N
7875	586	8477	2017-05-01	15:42:04	\N	\N	2	\N	\N
7876	586	8478	2017-05-01	15:42:04	\N	\N	2	\N	\N
7877	586	8479	2017-05-01	15:42:04	\N	\N	2	\N	\N
7878	586	8480	2017-05-01	15:42:04	\N	\N	2	\N	\N
7879	586	8481	2017-05-01	15:42:04	\N	\N	2	\N	\N
7880	586	8482	2017-05-01	15:42:04	\N	\N	2	\N	\N
7881	586	8483	2017-05-01	15:42:04	\N	\N	2	\N	\N
7882	586	8484	2017-05-01	15:42:04	\N	\N	2	\N	\N
7883	586	8485	2017-05-01	15:42:04	\N	\N	2	\N	\N
7884	586	8486	2017-05-01	15:42:04	\N	\N	2	\N	\N
7885	586	8487	2017-05-01	15:42:04	\N	\N	2	\N	\N
7886	586	8488	2017-05-01	15:42:04	\N	\N	2	\N	\N
7887	586	8489	2017-05-01	15:42:04	\N	\N	2	\N	\N
7888	586	8490	2017-05-01	15:42:04	\N	\N	2	\N	\N
7889	586	8491	2017-05-01	15:42:04	\N	\N	2	\N	\N
7890	586	8492	2017-05-01	15:42:04	\N	\N	2	\N	\N
7891	586	8493	2017-05-01	15:42:04	\N	\N	2	\N	\N
7892	586	8494	2017-05-01	15:42:04	\N	\N	2	\N	\N
7893	586	8495	2017-05-01	15:42:04	\N	\N	2	\N	\N
7894	586	8496	2017-05-01	15:42:04	\N	\N	2	\N	\N
7895	586	8497	2017-05-01	15:42:04	\N	\N	2	\N	\N
7896	586	8498	2017-05-01	15:42:04	\N	\N	2	\N	\N
7897	586	8499	2017-05-01	15:42:04	\N	\N	2	\N	\N
7898	586	8500	2017-05-01	15:42:04	\N	\N	2	\N	\N
7899	586	8501	2017-05-01	15:42:04	\N	\N	2	\N	\N
7900	586	8502	2017-05-01	15:42:04	\N	\N	2	\N	\N
7901	586	8503	2017-05-01	15:42:04	\N	\N	2	\N	\N
7902	587	8505	2017-05-01	15:42:04	\N	\N	3	\N	\N
7903	587	8506	2017-05-01	15:42:04	\N	\N	2	\N	\N
7904	587	8507	2017-05-01	15:42:04	\N	\N	2	\N	\N
7905	587	8508	2017-05-01	15:42:04	\N	\N	2	\N	\N
7906	587	8509	2017-05-01	15:42:04	\N	\N	2	\N	\N
7907	587	8510	2017-05-01	15:42:04	\N	\N	2	\N	\N
7908	587	8511	2017-05-01	15:42:04	\N	\N	2	\N	\N
7909	587	8512	2017-05-01	15:42:04	\N	\N	2	\N	\N
7910	587	8513	2017-05-01	15:42:04	\N	\N	2	\N	\N
7911	587	8514	2017-05-01	15:42:04	\N	\N	2	\N	\N
7912	587	8515	2017-05-01	15:42:04	\N	\N	2	\N	\N
7913	587	8516	2017-05-01	15:42:04	\N	\N	2	\N	\N
7914	587	8517	2017-05-01	15:42:04	\N	\N	2	\N	\N
7915	587	8518	2017-05-01	15:42:04	\N	\N	2	\N	\N
7916	587	8519	2017-05-01	15:42:04	\N	\N	2	\N	\N
7917	587	8520	2017-05-01	15:42:04	\N	\N	2	\N	\N
7918	587	8521	2017-05-01	15:42:04	\N	\N	2	\N	\N
7919	587	8522	2017-05-01	15:42:04	\N	\N	2	\N	\N
7920	587	8523	2017-05-01	15:42:04	\N	\N	2	\N	\N
7921	587	8524	2017-05-01	15:42:04	\N	\N	2	\N	\N
7922	587	8525	2017-05-01	15:42:04	\N	\N	2	\N	\N
7923	587	8526	2017-05-01	15:42:04	\N	\N	2	\N	\N
7924	587	8527	2017-05-01	15:42:04	\N	\N	2	\N	\N
7925	587	8528	2017-05-01	15:42:04	\N	\N	2	\N	\N
7926	587	8529	2017-05-01	15:42:04	\N	\N	2	\N	\N
7927	587	8530	2017-05-01	15:42:04	\N	\N	2	\N	\N
7928	587	8531	2017-05-01	15:42:04	\N	\N	2	\N	\N
7929	587	8532	2017-05-01	15:42:04	\N	\N	2	\N	\N
7930	587	8533	2017-05-01	15:42:04	\N	\N	2	\N	\N
7931	587	8534	2017-05-01	15:42:04	\N	\N	2	\N	\N
7932	587	8535	2017-05-01	15:42:04	\N	\N	2	\N	\N
7933	587	8536	2017-05-01	15:42:04	\N	\N	2	\N	\N
7934	587	8537	2017-05-01	15:42:04	\N	\N	1	\N	\N
7935	587	8538	2017-05-01	15:42:04	\N	\N	1	\N	\N
7936	587	8539	2017-05-01	15:42:04	\N	\N	1	\N	\N
7937	587	8540	2017-05-01	15:42:04	\N	\N	1	\N	\N
7938	587	8541	2017-05-01	15:42:04	\N	\N	1	\N	\N
7939	589	8544	2017-05-01	15:42:04	\N	\N	3	\N	\N
7940	589	8545	2017-05-01	15:42:04	\N	\N	3	\N	\N
7941	589	8546	2017-05-01	15:42:04	\N	\N	2	\N	\N
7942	589	8547	2017-05-01	15:42:04	\N	\N	2	\N	\N
7943	589	8548	2017-05-01	15:42:04	\N	\N	2	\N	\N
7944	589	8549	2017-05-01	15:42:04	\N	\N	2	\N	\N
7945	589	8550	2017-05-01	15:42:04	\N	\N	2	\N	\N
7946	589	8551	2017-05-01	15:43:04	\N	\N	2	\N	\N
7947	589	8552	2017-05-01	15:43:04	\N	\N	2	\N	\N
7948	589	8553	2017-05-01	15:43:04	\N	\N	2	\N	\N
7949	589	8554	2017-05-01	15:43:04	\N	\N	2	\N	\N
7950	589	8555	2017-05-01	15:43:04	\N	\N	2	\N	\N
7951	589	8556	2017-05-01	15:43:04	\N	\N	2	\N	\N
7952	589	8557	2017-05-01	15:43:04	\N	\N	2	\N	\N
7953	589	8558	2017-05-01	15:43:04	\N	\N	2	\N	\N
7954	589	8559	2017-05-01	15:43:04	\N	\N	2	\N	\N
7955	589	8560	2017-05-01	15:43:04	\N	\N	2	\N	\N
7956	589	8561	2017-05-01	15:43:04	\N	\N	2	\N	\N
7957	589	8562	2017-05-01	15:43:04	\N	\N	2	\N	\N
7958	589	8563	2017-05-01	15:43:04	\N	\N	2	\N	\N
7959	589	8564	2017-05-01	15:43:04	\N	\N	2	\N	\N
7960	589	8565	2017-05-01	15:43:04	\N	\N	2	\N	\N
7961	589	8566	2017-05-01	15:43:04	\N	\N	2	\N	\N
7962	589	8567	2017-05-01	15:43:04	\N	\N	2	\N	\N
7963	589	8568	2017-05-01	15:43:04	\N	\N	2	\N	\N
7964	589	8569	2017-05-01	15:43:04	\N	\N	2	\N	\N
7965	591	8572	2017-05-01	15:43:04	\N	\N	3	\N	\N
7966	591	8573	2017-05-01	15:43:04	\N	\N	3	\N	\N
7967	591	8574	2017-05-01	15:43:04	\N	\N	2	\N	\N
7968	591	8575	2017-05-01	15:43:04	\N	\N	2	\N	\N
7969	591	8576	2017-05-01	15:43:04	\N	\N	2	\N	\N
7970	591	8577	2017-05-01	15:43:04	\N	\N	2	\N	\N
7971	591	8578	2017-05-01	15:43:04	\N	\N	2	\N	\N
7972	591	8579	2017-05-01	15:43:04	\N	\N	2	\N	\N
7973	591	8580	2017-05-01	15:43:04	\N	\N	2	\N	\N
7974	591	8581	2017-05-01	15:43:04	\N	\N	2	\N	\N
7975	591	8582	2017-05-01	15:43:04	\N	\N	2	\N	\N
7976	591	8583	2017-05-01	15:43:04	\N	\N	2	\N	\N
7977	592	8586	2017-05-01	15:43:04	\N	\N	2	\N	\N
7978	592	8587	2017-05-01	15:43:04	\N	\N	2	\N	\N
7979	592	8588	2017-05-01	15:43:04	\N	\N	2	\N	\N
7980	592	8589	2017-05-01	15:43:04	\N	\N	2	\N	\N
7981	592	8590	2017-05-01	15:43:04	\N	\N	2	\N	\N
7982	592	8591	2017-05-01	15:43:04	\N	\N	2	\N	\N
7983	592	8592	2017-05-01	15:43:04	\N	\N	2	\N	\N
7984	593	8595	2017-05-01	15:43:04	\N	\N	3	\N	\N
7985	593	8596	2017-05-01	15:43:04	\N	\N	3	\N	\N
7986	593	8597	2017-05-01	15:43:04	\N	\N	3	\N	\N
7987	593	8598	2017-05-01	15:43:04	\N	\N	3	\N	\N
7988	593	8599	2017-05-01	15:43:04	\N	\N	2	\N	\N
7989	593	8600	2017-05-01	15:43:04	\N	\N	2	\N	\N
7990	593	8601	2017-05-01	15:43:04	\N	\N	2	\N	\N
7991	593	8602	2017-05-01	15:43:04	\N	\N	2	\N	\N
7992	593	8603	2017-05-01	15:43:04	\N	\N	2	\N	\N
7993	593	8604	2017-05-01	15:43:04	\N	\N	2	\N	\N
7994	593	8605	2017-05-01	15:43:04	\N	\N	2	\N	\N
7995	593	8606	2017-05-01	15:43:04	\N	\N	2	\N	\N
7996	593	8607	2017-05-01	15:43:04	\N	\N	2	\N	\N
7997	593	8608	2017-05-01	15:43:04	\N	\N	2	\N	\N
7998	593	8609	2017-05-01	15:43:04	\N	\N	2	\N	\N
7999	593	8610	2017-05-01	15:43:04	\N	\N	2	\N	\N
8000	593	8611	2017-05-01	15:43:04	\N	\N	2	\N	\N
8001	593	8612	2017-05-01	15:43:04	\N	\N	2	\N	\N
8002	593	8613	2017-05-01	15:43:04	\N	\N	2	\N	\N
8003	593	8614	2017-05-01	15:43:04	\N	\N	2	\N	\N
8004	593	8615	2017-05-01	15:43:04	\N	\N	2	\N	\N
8005	593	8616	2017-05-01	15:43:04	\N	\N	2	\N	\N
8006	593	8617	2017-05-01	15:43:04	\N	\N	2	\N	\N
8007	593	8618	2017-05-01	15:43:04	\N	\N	2	\N	\N
8008	593	8619	2017-05-01	15:43:04	\N	\N	2	\N	\N
8009	593	8620	2017-05-01	15:43:04	\N	\N	2	\N	\N
8010	593	8621	2017-05-01	15:43:04	\N	\N	2	\N	\N
8011	593	8622	2017-05-01	15:43:04	\N	\N	2	\N	\N
8012	593	8623	2017-05-01	15:43:04	\N	\N	2	\N	\N
8013	593	8624	2017-05-01	15:43:04	\N	\N	2	\N	\N
8014	593	8625	2017-05-01	15:43:04	\N	\N	2	\N	\N
8015	593	8626	2017-05-01	15:43:04	\N	\N	2	\N	\N
8016	593	8627	2017-05-01	15:43:04	\N	\N	2	\N	\N
8017	593	8628	2017-05-01	15:43:04	\N	\N	2	\N	\N
8018	593	8629	2017-05-01	15:43:04	\N	\N	2	\N	\N
8019	593	8630	2017-05-01	15:43:04	\N	\N	2	\N	\N
8020	593	8631	2017-05-01	15:43:04	\N	\N	2	\N	\N
8021	593	8632	2017-05-01	15:43:04	\N	\N	2	\N	\N
8022	593	8633	2017-05-01	15:43:04	\N	\N	2	\N	\N
8023	593	8634	2017-05-01	15:43:04	\N	\N	2	\N	\N
8024	593	8635	2017-05-01	15:43:04	\N	\N	2	\N	\N
8025	593	8636	2017-05-01	15:43:04	\N	\N	2	\N	\N
8026	593	8637	2017-05-01	15:43:04	\N	\N	2	\N	\N
8027	593	8638	2017-05-01	15:43:04	\N	\N	2	\N	\N
8028	593	8639	2017-05-01	15:43:04	\N	\N	2	\N	\N
8029	593	8640	2017-05-01	15:43:04	\N	\N	2	\N	\N
8030	593	8641	2017-05-01	15:43:04	\N	\N	2	\N	\N
8031	593	8642	2017-05-01	15:43:04	\N	\N	2	\N	\N
8032	593	8643	2017-05-01	15:43:04	\N	\N	2	\N	\N
8033	593	8644	2017-05-01	15:43:04	\N	\N	2	\N	\N
8034	593	8645	2017-05-01	15:43:04	\N	\N	2	\N	\N
8035	593	8646	2017-05-01	15:43:04	\N	\N	2	\N	\N
8036	593	8647	2017-05-01	15:43:04	\N	\N	2	\N	\N
8037	593	8648	2017-05-01	15:43:04	\N	\N	2	\N	\N
8038	593	8649	2017-05-01	15:43:04	\N	\N	2	\N	\N
8039	593	8650	2017-05-01	15:43:04	\N	\N	2	\N	\N
8040	593	8651	2017-05-01	15:43:04	\N	\N	2	\N	\N
8041	593	8652	2017-05-01	15:43:04	\N	\N	2	\N	\N
8042	593	8653	2017-05-01	15:43:04	\N	\N	2	\N	\N
8043	593	8654	2017-05-01	15:43:04	\N	\N	1	\N	\N
8044	594	8656	2017-05-01	15:43:04	\N	\N	3	\N	\N
8045	594	8657	2017-05-01	15:43:04	\N	\N	2	\N	\N
8046	594	8658	2017-05-01	15:43:04	\N	\N	2	\N	\N
8047	594	8659	2017-05-01	15:43:04	\N	\N	2	\N	\N
8048	594	8660	2017-05-01	15:43:04	\N	\N	2	\N	\N
8049	594	8661	2017-05-01	15:43:04	\N	\N	2	\N	\N
8050	594	8662	2017-05-01	15:43:04	\N	\N	2	\N	\N
8051	594	8663	2017-05-01	15:43:04	\N	\N	2	\N	\N
8052	594	8664	2017-05-01	15:43:04	\N	\N	2	\N	\N
8053	594	8665	2017-05-01	15:43:04	\N	\N	2	\N	\N
8054	594	8666	2017-05-01	15:43:04	\N	\N	2	\N	\N
8055	594	8667	2017-05-01	15:43:04	\N	\N	2	\N	\N
8056	595	8669	2017-05-01	15:43:04	\N	\N	3	\N	\N
8057	595	8670	2017-05-01	15:43:04	\N	\N	2	\N	\N
8058	595	8671	2017-05-01	15:43:04	\N	\N	2	\N	\N
8059	595	8672	2017-05-01	15:43:04	\N	\N	2	\N	\N
8060	595	8673	2017-05-01	15:43:04	\N	\N	2	\N	\N
8061	595	8674	2017-05-01	15:43:04	\N	\N	2	\N	\N
8062	595	8675	2017-05-01	15:43:04	\N	\N	2	\N	\N
8063	595	8676	2017-05-01	15:43:04	\N	\N	2	\N	\N
8064	595	8677	2017-05-01	15:43:04	\N	\N	2	\N	\N
8065	595	8678	2017-05-01	15:43:04	\N	\N	2	\N	\N
8066	595	8679	2017-05-01	15:43:04	\N	\N	2	\N	\N
8067	595	8680	2017-05-01	15:43:04	\N	\N	2	\N	\N
8068	595	8681	2017-05-01	15:43:04	\N	\N	2	\N	\N
8069	595	8682	2017-05-01	15:43:04	\N	\N	2	\N	\N
8070	595	8683	2017-05-01	15:43:04	\N	\N	2	\N	\N
8071	595	8684	2017-05-01	15:43:04	\N	\N	2	\N	\N
8072	595	8685	2017-05-01	15:43:04	\N	\N	2	\N	\N
8073	595	8686	2017-05-01	15:43:04	\N	\N	2	\N	\N
8074	595	8687	2017-05-01	15:43:04	\N	\N	2	\N	\N
8075	595	8688	2017-05-01	15:43:04	\N	\N	2	\N	\N
8076	595	8689	2017-05-01	15:43:04	\N	\N	2	\N	\N
8077	595	8690	2017-05-01	15:43:04	\N	\N	2	\N	\N
8078	595	8691	2017-05-01	15:43:04	\N	\N	2	\N	\N
8079	595	8692	2017-05-01	15:43:04	\N	\N	2	\N	\N
8080	595	8693	2017-05-01	15:43:04	\N	\N	2	\N	\N
8081	595	8694	2017-05-01	15:43:04	\N	\N	2	\N	\N
8082	595	8695	2017-05-01	15:43:04	\N	\N	1	\N	\N
8083	595	8696	2017-05-01	15:43:04	\N	\N	2	\N	\N
8084	596	8699	2017-05-01	15:43:04	\N	\N	2	\N	\N
8085	596	8700	2017-05-01	15:43:04	\N	\N	2	\N	\N
8086	596	8701	2017-05-01	15:43:04	\N	\N	2	\N	\N
8087	597	8703	2017-05-01	15:43:04	\N	\N	3	\N	\N
8088	597	8704	2017-05-01	15:43:04	\N	\N	3	\N	\N
8089	597	8705	2017-05-01	15:43:04	\N	\N	2	\N	\N
8090	597	8706	2017-05-01	15:43:04	\N	\N	2	\N	\N
8091	597	8707	2017-05-01	15:43:04	\N	\N	2	\N	\N
8092	597	8708	2017-05-01	15:43:04	\N	\N	2	\N	\N
8093	597	8709	2017-05-01	15:43:04	\N	\N	2	\N	\N
8094	597	8710	2017-05-01	15:43:04	\N	\N	2	\N	\N
8095	597	8711	2017-05-01	15:43:04	\N	\N	2	\N	\N
8096	597	8712	2017-05-01	15:43:04	\N	\N	2	\N	\N
8097	597	8713	2017-05-01	15:43:04	\N	\N	2	\N	\N
8098	597	8714	2017-05-01	15:43:04	\N	\N	2	\N	\N
8099	597	8715	2017-05-01	15:43:04	\N	\N	2	\N	\N
8100	597	8716	2017-05-01	15:43:04	\N	\N	2	\N	\N
8101	597	8717	2017-05-01	15:43:04	\N	\N	2	\N	\N
8102	597	8718	2017-05-01	15:43:04	\N	\N	2	\N	\N
8103	597	8719	2017-05-01	15:43:04	\N	\N	2	\N	\N
8104	597	8720	2017-05-01	15:43:04	\N	\N	2	\N	\N
8105	597	8721	2017-05-01	15:44:04	\N	\N	2	\N	\N
8106	597	8722	2017-05-01	15:44:04	\N	\N	2	\N	\N
8107	597	8723	2017-05-01	15:44:04	\N	\N	2	\N	\N
8108	597	8724	2017-05-01	15:44:04	\N	\N	2	\N	\N
8109	597	8725	2017-05-01	15:44:04	\N	\N	2	\N	\N
8110	597	8726	2017-05-01	15:44:04	\N	\N	2	\N	\N
8111	597	8727	2017-05-01	15:44:04	\N	\N	2	\N	\N
8112	597	8728	2017-05-01	15:44:04	\N	\N	2	\N	\N
8113	597	8729	2017-05-01	15:44:04	\N	\N	2	\N	\N
8114	597	8730	2017-05-01	15:44:04	\N	\N	2	\N	\N
8115	597	8731	2017-05-01	15:44:04	\N	\N	2	\N	\N
8116	597	8732	2017-05-01	15:44:04	\N	\N	2	\N	\N
8117	597	8733	2017-05-01	15:44:04	\N	\N	2	\N	\N
8118	597	8734	2017-05-01	15:44:04	\N	\N	2	\N	\N
8119	597	8735	2017-05-01	15:44:04	\N	\N	2	\N	\N
8120	597	8736	2017-05-01	15:44:04	\N	\N	2	\N	\N
8121	597	8737	2017-05-01	15:44:04	\N	\N	2	\N	\N
8122	597	8738	2017-05-01	15:44:04	\N	\N	2	\N	\N
8123	597	8739	2017-05-01	15:44:04	\N	\N	2	\N	\N
8124	597	8740	2017-05-01	15:44:04	\N	\N	2	\N	\N
8125	597	8741	2017-05-01	15:44:04	\N	\N	2	\N	\N
8126	597	8742	2017-05-01	15:44:04	\N	\N	2	\N	\N
8127	597	8743	2017-05-01	15:44:04	\N	\N	2	\N	\N
8128	597	8744	2017-05-01	15:44:04	\N	\N	2	\N	\N
8129	597	8745	2017-05-01	15:44:04	\N	\N	2	\N	\N
8130	597	8746	2017-05-01	15:44:04	\N	\N	2	\N	\N
8131	597	8747	2017-05-01	15:44:04	\N	\N	2	\N	\N
8132	597	8748	2017-05-01	15:44:04	\N	\N	2	\N	\N
8133	597	8749	2017-05-01	15:44:04	\N	\N	2	\N	\N
8134	597	8750	2017-05-01	15:44:04	\N	\N	2	\N	\N
8135	597	8751	2017-05-01	15:44:04	\N	\N	2	\N	\N
8136	597	8752	2017-05-01	15:44:04	\N	\N	2	\N	\N
8137	597	8753	2017-05-01	15:44:04	\N	\N	2	\N	\N
8138	597	8754	2017-05-01	15:44:04	\N	\N	2	\N	\N
8139	598	8756	2017-05-01	15:44:04	\N	\N	2	\N	\N
8140	599	8758	2017-05-01	15:44:04	\N	\N	2	\N	\N
8141	599	8759	2017-05-01	15:44:04	\N	\N	2	\N	\N
8142	600	8761	2017-05-01	15:44:04	\N	\N	3	\N	\N
8143	600	8762	2017-05-01	15:44:04	\N	\N	3	\N	\N
8144	600	8763	2017-05-01	15:44:04	\N	\N	2	\N	\N
8145	600	8764	2017-05-01	15:44:04	\N	\N	2	\N	\N
8146	600	8765	2017-05-01	15:44:04	\N	\N	2	\N	\N
8147	600	8766	2017-05-01	15:44:04	\N	\N	2	\N	\N
8148	600	8767	2017-05-01	15:44:04	\N	\N	2	\N	\N
8149	600	8768	2017-05-01	15:44:04	\N	\N	2	\N	\N
8150	600	8769	2017-05-01	15:44:04	\N	\N	2	\N	\N
8151	600	8770	2017-05-01	15:44:04	\N	\N	2	\N	\N
8152	600	8771	2017-05-01	15:44:04	\N	\N	2	\N	\N
8153	600	8772	2017-05-01	15:44:04	\N	\N	2	\N	\N
8154	600	8773	2017-05-01	15:44:04	\N	\N	2	\N	\N
8155	600	8774	2017-05-01	15:44:04	\N	\N	2	\N	\N
8156	600	8775	2017-05-01	15:44:04	\N	\N	2	\N	\N
8157	600	8776	2017-05-01	15:44:04	\N	\N	2	\N	\N
8158	600	8777	2017-05-01	15:44:04	\N	\N	2	\N	\N
8159	600	8778	2017-05-01	15:44:04	\N	\N	2	\N	\N
8160	600	8779	2017-05-01	15:44:04	\N	\N	2	\N	\N
8161	600	8780	2017-05-01	15:44:04	\N	\N	2	\N	\N
8162	600	8781	2017-05-01	15:44:04	\N	\N	2	\N	\N
8163	600	8782	2017-05-01	15:44:04	\N	\N	2	\N	\N
8164	600	8783	2017-05-01	15:44:04	\N	\N	2	\N	\N
8165	600	8784	2017-05-01	15:44:04	\N	\N	2	\N	\N
8166	600	8785	2017-05-01	15:44:04	\N	\N	2	\N	\N
8167	600	8786	2017-05-01	15:44:04	\N	\N	2	\N	\N
8168	600	8787	2017-05-01	15:44:04	\N	\N	2	\N	\N
8169	600	8788	2017-05-01	15:44:04	\N	\N	2	\N	\N
8170	600	8789	2017-05-01	15:44:04	\N	\N	2	\N	\N
8171	600	8790	2017-05-01	15:44:04	\N	\N	2	\N	\N
8172	600	8791	2017-05-01	15:44:04	\N	\N	2	\N	\N
8173	600	8792	2017-05-01	15:44:04	\N	\N	2	\N	\N
8174	600	8793	2017-05-01	15:44:04	\N	\N	2	\N	\N
8175	600	8794	2017-05-01	15:44:04	\N	\N	2	\N	\N
8176	600	8795	2017-05-01	15:44:04	\N	\N	2	\N	\N
8177	600	8796	2017-05-01	15:44:04	\N	\N	2	\N	\N
8178	600	8797	2017-05-01	15:44:04	\N	\N	2	\N	\N
8179	600	8798	2017-05-01	15:44:04	\N	\N	2	\N	\N
8180	600	8799	2017-05-01	15:44:04	\N	\N	2	\N	\N
8181	600	8800	2017-05-01	15:44:04	\N	\N	2	\N	\N
8182	600	8801	2017-05-01	15:44:04	\N	\N	2	\N	\N
8183	600	8802	2017-05-01	15:44:04	\N	\N	2	\N	\N
8184	600	8803	2017-05-01	15:44:04	\N	\N	2	\N	\N
8185	600	8804	2017-05-01	15:44:04	\N	\N	2	\N	\N
8186	600	8805	2017-05-01	15:44:04	\N	\N	2	\N	\N
8187	600	8806	2017-05-01	15:44:04	\N	\N	2	\N	\N
8188	600	8807	2017-05-01	15:44:04	\N	\N	2	\N	\N
8189	600	8808	2017-05-01	15:44:04	\N	\N	2	\N	\N
8190	600	8809	2017-05-01	15:44:04	\N	\N	2	\N	\N
8191	600	8810	2017-05-01	15:44:04	\N	\N	2	\N	\N
8192	600	8811	2017-05-01	15:44:04	\N	\N	2	\N	\N
8193	600	8812	2017-05-01	15:44:04	\N	\N	2	\N	\N
8194	600	8813	2017-05-01	15:44:04	\N	\N	2	\N	\N
8195	600	8814	2017-05-01	15:44:04	\N	\N	2	\N	\N
8196	600	8815	2017-05-01	15:44:04	\N	\N	2	\N	\N
8197	600	8816	2017-05-01	15:44:04	\N	\N	1	\N	\N
8198	600	8817	2017-05-01	15:44:04	\N	\N	1	\N	\N
8199	600	8818	2017-05-01	15:44:04	\N	\N	1	\N	\N
8200	600	8819	2017-05-01	15:44:04	\N	\N	1	\N	\N
8201	600	8820	2017-05-01	15:44:04	\N	\N	1	\N	\N
8202	600	8821	2017-05-01	15:44:04	\N	\N	1	\N	\N
8203	601	8823	2017-05-01	15:44:04	\N	\N	3	\N	\N
8204	601	8824	2017-05-01	15:44:04	\N	\N	2	\N	\N
8205	601	8825	2017-05-01	15:44:04	\N	\N	2	\N	\N
8206	601	8826	2017-05-01	15:44:04	\N	\N	2	\N	\N
8207	601	8827	2017-05-01	15:44:04	\N	\N	2	\N	\N
8208	601	8828	2017-05-01	15:44:04	\N	\N	2	\N	\N
8209	601	8829	2017-05-01	15:44:04	\N	\N	2	\N	\N
8210	601	8830	2017-05-01	15:44:04	\N	\N	2	\N	\N
8211	601	8831	2017-05-01	15:44:04	\N	\N	2	\N	\N
8212	601	8832	2017-05-01	15:44:04	\N	\N	2	\N	\N
8213	601	8833	2017-05-01	15:44:04	\N	\N	2	\N	\N
8214	601	8834	2017-05-01	15:44:04	\N	\N	2	\N	\N
8215	601	8835	2017-05-01	15:44:04	\N	\N	2	\N	\N
8216	601	8836	2017-05-01	15:44:04	\N	\N	2	\N	\N
8217	601	8837	2017-05-01	15:44:04	\N	\N	2	\N	\N
8218	601	8838	2017-05-01	15:44:04	\N	\N	2	\N	\N
8219	601	8839	2017-05-01	15:44:04	\N	\N	2	\N	\N
8220	601	8840	2017-05-01	15:44:04	\N	\N	2	\N	\N
8221	601	8841	2017-05-01	15:44:04	\N	\N	2	\N	\N
8222	601	8842	2017-05-01	15:44:04	\N	\N	2	\N	\N
8223	601	8843	2017-05-01	15:44:04	\N	\N	2	\N	\N
8224	601	8844	2017-05-01	15:44:04	\N	\N	2	\N	\N
8225	601	8845	2017-05-01	15:44:04	\N	\N	2	\N	\N
8226	601	8846	2017-05-01	15:44:04	\N	\N	2	\N	\N
8227	601	8847	2017-05-01	15:44:04	\N	\N	2	\N	\N
8228	601	8848	2017-05-01	15:44:04	\N	\N	2	\N	\N
8229	601	8849	2017-05-01	15:44:04	\N	\N	1	\N	\N
8230	601	8850	2017-05-01	15:44:04	\N	\N	1	\N	\N
8231	601	8851	2017-05-01	15:44:04	\N	\N	1	\N	\N
8232	601	8852	2017-05-01	15:44:04	\N	\N	1	\N	\N
8233	601	8853	2017-05-01	15:44:04	\N	\N	1	\N	\N
8234	601	8854	2017-05-01	15:44:04	\N	\N	1	\N	\N
8235	601	8855	2017-05-01	15:44:04	\N	\N	1	\N	\N
8236	611	8867	2017-05-01	15:45:04	\N	\N	3	\N	\N
8237	611	8868	2017-05-01	15:45:04	\N	\N	2	\N	\N
8238	611	8869	2017-05-01	15:45:04	\N	\N	2	\N	\N
8239	611	8870	2017-05-01	15:45:04	\N	\N	2	\N	\N
8240	611	8871	2017-05-01	15:45:04	\N	\N	2	\N	\N
8241	611	8872	2017-05-01	15:45:04	\N	\N	2	\N	\N
8242	611	8873	2017-05-01	15:45:04	\N	\N	2	\N	\N
8243	611	8874	2017-05-01	15:45:04	\N	\N	2	\N	\N
8244	611	8875	2017-05-01	15:45:04	\N	\N	2	\N	\N
8245	611	8876	2017-05-01	15:45:04	\N	\N	2	\N	\N
8246	611	8877	2017-05-01	15:45:04	\N	\N	2	\N	\N
8247	611	8878	2017-05-01	15:45:04	\N	\N	2	\N	\N
8248	611	8879	2017-05-01	15:45:04	\N	\N	2	\N	\N
8249	611	8880	2017-05-01	15:45:04	\N	\N	2	\N	\N
8250	611	8881	2017-05-01	15:45:04	\N	\N	2	\N	\N
8251	611	8882	2017-05-01	15:45:04	\N	\N	2	\N	\N
8252	611	8883	2017-05-01	15:45:04	\N	\N	2	\N	\N
8253	611	8884	2017-05-01	15:45:04	\N	\N	2	\N	\N
8254	611	8885	2017-05-01	15:45:04	\N	\N	2	\N	\N
8255	611	8886	2017-05-01	15:45:04	\N	\N	2	\N	\N
8256	611	8887	2017-05-01	15:45:04	\N	\N	2	\N	\N
8257	611	8888	2017-05-01	15:45:04	\N	\N	2	\N	\N
8258	611	8889	2017-05-01	15:45:04	\N	\N	2	\N	\N
8259	611	8890	2017-05-01	15:45:04	\N	\N	2	\N	\N
8260	611	8891	2017-05-01	15:45:04	\N	\N	2	\N	\N
8261	611	8892	2017-05-01	15:45:04	\N	\N	2	\N	\N
8262	611	8893	2017-05-01	15:45:04	\N	\N	2	\N	\N
8263	611	8894	2017-05-01	15:45:04	\N	\N	2	\N	\N
8264	611	8895	2017-05-01	15:45:04	\N	\N	2	\N	\N
8265	611	8896	2017-05-01	15:45:04	\N	\N	2	\N	\N
8266	611	8897	2017-05-01	15:45:04	\N	\N	2	\N	\N
8267	611	8898	2017-05-01	15:45:04	\N	\N	2	\N	\N
8268	611	8899	2017-05-01	15:45:04	\N	\N	2	\N	\N
8269	611	8900	2017-05-01	15:45:04	\N	\N	2	\N	\N
8270	611	8901	2017-05-01	15:45:04	\N	\N	2	\N	\N
8271	611	8902	2017-05-01	15:45:04	\N	\N	2	\N	\N
8272	611	8903	2017-05-01	15:45:04	\N	\N	2	\N	\N
8273	611	8904	2017-05-01	15:45:04	\N	\N	2	\N	\N
8274	611	8905	2017-05-01	15:45:04	\N	\N	2	\N	\N
8275	611	8906	2017-05-01	15:45:04	\N	\N	2	\N	\N
8276	611	8907	2017-05-01	15:45:04	\N	\N	2	\N	\N
8277	611	8908	2017-05-01	15:45:04	\N	\N	2	\N	\N
8278	611	8909	2017-05-01	15:45:04	\N	\N	2	\N	\N
8279	611	8910	2017-05-01	15:45:04	\N	\N	2	\N	\N
8280	611	8911	2017-05-01	15:45:04	\N	\N	2	\N	\N
8281	611	8912	2017-05-01	15:45:04	\N	\N	2	\N	\N
8282	611	8913	2017-05-01	15:45:04	\N	\N	2	\N	\N
8283	611	8914	2017-05-01	15:45:04	\N	\N	2	\N	\N
8284	611	8915	2017-05-01	15:45:04	\N	\N	2	\N	\N
8285	611	8916	2017-05-01	15:45:04	\N	\N	2	\N	\N
8286	611	8917	2017-05-01	15:45:04	\N	\N	2	\N	\N
8287	611	8918	2017-05-01	15:45:04	\N	\N	2	\N	\N
8288	611	8919	2017-05-01	15:45:04	\N	\N	2	\N	\N
8289	611	8920	2017-05-01	15:45:04	\N	\N	2	\N	\N
8290	611	8921	2017-05-01	15:45:04	\N	\N	2	\N	\N
8291	611	8922	2017-05-01	15:45:04	\N	\N	2	\N	\N
8292	611	8923	2017-05-01	15:45:04	\N	\N	2	\N	\N
8293	611	8924	2017-05-01	15:45:04	\N	\N	2	\N	\N
8294	611	8925	2017-05-01	15:45:04	\N	\N	1	\N	\N
8295	611	8926	2017-05-01	15:45:04	\N	\N	1	\N	\N
8296	615	8932	2017-05-01	15:45:04	\N	\N	3	\N	\N
8297	615	8933	2017-05-01	15:45:04	\N	\N	3	\N	\N
8298	615	8934	2017-05-01	15:45:04	\N	\N	3	\N	\N
8299	615	8935	2017-05-01	15:45:04	\N	\N	3	\N	\N
8300	615	8936	2017-05-01	15:45:04	\N	\N	3	\N	\N
8301	615	8937	2017-05-01	15:45:04	\N	\N	3	\N	\N
8302	615	8938	2017-05-01	15:45:04	\N	\N	3	\N	\N
8303	615	8939	2017-05-01	15:45:04	\N	\N	3	\N	\N
8304	615	8940	2017-05-01	15:45:04	\N	\N	3	\N	\N
8305	615	8941	2017-05-01	15:45:04	\N	\N	3	\N	\N
8306	615	8942	2017-05-01	15:45:04	\N	\N	3	\N	\N
8307	615	8943	2017-05-01	15:45:04	\N	\N	3	\N	\N
8308	615	8944	2017-05-01	15:45:04	\N	\N	3	\N	\N
8309	615	8945	2017-05-01	15:45:04	\N	\N	3	\N	\N
8310	615	8946	2017-05-01	15:45:04	\N	\N	3	\N	\N
8311	615	8947	2017-05-01	15:45:04	\N	\N	3	\N	\N
8312	615	8948	2017-05-01	15:45:04	\N	\N	3	\N	\N
8313	615	8949	2017-05-01	15:45:04	\N	\N	3	\N	\N
8314	615	8950	2017-05-01	15:45:04	\N	\N	3	\N	\N
8315	615	8951	2017-05-01	15:45:04	\N	\N	3	\N	\N
8316	615	8952	2017-05-01	15:45:04	\N	\N	3	\N	\N
8317	615	8953	2017-05-01	15:45:04	\N	\N	3	\N	\N
8318	615	8954	2017-05-01	15:45:04	\N	\N	3	\N	\N
8319	615	8955	2017-05-01	15:45:04	\N	\N	3	\N	\N
8320	615	8956	2017-05-01	15:45:04	\N	\N	3	\N	\N
8321	615	8957	2017-05-01	15:45:04	\N	\N	3	\N	\N
8322	615	8958	2017-05-01	15:45:04	\N	\N	2	\N	\N
8323	615	8959	2017-05-01	15:45:04	\N	\N	2	\N	\N
8324	615	8960	2017-05-01	15:45:04	\N	\N	2	\N	\N
8325	615	8961	2017-05-01	15:45:04	\N	\N	2	\N	\N
8326	615	8962	2017-05-01	15:45:04	\N	\N	2	\N	\N
8327	615	8963	2017-05-01	15:45:04	\N	\N	2	\N	\N
8328	615	8964	2017-05-01	15:45:04	\N	\N	2	\N	\N
8329	615	8965	2017-05-01	15:45:04	\N	\N	2	\N	\N
8330	615	8966	2017-05-01	15:45:04	\N	\N	2	\N	\N
8331	615	8967	2017-05-01	15:45:04	\N	\N	2	\N	\N
8332	615	8968	2017-05-01	15:45:04	\N	\N	2	\N	\N
8333	615	8969	2017-05-01	15:45:04	\N	\N	2	\N	\N
8334	615	8970	2017-05-01	15:45:04	\N	\N	2	\N	\N
8335	615	8971	2017-05-01	15:45:04	\N	\N	2	\N	\N
8336	615	8972	2017-05-01	15:45:04	\N	\N	2	\N	\N
8337	615	8973	2017-05-01	15:45:04	\N	\N	2	\N	\N
8338	615	8974	2017-05-01	15:45:04	\N	\N	2	\N	\N
8339	615	8975	2017-05-01	15:45:04	\N	\N	2	\N	\N
8340	615	8976	2017-05-01	15:45:04	\N	\N	2	\N	\N
8341	615	8977	2017-05-01	15:45:04	\N	\N	2	\N	\N
8342	615	8978	2017-05-01	15:45:04	\N	\N	2	\N	\N
8343	615	8979	2017-05-01	15:45:04	\N	\N	2	\N	\N
8344	615	8980	2017-05-01	15:45:04	\N	\N	2	\N	\N
8345	615	8981	2017-05-01	15:45:04	\N	\N	2	\N	\N
8346	615	8982	2017-05-01	15:45:04	\N	\N	2	\N	\N
8347	615	8983	2017-05-01	15:45:04	\N	\N	2	\N	\N
8348	615	8984	2017-05-01	15:45:04	\N	\N	2	\N	\N
8349	615	8985	2017-05-01	15:45:04	\N	\N	2	\N	\N
8350	615	8986	2017-05-01	15:45:04	\N	\N	2	\N	\N
8351	615	8987	2017-05-01	15:45:04	\N	\N	2	\N	\N
8352	615	8988	2017-05-01	15:45:04	\N	\N	2	\N	\N
8353	615	8989	2017-05-01	15:45:04	\N	\N	2	\N	\N
8354	615	8990	2017-05-01	15:45:04	\N	\N	2	\N	\N
8355	615	8991	2017-05-01	15:45:04	\N	\N	2	\N	\N
8356	615	8992	2017-05-01	15:45:04	\N	\N	2	\N	\N
8357	615	8993	2017-05-01	15:45:04	\N	\N	2	\N	\N
8358	615	8994	2017-05-01	15:45:04	\N	\N	2	\N	\N
8359	615	8995	2017-05-01	15:45:04	\N	\N	2	\N	\N
8360	615	8996	2017-05-01	15:45:04	\N	\N	1	\N	\N
8361	615	8997	2017-05-01	15:45:04	\N	\N	1	\N	\N
8362	616	8999	2017-05-01	15:46:04	\N	\N	3	\N	\N
8363	616	9000	2017-05-01	15:46:04	\N	\N	2	\N	\N
8364	616	9001	2017-05-01	15:46:04	\N	\N	2	\N	\N
8365	616	9002	2017-05-01	15:46:04	\N	\N	2	\N	\N
8366	616	9003	2017-05-01	15:46:04	\N	\N	2	\N	\N
8367	616	9004	2017-05-01	15:46:04	\N	\N	2	\N	\N
8368	616	9005	2017-05-01	15:46:04	\N	\N	2	\N	\N
8369	616	9006	2017-05-01	15:46:04	\N	\N	2	\N	\N
8370	617	9008	2017-05-01	15:46:04	\N	\N	3	\N	\N
8371	617	9009	2017-05-01	15:46:04	\N	\N	3	\N	\N
8372	617	9010	2017-05-01	15:46:04	\N	\N	3	\N	\N
8373	617	9011	2017-05-01	15:46:04	\N	\N	2	\N	\N
8374	617	9012	2017-05-01	15:46:04	\N	\N	2	\N	\N
8375	617	9013	2017-05-01	15:46:04	\N	\N	2	\N	\N
8376	617	9014	2017-05-01	15:46:04	\N	\N	2	\N	\N
8377	617	9015	2017-05-01	15:46:04	\N	\N	2	\N	\N
8378	617	9016	2017-05-01	15:46:04	\N	\N	2	\N	\N
8379	617	9017	2017-05-01	15:46:04	\N	\N	2	\N	\N
8380	617	9018	2017-05-01	15:46:04	\N	\N	2	\N	\N
8381	617	9019	2017-05-01	15:46:04	\N	\N	2	\N	\N
8382	617	9020	2017-05-01	15:46:04	\N	\N	2	\N	\N
8383	617	9021	2017-05-01	15:46:04	\N	\N	2	\N	\N
8384	617	9022	2017-05-01	15:46:04	\N	\N	2	\N	\N
8385	617	9023	2017-05-01	15:46:04	\N	\N	2	\N	\N
8386	617	9024	2017-05-01	15:46:04	\N	\N	2	\N	\N
8387	617	9025	2017-05-01	15:46:04	\N	\N	2	\N	\N
8388	617	9026	2017-05-01	15:46:04	\N	\N	2	\N	\N
8389	617	9027	2017-05-01	15:46:04	\N	\N	2	\N	\N
8390	617	9028	2017-05-01	15:46:04	\N	\N	2	\N	\N
8391	617	9029	2017-05-01	15:46:04	\N	\N	2	\N	\N
8392	617	9030	2017-05-01	15:46:04	\N	\N	2	\N	\N
8393	617	9031	2017-05-01	15:46:04	\N	\N	2	\N	\N
8394	617	9032	2017-05-01	15:46:04	\N	\N	2	\N	\N
8395	617	9033	2017-05-01	15:46:04	\N	\N	2	\N	\N
8396	617	9034	2017-05-01	15:46:04	\N	\N	2	\N	\N
8397	617	9035	2017-05-01	15:46:04	\N	\N	2	\N	\N
8398	617	9036	2017-05-01	15:46:04	\N	\N	2	\N	\N
8399	617	9037	2017-05-01	15:46:04	\N	\N	2	\N	\N
8400	617	9038	2017-05-01	15:46:04	\N	\N	2	\N	\N
8401	617	9039	2017-05-01	15:46:04	\N	\N	2	\N	\N
8402	617	9040	2017-05-01	15:46:04	\N	\N	2	\N	\N
8403	617	9041	2017-05-01	15:46:04	\N	\N	2	\N	\N
8404	617	9042	2017-05-01	15:46:04	\N	\N	2	\N	\N
8405	617	9043	2017-05-01	15:46:04	\N	\N	1	\N	\N
8406	617	9044	2017-05-01	15:46:04	\N	\N	1	\N	\N
8407	617	9045	2017-05-01	15:46:04	\N	\N	3	\N	\N
8408	617	9046	2017-05-01	15:46:04	\N	\N	1	\N	\N
8409	617	9047	2017-05-01	15:46:04	\N	\N	1	\N	\N
8410	617	9048	2017-05-01	15:46:04	\N	\N	1	\N	\N
8411	617	9049	2017-05-01	15:46:04	\N	\N	1	\N	\N
8412	617	9050	2017-05-01	15:46:04	\N	\N	1	\N	\N
8413	619	9053	2017-05-01	15:46:04	\N	\N	1	\N	\N
8414	623	9058	2017-05-01	15:46:04	\N	\N	2	\N	\N
8415	623	9059	2017-05-01	15:46:04	\N	\N	2	\N	\N
8416	623	9060	2017-05-01	15:46:04	\N	\N	2	\N	\N
8417	623	9061	2017-05-01	15:46:04	\N	\N	2	\N	\N
8418	623	9062	2017-05-01	15:46:04	\N	\N	2	\N	\N
8419	623	9063	2017-05-01	15:46:04	\N	\N	2	\N	\N
8420	623	9064	2017-05-01	15:46:04	\N	\N	2	\N	\N
8421	623	9065	2017-05-01	15:46:04	\N	\N	2	\N	\N
8422	623	9066	2017-05-01	15:46:04	\N	\N	2	\N	\N
8423	623	9067	2017-05-01	15:46:04	\N	\N	2	\N	\N
8424	623	9068	2017-05-01	15:46:04	\N	\N	2	\N	\N
8425	623	9069	2017-05-01	15:46:04	\N	\N	2	\N	\N
8426	623	9070	2017-05-01	15:46:04	\N	\N	2	\N	\N
8427	623	9071	2017-05-01	15:46:04	\N	\N	2	\N	\N
8428	623	9072	2017-05-01	15:46:04	\N	\N	2	\N	\N
8429	623	9073	2017-05-01	15:46:04	\N	\N	2	\N	\N
8430	623	9074	2017-05-01	15:46:04	\N	\N	2	\N	\N
8431	623	9075	2017-05-01	15:46:04	\N	\N	2	\N	\N
8432	623	9076	2017-05-01	15:46:04	\N	\N	2	\N	\N
8433	623	9077	2017-05-01	15:46:04	\N	\N	2	\N	\N
8434	623	9078	2017-05-01	15:46:04	\N	\N	2	\N	\N
8435	623	9079	2017-05-01	15:46:04	\N	\N	2	\N	\N
8436	623	9080	2017-05-01	15:46:04	\N	\N	2	\N	\N
8437	623	9081	2017-05-01	15:46:04	\N	\N	2	\N	\N
8438	623	9082	2017-05-01	15:46:04	\N	\N	2	\N	\N
8439	623	9083	2017-05-01	15:46:04	\N	\N	2	\N	\N
8440	623	9084	2017-05-01	15:46:04	\N	\N	2	\N	\N
8441	623	9085	2017-05-01	15:46:04	\N	\N	2	\N	\N
8442	623	9086	2017-05-01	15:46:04	\N	\N	2	\N	\N
8443	623	9087	2017-05-01	15:46:04	\N	\N	2	\N	\N
8444	623	9088	2017-05-01	15:46:04	\N	\N	2	\N	\N
8445	623	9089	2017-05-01	15:46:04	\N	\N	2	\N	\N
8446	623	9090	2017-05-01	15:46:04	\N	\N	2	\N	\N
8447	623	9091	2017-05-01	15:46:04	\N	\N	2	\N	\N
8448	623	9092	2017-05-01	15:46:04	\N	\N	2	\N	\N
8449	623	9093	2017-05-01	15:46:04	\N	\N	2	\N	\N
8450	623	9094	2017-05-01	15:46:04	\N	\N	2	\N	\N
8451	623	9095	2017-05-01	15:46:04	\N	\N	2	\N	\N
8452	623	9096	2017-05-01	15:46:04	\N	\N	2	\N	\N
8453	623	9097	2017-05-01	15:46:04	\N	\N	2	\N	\N
8454	623	9098	2017-05-01	15:46:04	\N	\N	2	\N	\N
8455	623	9099	2017-05-01	15:46:04	\N	\N	2	\N	\N
8456	623	9100	2017-05-01	15:46:04	\N	\N	2	\N	\N
8457	623	9101	2017-05-01	15:46:04	\N	\N	2	\N	\N
8458	623	9102	2017-05-01	15:46:04	\N	\N	2	\N	\N
8459	623	9103	2017-05-01	15:46:04	\N	\N	2	\N	\N
8460	623	9104	2017-05-01	15:46:04	\N	\N	2	\N	\N
8461	623	9105	2017-05-01	15:46:04	\N	\N	2	\N	\N
8462	623	9106	2017-05-01	15:46:04	\N	\N	2	\N	\N
8463	623	9107	2017-05-01	15:46:04	\N	\N	2	\N	\N
8464	623	9108	2017-05-01	15:46:04	\N	\N	2	\N	\N
8465	623	9109	2017-05-01	15:46:04	\N	\N	2	\N	\N
8466	623	9110	2017-05-01	15:46:04	\N	\N	2	\N	\N
8467	623	9111	2017-05-01	15:46:04	\N	\N	2	\N	\N
8468	624	9113	2017-05-01	15:46:04	\N	\N	3	\N	\N
8469	624	9114	2017-05-01	15:46:04	\N	\N	2	\N	\N
8470	624	9115	2017-05-01	15:46:04	\N	\N	2	\N	\N
8471	624	9116	2017-05-01	15:46:04	\N	\N	2	\N	\N
8472	624	9117	2017-05-01	15:46:04	\N	\N	2	\N	\N
8473	624	9118	2017-05-01	15:46:04	\N	\N	2	\N	\N
8474	624	9119	2017-05-01	15:46:04	\N	\N	2	\N	\N
8475	624	9120	2017-05-01	15:46:04	\N	\N	2	\N	\N
8476	624	9121	2017-05-01	15:46:04	\N	\N	2	\N	\N
8477	624	9122	2017-05-01	15:46:04	\N	\N	2	\N	\N
8478	624	9123	2017-05-01	15:46:04	\N	\N	2	\N	\N
8479	624	9124	2017-05-01	15:46:04	\N	\N	2	\N	\N
8480	624	9125	2017-05-01	15:46:04	\N	\N	2	\N	\N
8481	624	9126	2017-05-01	15:46:04	\N	\N	2	\N	\N
8482	625	9128	2017-05-01	15:47:04	\N	\N	3	\N	\N
8483	625	9129	2017-05-01	15:47:04	\N	\N	3	\N	\N
8484	625	9130	2017-05-01	15:47:04	\N	\N	3	\N	\N
8485	625	9131	2017-05-01	15:47:04	\N	\N	2	\N	\N
8486	625	9132	2017-05-01	15:47:04	\N	\N	2	\N	\N
8487	625	9133	2017-05-01	15:47:04	\N	\N	2	\N	\N
8488	625	9134	2017-05-01	15:47:04	\N	\N	2	\N	\N
8489	625	9135	2017-05-01	15:47:04	\N	\N	2	\N	\N
8490	625	9136	2017-05-01	15:47:04	\N	\N	2	\N	\N
8491	625	9137	2017-05-01	15:47:04	\N	\N	2	\N	\N
8492	625	9138	2017-05-01	15:47:04	\N	\N	2	\N	\N
8493	625	9139	2017-05-01	15:47:04	\N	\N	2	\N	\N
8494	625	9140	2017-05-01	15:47:04	\N	\N	2	\N	\N
8495	625	9141	2017-05-01	15:47:04	\N	\N	2	\N	\N
8496	625	9142	2017-05-01	15:47:04	\N	\N	2	\N	\N
8497	625	9143	2017-05-01	15:47:04	\N	\N	2	\N	\N
8498	625	9144	2017-05-01	15:47:04	\N	\N	2	\N	\N
8499	625	9145	2017-05-01	15:47:04	\N	\N	2	\N	\N
8500	625	9146	2017-05-01	15:47:04	\N	\N	2	\N	\N
8501	625	9147	2017-05-01	15:47:04	\N	\N	2	\N	\N
8502	625	9148	2017-05-01	15:47:04	\N	\N	2	\N	\N
8503	625	9149	2017-05-01	15:47:04	\N	\N	2	\N	\N
8504	625	9150	2017-05-01	15:47:04	\N	\N	2	\N	\N
8505	625	9151	2017-05-01	15:47:04	\N	\N	2	\N	\N
8506	625	9152	2017-05-01	15:47:04	\N	\N	2	\N	\N
8507	625	9153	2017-05-01	15:47:04	\N	\N	2	\N	\N
8508	626	9155	2017-05-01	15:47:04	\N	\N	3	\N	\N
8509	626	9156	2017-05-01	15:47:04	\N	\N	3	\N	\N
8510	626	9157	2017-05-01	15:47:04	\N	\N	3	\N	\N
8511	626	9158	2017-05-01	15:47:04	\N	\N	2	\N	\N
8512	626	9159	2017-05-01	15:47:04	\N	\N	2	\N	\N
8513	626	9160	2017-05-01	15:47:04	\N	\N	2	\N	\N
8514	626	9161	2017-05-01	15:47:04	\N	\N	2	\N	\N
8515	626	9162	2017-05-01	15:47:04	\N	\N	2	\N	\N
8516	626	9163	2017-05-01	15:47:04	\N	\N	2	\N	\N
8517	626	9164	2017-05-01	15:47:04	\N	\N	2	\N	\N
8518	626	9165	2017-05-01	15:47:04	\N	\N	2	\N	\N
8519	626	9166	2017-05-01	15:47:04	\N	\N	2	\N	\N
8520	626	9167	2017-05-01	15:47:04	\N	\N	2	\N	\N
8521	626	9168	2017-05-01	15:47:04	\N	\N	2	\N	\N
8522	626	9169	2017-05-01	15:47:04	\N	\N	2	\N	\N
8523	626	9170	2017-05-01	15:47:04	\N	\N	2	\N	\N
8524	626	9171	2017-05-01	15:47:04	\N	\N	2	\N	\N
8525	626	9172	2017-05-01	15:47:04	\N	\N	2	\N	\N
8526	626	9173	2017-05-01	15:47:04	\N	\N	2	\N	\N
8527	626	9174	2017-05-01	15:47:04	\N	\N	2	\N	\N
8528	626	9175	2017-05-01	15:47:04	\N	\N	2	\N	\N
8529	626	9176	2017-05-01	15:47:04	\N	\N	2	\N	\N
8530	626	9177	2017-05-01	15:47:04	\N	\N	2	\N	\N
8531	626	9178	2017-05-01	15:47:04	\N	\N	2	\N	\N
8532	626	9179	2017-05-01	15:47:04	\N	\N	2	\N	\N
8533	626	9180	2017-05-01	15:47:04	\N	\N	2	\N	\N
8534	626	9181	2017-05-01	15:47:04	\N	\N	2	\N	\N
8535	626	9182	2017-05-01	15:47:04	\N	\N	2	\N	\N
8536	626	9183	2017-05-01	15:47:04	\N	\N	2	\N	\N
8537	626	9184	2017-05-01	15:47:04	\N	\N	2	\N	\N
8538	626	9185	2017-05-01	15:47:04	\N	\N	2	\N	\N
8539	626	9186	2017-05-01	15:47:04	\N	\N	2	\N	\N
8540	626	9187	2017-05-01	15:47:04	\N	\N	2	\N	\N
8541	626	9188	2017-05-01	15:47:04	\N	\N	2	\N	\N
8542	626	9189	2017-05-01	15:47:04	\N	\N	2	\N	\N
8543	626	9190	2017-05-01	15:47:04	\N	\N	2	\N	\N
8544	626	9191	2017-05-01	15:47:04	\N	\N	2	\N	\N
8545	626	9192	2017-05-01	15:47:04	\N	\N	2	\N	\N
8546	626	9193	2017-05-01	15:47:04	\N	\N	2	\N	\N
8547	626	9194	2017-05-01	15:47:04	\N	\N	2	\N	\N
8548	626	9195	2017-05-01	15:47:04	\N	\N	2	\N	\N
8549	626	9196	2017-05-01	15:47:04	\N	\N	2	\N	\N
8550	626	9197	2017-05-01	15:47:04	\N	\N	2	\N	\N
8551	626	9198	2017-05-01	15:47:04	\N	\N	2	\N	\N
8552	626	9199	2017-05-01	15:47:04	\N	\N	2	\N	\N
8553	626	9200	2017-05-01	15:47:04	\N	\N	2	\N	\N
8554	626	9201	2017-05-01	15:47:04	\N	\N	2	\N	\N
8555	626	9202	2017-05-01	15:47:04	\N	\N	2	\N	\N
8556	626	9203	2017-05-01	15:47:04	\N	\N	2	\N	\N
8557	626	9204	2017-05-01	15:47:04	\N	\N	2	\N	\N
8558	626	9205	2017-05-01	15:47:04	\N	\N	2	\N	\N
8559	626	9206	2017-05-01	15:47:04	\N	\N	2	\N	\N
8560	626	9207	2017-05-01	15:47:04	\N	\N	2	\N	\N
8561	626	9208	2017-05-01	15:47:04	\N	\N	2	\N	\N
8562	626	9209	2017-05-01	15:47:04	\N	\N	2	\N	\N
8563	626	9210	2017-05-01	15:47:04	\N	\N	2	\N	\N
8564	626	9211	2017-05-01	15:47:04	\N	\N	2	\N	\N
8565	626	9212	2017-05-01	15:47:04	\N	\N	2	\N	\N
8566	626	9213	2017-05-01	15:47:04	\N	\N	2	\N	\N
8567	627	9215	2017-05-01	15:47:04	\N	\N	3	\N	\N
8568	627	9216	2017-05-01	15:47:04	\N	\N	3	\N	\N
8569	627	9217	2017-05-01	15:47:04	\N	\N	2	\N	\N
8570	627	9218	2017-05-01	15:47:04	\N	\N	2	\N	\N
8571	627	9219	2017-05-01	15:47:04	\N	\N	2	\N	\N
8572	627	9220	2017-05-01	15:47:04	\N	\N	2	\N	\N
8573	627	9221	2017-05-01	15:47:04	\N	\N	2	\N	\N
8574	627	9222	2017-05-01	15:47:04	\N	\N	2	\N	\N
8575	627	9223	2017-05-01	15:47:04	\N	\N	2	\N	\N
8576	627	9224	2017-05-01	15:47:04	\N	\N	2	\N	\N
8577	627	9225	2017-05-01	15:47:04	\N	\N	2	\N	\N
8578	627	9226	2017-05-01	15:47:04	\N	\N	2	\N	\N
8579	627	9227	2017-05-01	15:47:04	\N	\N	2	\N	\N
8580	627	9228	2017-05-01	15:47:04	\N	\N	2	\N	\N
8581	627	9229	2017-05-01	15:47:04	\N	\N	2	\N	\N
8582	627	9230	2017-05-01	15:47:04	\N	\N	2	\N	\N
8583	627	9231	2017-05-01	15:47:04	\N	\N	2	\N	\N
8584	627	9232	2017-05-01	15:47:04	\N	\N	2	\N	\N
8585	627	9233	2017-05-01	15:47:04	\N	\N	2	\N	\N
8586	627	9234	2017-05-01	15:47:04	\N	\N	2	\N	\N
8587	627	9235	2017-05-01	15:47:04	\N	\N	2	\N	\N
8588	627	9236	2017-05-01	15:47:04	\N	\N	2	\N	\N
8589	627	9237	2017-05-01	15:47:04	\N	\N	2	\N	\N
8590	627	9238	2017-05-01	15:47:04	\N	\N	2	\N	\N
8591	628	9240	2017-05-01	15:47:04	\N	\N	3	\N	\N
8592	628	9241	2017-05-01	15:47:04	\N	\N	3	\N	\N
8593	628	9242	2017-05-01	15:47:04	\N	\N	2	\N	\N
8594	628	9243	2017-05-01	15:47:04	\N	\N	2	\N	\N
8595	628	9244	2017-05-01	15:47:04	\N	\N	2	\N	\N
8596	628	9245	2017-05-01	15:47:04	\N	\N	2	\N	\N
8597	628	9246	2017-05-01	15:47:04	\N	\N	2	\N	\N
8598	628	9247	2017-05-01	15:47:04	\N	\N	2	\N	\N
8599	628	9248	2017-05-01	15:47:04	\N	\N	2	\N	\N
8600	628	9249	2017-05-01	15:47:04	\N	\N	2	\N	\N
8601	628	9250	2017-05-01	15:47:04	\N	\N	2	\N	\N
8602	628	9251	2017-05-01	15:47:04	\N	\N	2	\N	\N
8603	628	9252	2017-05-01	15:47:04	\N	\N	2	\N	\N
8604	628	9253	2017-05-01	15:47:04	\N	\N	2	\N	\N
8605	628	9254	2017-05-01	15:47:04	\N	\N	2	\N	\N
8606	628	9255	2017-05-01	15:47:04	\N	\N	2	\N	\N
8607	628	9256	2017-05-01	15:47:04	\N	\N	2	\N	\N
8608	628	9257	2017-05-01	15:47:04	\N	\N	2	\N	\N
8609	628	9258	2017-05-01	15:47:04	\N	\N	2	\N	\N
8610	628	9259	2017-05-01	15:47:04	\N	\N	2	\N	\N
8611	628	9260	2017-05-01	15:47:04	\N	\N	2	\N	\N
8612	628	9261	2017-05-01	15:47:04	\N	\N	2	\N	\N
8613	628	9262	2017-05-01	15:47:04	\N	\N	2	\N	\N
8614	628	9263	2017-05-01	15:47:04	\N	\N	2	\N	\N
8615	628	9264	2017-05-01	15:47:04	\N	\N	2	\N	\N
8616	628	9265	2017-05-01	15:47:04	\N	\N	2	\N	\N
8617	628	9266	2017-05-01	15:47:04	\N	\N	2	\N	\N
8618	628	9267	2017-05-01	15:47:04	\N	\N	2	\N	\N
8619	628	9268	2017-05-01	15:47:04	\N	\N	2	\N	\N
8620	628	9269	2017-05-01	15:47:04	\N	\N	2	\N	\N
8621	628	9270	2017-05-01	15:47:04	\N	\N	2	\N	\N
8622	628	9271	2017-05-01	15:47:04	\N	\N	2	\N	\N
8623	628	9272	2017-05-01	15:47:04	\N	\N	2	\N	\N
8624	628	9273	2017-05-01	15:47:04	\N	\N	2	\N	\N
8625	628	9274	2017-05-01	15:47:04	\N	\N	2	\N	\N
8626	628	9275	2017-05-01	15:47:04	\N	\N	2	\N	\N
8627	628	9276	2017-05-01	15:47:04	\N	\N	2	\N	\N
8628	628	9277	2017-05-01	15:47:04	\N	\N	2	\N	\N
8629	628	9278	2017-05-01	15:47:04	\N	\N	2	\N	\N
8630	628	9279	2017-05-01	15:47:04	\N	\N	2	\N	\N
8631	628	9280	2017-05-01	15:47:04	\N	\N	2	\N	\N
8632	628	9281	2017-05-01	15:47:04	\N	\N	2	\N	\N
8633	628	9282	2017-05-01	15:47:04	\N	\N	2	\N	\N
8634	628	9283	2017-05-01	15:47:04	\N	\N	2	\N	\N
8635	628	9284	2017-05-01	15:47:04	\N	\N	2	\N	\N
8636	628	9285	2017-05-01	15:47:04	\N	\N	2	\N	\N
8637	628	9286	2017-05-01	15:47:04	\N	\N	2	\N	\N
8638	628	9287	2017-05-01	15:47:04	\N	\N	2	\N	\N
8639	628	9288	2017-05-01	15:47:04	\N	\N	2	\N	\N
8640	628	9289	2017-05-01	15:47:04	\N	\N	2	\N	\N
8641	628	9290	2017-05-01	15:47:04	\N	\N	2	\N	\N
8642	628	9291	2017-05-01	15:47:04	\N	\N	2	\N	\N
8643	628	9292	2017-05-01	15:47:04	\N	\N	2	\N	\N
8644	628	9293	2017-05-01	15:47:04	\N	\N	2	\N	\N
8645	630	9296	2017-05-01	15:47:04	\N	\N	2	\N	\N
8646	630	9297	2017-05-01	15:47:04	\N	\N	2	\N	\N
8647	630	9298	2017-05-01	15:47:04	\N	\N	2	\N	\N
8648	630	9299	2017-05-01	15:47:04	\N	\N	2	\N	\N
8649	630	9300	2017-05-01	15:47:04	\N	\N	2	\N	\N
8650	630	9301	2017-05-01	15:47:04	\N	\N	2	\N	\N
8651	630	9302	2017-05-01	15:47:04	\N	\N	2	\N	\N
8652	630	9303	2017-05-01	15:47:04	\N	\N	2	\N	\N
8653	630	9304	2017-05-01	15:47:04	\N	\N	2	\N	\N
8654	630	9305	2017-05-01	15:47:04	\N	\N	2	\N	\N
8655	630	9306	2017-05-01	15:47:04	\N	\N	2	\N	\N
8656	631	9308	2017-05-01	15:48:04	\N	\N	3	\N	\N
8657	631	9309	2017-05-01	15:48:04	\N	\N	3	\N	\N
8658	631	9310	2017-05-01	15:48:04	\N	\N	3	\N	\N
8659	631	9311	2017-05-01	15:48:04	\N	\N	3	\N	\N
8660	631	9312	2017-05-01	15:48:04	\N	\N	3	\N	\N
8661	631	9313	2017-05-01	15:48:04	\N	\N	3	\N	\N
8662	631	9314	2017-05-01	15:48:04	\N	\N	3	\N	\N
8663	631	9315	2017-05-01	15:48:04	\N	\N	3	\N	\N
8664	631	9316	2017-05-01	15:48:04	\N	\N	3	\N	\N
8665	631	9317	2017-05-01	15:48:04	\N	\N	3	\N	\N
8666	631	9318	2017-05-01	15:48:04	\N	\N	3	\N	\N
8667	631	9319	2017-05-01	15:48:04	\N	\N	3	\N	\N
8668	631	9320	2017-05-01	15:48:04	\N	\N	2	\N	\N
8669	631	9321	2017-05-01	15:48:04	\N	\N	2	\N	\N
8670	631	9322	2017-05-01	15:48:04	\N	\N	2	\N	\N
8671	631	9323	2017-05-01	15:48:04	\N	\N	2	\N	\N
8672	631	9324	2017-05-01	15:48:04	\N	\N	2	\N	\N
8673	631	9325	2017-05-01	15:48:04	\N	\N	2	\N	\N
8674	631	9326	2017-05-01	15:48:04	\N	\N	2	\N	\N
8675	631	9327	2017-05-01	15:48:04	\N	\N	2	\N	\N
8676	631	9328	2017-05-01	15:48:04	\N	\N	2	\N	\N
8677	631	9329	2017-05-01	15:48:04	\N	\N	2	\N	\N
8678	631	9330	2017-05-01	15:48:04	\N	\N	2	\N	\N
8679	631	9331	2017-05-01	15:48:04	\N	\N	2	\N	\N
8680	631	9332	2017-05-01	15:48:04	\N	\N	2	\N	\N
8681	631	9333	2017-05-01	15:48:04	\N	\N	2	\N	\N
8682	631	9334	2017-05-01	15:48:04	\N	\N	2	\N	\N
8683	631	9335	2017-05-01	15:48:04	\N	\N	2	\N	\N
8684	631	9336	2017-05-01	15:48:04	\N	\N	2	\N	\N
8685	631	9337	2017-05-01	15:48:04	\N	\N	2	\N	\N
8686	631	9338	2017-05-01	15:48:04	\N	\N	2	\N	\N
8687	631	9339	2017-05-01	15:48:04	\N	\N	2	\N	\N
8688	631	9340	2017-05-01	15:48:04	\N	\N	2	\N	\N
8689	631	9341	2017-05-01	15:48:04	\N	\N	2	\N	\N
8690	631	9342	2017-05-01	15:48:04	\N	\N	2	\N	\N
8691	631	9343	2017-05-01	15:48:04	\N	\N	2	\N	\N
8692	631	9344	2017-05-01	15:48:04	\N	\N	2	\N	\N
8693	631	9345	2017-05-01	15:48:04	\N	\N	2	\N	\N
8694	631	9346	2017-05-01	15:48:04	\N	\N	2	\N	\N
8695	631	9347	2017-05-01	15:48:04	\N	\N	2	\N	\N
8696	631	9348	2017-05-01	15:48:04	\N	\N	2	\N	\N
8697	631	9349	2017-05-01	15:48:04	\N	\N	2	\N	\N
8698	631	9350	2017-05-01	15:48:04	\N	\N	2	\N	\N
8699	631	9351	2017-05-01	15:48:04	\N	\N	2	\N	\N
8700	631	9352	2017-05-01	15:48:04	\N	\N	2	\N	\N
8701	631	9353	2017-05-01	15:48:04	\N	\N	2	\N	\N
8702	631	9354	2017-05-01	15:48:04	\N	\N	2	\N	\N
8703	631	9355	2017-05-01	15:48:04	\N	\N	2	\N	\N
8704	631	9356	2017-05-01	15:48:04	\N	\N	2	\N	\N
8705	631	9357	2017-05-01	15:48:04	\N	\N	2	\N	\N
8706	631	9358	2017-05-01	15:48:04	\N	\N	2	\N	\N
8707	631	9359	2017-05-01	15:48:04	\N	\N	2	\N	\N
8708	631	9360	2017-05-01	15:48:04	\N	\N	2	\N	\N
8709	631	9361	2017-05-01	15:48:04	\N	\N	2	\N	\N
8710	631	9362	2017-05-01	15:48:04	\N	\N	2	\N	\N
8711	631	9363	2017-05-01	15:48:04	\N	\N	2	\N	\N
8712	631	9364	2017-05-01	15:48:04	\N	\N	2	\N	\N
8713	632	9366	2017-05-01	15:48:04	\N	\N	2	\N	\N
8714	632	9367	2017-05-01	15:48:04	\N	\N	2	\N	\N
8715	632	9368	2017-05-01	15:48:04	\N	\N	2	\N	\N
8716	632	9369	2017-05-01	15:48:04	\N	\N	2	\N	\N
8717	632	9370	2017-05-01	15:48:04	\N	\N	2	\N	\N
8718	632	9371	2017-05-01	15:48:04	\N	\N	2	\N	\N
8719	632	9372	2017-05-01	15:48:04	\N	\N	2	\N	\N
8720	632	9373	2017-05-01	15:48:04	\N	\N	2	\N	\N
8721	632	9374	2017-05-01	15:48:04	\N	\N	2	\N	\N
8722	632	9375	2017-05-01	15:48:04	\N	\N	2	\N	\N
8723	632	9376	2017-05-01	15:48:04	\N	\N	2	\N	\N
8724	633	9378	2017-05-01	15:48:04	\N	\N	2	\N	\N
8725	634	9380	2017-05-01	15:48:04	\N	\N	3	\N	\N
8726	634	9381	2017-05-01	15:48:04	\N	\N	2	\N	\N
8727	634	9382	2017-05-01	15:48:04	\N	\N	3	\N	\N
8728	634	9383	2017-05-01	15:48:04	\N	\N	3	\N	\N
8729	634	9384	2017-05-01	15:48:04	\N	\N	2	\N	\N
8730	634	9385	2017-05-01	15:48:04	\N	\N	2	\N	\N
8731	634	9386	2017-05-01	15:48:04	\N	\N	1	\N	\N
8732	634	9387	2017-05-01	15:48:04	\N	\N	1	\N	\N
8733	637	9391	2017-05-01	15:48:04	\N	\N	3	\N	\N
8734	637	9392	2017-05-01	15:48:04	\N	\N	3	\N	\N
8735	637	9393	2017-05-01	15:48:04	\N	\N	2	\N	\N
8736	637	9394	2017-05-01	15:48:04	\N	\N	2	\N	\N
8737	637	9395	2017-05-01	15:48:04	\N	\N	2	\N	\N
8738	637	9396	2017-05-01	15:48:04	\N	\N	2	\N	\N
8739	637	9397	2017-05-01	15:48:04	\N	\N	2	\N	\N
8740	637	9398	2017-05-01	15:48:04	\N	\N	2	\N	\N
8741	637	9399	2017-05-01	15:48:04	\N	\N	2	\N	\N
8742	637	9400	2017-05-01	15:48:04	\N	\N	2	\N	\N
8743	637	9401	2017-05-01	15:48:04	\N	\N	2	\N	\N
8744	637	9402	2017-05-01	15:48:04	\N	\N	2	\N	\N
8745	637	9403	2017-05-01	15:48:04	\N	\N	2	\N	\N
8746	637	9404	2017-05-01	15:48:04	\N	\N	2	\N	\N
8747	637	9405	2017-05-01	15:48:04	\N	\N	2	\N	\N
8748	637	9406	2017-05-01	15:48:04	\N	\N	2	\N	\N
8749	637	9407	2017-05-01	15:48:04	\N	\N	2	\N	\N
8750	637	9408	2017-05-01	15:48:04	\N	\N	2	\N	\N
8751	637	9409	2017-05-01	15:48:04	\N	\N	2	\N	\N
8752	637	9410	2017-05-01	15:48:04	\N	\N	2	\N	\N
8753	637	9411	2017-05-01	15:48:04	\N	\N	2	\N	\N
8754	637	9412	2017-05-01	15:48:04	\N	\N	2	\N	\N
8755	637	9413	2017-05-01	15:48:04	\N	\N	2	\N	\N
8756	637	9414	2017-05-01	15:48:04	\N	\N	2	\N	\N
8757	637	9415	2017-05-01	15:48:04	\N	\N	2	\N	\N
8758	637	9416	2017-05-01	15:48:04	\N	\N	2	\N	\N
8759	637	9417	2017-05-01	15:48:04	\N	\N	2	\N	\N
8760	637	9418	2017-05-01	15:48:04	\N	\N	2	\N	\N
8761	637	9419	2017-05-01	15:48:04	\N	\N	2	\N	\N
8762	637	9420	2017-05-01	15:48:04	\N	\N	2	\N	\N
8763	637	9421	2017-05-01	15:48:04	\N	\N	2	\N	\N
8764	637	9422	2017-05-01	15:48:04	\N	\N	2	\N	\N
8765	637	9423	2017-05-01	15:48:04	\N	\N	2	\N	\N
8766	637	9424	2017-05-01	15:48:04	\N	\N	2	\N	\N
8767	637	9425	2017-05-01	15:48:04	\N	\N	2	\N	\N
8768	637	9426	2017-05-01	15:48:04	\N	\N	2	\N	\N
8769	637	9427	2017-05-01	15:48:04	\N	\N	2	\N	\N
8770	637	9428	2017-05-01	15:48:04	\N	\N	2	\N	\N
8771	637	9429	2017-05-01	15:48:04	\N	\N	2	\N	\N
8772	637	9430	2017-05-01	15:48:04	\N	\N	2	\N	\N
8773	637	9431	2017-05-01	15:48:04	\N	\N	2	\N	\N
8774	637	9432	2017-05-01	15:48:04	\N	\N	2	\N	\N
8775	637	9433	2017-05-01	15:48:04	\N	\N	2	\N	\N
8776	637	9434	2017-05-01	15:48:04	\N	\N	2	\N	\N
8777	637	9435	2017-05-01	15:48:04	\N	\N	2	\N	\N
8778	637	9436	2017-05-01	15:48:04	\N	\N	2	\N	\N
8779	637	9437	2017-05-01	15:48:04	\N	\N	2	\N	\N
8780	637	9438	2017-05-01	15:48:04	\N	\N	2	\N	\N
8781	637	9439	2017-05-01	15:48:04	\N	\N	2	\N	\N
8782	637	9440	2017-05-01	15:48:04	\N	\N	2	\N	\N
8783	637	9441	2017-05-01	15:48:04	\N	\N	2	\N	\N
8784	637	9442	2017-05-01	15:48:04	\N	\N	2	\N	\N
8785	637	9443	2017-05-01	15:48:04	\N	\N	2	\N	\N
8786	637	9444	2017-05-01	15:48:04	\N	\N	2	\N	\N
8787	637	9445	2017-05-01	15:48:04	\N	\N	2	\N	\N
8788	637	9446	2017-05-01	15:48:04	\N	\N	2	\N	\N
8789	637	9447	2017-05-01	15:48:04	\N	\N	2	\N	\N
8790	637	9448	2017-05-01	15:48:04	\N	\N	2	\N	\N
8791	637	9449	2017-05-01	15:48:04	\N	\N	2	\N	\N
8792	637	9450	2017-05-01	15:48:04	\N	\N	2	\N	\N
8793	638	9452	2017-05-01	15:48:04	\N	\N	3	\N	\N
8794	638	9453	2017-05-01	15:48:04	\N	\N	2	\N	\N
8795	638	9454	2017-05-01	15:48:04	\N	\N	2	\N	\N
8796	638	9455	2017-05-01	15:48:04	\N	\N	2	\N	\N
8797	638	9456	2017-05-01	15:48:04	\N	\N	2	\N	\N
8798	638	9457	2017-05-01	15:48:04	\N	\N	1	\N	\N
8799	640	9460	2017-05-01	15:49:04	\N	\N	2	\N	\N
8800	640	9461	2017-05-01	15:49:04	\N	\N	2	\N	\N
8801	640	9462	2017-05-01	15:49:04	\N	\N	2	\N	\N
8802	640	9463	2017-05-01	15:49:04	\N	\N	2	\N	\N
8803	640	9464	2017-05-01	15:49:04	\N	\N	2	\N	\N
8804	640	9465	2017-05-01	15:49:04	\N	\N	2	\N	\N
8805	640	9466	2017-05-01	15:49:04	\N	\N	2	\N	\N
8806	640	9467	2017-05-01	15:49:04	\N	\N	2	\N	\N
8807	640	9468	2017-05-01	15:49:04	\N	\N	2	\N	\N
8808	641	9470	2017-05-01	15:49:04	\N	\N	2	\N	\N
8809	641	9471	2017-05-01	15:49:04	\N	\N	2	\N	\N
8810	641	9472	2017-05-01	15:49:04	\N	\N	2	\N	\N
8811	641	9473	2017-05-01	15:49:04	\N	\N	2	\N	\N
8812	641	9474	2017-05-01	15:49:04	\N	\N	2	\N	\N
8813	642	9477	2017-05-01	15:49:04	\N	\N	2	\N	\N
8814	642	9478	2017-05-01	15:49:04	\N	\N	2	\N	\N
8815	642	9479	2017-05-01	15:49:04	\N	\N	2	\N	\N
8816	642	9480	2017-05-01	15:49:04	\N	\N	2	\N	\N
8817	642	9481	2017-05-01	15:49:04	\N	\N	2	\N	\N
8818	643	9483	2017-05-01	15:49:04	\N	\N	2	\N	\N
8819	643	9484	2017-05-01	15:49:04	\N	\N	2	\N	\N
8820	643	9485	2017-05-01	15:49:04	\N	\N	2	\N	\N
8821	643	9486	2017-05-01	15:49:04	\N	\N	2	\N	\N
8822	643	9487	2017-05-01	15:49:04	\N	\N	2	\N	\N
8823	643	9488	2017-05-01	15:49:04	\N	\N	2	\N	\N
8824	643	9489	2017-05-01	15:49:04	\N	\N	2	\N	\N
8825	643	9490	2017-05-01	15:49:04	\N	\N	2	\N	\N
8826	644	9492	2017-05-01	15:49:04	\N	\N	2	\N	\N
8827	644	9493	2017-05-01	15:49:04	\N	\N	2	\N	\N
8828	644	9494	2017-05-01	15:49:04	\N	\N	2	\N	\N
8829	645	9497	2017-05-01	15:49:04	\N	\N	3	\N	\N
8830	645	9498	2017-05-01	15:49:04	\N	\N	2	\N	\N
8831	646	9500	2017-05-01	15:49:04	\N	\N	3	\N	\N
8832	646	9501	2017-05-01	15:49:04	\N	\N	3	\N	\N
8833	646	9502	2017-05-01	15:49:04	\N	\N	3	\N	\N
8834	646	9503	2017-05-01	15:49:04	\N	\N	2	\N	\N
8835	646	9504	2017-05-01	15:49:04	\N	\N	2	\N	\N
8836	646	9505	2017-05-01	15:49:04	\N	\N	2	\N	\N
8837	646	9506	2017-05-01	15:49:04	\N	\N	2	\N	\N
8838	646	9507	2017-05-01	15:49:04	\N	\N	2	\N	\N
8839	646	9508	2017-05-01	15:49:04	\N	\N	2	\N	\N
8840	646	9509	2017-05-01	15:49:04	\N	\N	2	\N	\N
8841	646	9510	2017-05-01	15:49:04	\N	\N	2	\N	\N
8842	646	9511	2017-05-01	15:49:04	\N	\N	2	\N	\N
8843	646	9512	2017-05-01	15:49:04	\N	\N	2	\N	\N
8844	646	9513	2017-05-01	15:49:04	\N	\N	2	\N	\N
8845	646	9514	2017-05-01	15:49:04	\N	\N	2	\N	\N
8846	646	9515	2017-05-01	15:49:04	\N	\N	2	\N	\N
8847	646	9516	2017-05-01	15:49:04	\N	\N	2	\N	\N
8848	646	9517	2017-05-01	15:49:04	\N	\N	2	\N	\N
8849	646	9518	2017-05-01	15:49:04	\N	\N	2	\N	\N
8850	648	9522	2017-05-01	15:49:04	\N	\N	3	\N	\N
8851	648	9523	2017-05-01	15:49:04	\N	\N	2	\N	\N
8852	648	9524	2017-05-01	15:49:04	\N	\N	2	\N	\N
8853	648	9525	2017-05-01	15:49:04	\N	\N	2	\N	\N
8854	648	9526	2017-05-01	15:49:04	\N	\N	2	\N	\N
8855	648	9527	2017-05-01	15:49:04	\N	\N	2	\N	\N
8856	648	9528	2017-05-01	15:49:04	\N	\N	2	\N	\N
8857	648	9529	2017-05-01	15:49:04	\N	\N	2	\N	\N
8858	648	9530	2017-05-01	15:49:04	\N	\N	2	\N	\N
8859	648	9531	2017-05-01	15:49:04	\N	\N	2	\N	\N
8860	648	9532	2017-05-01	15:49:04	\N	\N	2	\N	\N
8861	648	9533	2017-05-01	15:49:04	\N	\N	2	\N	\N
8862	648	9534	2017-05-01	15:49:04	\N	\N	2	\N	\N
8863	648	9535	2017-05-01	15:49:04	\N	\N	2	\N	\N
8864	648	9536	2017-05-01	15:49:04	\N	\N	2	\N	\N
8865	648	9537	2017-05-01	15:49:04	\N	\N	2	\N	\N
8866	648	9538	2017-05-01	15:49:04	\N	\N	2	\N	\N
8867	648	9539	2017-05-01	15:49:04	\N	\N	2	\N	\N
8868	648	9540	2017-05-01	15:49:04	\N	\N	2	\N	\N
8869	648	9541	2017-05-01	15:49:04	\N	\N	2	\N	\N
8870	648	9542	2017-05-01	15:49:04	\N	\N	2	\N	\N
8871	648	9543	2017-05-01	15:49:04	\N	\N	2	\N	\N
8872	648	9544	2017-05-01	15:49:04	\N	\N	2	\N	\N
8873	648	9545	2017-05-01	15:49:04	\N	\N	2	\N	\N
8874	648	9546	2017-05-01	15:49:04	\N	\N	2	\N	\N
8875	648	9547	2017-05-01	15:49:04	\N	\N	2	\N	\N
8876	648	9548	2017-05-01	15:49:04	\N	\N	2	\N	\N
8877	648	9549	2017-05-01	15:49:04	\N	\N	2	\N	\N
8878	648	9550	2017-05-01	15:49:04	\N	\N	2	\N	\N
8879	648	9551	2017-05-01	15:49:04	\N	\N	2	\N	\N
8880	648	9552	2017-05-01	15:49:04	\N	\N	2	\N	\N
8881	648	9553	2017-05-01	15:49:04	\N	\N	2	\N	\N
8882	648	9554	2017-05-01	15:49:04	\N	\N	2	\N	\N
8883	648	9555	2017-05-01	15:49:04	\N	\N	2	\N	\N
8884	648	9556	2017-05-01	15:49:04	\N	\N	2	\N	\N
8885	648	9557	2017-05-01	15:49:04	\N	\N	2	\N	\N
8886	648	9558	2017-05-01	15:49:04	\N	\N	2	\N	\N
8887	648	9559	2017-05-01	15:49:04	\N	\N	2	\N	\N
8888	648	9560	2017-05-01	15:49:04	\N	\N	2	\N	\N
8889	648	9561	2017-05-01	15:49:04	\N	\N	2	\N	\N
8890	648	9562	2017-05-01	15:49:04	\N	\N	2	\N	\N
8891	648	9563	2017-05-01	15:49:04	\N	\N	2	\N	\N
8892	648	9564	2017-05-01	15:50:04	\N	\N	2	\N	\N
8893	648	9565	2017-05-01	15:50:04	\N	\N	2	\N	\N
8894	648	9566	2017-05-01	15:50:04	\N	\N	2	\N	\N
8895	648	9567	2017-05-01	15:50:04	\N	\N	2	\N	\N
8896	648	9568	2017-05-01	15:50:04	\N	\N	2	\N	\N
8897	648	9569	2017-05-01	15:50:04	\N	\N	2	\N	\N
8898	648	9570	2017-05-01	15:50:04	\N	\N	2	\N	\N
8899	648	9571	2017-05-01	15:50:04	\N	\N	2	\N	\N
8900	648	9572	2017-05-01	15:50:04	\N	\N	2	\N	\N
8901	648	9573	2017-05-01	15:50:04	\N	\N	2	\N	\N
8902	655	9581	2017-05-01	15:50:04	\N	\N	2	\N	\N
8903	655	9582	2017-05-01	15:50:04	\N	\N	2	\N	\N
8904	655	9583	2017-05-01	15:50:04	\N	\N	2	\N	\N
8905	655	9584	2017-05-01	15:50:04	\N	\N	2	\N	\N
8906	655	9585	2017-05-01	15:50:04	\N	\N	2	\N	\N
8907	655	9586	2017-05-01	15:50:04	\N	\N	2	\N	\N
8908	655	9587	2017-05-01	15:50:04	\N	\N	2	\N	\N
8909	655	9588	2017-05-01	15:50:04	\N	\N	2	\N	\N
8910	655	9589	2017-05-01	15:50:04	\N	\N	2	\N	\N
8911	655	9590	2017-05-01	15:50:04	\N	\N	2	\N	\N
8912	655	9591	2017-05-01	15:50:04	\N	\N	2	\N	\N
8913	655	9592	2017-05-01	15:50:04	\N	\N	2	\N	\N
8914	655	9593	2017-05-01	15:50:04	\N	\N	2	\N	\N
8915	655	9594	2017-05-01	15:50:04	\N	\N	2	\N	\N
8916	655	9595	2017-05-01	15:50:04	\N	\N	2	\N	\N
8917	655	9596	2017-05-01	15:50:04	\N	\N	2	\N	\N
8918	655	9597	2017-05-01	15:50:04	\N	\N	2	\N	\N
8919	655	9598	2017-05-01	15:50:04	\N	\N	2	\N	\N
8920	655	9599	2017-05-01	15:50:04	\N	\N	2	\N	\N
8921	655	9600	2017-05-01	15:50:04	\N	\N	2	\N	\N
8922	655	9601	2017-05-01	15:50:04	\N	\N	2	\N	\N
8923	655	9602	2017-05-01	15:50:04	\N	\N	2	\N	\N
8924	655	9603	2017-05-01	15:50:04	\N	\N	2	\N	\N
8925	655	9604	2017-05-01	15:50:04	\N	\N	2	\N	\N
8926	655	9605	2017-05-01	15:50:04	\N	\N	2	\N	\N
8927	655	9606	2017-05-01	15:50:04	\N	\N	2	\N	\N
8928	655	9607	2017-05-01	15:50:04	\N	\N	2	\N	\N
8929	655	9608	2017-05-01	15:50:04	\N	\N	2	\N	\N
8930	655	9609	2017-05-01	15:50:04	\N	\N	2	\N	\N
8931	655	9610	2017-05-01	15:50:04	\N	\N	2	\N	\N
8932	655	9611	2017-05-01	15:50:04	\N	\N	2	\N	\N
8933	655	9612	2017-05-01	15:50:04	\N	\N	2	\N	\N
8934	655	9613	2017-05-01	15:50:04	\N	\N	2	\N	\N
8935	655	9614	2017-05-01	15:50:04	\N	\N	2	\N	\N
8936	655	9615	2017-05-01	15:50:04	\N	\N	2	\N	\N
8937	655	9616	2017-05-01	15:50:04	\N	\N	2	\N	\N
8938	655	9617	2017-05-01	15:50:04	\N	\N	2	\N	\N
8939	655	9618	2017-05-01	15:50:04	\N	\N	2	\N	\N
8940	655	9619	2017-05-01	15:50:04	\N	\N	2	\N	\N
8941	655	9620	2017-05-01	15:50:04	\N	\N	2	\N	\N
8942	655	9621	2017-05-01	15:50:04	\N	\N	2	\N	\N
8943	655	9622	2017-05-01	15:50:04	\N	\N	2	\N	\N
8944	655	9623	2017-05-01	15:50:04	\N	\N	2	\N	\N
8945	655	9624	2017-05-01	15:50:04	\N	\N	2	\N	\N
8946	655	9625	2017-05-01	15:50:04	\N	\N	2	\N	\N
8947	655	9626	2017-05-01	15:50:04	\N	\N	2	\N	\N
8948	655	9627	2017-05-01	15:50:04	\N	\N	2	\N	\N
8949	655	9628	2017-05-01	15:50:04	\N	\N	2	\N	\N
8950	655	9629	2017-05-01	15:50:04	\N	\N	2	\N	\N
8951	655	9630	2017-05-01	15:50:04	\N	\N	2	\N	\N
8952	655	9631	2017-05-01	15:50:04	\N	\N	2	\N	\N
8953	655	9632	2017-05-01	15:50:04	\N	\N	2	\N	\N
8954	655	9633	2017-05-01	15:50:04	\N	\N	2	\N	\N
8955	655	9634	2017-05-01	15:50:04	\N	\N	2	\N	\N
8956	655	9635	2017-05-01	15:50:04	\N	\N	2	\N	\N
8957	655	9636	2017-05-01	15:50:04	\N	\N	1	\N	\N
8958	657	9639	2017-05-01	15:50:04	\N	\N	2	\N	\N
8959	657	9640	2017-05-01	15:50:04	\N	\N	2	\N	\N
8960	657	9641	2017-05-01	15:50:04	\N	\N	2	\N	\N
8961	657	9642	2017-05-01	15:50:04	\N	\N	2	\N	\N
8962	657	9643	2017-05-01	15:50:04	\N	\N	2	\N	\N
8963	657	9644	2017-05-01	15:50:04	\N	\N	2	\N	\N
8964	657	9645	2017-05-01	15:50:04	\N	\N	2	\N	\N
8965	657	9646	2017-05-01	15:50:04	\N	\N	2	\N	\N
8966	657	9647	2017-05-01	15:50:04	\N	\N	2	\N	\N
8967	657	9648	2017-05-01	15:50:04	\N	\N	2	\N	\N
8968	657	9649	2017-05-01	15:50:04	\N	\N	2	\N	\N
8969	657	9650	2017-05-01	15:50:04	\N	\N	2	\N	\N
8970	657	9651	2017-05-01	15:50:04	\N	\N	2	\N	\N
8971	657	9652	2017-05-01	15:50:04	\N	\N	2	\N	\N
8972	657	9653	2017-05-01	15:50:04	\N	\N	2	\N	\N
8973	657	9654	2017-05-01	15:50:04	\N	\N	2	\N	\N
8974	657	9655	2017-05-01	15:50:04	\N	\N	2	\N	\N
8975	657	9656	2017-05-01	15:50:04	\N	\N	2	\N	\N
8976	657	9657	2017-05-01	15:50:04	\N	\N	2	\N	\N
8977	657	9658	2017-05-01	15:50:04	\N	\N	2	\N	\N
8978	657	9659	2017-05-01	15:50:04	\N	\N	2	\N	\N
8979	657	9660	2017-05-01	15:50:04	\N	\N	2	\N	\N
8980	657	9661	2017-05-01	15:50:04	\N	\N	2	\N	\N
8981	657	9662	2017-05-01	15:50:04	\N	\N	2	\N	\N
8982	657	9663	2017-05-01	15:50:04	\N	\N	2	\N	\N
8983	657	9664	2017-05-01	15:50:04	\N	\N	2	\N	\N
8984	657	9665	2017-05-01	15:50:04	\N	\N	2	\N	\N
8985	658	9667	2017-05-01	15:50:04	\N	\N	2	\N	\N
8986	658	9668	2017-05-01	15:50:04	\N	\N	2	\N	\N
8987	658	9669	2017-05-01	15:50:04	\N	\N	2	\N	\N
8988	658	9670	2017-05-01	15:50:04	\N	\N	2	\N	\N
8989	658	9671	2017-05-01	15:50:04	\N	\N	2	\N	\N
8990	658	9672	2017-05-01	15:50:04	\N	\N	2	\N	\N
8991	658	9673	2017-05-01	15:50:04	\N	\N	2	\N	\N
8992	658	9674	2017-05-01	15:51:04	\N	\N	2	\N	\N
8993	658	9675	2017-05-01	15:51:04	\N	\N	2	\N	\N
8994	658	9676	2017-05-01	15:51:04	\N	\N	2	\N	\N
8995	658	9677	2017-05-01	15:51:04	\N	\N	2	\N	\N
8996	659	9679	2017-05-01	15:51:04	\N	\N	2	\N	\N
8997	659	9680	2017-05-01	15:51:04	\N	\N	2	\N	\N
8998	659	9681	2017-05-01	15:51:04	\N	\N	2	\N	\N
8999	659	9682	2017-05-01	15:51:04	\N	\N	2	\N	\N
9000	659	9683	2017-05-01	15:51:04	\N	\N	2	\N	\N
9001	659	9684	2017-05-01	15:51:04	\N	\N	2	\N	\N
9002	659	9685	2017-05-01	15:51:04	\N	\N	2	\N	\N
9003	659	9686	2017-05-01	15:51:04	\N	\N	2	\N	\N
9004	659	9687	2017-05-01	15:51:04	\N	\N	2	\N	\N
9005	659	9688	2017-05-01	15:51:04	\N	\N	2	\N	\N
9006	659	9689	2017-05-01	15:51:04	\N	\N	2	\N	\N
9007	659	9690	2017-05-01	15:51:04	\N	\N	2	\N	\N
9008	659	9691	2017-05-01	15:51:04	\N	\N	2	\N	\N
9009	659	9692	2017-05-01	15:51:04	\N	\N	2	\N	\N
9010	659	9693	2017-05-01	15:51:04	\N	\N	2	\N	\N
9011	659	9694	2017-05-01	15:51:04	\N	\N	2	\N	\N
9012	659	9695	2017-05-01	15:51:04	\N	\N	2	\N	\N
9013	661	9698	2017-05-01	15:51:04	\N	\N	3	\N	\N
9014	661	9699	2017-05-01	15:51:04	\N	\N	3	\N	\N
9015	661	9700	2017-05-01	15:51:04	\N	\N	2	\N	\N
9016	661	9701	2017-05-01	15:51:04	\N	\N	2	\N	\N
9017	661	9702	2017-05-01	15:51:04	\N	\N	2	\N	\N
9018	661	9703	2017-05-01	15:51:04	\N	\N	2	\N	\N
9019	661	9704	2017-05-01	15:51:04	\N	\N	2	\N	\N
9020	661	9705	2017-05-01	15:51:04	\N	\N	2	\N	\N
9021	661	9706	2017-05-01	15:51:04	\N	\N	2	\N	\N
9022	661	9707	2017-05-01	15:51:04	\N	\N	2	\N	\N
9023	661	9708	2017-05-01	15:51:04	\N	\N	2	\N	\N
9024	661	9709	2017-05-01	15:51:04	\N	\N	2	\N	\N
9025	661	9710	2017-05-01	15:51:04	\N	\N	2	\N	\N
9026	661	9711	2017-05-01	15:51:04	\N	\N	2	\N	\N
9027	661	9712	2017-05-01	15:51:04	\N	\N	2	\N	\N
9028	661	9713	2017-05-01	15:51:04	\N	\N	2	\N	\N
9029	661	9714	2017-05-01	15:51:04	\N	\N	2	\N	\N
9030	661	9715	2017-05-01	15:51:04	\N	\N	2	\N	\N
9031	661	9716	2017-05-01	15:51:04	\N	\N	2	\N	\N
9032	661	9717	2017-05-01	15:51:04	\N	\N	2	\N	\N
9033	661	9718	2017-05-01	15:51:04	\N	\N	2	\N	\N
9034	661	9719	2017-05-01	15:51:04	\N	\N	2	\N	\N
9035	661	9720	2017-05-01	15:51:04	\N	\N	2	\N	\N
9036	661	9721	2017-05-01	15:51:04	\N	\N	2	\N	\N
9037	661	9722	2017-05-01	15:51:04	\N	\N	2	\N	\N
9038	661	9723	2017-05-01	15:51:04	\N	\N	2	\N	\N
9039	661	9724	2017-05-01	15:51:04	\N	\N	2	\N	\N
9040	661	9725	2017-05-01	15:51:04	\N	\N	2	\N	\N
9041	661	9726	2017-05-01	15:51:04	\N	\N	2	\N	\N
9042	661	9727	2017-05-01	15:51:04	\N	\N	2	\N	\N
9043	661	9728	2017-05-01	15:51:04	\N	\N	2	\N	\N
9044	661	9729	2017-05-01	15:51:04	\N	\N	2	\N	\N
9045	661	9730	2017-05-01	15:51:04	\N	\N	2	\N	\N
9046	661	9731	2017-05-01	15:51:04	\N	\N	2	\N	\N
9047	661	9732	2017-05-01	15:51:04	\N	\N	2	\N	\N
9048	661	9733	2017-05-01	15:51:04	\N	\N	2	\N	\N
9049	661	9734	2017-05-01	15:51:04	\N	\N	2	\N	\N
9050	661	9735	2017-05-01	15:51:04	\N	\N	2	\N	\N
9051	661	9736	2017-05-01	15:51:04	\N	\N	2	\N	\N
9052	661	9737	2017-05-01	15:51:04	\N	\N	2	\N	\N
9053	661	9738	2017-05-01	15:51:04	\N	\N	2	\N	\N
9054	661	9739	2017-05-01	15:51:04	\N	\N	2	\N	\N
9055	661	9740	2017-05-01	15:51:04	\N	\N	2	\N	\N
9056	661	9741	2017-05-01	15:51:04	\N	\N	2	\N	\N
9057	661	9742	2017-05-01	15:51:04	\N	\N	2	\N	\N
9058	661	9743	2017-05-01	15:51:04	\N	\N	2	\N	\N
9059	661	9744	2017-05-01	15:51:04	\N	\N	2	\N	\N
9060	661	9745	2017-05-01	15:51:04	\N	\N	2	\N	\N
9061	661	9746	2017-05-01	15:51:04	\N	\N	2	\N	\N
9062	661	9747	2017-05-01	15:51:04	\N	\N	1	\N	\N
9063	661	9748	2017-05-01	15:51:04	\N	\N	1	\N	\N
9064	661	9749	2017-05-01	15:51:04	\N	\N	1	\N	\N
9065	666	9755	2017-05-01	15:51:04	\N	\N	3	\N	\N
9066	666	9756	2017-05-01	15:51:04	\N	\N	3	\N	\N
9067	666	9757	2017-05-01	15:51:04	\N	\N	3	\N	\N
9068	666	9758	2017-05-01	15:51:04	\N	\N	3	\N	\N
9069	666	9759	2017-05-01	15:51:04	\N	\N	2	\N	\N
9070	666	9760	2017-05-01	15:51:04	\N	\N	2	\N	\N
9071	666	9761	2017-05-01	15:51:04	\N	\N	2	\N	\N
9072	666	9762	2017-05-01	15:51:04	\N	\N	3	\N	\N
9073	666	9763	2017-05-01	15:51:04	\N	\N	2	\N	\N
9074	666	9764	2017-05-01	15:51:04	\N	\N	2	\N	\N
9075	666	9765	2017-05-01	15:51:04	\N	\N	2	\N	\N
9076	666	9766	2017-05-01	15:51:04	\N	\N	2	\N	\N
9077	666	9767	2017-05-01	15:51:04	\N	\N	2	\N	\N
9078	666	9768	2017-05-01	15:51:04	\N	\N	2	\N	\N
9079	666	9769	2017-05-01	15:51:04	\N	\N	2	\N	\N
9080	666	9770	2017-05-01	15:51:04	\N	\N	2	\N	\N
9081	666	9771	2017-05-01	15:51:04	\N	\N	2	\N	\N
9082	666	9772	2017-05-01	15:51:04	\N	\N	2	\N	\N
9083	666	9773	2017-05-01	15:51:04	\N	\N	2	\N	\N
9084	666	9774	2017-05-01	15:51:04	\N	\N	2	\N	\N
9085	666	9775	2017-05-01	15:51:04	\N	\N	2	\N	\N
9086	666	9776	2017-05-01	15:51:04	\N	\N	2	\N	\N
9087	666	9777	2017-05-01	15:51:04	\N	\N	3	\N	\N
9088	666	9778	2017-05-01	15:51:04	\N	\N	3	\N	\N
9089	666	9779	2017-05-01	15:51:04	\N	\N	3	\N	\N
9090	666	9780	2017-05-01	15:51:04	\N	\N	3	\N	\N
9091	666	9781	2017-05-01	15:51:04	\N	\N	3	\N	\N
9092	666	9782	2017-05-01	15:51:04	\N	\N	2	\N	\N
9093	666	9783	2017-05-01	15:51:04	\N	\N	2	\N	\N
9094	666	9784	2017-05-01	15:51:04	\N	\N	2	\N	\N
9095	666	9785	2017-05-01	15:51:04	\N	\N	2	\N	\N
9096	666	9786	2017-05-01	15:51:04	\N	\N	2	\N	\N
9097	666	9787	2017-05-01	15:51:04	\N	\N	2	\N	\N
9098	666	9788	2017-05-01	15:51:04	\N	\N	2	\N	\N
9099	666	9789	2017-05-01	15:51:04	\N	\N	2	\N	\N
9100	666	9790	2017-05-01	15:51:04	\N	\N	3	\N	\N
9101	666	9791	2017-05-01	15:51:04	\N	\N	2	\N	\N
9102	668	9794	2017-05-01	15:52:04	\N	\N	3	\N	\N
9103	668	9795	2017-05-01	15:52:04	\N	\N	2	\N	\N
9104	668	9796	2017-05-01	15:52:04	\N	\N	2	\N	\N
9105	668	9797	2017-05-01	15:52:04	\N	\N	2	\N	\N
9106	668	9798	2017-05-01	15:52:04	\N	\N	2	\N	\N
9107	668	9799	2017-05-01	15:52:04	\N	\N	2	\N	\N
9108	668	9800	2017-05-01	15:52:04	\N	\N	2	\N	\N
9109	668	9801	2017-05-01	15:52:04	\N	\N	2	\N	\N
9110	668	9802	2017-05-01	15:52:04	\N	\N	2	\N	\N
9111	668	9803	2017-05-01	15:52:04	\N	\N	2	\N	\N
9112	668	9804	2017-05-01	15:52:04	\N	\N	2	\N	\N
9113	668	9805	2017-05-01	15:52:04	\N	\N	2	\N	\N
9114	668	9806	2017-05-01	15:52:04	\N	\N	2	\N	\N
9115	668	9807	2017-05-01	15:52:04	\N	\N	2	\N	\N
9116	668	9808	2017-05-01	15:52:04	\N	\N	2	\N	\N
9117	668	9809	2017-05-01	15:52:04	\N	\N	2	\N	\N
9118	668	9810	2017-05-01	15:52:04	\N	\N	2	\N	\N
9119	668	9811	2017-05-01	15:52:04	\N	\N	2	\N	\N
9120	668	9812	2017-05-01	15:52:04	\N	\N	2	\N	\N
9121	669	9815	2017-05-01	15:52:04	\N	\N	3	\N	\N
9122	669	9816	2017-05-01	15:52:04	\N	\N	3	\N	\N
9123	669	9817	2017-05-01	15:52:04	\N	\N	3	\N	\N
9124	669	9818	2017-05-01	15:52:04	\N	\N	2	\N	\N
9125	669	9819	2017-05-01	15:52:04	\N	\N	2	\N	\N
9126	669	9820	2017-05-01	15:52:04	\N	\N	2	\N	\N
9127	669	9821	2017-05-01	15:52:04	\N	\N	2	\N	\N
9128	669	9822	2017-05-01	15:52:04	\N	\N	2	\N	\N
9129	669	9823	2017-05-01	15:52:04	\N	\N	2	\N	\N
9130	669	9824	2017-05-01	15:52:04	\N	\N	2	\N	\N
9131	669	9825	2017-05-01	15:52:04	\N	\N	2	\N	\N
9132	669	9826	2017-05-01	15:52:04	\N	\N	2	\N	\N
9133	669	9827	2017-05-01	15:52:04	\N	\N	2	\N	\N
9134	669	9828	2017-05-01	15:52:04	\N	\N	2	\N	\N
9135	669	9829	2017-05-01	15:52:04	\N	\N	2	\N	\N
9136	669	9830	2017-05-01	15:52:04	\N	\N	2	\N	\N
9137	669	9831	2017-05-01	15:52:04	\N	\N	2	\N	\N
9138	669	9832	2017-05-01	15:52:04	\N	\N	2	\N	\N
9139	669	9833	2017-05-01	15:52:04	\N	\N	2	\N	\N
9140	669	9834	2017-05-01	15:52:04	\N	\N	2	\N	\N
9141	669	9835	2017-05-01	15:52:04	\N	\N	2	\N	\N
9142	669	9836	2017-05-01	15:52:04	\N	\N	2	\N	\N
9143	669	9837	2017-05-01	15:52:04	\N	\N	2	\N	\N
9144	669	9838	2017-05-01	15:52:04	\N	\N	2	\N	\N
9145	669	9839	2017-05-01	15:52:04	\N	\N	2	\N	\N
9146	669	9840	2017-05-01	15:52:04	\N	\N	2	\N	\N
9147	669	9841	2017-05-01	15:52:04	\N	\N	2	\N	\N
9148	669	9842	2017-05-01	15:52:04	\N	\N	2	\N	\N
9149	669	9843	2017-05-01	15:52:04	\N	\N	2	\N	\N
9150	669	9844	2017-05-01	15:52:04	\N	\N	2	\N	\N
9151	669	9845	2017-05-01	15:52:04	\N	\N	2	\N	\N
9152	669	9846	2017-05-01	15:52:04	\N	\N	2	\N	\N
9153	669	9847	2017-05-01	15:52:04	\N	\N	2	\N	\N
9154	669	9848	2017-05-01	15:52:04	\N	\N	2	\N	\N
9155	669	9849	2017-05-01	15:52:04	\N	\N	2	\N	\N
9156	669	9850	2017-05-01	15:52:04	\N	\N	2	\N	\N
9157	669	9851	2017-05-01	15:52:04	\N	\N	2	\N	\N
9158	669	9852	2017-05-01	15:52:04	\N	\N	2	\N	\N
9159	669	9853	2017-05-01	15:52:04	\N	\N	2	\N	\N
9160	669	9854	2017-05-01	15:52:04	\N	\N	2	\N	\N
9161	669	9855	2017-05-01	15:52:04	\N	\N	2	\N	\N
9162	669	9856	2017-05-01	15:52:04	\N	\N	2	\N	\N
9163	669	9857	2017-05-01	15:52:04	\N	\N	2	\N	\N
9164	669	9858	2017-05-01	15:52:04	\N	\N	2	\N	\N
9165	669	9859	2017-05-01	15:52:04	\N	\N	2	\N	\N
9166	669	9860	2017-05-01	15:52:04	\N	\N	2	\N	\N
9167	669	9861	2017-05-01	15:52:04	\N	\N	2	\N	\N
9168	669	9862	2017-05-01	15:52:04	\N	\N	2	\N	\N
9169	669	9863	2017-05-01	15:52:04	\N	\N	2	\N	\N
9170	669	9864	2017-05-01	15:52:04	\N	\N	2	\N	\N
9171	669	9865	2017-05-01	15:52:04	\N	\N	2	\N	\N
9172	669	9866	2017-05-01	15:52:04	\N	\N	2	\N	\N
9173	669	9867	2017-05-01	15:52:04	\N	\N	2	\N	\N
9174	669	9868	2017-05-01	15:52:04	\N	\N	2	\N	\N
9175	669	9869	2017-05-01	15:52:04	\N	\N	2	\N	\N
9176	669	9870	2017-05-01	15:52:04	\N	\N	2	\N	\N
9177	669	9871	2017-05-01	15:52:04	\N	\N	2	\N	\N
9178	669	9872	2017-05-01	15:52:04	\N	\N	2	\N	\N
9179	669	9873	2017-05-01	15:52:04	\N	\N	2	\N	\N
9180	669	9874	2017-05-01	15:52:04	\N	\N	2	\N	\N
9181	669	9875	2017-05-01	15:52:04	\N	\N	2	\N	\N
9182	670	9877	2017-05-01	15:52:04	\N	\N	3	\N	\N
9183	670	9878	2017-05-01	15:52:04	\N	\N	2	\N	\N
9184	670	9879	2017-05-01	15:52:04	\N	\N	2	\N	\N
9185	670	9880	2017-05-01	15:52:04	\N	\N	2	\N	\N
9186	670	9881	2017-05-01	15:52:04	\N	\N	2	\N	\N
9187	670	9882	2017-05-01	15:52:04	\N	\N	2	\N	\N
9188	670	9883	2017-05-01	15:52:04	\N	\N	2	\N	\N
9189	670	9884	2017-05-01	15:52:04	\N	\N	2	\N	\N
9190	670	9885	2017-05-01	15:52:04	\N	\N	2	\N	\N
9191	670	9886	2017-05-01	15:52:04	\N	\N	2	\N	\N
9192	670	9887	2017-05-01	15:52:04	\N	\N	2	\N	\N
9193	670	9888	2017-05-01	15:52:04	\N	\N	2	\N	\N
9194	670	9889	2017-05-01	15:52:04	\N	\N	2	\N	\N
9195	670	9890	2017-05-01	15:52:04	\N	\N	2	\N	\N
9196	670	9891	2017-05-01	15:52:04	\N	\N	2	\N	\N
9197	670	9892	2017-05-01	15:52:04	\N	\N	2	\N	\N
9198	670	9893	2017-05-01	15:52:04	\N	\N	2	\N	\N
9199	670	9894	2017-05-01	15:52:04	\N	\N	2	\N	\N
9200	670	9895	2017-05-01	15:52:04	\N	\N	2	\N	\N
9201	670	9896	2017-05-01	15:52:04	\N	\N	2	\N	\N
\.


--
-- TOC entry 2656 (class 0 OID 0)
-- Dependencies: 190
-- Name: grupo_pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_pessoa_id_seq', 9226, true);


--
-- TOC entry 2337 (class 0 OID 16721)
-- Dependencies: 193
-- Data for Name: grupo_pessoa_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_pessoa_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	VISITOR	2016-05-02	11:29:45.44539	\N	\N
2	CONSOLIDATION	2016-05-02	11:29:45.44539	\N	\N
3	MEMBER	2016-05-02	11:29:45.44539	\N	\N
\.


--
-- TOC entry 2657 (class 0 OID 0)
-- Dependencies: 192
-- Name: grupo_pessoa_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_pessoa_tipo_id_seq', 2, true);


--
-- TOC entry 2317 (class 0 OID 16432)
-- Dependencies: 173
-- Data for Name: grupo_responsavel; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_responsavel (id, data_criacao, hora_criacao, pessoa_id, grupo_id, data_inativacao, hora_inativacao) FROM stdin;
2	2017-01-17	10:50:59.179405	1	2	\N	\N
3	2017-01-17	10:51:02.227292	1	3	\N	\N
4	2017-01-17	10:51:05.979821	1	4	\N	\N
5	2017-01-17	10:51:09.411377	1	5	\N	\N
6	2017-01-17	10:51:13.260057	1	6	\N	\N
7	2017-01-17	10:51:16.507269	1	7	\N	\N
1	2017-01-16	14:26:19.252343	1	1	\N	\N
576	2017-05-01	15:34:04	7102	515	\N	\N
577	2017-05-01	15:34:04	7103	515	\N	\N
578	2017-05-01	15:34:04	7104	516	\N	\N
579	2017-05-01	15:34:04	7105	516	\N	\N
580	2017-05-01	15:34:04	7117	517	\N	\N
581	2017-05-01	15:34:04	7118	517	\N	\N
582	2017-05-01	15:34:04	7208	518	\N	\N
583	2017-05-01	15:34:04	7269	519	\N	\N
584	2017-05-01	15:34:04	7286	520	\N	\N
585	2017-05-01	15:34:04	7287	521	\N	\N
586	2017-05-01	15:34:04	7290	522	\N	\N
587	2017-05-01	15:35:04	7308	523	\N	\N
588	2017-05-01	15:35:04	7309	524	\N	\N
589	2017-05-01	15:35:04	7320	525	\N	\N
590	2017-05-01	15:35:04	7334	526	\N	\N
591	2017-05-01	15:35:04	7394	527	\N	\N
592	2017-05-01	15:35:04	7412	528	\N	\N
593	2017-05-01	15:35:04	7413	528	\N	\N
594	2017-05-01	15:35:04	7428	529	\N	\N
595	2017-05-01	15:36:04	7490	530	\N	\N
596	2017-05-01	15:36:04	7491	531	\N	\N
597	2017-05-01	15:36:04	7494	532	\N	\N
598	2017-05-01	15:36:04	7495	533	\N	\N
599	2017-05-01	15:36:04	7496	534	\N	\N
600	2017-05-01	15:36:04	7497	535	\N	\N
601	2017-05-01	15:36:04	7507	536	\N	\N
602	2017-05-01	15:36:04	7508	536	\N	\N
603	2017-05-01	15:36:04	7531	537	\N	\N
604	2017-05-01	15:36:04	7541	538	\N	\N
605	2017-05-01	15:36:04	7542	539	\N	\N
606	2017-05-01	15:36:04	7543	540	\N	\N
607	2017-05-01	15:36:04	7544	541	\N	\N
608	2017-05-01	15:36:04	7567	542	\N	\N
609	2017-05-01	15:37:04	7568	543	\N	\N
610	2017-05-01	15:37:04	7599	544	\N	\N
611	2017-05-01	15:37:04	7609	545	\N	\N
612	2017-05-01	15:37:04	7632	546	\N	\N
613	2017-05-01	15:37:04	7641	547	\N	\N
614	2017-05-01	15:37:04	7660	548	\N	\N
615	2017-05-01	15:37:04	7661	549	\N	\N
616	2017-05-01	15:37:04	7683	550	\N	\N
617	2017-05-01	15:38:04	7711	551	\N	\N
618	2017-05-01	15:38:04	7712	551	\N	\N
619	2017-05-01	15:38:04	7744	552	\N	\N
620	2017-05-01	15:38:04	7805	553	\N	\N
621	2017-05-01	15:38:04	7811	554	\N	\N
622	2017-05-01	15:38:04	7812	555	\N	\N
623	2017-05-01	15:38:04	7813	555	\N	\N
624	2017-05-01	15:38:04	7822	556	\N	\N
625	2017-05-01	15:38:04	7823	556	\N	\N
626	2017-05-01	15:38:04	7870	557	\N	\N
627	2017-05-01	15:39:04	7918	558	\N	\N
628	2017-05-01	15:39:04	7952	559	\N	\N
629	2017-05-01	15:39:04	8016	560	\N	\N
630	2017-05-01	15:39:04	8017	561	\N	\N
631	2017-05-01	15:39:04	8043	562	\N	\N
632	2017-05-01	15:39:04	8061	563	\N	\N
633	2017-05-01	15:40:04	8081	564	\N	\N
634	2017-05-01	15:40:04	8087	565	\N	\N
635	2017-05-01	15:40:04	8099	566	\N	\N
636	2017-05-01	15:40:04	8158	567	\N	\N
637	2017-05-01	15:40:04	8159	568	\N	\N
638	2017-05-01	15:40:04	8187	569	\N	\N
639	2017-05-01	15:40:04	8188	570	\N	\N
640	2017-05-01	15:40:04	8189	571	\N	\N
641	2017-05-01	15:40:04	8213	572	\N	\N
642	2017-05-01	15:40:04	8214	572	\N	\N
643	2017-05-01	15:41:04	8280	573	\N	\N
644	2017-05-01	15:41:04	8281	574	\N	\N
645	2017-05-01	15:41:04	8282	575	\N	\N
646	2017-05-01	15:41:04	8294	576	\N	\N
647	2017-05-01	15:41:04	8306	577	\N	\N
648	2017-05-01	15:41:04	8320	578	\N	\N
649	2017-05-01	15:41:04	8321	578	\N	\N
650	2017-05-01	15:41:04	8380	579	\N	\N
651	2017-05-01	15:41:04	8397	580	\N	\N
652	2017-05-01	15:42:04	8452	581	\N	\N
653	2017-05-01	15:42:04	8453	582	\N	\N
654	2017-05-01	15:42:04	8454	583	\N	\N
655	2017-05-01	15:42:04	8455	584	\N	\N
656	2017-05-01	15:42:04	8456	585	\N	\N
657	2017-05-01	15:42:04	8457	586	\N	\N
658	2017-05-01	15:42:04	8504	587	\N	\N
659	2017-05-01	15:42:04	8542	588	\N	\N
660	2017-05-01	15:42:04	8543	589	\N	\N
661	2017-05-01	15:43:04	8570	590	\N	\N
662	2017-05-01	15:43:04	8571	591	\N	\N
663	2017-05-01	15:43:04	8584	592	\N	\N
664	2017-05-01	15:43:04	8585	592	\N	\N
665	2017-05-01	15:43:04	8593	593	\N	\N
666	2017-05-01	15:43:04	8594	593	\N	\N
667	2017-05-01	15:43:04	8655	594	\N	\N
668	2017-05-01	15:43:04	8668	595	\N	\N
669	2017-05-01	15:43:04	8697	596	\N	\N
670	2017-05-01	15:43:04	8698	596	\N	\N
671	2017-05-01	15:43:04	8702	597	\N	\N
672	2017-05-01	15:44:04	8755	598	\N	\N
673	2017-05-01	15:44:04	8757	599	\N	\N
674	2017-05-01	15:44:04	8760	600	\N	\N
675	2017-05-01	15:44:04	8822	601	\N	\N
676	2017-05-01	15:44:04	8856	602	\N	\N
677	2017-05-01	15:44:04	8857	603	\N	\N
678	2017-05-01	15:44:04	8858	604	\N	\N
679	2017-05-01	15:44:04	8859	605	\N	\N
680	2017-05-01	15:44:04	8860	606	\N	\N
681	2017-05-01	15:45:04	8861	607	\N	\N
682	2017-05-01	15:45:04	8862	608	\N	\N
683	2017-05-01	15:45:04	8863	609	\N	\N
684	2017-05-01	15:45:04	8864	610	\N	\N
685	2017-05-01	15:45:04	8865	611	\N	\N
686	2017-05-01	15:45:04	8866	611	\N	\N
687	2017-05-01	15:45:04	8927	612	\N	\N
688	2017-05-01	15:45:04	8928	613	\N	\N
689	2017-05-01	15:45:04	8929	614	\N	\N
690	2017-05-01	15:45:04	8930	615	\N	\N
691	2017-05-01	15:45:04	8931	615	\N	\N
692	2017-05-01	15:46:04	8998	616	\N	\N
693	2017-05-01	15:46:04	9007	617	\N	\N
694	2017-05-01	15:46:04	9051	618	\N	\N
695	2017-05-01	15:46:04	9052	619	\N	\N
696	2017-05-01	15:46:04	9054	620	\N	\N
697	2017-05-01	15:46:04	9055	621	\N	\N
698	2017-05-01	15:46:04	9056	622	\N	\N
699	2017-05-01	15:46:04	9057	623	\N	\N
700	2017-05-01	15:46:04	9112	624	\N	\N
701	2017-05-01	15:47:04	9127	625	\N	\N
702	2017-05-01	15:47:04	9154	626	\N	\N
703	2017-05-01	15:47:04	9214	627	\N	\N
704	2017-05-01	15:47:04	9239	628	\N	\N
705	2017-05-01	15:47:04	9294	629	\N	\N
706	2017-05-01	15:47:04	9295	630	\N	\N
707	2017-05-01	15:48:04	9307	631	\N	\N
708	2017-05-01	15:48:04	9365	632	\N	\N
709	2017-05-01	15:48:04	9377	633	\N	\N
710	2017-05-01	15:48:04	9379	634	\N	\N
711	2017-05-01	15:48:04	9388	635	\N	\N
712	2017-05-01	15:48:04	9389	636	\N	\N
713	2017-05-01	15:48:04	9390	637	\N	\N
714	2017-05-01	15:48:04	9451	638	\N	\N
715	2017-05-01	15:49:04	9458	639	\N	\N
716	2017-05-01	15:49:04	9459	640	\N	\N
717	2017-05-01	15:49:04	9469	641	\N	\N
718	2017-05-01	15:49:04	9475	642	\N	\N
719	2017-05-01	15:49:04	9476	642	\N	\N
720	2017-05-01	15:49:04	9482	643	\N	\N
721	2017-05-01	15:49:04	9491	644	\N	\N
722	2017-05-01	15:49:04	9495	645	\N	\N
723	2017-05-01	15:49:04	9496	645	\N	\N
724	2017-05-01	15:49:04	9499	646	\N	\N
725	2017-05-01	15:49:04	9519	647	\N	\N
726	2017-05-01	15:49:04	9520	648	\N	\N
727	2017-05-01	15:49:04	9521	648	\N	\N
728	2017-05-01	15:50:04	9574	649	\N	\N
729	2017-05-01	15:50:04	9575	650	\N	\N
730	2017-05-01	15:50:04	9576	651	\N	\N
731	2017-05-01	15:50:04	9577	652	\N	\N
732	2017-05-01	15:50:04	9578	653	\N	\N
733	2017-05-01	15:50:04	9579	654	\N	\N
734	2017-05-01	15:50:04	9580	655	\N	\N
735	2017-05-01	15:50:04	9637	656	\N	\N
736	2017-05-01	15:50:04	9638	657	\N	\N
737	2017-05-01	15:50:04	9666	658	\N	\N
738	2017-05-01	15:51:04	9678	659	\N	\N
739	2017-05-01	15:51:04	9696	660	\N	\N
740	2017-05-01	15:51:04	9697	661	\N	\N
741	2017-05-01	15:51:04	9750	662	\N	\N
742	2017-05-01	15:51:04	9751	663	\N	\N
743	2017-05-01	15:51:04	9752	664	\N	\N
744	2017-05-01	15:51:04	9753	665	\N	\N
745	2017-05-01	15:51:04	9754	666	\N	\N
746	2017-05-01	15:51:04	9792	667	\N	\N
747	2017-05-01	15:52:04	9793	668	\N	\N
748	2017-05-01	15:52:04	9813	669	\N	\N
749	2017-05-01	15:52:04	9814	669	\N	\N
750	2017-05-01	15:52:04	9876	670	\N	\N
751	2017-05-01	15:52:04	9897	671	\N	\N
752	2017-05-01	15:52:04	9898	672	\N	\N
\.


--
-- TOC entry 2658 (class 0 OID 0)
-- Dependencies: 172
-- Name: grupo_responsavel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_responsavel_id_seq', 752, true);


--
-- TOC entry 2351 (class 0 OID 16944)
-- Dependencies: 207
-- Data for Name: hierarquia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY hierarquia (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	BISPO	2016-11-03	11:56:33.903698	\N	\N
4	DIACONO	2016-11-03	11:56:33.903698	\N	\N
5	OBREIRO	2016-11-03	11:56:33.903698	\N	\N
6	LIDER DE CELULA	2016-11-28	09:44:40.217473	\N	\N
2	PASTOR	2016-11-03	11:56:33.903698	\N	\N
3	MISSIONARIO	2016-11-03	11:56:33.903698	\N	\N
\.


--
-- TOC entry 2659 (class 0 OID 0)
-- Dependencies: 206
-- Name: hierarquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('hierarquia_id_seq', 6, true);


--
-- TOC entry 2315 (class 0 OID 16388)
-- Dependencies: 171
-- Data for Name: pessoa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pessoa (id, nome, email, senha, data_criacao, data_inativacao, documento, data_nascimento, token, token_data, token_hora, hora_criacao, hora_inativacao, telefone, foto, data_revisao, sexo, atualizar_dados) FROM stdin;
9899	IVAN DRAG QUEEN	\N	\N	2017-05-09	\N	\N	\N	\N	\N	\N	11:58:12	\N	6191776658	\N	\N	\N	S
156	URSULA ALUNO	\N	\N	2017-01-18	\N	\N	1999-01-12	\N	\N	\N	11:05:04.635366	\N	998510764	\N	\N	F	N
165	DIEGO aluno	\N	\N	2017-01-20	\N	\N	1986-10-27	\N	\N	\N	13:24:10	\N	\N	\N	\N	M	N
1	LEONARDO PEREIRA MAGALHAES	admin	46f94c8de14fb36680850768ff1b7f2a	2017-01-31	\N	\N	1990-03-07	\N	\N	\N	11:55:49	\N	\N	leo_azul.jpg	\N	M	N
9900	SEGURANçA DE BOITE GAY LOPES	\N	\N	2017-05-09	\N	\N	\N	\N	\N	\N	11:21:13	\N	61917766587	\N	\N	\N	S
9901	IVAN SHABLEIA	\N	\N	2017-05-11	\N	\N	1993-12-11	\N	\N	\N	13:38:34	\N	6191776658	\N	\N	M	S
9902	HACHUBIRACHOS	\N	\N	2017-05-11	\N	\N	\N	\N	\N	\N	15:44:41	\N	6181818181	\N	\N	\N	S
7812	LEONARDO PEREIRA MAGALHAES	falecomleonardopereira@gmail.com	a420384997c8a1a93d5a84046117c2aa	2017-05-01	\N	2829942116	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
9903	IVAN SHABLEIA	\N	\N	2017-05-12	\N	\N	1993-12-11	\N	\N	\N	12:18:20	\N	6191776658	\N	\N	M	S
9904	IVAN SHABLEIA	\N	\N	2017-05-12	\N	\N	1993-12-11	\N	\N	\N	14:26:29	\N	6191776658	\N	\N	M	S
9905	IVAN SHABLEIA	\N	\N	2017-05-12	\N	\N	1993-12-11	\N	\N	\N	14:28:31	\N	6191776658	\N	\N	M	S
9906	TESTE	\N	\N	2017-05-12	\N	\N	\N	\N	\N	\N	15:14:33	\N	1111111111	\N	\N	\N	S
9907	TIAGO VIZINHO	\N	\N	2017-05-15	\N	\N	\N	\N	\N	\N	10:32:39	\N	61986562225	\N	\N	\N	S
9908	TESTE	\N	\N	2017-05-15	\N	\N	\N	\N	\N	\N	10:06:37	\N	61998510703	\N	\N	\N	S
9909	TESTE MENSAGEM	\N	\N	2017-05-15	\N	\N	\N	\N	\N	\N	10:36:53	\N	61998510703	\N	\N	\N	S
9910	JUNO UNIVERSAL	\N	\N	2017-05-15	\N	\N	1983-12-11	\N	\N	\N	16:09:32	\N	6191776658	\N	\N	M	S
8256	MARCELO ROSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
9911	SANDRA CUNHA	\N	\N	2017-05-15	\N	\N	1997-11-02	\N	\N	\N	16:44:32	\N	61917766588	\N	\N	F	S
9912	TESTE	\N	\N	2017-05-16	\N	\N	\N	\N	\N	\N	09:48:43	\N	5665656565	\N	\N	\N	S
9913	ARTHU	\N	\N	2017-05-16	\N	\N	\N	\N	\N	\N	19:20:43	\N	6133670777	\N	\N	\N	S
9914	PESSOA TESTE	\N	\N	2017-05-17	\N	\N	\N	\N	\N	\N	11:00:10	\N	6191776658	\N	\N	\N	S
9915	FULANO TESTE 	\N	\N	2017-05-17	\N	\N	\N	\N	\N	\N	11:23:10	\N	6191776658	\N	\N	\N	S
9916	IVAN PINTUDÃO	\N	\N	2017-05-19	\N	\N	2003-06-03	\N	\N	\N	14:43:33	\N	6184848484	\N	\N	F	S
9524	ALEX GAY	\N	\N	2017-05-01	\N	\N	2006-07-06	\N	\N	\N	15:49:04	\N	78895623	\N	\N	M	S
8894	MãE DO JUNO	\N	\N	2017-05-01	\N	\N	1951-09-10	\N	\N	\N	15:45:04	\N	6185372330	\N	\N	F	S
7102	LUCAS CUNHA		1bddc1a2c3f1e97b94a50310ea94308c	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7103	PRISCILA RODOVALHO CUNHA		85862151eaed9bbc8b94373243e687cf	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7104	DIEGO ALLAN KORT	diegokort@gmail.com	765a753d7b60630be5ceb8e38870ee7b	2017-05-01	\N	35141122824	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7105	URSULA ESTEFAM ALENCAR KORT	ursulakort@hotmail.com	bee0c97fa13de1ec7a6e32af9f99b6c2	2017-05-01	\N	35456340860	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7106	ANDRESSA CHRISTINA CAETANO GARCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61986060170	\N	\N	\N	S
7107	DAVI LUCAS RODOVALHO CUNHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61996258777	\N	\N	\N	S
7108	DUDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6174010288	\N	\N	\N	S
7109	GABRIEL RODOVALHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6174010288	\N	\N	\N	S
7110	KAKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6174010288	\N	\N	\N	S
7111	MIGUEL BLOIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6174010288	\N	\N	\N	S
7112	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6174010288	\N	\N	\N	S
7113	PRISCILA HOLANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6184696360	\N	\N	\N	S
7114	SARA HOLANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6186218343	\N	\N	\N	S
7115	VINICIUS MACUS FERREIRA CAETANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61981903945	\N	\N	\N	S
7116	WESLEY WELL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6186214012	\N	\N	\N	S
7117	RAPHAEL SILVERIO DE PAULA	rsilverio2012@hotmail.com	f5bb0c8de146c67b44babbf4e6584cc0	2017-05-01	\N	1789736102	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7118	RAYANE ALMEIDA SOUSA DE PAULA	naneasousa@hotmail.com	81dc9bdb52d04dc20036dbd8313ed055	2017-05-01	\N	72878274172	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7119	ALEX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236658	\N	\N	\N	S
7120	DéBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	95612782	\N	\N	\N	S
7121	EDUARDO CASTRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61982599535	\N	\N	\N	S
7122	ELAINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7123	GABI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86355856	\N	\N	\N	S
7124	HELLEN 21	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7125	INGRID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7126	JúLIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7127	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7128	LUIZINHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7129	NEGUEBA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7130	ROBERTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7131	THAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7132	ADRIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7133	ANDRE PAI DO PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7134	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7135	BIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7136	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7137	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	93686770	\N	\N	\N	S
7138	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7139	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7140	BU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86235655	\N	\N	\N	S
7141	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7142	CALIXTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	85599514	\N	\N	\N	S
7143	CAMILA 21	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7144	CARLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7145	DAVI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7146	DéBORAH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7147	DENISE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	92580571	\N	\N	\N	S
7148	DIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7149	DONA CLAUDIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7150	DORIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	82596776	\N	\N	\N	S
7151	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7152	DUDU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7153	EDUARDO NILBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7154	ESPOSA DO JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7155	FABIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86326554	\N	\N	\N	S
7156	FáBIO P NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7157	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7158	FERNANDA 22	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7159	FLAVIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7160	FRANCISCO 23	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7161	GEOVANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7162	GILVAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	0	\N	\N	\N	S
7163	GLAUBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7164	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7165	HéRIKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7166	HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7167	IAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7168	ISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7169	JARDEILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7170	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7171	JOAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7172	JOAO 23	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7173	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7174	KAREN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	91365508	\N	\N	\N	S
7175	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86543221	\N	\N	\N	S
7176	LEANDRO 21	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7177	LEANDRO PRIMO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	85329591	\N	\N	\N	S
7178	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	0	\N	\N	\N	S
7179	LUCAS 22	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7180	LUQUINHAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7181	MAE DA BIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7182	MARCO LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	0	\N	\N	\N	S
7183	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7184	MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7185	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7186	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	0	\N	\N	\N	S
7187	MAYCOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7188	MENDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7189	MERCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7190	MIKAELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7191	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7192	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7193	POLIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	91365508	\N	\N	\N	S
7194	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7195	RAYSSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7196	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7197	SAMANTHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7198	SOSY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86440691	\N	\N	\N	S
7199	TACIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7200	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	81111111	\N	\N	\N	S
7201	VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7202	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	93533023	\N	\N	\N	S
7203	WESLEI VICENTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7204	WESLLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7205	GIOVANNA AZEVEDO PUJANI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	84321947	\N	\N	\N	S
7206	LHITTS MARIA RODRIGUES DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	96927536	\N	\N	\N	S
7207	THIAGO EMANUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	95703907	\N	\N	\N	S
7208	BRENO DE LUCENA	brinito_breno21terror@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6727148171	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7209	THIAGO EMANUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	95703907	\N	\N	\N	S
7210	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7211	AMANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7212	ANA VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7213	ANDRé	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7214	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7215	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6184185866	\N	\N	\N	S
7216	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7217	CRISTIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7218	DANILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7219	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7220	EDVALDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7221	ELOISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7222	ESTEFANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7223	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7224	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7225	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7226	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7227	HILANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7228	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7229	ISAAC	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7230	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7231	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7232	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	85295992	\N	\N	\N	S
7233	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7234	KAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7235	KAREN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7236	KELVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7237	KENIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7238	LAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7239	LAUANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7240	LAUANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7241	LIDIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7242	LILIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7243	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7244	LUIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7245	LUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7246	MARCIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7247	MARCOS VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7248	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7249	MILENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	86236553	\N	\N	\N	S
7250	NAIRANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7251	NATALIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7252	NATAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7253	PABLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7254	PATRICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7255	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7256	PETERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7257	PSY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7258	RAILTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7259	RENAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7260	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7261	RONAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7262	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7263	SUZY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7264	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	12345678	\N	\N	\N	S
7265	VINICIOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7266	VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7267	WILIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7268	YURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7269	THIAGO EMANUEL ALVES CRUZ	thiago@hotmail.com	cd2e424b6a2965156c84925b5b50c59e	2017-05-01	\N	20648312151	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7270	ALEX GUEDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61123456789	\N	\N	\N	S
7271	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7272	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7273	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7274	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7275	ISAQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7276	JOAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7277	KALEB	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7278	KAYO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7279	LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6212345678	\N	\N	\N	S
7280	PAI DO CAUA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7281	WELL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61984299017	\N	\N	\N	S
7282	YAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7283	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7284	MICHAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7285	MICHAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6112345678	\N	\N	\N	S
7286	YURI SILVA	yuri.@hotmail.com	64ef22c01048e99758b495e956d85ba2	2017-05-01	\N	24164233125	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7287	VICTOR HUGO DA SILVA PEREIRA	vitor.@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	77847187728	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7288	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61231356123	\N	\N	\N	S
7289	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6123456787	\N	\N	\N	S
7290	BRENDA BEATRIZ	brendinha12pulsar4.2@gmail.com	323242097368577e6f3aac03c6dcedb6	2017-05-01	\N	6203063193	\N	\N	\N	\N	15:34:04	\N	\N	\N	\N	\N	N
7291	BRENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6196135196	\N	\N	\N	S
7292	GEOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	95359081	\N	\N	\N	S
7293	RAFAELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6186	\N	\N	\N	S
7294	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6194190783	\N	\N	\N	S
7295	ADSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61984074553	\N	\N	\N	S
7296	ALISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61984074553	\N	\N	\N	S
7297	BENNETT	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6182681284	\N	\N	\N	S
7298	BRENDA A	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	85796357	\N	\N	\N	S
7299	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61996135196	\N	\N	\N	S
7300	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	91192128	\N	\N	\N	S
7301	EDILENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61996135196	\N	\N	\N	S
7302	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61996135196	\N	\N	\N	S
7303	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6195359081	\N	\N	\N	S
7304	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	94001696	\N	\N	\N	S
7305	LUAN CLEBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	61992569009	\N	\N	\N	S
7306	POLIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	95359081	\N	\N	\N	S
7307	SAMUEL PADUA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:34:04	\N	6185454627	\N	\N	\N	S
7308	FLáVIO  SOUSA	flavio_oul@hotmail.com	ad1f1a6bf1e3d574c9f4a4866c65394a	2017-05-01	\N	70743365135	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7309	RAFAELA DA SILVA SANTOS	brendabeatriz7070@gmail.com	582e51fc3f5e3a4627dc58cd32115efa	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7310	AGATHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6193354079	\N	\N	\N	S
7311	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6191288135	\N	\N	\N	S
7312	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	93354079	\N	\N	\N	S
7313	LORENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	93354079	\N	\N	\N	S
7314	PETERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	93354079	\N	\N	\N	S
7315	PETRICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	99010531	\N	\N	\N	S
7316	RICHARD	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95534079	\N	\N	\N	S
7317	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	93354079	\N	\N	\N	S
7318	THIAGO BRITO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	93354079	\N	\N	\N	S
7319	WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6191288135	\N	\N	\N	S
7320	GIOVANNA EVILYN	giovannaevelyn321@gmail.com	79d2d812bf677287382b68106237b5ee	2017-05-01	\N	26022189870	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7321	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	96958562	\N	\N	\N	S
7322	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95359081	\N	\N	\N	S
7323	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85583705	\N	\N	\N	S
7324	DUDA, EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85677231	\N	\N	\N	S
7325	FLAVIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95747293	\N	\N	\N	S
7326	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85747293	\N	\N	\N	S
7327	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85747293	\N	\N	\N	S
7328	IAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95359081	\N	\N	\N	S
7329	JONINHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85747293	\N	\N	\N	S
7330	LAUANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85121308	\N	\N	\N	S
7331	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	84382677	\N	\N	\N	S
7332	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95359081	\N	\N	\N	S
7333	PRIMO DO IAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	86015736	\N	\N	\N	S
7334	RAFAELA SILVA SANTANA	rafaeladasilvasantana2010@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6860777176	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7335	AGATA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85313497	\N	\N	\N	S
7336	ALICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6193234322	\N	\N	\N	S
7337	AMANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6195312412	\N	\N	\N	S
7338	ANA CAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	33712440	\N	\N	\N	S
7339	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	94736478	\N	\N	\N	S
7340	BRENDA SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	93415227	\N	\N	\N	S
7341	CAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6198765656	\N	\N	\N	S
7342	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6184456966	\N	\N	\N	S
7343	CAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6185313497	\N	\N	\N	S
7344	DANILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85022248	\N	\N	\N	S
7345	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6195658535	\N	\N	\N	S
7346	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6195369071	\N	\N	\N	S
7347	EDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	62728282	\N	\N	\N	S
7348	ELIETE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6186324511	\N	\N	\N	S
7349	ELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	86514558	\N	\N	\N	S
7350	ERICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6193314255	\N	\N	\N	S
7351	ESTER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95359081	\N	\N	\N	S
7352	EVELIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85313497	\N	\N	\N	S
7353	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85214511	\N	\N	\N	S
7354	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6198898234	\N	\N	\N	S
7355	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85022248	\N	\N	\N	S
7356	GEOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85022248	\N	\N	\N	S
7357	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6185313497	\N	\N	\N	S
7358	IGHOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95359081	\N	\N	\N	S
7359	INGRID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6195359071	\N	\N	\N	S
7360	ISRAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	35214511	\N	\N	\N	S
7361	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	81140072	\N	\N	\N	S
7362	JENIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	86683827	\N	\N	\N	S
7363	JERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61995359081	\N	\N	\N	S
7364	JOãO VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6181234355	\N	\N	\N	S
7365	KALINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6184323454	\N	\N	\N	S
7366	KARLA KAROLINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6186172939	\N	\N	\N	S
7367	LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95372810	\N	\N	\N	S
7368	MARIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6185256551	\N	\N	\N	S
7369	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	81234567	\N	\N	\N	S
7370	POLIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6184533445	\N	\N	\N	S
7371	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6185349081	\N	\N	\N	S
7372	RAFAELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85313467	\N	\N	\N	S
7373	RAFAELA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85022248	\N	\N	\N	S
7374	RAISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6134214611	\N	\N	\N	S
7375	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61987234567	\N	\N	\N	S
7376	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61987234567	\N	\N	\N	S
7377	ROMARIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	35214511	\N	\N	\N	S
7378	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61985891023	\N	\N	\N	S
7379	SARAIVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	86313497	\N	\N	\N	S
7380	TAMIRES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6196345466	\N	\N	\N	S
7381	THAYSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95359081	\N	\N	\N	S
7382	WELINGTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85313497	\N	\N	\N	S
7383	ANA LUISA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	91126715	\N	\N	\N	S
7384	ANA LUISA SILVA DA CONCEIçãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	91126715	\N	\N	\N	S
7385	DAVID VITOR QUEIROZ DE JESUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61985891023	\N	\N	\N	S
7386	ELIAS BRENDO GARCIA BASILIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6186173668	\N	\N	\N	S
7387	LAYSSA LANER CARDOSO PIRES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85922565	\N	\N	\N	S
7388	LILA CABRAL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	957124000	\N	\N	\N	S
7389	MATHEUS LACERDA DE MELLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85212670	\N	\N	\N	S
7390	MATHEUS LACERDA DE MELLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	85312670	\N	\N	\N	S
7391	PALOMA GLAUCIA NOBRE DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95633354	\N	\N	\N	S
7392	POLIANA ARAGãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	91365508	\N	\N	\N	S
7393	RAFAEL OLIVEIRA DE CASTRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6185567112	\N	\N	\N	S
7394	FABIO CARDOSO	fabiocardoso11@hotmail.com	2eade6253fc64628743cb29fc1c63e11	2017-05-01	\N	5480347101	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7395	BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7396	DARLA DUARTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7397	DéBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7398	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	92821885	\N	\N	\N	S
7399	GABRIELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7400	ISAAC	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7401	JAINY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7402	JOãO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7403	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	92821885	\N	\N	\N	S
7404	JULIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7405	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	92821885	\N	\N	\N	S
7406	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	92821885	\N	\N	\N	S
7407	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7408	RENATA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	92821885	\N	\N	\N	S
7409	TAINá	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	92821885	\N	\N	\N	S
7410	WALACE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7411	WESLLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95821885	\N	\N	\N	S
7412	JEISON ROSA DE CARVALHO	jeisonrosa18@gmail.com	834b22beff8e7634b290a1f52d7813bc	2017-05-01	\N	1825974152	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7413	DAIANE DE CARVALHO ROSA	day71pw@gmail.com	834b22beff8e7634b290a1f52d7813bc	2017-05-01	\N	5710467111	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7414	BRUNAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991353582	\N	\N	\N	S
7415	ANA GLORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	91248087	\N	\N	\N	S
7416	DAIANE ROSA DOS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	86795501	\N	\N	\N	S
7417	FELIPE TEILISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	91353582	\N	\N	\N	S
7418	GABRIEL ELIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991353582	\N	\N	\N	S
7419	JOHNNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991353582	\N	\N	\N	S
7420	LARISSA GONçALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991559787	\N	\N	\N	S
7421	LARISSA RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61985258415	\N	\N	\N	S
7422	LETICIA RODRUIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991353582	\N	\N	\N	S
7423	MARIA ZELIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	91248087	\N	\N	\N	S
7424	VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991353582	\N	\N	\N	S
7425	WALAS SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	99373979	\N	\N	\N	S
7426	WELCIO BATISTA DUARTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	95510321	\N	\N	\N	S
7427	WELITON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61991353582	\N	\N	\N	S
7428	OLYVER PHILIPPE ARAúJO	olyver.pessoal@gmail.com	dfdd4a5f654ebbc3a4fcd75fe3c32129	2017-05-01	\N	4590269171	\N	\N	\N	\N	15:35:04	\N	\N	\N	\N	\N	N
7429	KADU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7430	RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7431	VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7432	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7433	ARTHUR 18 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7434	BRUNO 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7435	CAIQUE M NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7436	CAUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7437	DAIANE 14 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7438	DANILO 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7439	DAVID 36 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7440	DENNER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7441	DIEGO 14 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7442	DIORIPES 18 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7443	EDUARDO 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7444	EMANUEL 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7445	FELIPE 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7446	FLAVIA DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7447	GABRIEL 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7448	GABRIELA PRIVER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7449	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7450	GUSTAVO 14 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7451	HARLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7452	HERBERT	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7453	HERBERT 18 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7454	HERNESTRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7455	IAGO M NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7456	IGOR 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7457	IGOR M NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7458	ITALO 18 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7459	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7460	JOãO 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7461	JOãO 18 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7462	JOSE 30/26 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7463	KALIL SOL NAS.	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7464	KAUAN 24 SUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7465	KLETELEN 32 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7466	LAIANE PRIVER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7467	LAURA QNL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7468	LEO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7469	LEONARDO 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	6184624723	\N	\N	\N	S
7470	LUAN 14 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7471	LUAN PRO DF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7472	LUIZ 32 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7473	MATHEUS 5 PNORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7474	MATHEUS DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7475	MATHEUS LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7476	NATAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7477	PEDRO 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7478	RAFAEL 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7479	RAFAEL 26 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7480	SAMUEL M NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7481	SARA 36 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7482	THAINARA DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7483	VITOR A 10 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7484	WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7485	WILL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7486	YAGO 12 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7487	WALLISON 23 NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7488	IVO PRO DF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7489	NATASHA 18 PSUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:35:04	\N	61984624723	\N	\N	\N	S
7490	FERNANDO VICTOR LOPES DOS SANTOS	fernandodzh674@gmail.com	934b535800b1cba8f96a5d72f72f1611	2017-05-01	\N	6379075151	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7491	EDUARDO VINICIUS DA SILVA	kadu.blackbelt22.13@gmail.com	b59c67bf196a4758191e42f76670ceba	2017-05-01	\N	2611183317	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7492	ERICK FELIPY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61991337785	\N	\N	\N	S
7493	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61984624723	\N	\N	\N	S
7494	IGOR ALVES COUTINHO	igorallves12@hotmail.com	4d54b0381aa20ec7f9c348a07fb16453	2017-05-01	\N	3882836105	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7495	SAMUEL COSTA DO ROSáRIO	samuelkrump51@gmail.com	dbc4d84bfcfe2284ba11beffb853a8c4	2017-05-01	\N	6712461197	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7496	VANESSA NASCIMENTO BRAGA	vanessinha.n@gmail.com	7dbb635c1c5c9350b54868aa137bc0b0	2017-05-01	\N	7703306142	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7497	VINICIUS SALES DA SILVA	vynny8@live.com	9982ccd72f31a808fbfbcd096e89631f	2017-05-01	\N	7227455165	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7498	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7499	CAUÃ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61991586826	\N	\N	\N	S
7500	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7501	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7502	JOÃO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7503	JOSÉ GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7504	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7505	YAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7506	YAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6186271791	\N	\N	\N	S
7507	VITOR HUGO	vneves313@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6882164126	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7508	THALITA GONçALVES	thalitaa297@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	5184817190	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7509	LUCAS DANTAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7510	THAUNE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6184523163	\N	\N	\N	S
7511	WIKER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7512	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6199124292	\N	\N	\N	S
7513	BI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84523163	\N	\N	\N	S
7514	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7515	EMYLI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84523163	\N	\N	\N	S
7516	EVY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	984523163	\N	\N	\N	S
7517	FELIX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7518	GUILERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84673811	\N	\N	\N	S
7519	IAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7520	JAILSON SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	91817365	\N	\N	\N	S
7521	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84523163	\N	\N	\N	S
7522	JOAO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7523	MATHEUS GERMANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6184027851	\N	\N	\N	S
7524	NAZARé	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	984331912	\N	\N	\N	S
7525	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6184445267	\N	\N	\N	S
7526	THALES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7527	TIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7528	TIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7529	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6184445267	\N	\N	\N	S
7530	WENDEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	84445267	\N	\N	\N	S
7531	DIEGO DE SOUZA ROCHA	diegorochamuaythay@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	11905606745	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7532	GABRIEL ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6185631844	\N	\N	\N	S
7533	GABRIEL BARBOSA SOARES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	984800875	\N	\N	\N	S
7534	JOHNNY GLEIDSON GOMES DE JESUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61992504313	\N	\N	\N	S
7535	PEDRO FILIPE SILVA DE OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61981709470	\N	\N	\N	S
7536	CAIO CéSAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6185602051	\N	\N	\N	S
7537	FABRICIO ALVEZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61985631844	\N	\N	\N	S
7538	KAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61991516898	\N	\N	\N	S
7539	LARRISA GONSALVEZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6193668236	\N	\N	\N	S
7540	PEDRO HENRIQUE OLIVEIRA E MOURA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	6192693124	\N	\N	\N	S
7541	GABRIE ALVEZ RIBEIRO	gabrielariel38@gmail.com	3c3aa76a108f01d31dc3e2c49fee0aaf	2017-05-01	\N	5678187139	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7542	GABRIEL BARBOSA DOS SANTOS	gabrielfranca018@gmail.com	b89c90799c6604d289c3c96ea4e736c4	2017-05-01	\N	4153443107	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7543	JOHNNY GLEIDSON GOMES DE JESUS	johnny.m4@gmail.com	967c50771879e8cb6358e87308c96569	2017-05-01	\N	5925476123	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7544	CAROL ALMEIDA	carolinedealmeida144@gmail.com	7a50aea9f0691d5e1c1e106d7fe31f57	2017-05-01	\N	735746516	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7545	JHEE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61985760355	\N	\N	\N	S
7546	LéOOO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61995994949	\N	\N	\N	S
7547	AINY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61993338535	\N	\N	\N	S
7548	AMG DA ANNE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996790247	\N	\N	\N	S
7549	AMG DO LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996790247	\N	\N	\N	S
7550	ANNE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61984997783	\N	\N	\N	S
7551	BáRBARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996920908	\N	\N	\N	S
7552	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996620419	\N	\N	\N	S
7553	GRAZY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996920908	\N	\N	\N	S
7554	JACIARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996790247	\N	\N	\N	S
7555	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61992840096	\N	\N	\N	S
7556	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61999766333	\N	\N	\N	S
7557	LUCKAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61991813978	\N	\N	\N	S
7558	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996920908	\N	\N	\N	S
7559	MEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61991198101	\N	\N	\N	S
7560	NEIDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996790247	\N	\N	\N	S
7561	NICOLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996790247	\N	\N	\N	S
7562	OZI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61993376024	\N	\N	\N	S
7563	RAYSSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61992840096	\N	\N	\N	S
7564	ROGéRIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61996790247	\N	\N	\N	S
7565	SAFIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61982988622	\N	\N	\N	S
7566	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:36:04	\N	61986316012	\N	\N	\N	S
7567	JUAN PEDRO CACERES CHAMORRO ZILENO	juanzileno1@gmail.com	3dacf626af950bd1c04371d5300e47ea	2017-05-01	\N	5670749197	\N	\N	\N	\N	15:36:04	\N	\N	\N	\N	\N	N
7568	LUAN CLEBER PEREIRA DE JESUS	luanzitobkb1@gmail.com	fcea920f7412b5da7be0cf42b8c93759	2017-05-01	\N	5694705106	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7569	ANNA RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61996260294	\N	\N	\N	S
7570	ÍCARO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192569009	\N	\N	\N	S
7571	CARLOS EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7572	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7573	DENER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192569009	\N	\N	\N	S
7574	DERLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192569009	\N	\N	\N	S
7575	ERICK DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61985459081	\N	\N	\N	S
7576	EZEQUIEL DOMINGOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6199269009	\N	\N	\N	S
7577	GABRIEL PEDROSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7578	GUILHERME DINIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61985633367	\N	\N	\N	S
7579	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7580	HARã	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7581	ISABELLY NERES DE SOUSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7582	JONATHAN SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991249133	\N	\N	\N	S
7583	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192569009	\N	\N	\N	S
7584	KALEBY DOMINGOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7585	LEONARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7586	LUCAS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7587	MATHEUS CASTRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61995365082	\N	\N	\N	S
7588	MICHAEL DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992421786	\N	\N	\N	S
7589	MOISES GOUVEIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7590	PEDRO LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7591	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7592	RODRIGO DE SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7593	SABRINA LEAL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7594	VICTOR ENZO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192569009	\N	\N	\N	S
7595	WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992569009	\N	\N	\N	S
7596	WILKSOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192569009	\N	\N	\N	S
7597	WILLIAMS JUNIR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61996260294	\N	\N	\N	S
7598	REGINALDO RODRIGUES NERDS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61995812969	\N	\N	\N	S
7599	JENNIFER LORRANY FERREIRA DE TORRES	jennywayland16@gmail.com	827ccb0eea8a706c4c34a16891f84e7b	2017-05-01	\N	7067314178	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7600	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6186316012	\N	\N	\N	S
7601	DAIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7602	EDIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7603	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7604	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7605	JANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7606	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6196184444	\N	\N	\N	S
7607	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7608	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192669356	\N	\N	\N	S
7609	WELLINGTON JOSE DA SILVA	wellingtonjose27@outlook.com	5094ec0a65bb2fedbb1eebd7625e567f	2017-05-01	\N	7117224126	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7610	INGRID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991438397	\N	\N	\N	S
7611	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61994382889	\N	\N	\N	S
7612	MEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991198101	\N	\N	\N	S
7613	URIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7614	ARYELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7615	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7616	BEATRIZ FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61998177764	\N	\N	\N	S
7617	BIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7618	CAREM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7619	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7620	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7621	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7622	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7623	FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7624	HYTALO OLIVEIRA FEITOSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6199586072	\N	\N	\N	S
7625	LORENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7626	LORENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991404744	\N	\N	\N	S
7627	MARIA DO SOCORRO NASCIMENTO SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61981532136	\N	\N	\N	S
7628	MURILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6133758192	\N	\N	\N	S
7629	ROSE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7630	STE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991438397	\N	\N	\N	S
7631	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991918278	\N	\N	\N	S
7632	STEFANNY SILVA DO NASCIMENTO	stefannysilva1627@outlook.com	1f056eb7e226fb1b387e8502e5b33b40	2017-05-01	\N	6168890114	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7633	INGRID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991436397	\N	\N	\N	S
7634	MELISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991198101	\N	\N	\N	S
7635	ARIELY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991215693	\N	\N	\N	S
7636	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61999788292	\N	\N	\N	S
7637	LORENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61991404744	\N	\N	\N	S
7638	MIKAELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992253797	\N	\N	\N	S
7639	YASMIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61992814606	\N	\N	\N	S
7640	DANIEL SILVA OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61984472946	\N	\N	\N	S
7641	FILIPE BATISTA RAMOS OLIVEIRA	filipebro6@gmail.com	114dde61ee8708c9c30c287ea2b919e1	2017-05-01	\N	6287926112	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7642	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61993200347	\N	\N	\N	S
7643	VICTOR NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	985033768	\N	\N	\N	S
7644	ZANGA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7645	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7646	BOLINHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	996501281	\N	\N	\N	S
7647	DANIELZIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	98649	\N	\N	\N	S
7648	ELIAS EVANGELISTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	991570932	\N	\N	\N	S
7649	GRIEZMAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	983783914	\N	\N	\N	S
7650	LELEH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61984217291	\N	\N	\N	S
7651	LOOH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560211	\N	\N	\N	S
7652	LUAN HENRIQUE  DE SOUSA QUEIROZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61984425380	\N	\N	\N	S
7653	NATHALIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7654	PEDRO LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	1040	\N	\N	\N	S
7655	RAFAELA GALEGA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61984801721	\N	\N	\N	S
7656	RAYSSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	61984801728	\N	\N	\N	S
7657	VINíCIUS RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	1949	\N	\N	\N	S
7658	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7659	VIVIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	1949	\N	\N	\N	S
7660	VICTOR DO NASCIMENTO DOS SANTOS	victordnascimento6@gmail.com	3bf76808ea2ae6f998d491572395ac02	2017-05-01	\N	33475584050	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7661	WANDERSON VINíCIUS	wandvinialves@gmail.com	d93591bdf7860e1e4ee2fca799911215	2017-05-01	\N	6305147124	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7662	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7663	NATHALIA GOMES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7664	VITóRIA SIQUEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7665	ANA REBECA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7666	ANDRESSA A.	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7667	ANTONIO RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7668	ÍTALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7669	DAVI ( CAJA )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7670	EMANUEL JOCHUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7671	FREDERICO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7672	JOAO VITOR ( FEI )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7673	LORRANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7674	PEDRO LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7675	THAIS...	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7676	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7677	VITOR NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7678	VIVIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7679	VITOR GALEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7680	RAFAELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7681	MILENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7682	BáRBARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6185560311	\N	\N	\N	S
7683	NAYANE ESTHER FERNANDES SILVA	nayhannesther@gmail.com	999f1c4ccce5b1fee7fe3e146a6d7738	2017-05-01	\N	6253001140	\N	\N	\N	\N	15:37:04	\N	\N	\N	\N	\N	N
7684	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	35816292	\N	\N	\N	S
7685	ADRIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	84049505	\N	\N	\N	S
7686	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	93752719	\N	\N	\N	S
7687	ANNE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	84049505	\N	\N	\N	S
7688	ARIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	84049505	\N	\N	\N	S
7689	ARILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	84049505	\N	\N	\N	S
7690	ÁGAPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	84244861	\N	\N	\N	S
7691	CELIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	94081235	\N	\N	\N	S
7692	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	84879171	\N	\N	\N	S
7693	ELICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	95813711	\N	\N	\N	S
7694	ELISANGELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	91134299	\N	\N	\N	S
7695	EZEQUIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	93752719	\N	\N	\N	S
7696	GEOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	99092067	\N	\N	\N	S
7697	ISRAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	99399516	\N	\N	\N	S
7698	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	91353048	\N	\N	\N	S
7699	LUCAS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	35816292	\N	\N	\N	S
7700	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	83794112	\N	\N	\N	S
7701	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6192090021	\N	\N	\N	S
7702	RAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	82567551	\N	\N	\N	S
7703	RONIE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	93329942	\N	\N	\N	S
7704	ROSIMEIRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	94081235	\N	\N	\N	S
7705	SANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	85471063	\N	\N	\N	S
7706	SELMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	83308676	\N	\N	\N	S
7707	THAINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	95320193	\N	\N	\N	S
7708	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	95828051	\N	\N	\N	S
7709	YSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	85292253	\N	\N	\N	S
7710	CLAUDIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:37:04	\N	6292802662	\N	\N	\N	S
7711	GLAUCO FERREIRA DE SOUZA VIDAL	e-glauco@ibest.com.br	a7016390d1c717390fd75f393411269b	2017-05-01	\N	90627253172	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7712	AGILCE ALVES DA SILVA VIDAL	agilces@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	99975882153	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7713	GEANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7714	GEOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7715	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7716	VIVIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186629971	\N	\N	\N	S
7717	ALISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7718	ANA PAULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61985604385	\N	\N	\N	S
7719	ARMANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6198564287	\N	\N	\N	S
7720	BRAULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7721	BRUNA RAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7722	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7723	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7724	DEDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186564287	\N	\N	\N	S
7725	DUDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7726	EMERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7727	EMILY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7728	EUNIMAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7729	FLAVIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986038779	\N	\N	\N	S
7730	GIOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186629971	\N	\N	\N	S
7731	ISAAC	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7732	JOEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7733	JOEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186564974	\N	\N	\N	S
7734	KAREN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7735	KLISMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986564287	\N	\N	\N	S
7736	LALA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7737	LARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7738	LARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186564287	\N	\N	\N	S
7739	LEO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186629971	\N	\N	\N	S
7740	LUIZ FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	94114367	\N	\N	\N	S
7741	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7742	NAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6198829971	\N	\N	\N	S
7743	TATA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986629971	\N	\N	\N	S
7744	JOÃO VICTOR DE SOUZA OLIVEIRA	joaovictor@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6463021164	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7745	ISAC	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7746	JOEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7747	LUCAS MEDEIROS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	98110161	\N	\N	\N	S
7748	MAURO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7749	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7750	ALANIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6194016977	\N	\N	\N	S
7751	ANA CAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	96624838	\N	\N	\N	S
7752	ANA LUíSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	96624600	\N	\N	\N	S
7753	ANDREIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7754	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	96624838	\N	\N	\N	S
7755	ARTHUR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7756	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6196624838	\N	\N	\N	S
7757	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7758	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	99274826	\N	\N	\N	S
7759	DéBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	93315645	\N	\N	\N	S
7760	DIEGO GRUBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7761	ENZO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7762	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184422438	\N	\N	\N	S
7763	FELIPE MARADONA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7764	FRANCISCO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7765	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	86485358	\N	\N	\N	S
7766	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7767	GABY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184422438	\N	\N	\N	S
7768	GEOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6191225333	\N	\N	\N	S
7769	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6182166095	\N	\N	\N	S
7770	HANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7771	HUDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7772	ISABELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6191991931	\N	\N	\N	S
7773	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7774	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7775	IVAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7776	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7777	JOãO PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7778	JULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7779	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6185811796	\N	\N	\N	S
7780	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7781	JUSTINO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7782	KADU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6195504460	\N	\N	\N	S
7783	KAUANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7784	LARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6191225333	\N	\N	\N	S
7785	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6192964847	\N	\N	\N	S
7786	LEONARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	99274826	\N	\N	\N	S
7787	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	92395715	\N	\N	\N	S
7788	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6196624600	\N	\N	\N	S
7789	MAICOSUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7790	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7791	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	89066237	\N	\N	\N	S
7792	NEIDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6191952450	\N	\N	\N	S
7793	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186228640	\N	\N	\N	S
7794	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6186485358	\N	\N	\N	S
7795	PEDRO HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6182629296	\N	\N	\N	S
7796	RAFAELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6185193364	\N	\N	\N	S
7797	RAQUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6192595851	\N	\N	\N	S
7798	REBECCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6191023048	\N	\N	\N	S
7799	RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7800	SAMUEL SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7801	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7802	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7803	MARIA DA PAZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7804	GISELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6193315645	\N	\N	\N	S
7805	PEDRO HENRIQUE LOIOLA CUNHA	phlccrvg19@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6520042102	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7806	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61982032440	\N	\N	\N	S
7807	EDUARDO VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	1348	\N	\N	\N	S
7808	EDUARDO VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	1348	\N	\N	\N	S
7809	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61982032440	\N	\N	\N	S
7810	RENIVALDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6182032440	\N	\N	\N	S
7811	PAULO HENRIQUE RIBEIRO DE SOUZA	pauloh159@live.com	cca966403536569db389c37822721c35	2017-05-01	\N	6411166161	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7813	VIVIAN LARISSA XAVIER CAVALCANTE	vivianlavigne22@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6498128160	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7814	ALEX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6133573450	\N	\N	\N	S
7815	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61998510703	\N	\N	\N	S
7816	FRANCISCO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61986375719	\N	\N	\N	S
7817	FICANTE ALEX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61998510703	\N	\N	\N	S
7820	VIVIANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	33760777	\N	\N	\N	S
7821	WESLLEM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	61996082549	\N	\N	\N	S
7822	ANDERSON MENDES SANTOS	anderson_plg@hotmail.com	047448006c3bb77525514b762b6ba773	2017-05-01	\N	3610000147	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7823	CARLA EDILANE DA CONCEIçãO ARAúJO	carlaec.araujo@gmail.com	4aa1dd24c949ebb1a8531e874ebc8812	2017-05-01	\N	60491643390	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7824	AMANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7825	LANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6185284585	\N	\N	\N	S
7826	HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	1684855240	\N	\N	\N	S
7827	ATENOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7828	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7829	CHAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7830	CHIRLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7831	CRIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7832	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7833	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7834	EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7835	ELIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	1684855240	\N	\N	\N	S
7836	ERLANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7837	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7838	FLáVIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7839	FLáVIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7840	FRANCIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7841	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7842	GEANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	1685284585	\N	\N	\N	S
7843	GIOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855340	\N	\N	\N	S
7844	HELENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7845	HELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6185284558	\N	\N	\N	S
7846	HUDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7847	ISABELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7848	ISAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7849	JAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7850	JEAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7851	JHON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7852	JOAO VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7818	LUCAS CALISTENIA	\N	\N	2017-05-15	\N	\N	\N	\N	\N	\N	13:17:23	\N	61985258339	\N	\N	\N	S
7819	MIZAEL	\N	\N	2017-05-13	\N	\N	\N	\N	\N	\N	17:04:13	\N	6133573450	\N	\N	\N	S
7853	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7854	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6185284585	\N	\N	\N	S
7855	LAURA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6184855240	\N	\N	\N	S
7856	LUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7857	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7858	MAQUIDIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7859	MARCONDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	6185284585	\N	\N	\N	S
7860	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	85284585	\N	\N	\N	S
7861	MARCOS PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7862	MEYRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84073527	\N	\N	\N	S
7863	MONALISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7864	NAILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	92285818	\N	\N	\N	S
7865	NAIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7866	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7867	PEDRO HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7868	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7869	SORAIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	84855240	\N	\N	\N	S
7870	GUILHERME MARINHO MONTEIRO	guilhermemarinho007@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	70712402136	\N	\N	\N	\N	15:38:04	\N	\N	\N	\N	\N	N
7871	ARTHUR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:38:04	\N	95568837	\N	\N	\N	S
7872	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7873	BRUNO-M	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193632788	\N	\N	\N	S
7874	BRUNO-M	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193632788	\N	\N	\N	S
7875	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61999272277	\N	\N	\N	S
7876	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7877	CARL MAX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7878	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722388	\N	\N	\N	S
7879	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7880	ELIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722788	\N	\N	\N	S
7881	ELIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722788	\N	\N	\N	S
7882	EZEQUIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192277288	\N	\N	\N	S
7883	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7884	GEIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722788	\N	\N	\N	S
7885	GISELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195568837	\N	\N	\N	S
7886	GUTEMBERG	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192550440	\N	\N	\N	S
7887	HELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195568837	\N	\N	\N	S
7888	IDELFONSO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195568837	\N	\N	\N	S
7889	IVAN CLEBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61993748421	\N	\N	\N	S
7890	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92722788	\N	\N	\N	S
7891	JOãO VICTOR / BIL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7892	JOãO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7893	JOHN WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7894	JUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195568837	\N	\N	\N	S
7895	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192422455	\N	\N	\N	S
7896	LUANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7897	MARCELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192533568	\N	\N	\N	S
7898	MARCONDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7899	MARKIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92722788	\N	\N	\N	S
7900	MATHEUS BATISTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7901	MATHEUS SOUSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7902	MãE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61612590015	\N	\N	\N	S
7903	NAISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192727882	\N	\N	\N	S
7904	NILTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7905	PUBLIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722788	\N	\N	\N	S
7906	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722733	\N	\N	\N	S
7907	RANDLEI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722788	\N	\N	\N	S
7908	RODRIDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92590015	\N	\N	\N	S
7909	RODRIDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92590015	\N	\N	\N	S
7910	SHAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195568837	\N	\N	\N	S
7911	TATI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192582755	\N	\N	\N	S
7912	TIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	95568837	\N	\N	\N	S
7913	VINICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192725888	\N	\N	\N	S
7914	WILIAM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6194632788	\N	\N	\N	S
7915	YAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192530015	\N	\N	\N	S
7916	YAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192530015	\N	\N	\N	S
7917	JOHN WESLLEY ANDRADE GALVãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195461804	\N	\N	\N	S
7918	GEANDERSON NUNES DE SOUZA LIMA	gnslnunes@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6155031100	\N	\N	\N	\N	15:39:04	\N	\N	\N	\N	\N	N
7919	JOSé NUNES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193452271	\N	\N	\N	S
7920	AZAFE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192505003	\N	\N	\N	S
7921	CARL MAX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992508225	\N	\N	\N	S
7922	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7923	DANILO ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193460035	\N	\N	\N	S
7924	DIEGO ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193460035	\N	\N	\N	S
7925	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7926	EZEQUIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192505003	\N	\N	\N	S
7927	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6186336790	\N	\N	\N	S
7928	IVAN KLEBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192505003	\N	\N	\N	S
7929	JEAN ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7930	JEAN VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991520204	\N	\N	\N	S
7931	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7932	JOãO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7933	JOSé NUNES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193452271	\N	\N	\N	S
7934	JUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7935	LIZANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992508225	\N	\N	\N	S
7936	LUZEILCE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192391718	\N	\N	\N	S
7937	MARCONDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191702765	\N	\N	\N	S
7938	MARIA RITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7939	MARINETE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7940	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7941	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192505003	\N	\N	\N	S
7942	NILTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7943	RAILANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195378230	\N	\N	\N	S
7944	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6194065802	\N	\N	\N	S
7945	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192505003	\N	\N	\N	S
7946	THALES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6195329964	\N	\N	\N	S
7947	UALES NUNES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7948	UELES NUNES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6194782912	\N	\N	\N	S
7949	WILLIAM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7950	YAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192505003	\N	\N	\N	S
7951	YUSSEF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992505003	\N	\N	\N	S
7952	AMANDA LARISSA BARBOZA	amanda1guilherme123@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	7391361194	\N	\N	\N	\N	15:39:04	\N	\N	\N	\N	\N	N
7953	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7954	ALANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7955	ALEF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7956	ALINE MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7957	ANGELICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7958	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7959	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7960	CARLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7961	CARLOS IF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7962	CHAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6184855240	\N	\N	\N	S
7963	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7964	DAVID SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191714602	\N	\N	\N	S
7965	ELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7966	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7967	FLáVIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7968	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7969	GEISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7970	GISELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7971	GRAZIELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7972	HELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7973	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7974	JAINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7975	JAQUELINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7976	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192722788	\N	\N	\N	S
7977	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7978	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7979	JULIELY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7980	KAROLAYNE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7981	KAROLYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7982	KATHELEN LORRANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7983	LAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991022511	\N	\N	\N	S
7984	LAISLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61995733129	\N	\N	\N	S
7985	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7986	LUCAS ARAúJO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7987	LUIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7988	MARCINHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7989	MARIA EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7990	MONICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7991	NAIANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7992	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7993	RAYSSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7994	ROGER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6196759730	\N	\N	\N	S
7995	SABRINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
7996	SAMILY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7997	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7998	SARAH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
7999	SARAH IF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8000	TANDLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
8001	THAUANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
8002	TIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8003	WESLEY ABREU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8004	YAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192172873	\N	\N	\N	S
8005	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8006	DANI IFG	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8007	GABRIEL IFG	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8008	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8009	RENAN IFG	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8010	RICHARDSSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8011	CAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8012	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8013	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8014	HUDSSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8015	MARIA RITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992172873	\N	\N	\N	S
8016	MATEUS MONTEIRO ROSARIO SILVA	monteiromatheus426@gmail.com	f57354d61c45d880365bcd80002dbdf0	2017-05-01	\N	5835918178	\N	\N	\N	\N	15:39:04	\N	\N	\N	\N	\N	N
8017	STEFANY PEREIRA  MELQUIADES	fannymel99@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6699840193	\N	\N	\N	\N	15:39:04	\N	\N	\N	\N	\N	N
8018	LAUANY CRISTINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6194350016	\N	\N	\N	S
8019	AMG KAREN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8020	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191656999	\N	\N	\N	S
8021	ANA MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8022	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991433801	\N	\N	\N	S
8023	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8024	DALILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8025	DARLANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8026	ELANIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992903797	\N	\N	\N	S
8027	HEROLDINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191656999	\N	\N	\N	S
8028	IRMãO VICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8029	ISABEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193167319	\N	\N	\N	S
8030	JOAO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8031	KETHLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8032	KETHLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61993457300	\N	\N	\N	S
8033	LELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191656999	\N	\N	\N	S
8034	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191656999	\N	\N	\N	S
8035	NANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8036	ODAIR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8037	ODAIR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8038	ROBERTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6199552241	\N	\N	\N	S
8039	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8040	TATAH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8041	THAMIRES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61991656999	\N	\N	\N	S
8042	KETHLEN RODRIGUES SOARES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61993457300	\N	\N	\N	S
8043	LUCAS GABRIEL FERREIRA ROCHA	lukaazbiel@gmail.com	d23ac252075d9d8c3a8f66f6c3dfc306	2017-05-01	\N	5748987198	\N	\N	\N	\N	15:39:04	\N	\N	\N	\N	\N	N
8044	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61988888888	\N	\N	\N	S
8045	AMANDA CáSSIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61983577467	\N	\N	\N	S
8046	ANDREZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6182172568	\N	\N	\N	S
8047	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61977777777	\N	\N	\N	S
8048	CAIO DLEON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61999999999	\N	\N	\N	S
8049	CAROLINE DA FRANçA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6133582611	\N	\N	\N	S
8050	DEBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6133582911	\N	\N	\N	S
8051	ELDINEI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6184123400	\N	\N	\N	S
8052	HERNESTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61999999999	\N	\N	\N	S
8053	JANAINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6186094123	\N	\N	\N	S
8054	JULLIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61977776666	\N	\N	\N	S
8055	KELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992224087	\N	\N	\N	S
8056	LENILSON LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61999999999	\N	\N	\N	S
8057	MATHEUS MORAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61999998888	\N	\N	\N	S
8058	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61984431699	\N	\N	\N	S
8059	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61999999999	\N	\N	\N	S
8060	WILL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61984431699	\N	\N	\N	S
8061	MICAELA LISBOA	micaela.imb@gmail.com	d87fa7efce37cddd6fcdfe18a0c89239	2017-05-01	\N	5436509100	\N	\N	\N	\N	15:39:04	\N	\N	\N	\N	\N	N
8062	ANA KAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8063	ANANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8064	GIOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6191077264	\N	\N	\N	S
8065	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61929906060	\N	\N	\N	S
8066	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92990606	\N	\N	\N	S
8067	KAREN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6184059328	\N	\N	\N	S
8068	KAROL 26	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8069	KAYNAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92990606	\N	\N	\N	S
8070	LUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	92990606	\N	\N	\N	S
8071	LUCAS WILLIAM 26	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8072	PAULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61929229060	\N	\N	\N	S
8073	PâMELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992990606	\N	\N	\N	S
8074	RAELI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8075	RAI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	61992990606	\N	\N	\N	S
8076	REBECA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8077	THALITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8078	VALQUIRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8079	WALERIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6193777679	\N	\N	\N	S
8080	WIDNEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:39:04	\N	6192990606	\N	\N	\N	S
8081	VITOR HUGO SANTANA BENISIO	victoryory@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	4978416140	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8082	BRUNA TAYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6134657689	\N	\N	\N	S
8083	ABYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6153637373	\N	\N	\N	S
8084	MATEU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	5135376373	\N	\N	\N	S
8085	RODRIGO CORREIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6133676373	\N	\N	\N	S
8086	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6134565773	\N	\N	\N	S
8087	STEPHANY LARISSA LOPES DOS SANTOS	lorranygatinha804@hotmail.com	2faade28aece106d1eded0a18350e8c6	2017-05-01	\N	4888883106	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8088	BIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195800614	\N	\N	\N	S
8089	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995800614	\N	\N	\N	S
8090	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195800614	\N	\N	\N	S
8091	ISABELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995800614	\N	\N	\N	S
8092	LAILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995800614	\N	\N	\N	S
8093	LUANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195800614	\N	\N	\N	S
8094	PALOMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995800614	\N	\N	\N	S
8095	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195800614	\N	\N	\N	S
8096	SARAH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61993468047	\N	\N	\N	S
8097	TALLYTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995800614	\N	\N	\N	S
8098	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995800614	\N	\N	\N	S
8099	ROBSON DE OLIVEIRA	robsondeoliveira.snt@gmail.com	7c2ac58d057754dfad0939526819c3cd	2017-05-01	\N	6748423163	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8100	LEO ( ALUNO IV )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8101	RAQUEL ( ALUNA IV )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8102	1..	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61999679567	\N	\N	\N	S
8103	2...	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8104	3.MM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8105	AMIGO DO ARTHUR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8106	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8107	ARTHUR ( TUCANO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8108	BIANCA ( LEO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8109	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8110	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8111	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8112	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8113	DANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8114	DAVID OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195675125	\N	\N	\N	S
8115	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61999679567	\N	\N	\N	S
8116	DOUGLAS ( AMG DO VINÍCIUS )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8117	EDMILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8118	ELOA PRIMA RAQUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8119	ERIKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8120	ESTHER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195041253	\N	\N	\N	S
8121	FELIPE ( AMG WELLISSON )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6191549185	\N	\N	\N	S
8122	FRANCIELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61999679567	\N	\N	\N	S
8123	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	614892	\N	\N	\N	S
8124	GABRIEL ( IRMAO DO WALLISSON )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	613069	\N	\N	\N	S
8125	GABY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8126	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8127	HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8128	HENRIQUE ( AMIGO MATHEUS - LEO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8129	HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8130	IRMA DA RHANARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8131	ISABELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8132	ISAQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195685317	\N	\N	\N	S
8133	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8134	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8135	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8136	JOAO PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8137	JOãO PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61999679567	\N	\N	\N	S
8138	KALINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8139	KARINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8140	KAROL ( IRMA DO LEO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6186487905	\N	\N	\N	S
8141	KELLY ( LEO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8142	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6186745192	\N	\N	\N	S
8143	LAURA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6198308	\N	\N	\N	S
8144	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8145	LENADRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8146	LUCAS ( RAQUEL - TíMIDO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8147	LUCAS RODRIGUES DA COSTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6184147024	\N	\N	\N	S
8148	LUCIANE ( MAE DO LEO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6186487905	\N	\N	\N	S
8149	LUISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8150	LUIZ HENRIQUE ( PRé REVISIONISTA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8151	MANUELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8152	MATEUS ( LEONARDO )	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8153	MATHEUS AMG RA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8154	MIRASSOL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6195679967	\N	\N	\N	S
8155	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8156	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679965	\N	\N	\N	S
8157	WALISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995679967	\N	\N	\N	S
8158	LEONARDO BARROS DOS SANTOS	leleu2009@yahoo.com.br	7c2ac58d057754dfad0939526819c3cd	2017-05-01	\N	7167579157	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8159	RAQUEL RODRIGUES DA COSTA	raquel.rdc99@gmail.com	c33df21b5488f7bd35bba2c5d091039f	2017-05-01	\N	6776281106	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8160	RHANARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995049506	\N	\N	\N	S
8161	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8162	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8163	CARLOS GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8164	CICERO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6183786183	\N	\N	\N	S
8165	DANIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8166	DéBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61992089484	\N	\N	\N	S
8167	EDIMILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8168	ELKIANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61991512425	\N	\N	\N	S
8169	ELOA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6183786183	\N	\N	\N	S
8170	ENELITON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6193528469	\N	\N	\N	S
8171	ESTHER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6198378183	\N	\N	\N	S
8172	GEANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8173	ISABELA ROCHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6183786183	\N	\N	\N	S
8174	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8175	JOSE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8176	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8177	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8178	LUIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61986423423	\N	\N	\N	S
8179	MANUELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61991264175	\N	\N	\N	S
8180	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8181	MICHELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8182	NEIDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8183	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983736183	\N	\N	\N	S
8184	RUBENS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8185	WENYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985640422	\N	\N	\N	S
8186	MATHEUS Z	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61983786183	\N	\N	\N	S
8187	WALISSON CAMPOS ALEXANDRE	walissoncampos169@gmail.com	58839f62714bed3d83d9ab7eb13fb0ce	2017-05-01	\N	6719071157	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8188	KASSIANY CAMPOS ALEXANDRE	katyk169@hotmail.com	07d98c699e487fb0663f504b48f0aa3c	2017-05-01	\N	7615918189	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8189	LUIZ FERNANDO DOS SANTOS ARAUJO	fernando.fefer@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	5068270113	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8190	ALISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6186118539	\N	\N	\N	S
8191	ANDREIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61993668550	\N	\N	\N	S
8192	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6191433801	\N	\N	\N	S
8193	ARMANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6193668550	\N	\N	\N	S
8194	ARMANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6193589698	\N	\N	\N	S
8195	CHRISTOPHER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61995430474	\N	\N	\N	S
8196	DAVI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8197	DEVISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8198	ERIKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61993668550	\N	\N	\N	S
8199	FABRICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8200	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6191206786	\N	\N	\N	S
8201	FLAVIA (GLAUCO)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6193668550	\N	\N	\N	S
8202	HELIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8203	JOICE NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61985771457	\N	\N	\N	S
8204	JOSé RIBAMAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61619572414	\N	\N	\N	S
8205	KLISMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61981793430	\N	\N	\N	S
8206	LEILANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61993668550	\N	\N	\N	S
8207	LUIZ FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8208	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8209	MáRCIO SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61993668550	\N	\N	\N	S
8210	NAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8211	PAULO MARCELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8212	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6181793430	\N	\N	\N	S
8213	WILKSON MONTEIRO ALVES DOS SANTOS	wilkson123221@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	2258075130	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8214	BRENDA LARISSA QUERINO DANTAS	samarthalita@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	5846598145	\N	\N	\N	\N	15:40:04	\N	\N	\N	\N	\N	N
8215	ANDREI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	82885050	\N	\N	\N	S
8216	YANCA OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	61994051002	\N	\N	\N	S
8217	ALAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8218	ALANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8219	ALEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8220	ALISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8221	ANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185823872	\N	\N	\N	S
8222	ÍCARO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8223	BRUNO FEITOSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8224	CAIO CéSAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8225	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	92631906	\N	\N	\N	S
8226	DAGMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8227	DAVI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8228	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8229	DENISE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8230	DEYSE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8231	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8232	EDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8233	EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8234	EMERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185823872	\N	\N	\N	S
8235	ERICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8236	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8237	FELIPE MANDRAK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	6185302216	\N	\N	\N	S
8238	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:40:04	\N	85302216	\N	\N	\N	S
8239	GEOVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8240	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8241	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8242	HERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8243	HUDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8244	HUDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185382216	\N	\N	\N	S
8245	JHON JHON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8246	JOARLYSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8247	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8248	KAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8249	KAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8250	LEONARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8251	LORENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8252	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	93108950	\N	\N	\N	S
8253	LUIS GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8254	LUKAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8255	LURDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8257	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8258	MARIA LúCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6184047478	\N	\N	\N	S
8259	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8260	MAYCOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8261	MáRCIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	93576666	\N	\N	\N	S
8262	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8263	REINALDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	93940021	\N	\N	\N	S
8264	RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8265	RICARDO ARAúJO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8266	SAMUEL SALLES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8267	STEFANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8268	TIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8269	VANDERLEI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8270	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8271	VITOR HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	85302216	\N	\N	\N	S
8272	WALLISSOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	86082903	\N	\N	\N	S
8273	WILKSOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8274	YANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8275	MARLON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8276	GERNIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8277	DAYLON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8278	WENDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302219	\N	\N	\N	S
8279	ROGéRIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8280	FERNANDO RODRIGUES MESQUITA	fernandovdlok@hotmail.com	001a5be723d7ee7956673a717253eec8	2017-05-01	\N	3265475174	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8281	ANDREY ÍCARO GUEDES DA SILVA	blackmenordc@hotmail.com	f7871a649320489052f2cce6a2e050ce	2017-05-01	\N	7313805179	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8282	MARIA LúCIA ARAúJO DA SILVA	indiasinha@outlook.com	44b925262774a30546454b9b3c9b01c3	2017-05-01	\N	7908543170	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8283	ANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8284	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8285	GABRIELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8286	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8287	JAQUELINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8288	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8289	MAGALHãES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6184047478	\N	\N	\N	S
8290	MARINALVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6184047478	\N	\N	\N	S
8291	MARINALVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6184047478	\N	\N	\N	S
8292	MIKAELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8293	WALLISSOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8294	SAMUEL SALES PEREIRA	samuelsales411@gmail.com	5d54abe4c413ff73cfb7cfb7c88fac0c	2017-05-01	\N	6406895150	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8295	ARTUR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8296	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8297	DEIVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8298	EMERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8299	EMILY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8300	EZEQUIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8301	ISABEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8302	KAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8303	LUKAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8304	RYAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8305	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8306	EDUARDA ARAúJO SILVA	aeduarda442@gmail.com	75b99969715f711eba4afd69b8e514cb	2017-05-01	\N	7036887150	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8307	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8308	GABRIEL BRAGA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8309	IASMIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8310	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8311	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6195391323	\N	\N	\N	S
8312	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185561528	\N	\N	\N	S
8313	JHONATAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8314	JOEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8315	LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8316	MARINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8317	RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8318	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8319	YANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185302216	\N	\N	\N	S
8320	BRUNO EDUARDO SANTOS BRITO	besb_88@hotmail.com	b716171dee43b0fbc22d2a3bb291dc92	2017-05-01	\N	4513236531	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8321	CRISLAINE MARIA DE SANTANA SANTOS BRITO	crissantana88@gmail.com	39392cf116f5156652fae43134ce4ad2	2017-05-01	\N	4894018560	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8322	ABRAãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61956236538	\N	\N	\N	S
8323	ANDREIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6192635258	\N	\N	\N	S
8324	ARTUR BRANCO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	90909090	\N	\N	\N	S
8325	BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61995623562	\N	\N	\N	S
8326	CAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6191623568	\N	\N	\N	S
8327	ELTON SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6194233333	\N	\N	\N	S
8328	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6188776655	\N	\N	\N	S
8329	GEOVANNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185684876	\N	\N	\N	S
8330	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198653246	\N	\N	\N	S
8331	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6194613252	\N	\N	\N	S
8332	LUIZ FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6192636685	\N	\N	\N	S
8333	MIQUEAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61852635241	\N	\N	\N	S
8334	NEIDE MATIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	84878804	\N	\N	\N	S
8335	ROBERTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	95623524	\N	\N	\N	S
8336	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6178968578	\N	\N	\N	S
8337	ANA PAULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6151623464	\N	\N	\N	S
8338	ANDREIA (BETO)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987598685	\N	\N	\N	S
8339	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61956362632	\N	\N	\N	S
8340	BRENDESTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6182635241	\N	\N	\N	S
8341	BRUNO (BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198689869	\N	\N	\N	S
8342	BRUNO(ANDREIA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985875869	\N	\N	\N	S
8343	CLAYLTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6184516235	\N	\N	\N	S
8344	DANIEL (GELI)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	7984575896	\N	\N	\N	S
8345	FELIPE(DANIEL)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	7987587585	\N	\N	\N	S
8346	FLAVIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61916513216	\N	\N	\N	S
8347	GABI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61956235687	\N	\N	\N	S
8348	GABRIELA MOURA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985858586	\N	\N	\N	S
8349	GABRIELA(BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198968986	\N	\N	\N	S
8350	GELI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6187987878	\N	\N	\N	S
8351	GUILHERME (IRMÃO BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61852569686	\N	\N	\N	S
8352	IGOR (IRMÃO DA BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6189785986	\N	\N	\N	S
8353	IRMÃ DA  ANDREIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985858585	\N	\N	\N	S
8354	JEAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987766554	\N	\N	\N	S
8355	JONATHAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6195624251	\N	\N	\N	S
8356	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6195623568	\N	\N	\N	S
8357	MARCIA (ANDREIA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987875855	\N	\N	\N	S
8358	MATEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987878655	\N	\N	\N	S
8359	MATHEUS D BIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6195623568	\N	\N	\N	S
8360	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	615620	\N	\N	\N	S
8361	RAYANE IRMã ABRAãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	94583671	\N	\N	\N	S
8362	REBECA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6197643162	\N	\N	\N	S
8363	RICARDO (PAI GEOVANA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6184585858	\N	\N	\N	S
8364	SARA (CONS. BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6186865986	\N	\N	\N	S
8365	THIAGO CELULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6194653281	\N	\N	\N	S
8366	VINICIUS VIZINHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6194856352	\N	\N	\N	S
8367	WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61995623521	\N	\N	\N	S
8368	YASMIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987656543	\N	\N	\N	S
8369	YURE BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198686896	\N	\N	\N	S
8370	JULIANA PEREIRA DE SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61999120257	\N	\N	\N	S
8371	MARTHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6114725836	\N	\N	\N	S
8372	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61975847585	\N	\N	\N	S
8373	PRISCILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6187978548	\N	\N	\N	S
8374	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61585858585	\N	\N	\N	S
8375	LAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185869696	\N	\N	\N	S
8376	DANIELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198989895	\N	\N	\N	S
8377	DANIELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198989895	\N	\N	\N	S
8378	THAMIRES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185858585	\N	\N	\N	S
8379	AMIGA DA DANI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6158588586	\N	\N	\N	S
8380	ARTUR BRANCO RAMOS OLIVEIRA	artur-11@live.com	bdb8c008fa551ba75f8481963f2201da	2017-05-01	\N	6287896108	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8381	BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985982576	\N	\N	\N	S
8382	DUDA(NAM.PEDRO)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987675785	\N	\N	\N	S
8383	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985008144	\N	\N	\N	S
8384	FILIPE(IRMãO)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985998144	\N	\N	\N	S
8385	GULHERME(IRM BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985982576	\N	\N	\N	S
8386	IGOR(BRENDA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61685765967	\N	\N	\N	S
8387	KAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185998144	\N	\N	\N	S
8388	LUCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61767868569	\N	\N	\N	S
8389	MARINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985998144	\N	\N	\N	S
8390	PEDRO PRIM.	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61987567654	\N	\N	\N	S
8391	PEDRO(LUCA)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985998144	\N	\N	\N	S
8392	TECA(PRIM)	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	61985998322	\N	\N	\N	S
8393	TIA DANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185982576	\N	\N	\N	S
8394	TIA ELIZANGELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198507354	\N	\N	\N	S
8395	TIO MARCELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6198778587	\N	\N	\N	S
8396	VALQUIRIA MãE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	89786756	\N	\N	\N	S
8397	ROBERTO FERREIRA BATISTA	robertoferreirabatista@hotmail.com	aab722da21be7ad07a3b72eede4a9e9a	2017-05-01	\N	7270409528	\N	\N	\N	\N	15:41:04	\N	\N	\N	\N	\N	N
8398	ANDRéIA SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8399	ANINHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8400	ANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8401	BIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8402	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8403	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8404	DARCI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8405	DAVID RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8406	ERIC V	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8407	FáBIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8408	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8409	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	12345678	\N	\N	\N	S
8410	GUILHERME THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6185818866	\N	\N	\N	S
8411	GUSTAVO SOUSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:41:04	\N	6112345678	\N	\N	\N	S
8412	IGO HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8413	ISAQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61123456678	\N	\N	\N	S
8414	JACó SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8415	JAQUELINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	85181965	\N	\N	\N	S
8416	JéSSICA CRISTINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8417	JEANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6183552228	\N	\N	\N	S
8418	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8419	JOâO VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8420	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8421	JOãO VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8422	JULIO CERSAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8423	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8424	LAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8425	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8426	LEANDRO PEREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8427	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61992051368	\N	\N	\N	S
8428	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8429	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8430	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8431	LUDIMILA FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8432	LUIZ FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8433	MARCOS VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994002519	\N	\N	\N	S
8434	MARCOS VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8435	NENA FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8436	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8437	QUITéRIA SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8438	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8439	RENAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8440	ROBERT	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8441	RODRIGO FERREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8442	RONALD	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8443	RUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8444	THIAGO ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8445	VICTOR HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	112345678	\N	\N	\N	S
8446	VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	12345678	\N	\N	\N	S
8447	VITOR GOMES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8448	WANESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8449	WESLLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8450	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8451	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8452	GUSTAVO DE MORAES MENDES PEREIRA	gustavodemorais.m1616@gamail.com	104796e22efefe9d0a14b47f15ae581d	2017-05-01	\N	5689526185	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8453	RôMULO DE ALMEIDA MENDES	romulodealmeida@gmail.com	2ddbd34cff4349d2718b5d99fc6d1c9d	2017-05-01	\N	1538473100	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8454	ELIEL DOS SANTOS SILVA	eliel.santos2008@gmail.com	fcf1c7315df669fa72913279db5c239a	2017-05-01	\N	4475324130	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8455	ERIC CASTRO DOS SANTOS	ericbkb10.3@gmail.com	2a69f593efb177cad09ac6cde226b882	2017-05-01	\N	7428202116	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8456	LEANDRO PEREIRA DE SOUSA	leandropereira3067@gmail.com	07fa826660c5904e6681a6c0308da066	2017-05-01	\N	70729116182	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8457	GUILHERME HENRIQUE DE SOUSA MARQUES	guilhermeheneique146@gmail.com	09db709cff285627e5ce77d158fb7482	2017-05-01	\N	970481195	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8458	ANA BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8459	JAINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8460	ADRIANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8461	ALESSANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8462	ALYSSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8463	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8464	CRISTIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8465	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61986193519	\N	\N	\N	S
8466	DIENYFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8467	DIOGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8468	ELIZANGELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	992908002	\N	\N	\N	S
8469	ERINADIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8470	FABIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61999180408	\N	\N	\N	S
8471	FABIO LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	992908002	\N	\N	\N	S
8472	FERNANDO ANTONIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	998659969	\N	\N	\N	S
8473	GABRIEL 2	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6196320448	\N	\N	\N	S
8474	GABRIEL FONTINELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	983022868	\N	\N	\N	S
8475	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6186193519	\N	\N	\N	S
8476	ISAQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61981551835	\N	\N	\N	S
8477	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8478	IVONE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8479	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8480	JOAO PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8481	JOAO VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	991046948	\N	\N	\N	S
8482	KETHELL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8483	LEOMIR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8484	LEONARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8485	MAICON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8486	MARCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	982261553	\N	\N	\N	S
8487	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6198348703	\N	\N	\N	S
8488	MARIA EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8489	MARIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8490	MARLENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	984839105	\N	\N	\N	S
8491	MATEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6112345678	\N	\N	\N	S
8492	MAYLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8493	MÃE DO ÍTALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61981551835	\N	\N	\N	S
8494	MILLENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8495	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8496	RAYSSANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8497	ROSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8498	SILVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6186193519	\N	\N	\N	S
8499	STEFANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8500	TAIVIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	992908002	\N	\N	\N	S
8501	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6192908002	\N	\N	\N	S
8502	THALYSSON SERGIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	984959054	\N	\N	\N	S
8503	THAYNARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194092256	\N	\N	\N	S
8504	LUCAS SANTOS DE MOURA	lucasdaifth@gmail.com	ff377aff39a9345a9cca803fb5c5c081	2017-05-01	\N	6791655178	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8505	DARINALDO JUNIO SILVA MONTEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8506	AURICELIA DE CARVALHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61986534350	\N	\N	\N	S
8507	BRENO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8508	CLEBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6186682764	\N	\N	\N	S
8509	CLEBERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6186682764	\N	\N	\N	S
8510	DOGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8511	FABIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61993708749	\N	\N	\N	S
8512	FABIO SANTOS SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8513	FABRICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61993708749	\N	\N	\N	S
8514	GABRIELA SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61986534350	\N	\N	\N	S
8515	GEICYANE MOURA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61983740611	\N	\N	\N	S
8516	GIGANTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8517	GILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8518	GORDIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8519	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8520	JUNIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8521	KEVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6198586216	\N	\N	\N	S
8522	LEANDRO EVANGELISTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8523	MAICON WILLIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61993708749	\N	\N	\N	S
8524	MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8525	NEGUIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8526	PAULA MARTINS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8527	PEDRO OLIVEIRA LL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8528	SAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61986361881	\N	\N	\N	S
8529	SARA MORENO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61983740611	\N	\N	\N	S
8530	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8531	THIAGO SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8532	TITAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8533	VINI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8534	VINICíOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61994236236	\N	\N	\N	S
8535	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6186534350	\N	\N	\N	S
8536	ZAINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6194236236	\N	\N	\N	S
8537	BEATRIZ DE OLIVEIRA FRAGA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61993981072	\N	\N	\N	S
8538	LEANDRO EVANGELISTA COSTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6195065297	\N	\N	\N	S
8539	MARIANA GABRIELA APARECIDA DA SILVA MONTEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6184068574	\N	\N	\N	S
8540	MARIANA GABRIELA APARECIDA DA SILVA MONTEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6184068574	\N	\N	\N	S
8541	WDSON ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6186361881	\N	\N	\N	S
8542	DARINALDO DA SILVA MONTEIRO	darinaldo63@gmail.com	2183a013c8c0b3a824cbe3b621709548	2017-05-01	\N	5952706118	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8543	LUIZ FILIPE DO SANTOS LEITE	sluizfilipe3@gmail.com	654e4dc5b90b7478671fe6448cab3f32	2017-05-01	\N	5559429104	\N	\N	\N	\N	15:42:04	\N	\N	\N	\N	\N	N
8544	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61985513638	\N	\N	\N	S
8545	PEDRO H	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	6185143283	\N	\N	\N	S
8546	ALEX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61991058759	\N	\N	\N	S
8547	ALLYSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61981469931	\N	\N	\N	S
8548	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61985091405	\N	\N	\N	S
8549	AUGUSTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61993221285	\N	\N	\N	S
8550	CLAUDIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:42:04	\N	61985619181	\N	\N	\N	S
8551	DANILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995516483	\N	\N	\N	S
8552	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61991839208	\N	\N	\N	S
8553	DAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992925819	\N	\N	\N	S
8554	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61982805825	\N	\N	\N	S
8555	FABRINI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61982637667	\N	\N	\N	S
8556	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992547501	\N	\N	\N	S
8557	FILIPE H	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985697391	\N	\N	\N	S
8558	JOYCE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984977268	\N	\N	\N	S
8559	ISRAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992222393	\N	\N	\N	S
8560	JEANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61991650286	\N	\N	\N	S
8561	JOSSI DE JESUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985372755	\N	\N	\N	S
8562	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985091405	\N	\N	\N	S
8563	KAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195819333	\N	\N	\N	S
8564	LEONARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985398843	\N	\N	\N	S
8565	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185091405	\N	\N	\N	S
8566	SARAH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61983114494	\N	\N	\N	S
8567	THAMIRES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985091405	\N	\N	\N	S
8568	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185091405	\N	\N	\N	S
8569	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992203772	\N	\N	\N	S
8570	ROGER ALVES SAMPAIO	roger.alves.bsb@gmail.com	2301b59830e2b8e3f334210b8202deb4	2017-05-01	\N	4955787118	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8571	PEDRO HENRIQUE RODRIGUES SOARES	pe2013soares514@gmail.com	afab4c4599068498ef226eca224f00dd	2017-05-01	\N	2737052106	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8572	LUIS FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185621526	\N	\N	\N	S
8573	ROGER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6181469931	\N	\N	\N	S
8574	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995694619	\N	\N	\N	S
8575	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993116377	\N	\N	\N	S
8576	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185143283	\N	\N	\N	S
8577	FELIPE HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984977268	\N	\N	\N	S
8578	FILIPE CARVALHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61983441687	\N	\N	\N	S
8579	JEAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61994107551	\N	\N	\N	S
8580	JONATAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185143283	\N	\N	\N	S
8581	JOSSI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985091405	\N	\N	\N	S
8582	LUKAS GONçALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995694619	\N	\N	\N	S
8583	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995079300	\N	\N	\N	S
8584	MIQUEIAS FALCAO	miqueias.eufalcao@gmail.com	5300412712edaf8533dbf98a90c402b2	2017-05-01	\N	1537550160	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8585	ANDREIA MARIA	andreiabkb10@gmail.com	\N	2017-05-01	\N	2853599167	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8586	BRUNO ENRIC	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61994034461	\N	\N	\N	S
8587	ELIANIR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986539691	\N	\N	\N	S
8588	ELIDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993380246	\N	\N	\N	S
8589	EMILLY CATARINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985548047	\N	\N	\N	S
8590	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61994034462	\N	\N	\N	S
8591	KEVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61994034461	\N	\N	\N	S
8592	WELBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61994034462	\N	\N	\N	S
8593	DANIEL AIRES FALCAO	daniel.a.falcao@hotmail.com	bd5af7cd922fd2603be4ee3dc43b0b77	2017-05-01	\N	99203839100	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8594	SILVIA MIRANDA	silviacavalcante@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	718395182	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8595	CIDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61142	\N	\N	\N	S
8596	DIEGO AZEVEDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986351554	\N	\N	\N	S
8597	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61142	\N	\N	\N	S
8598	JUNIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61983661233	\N	\N	\N	S
8599	ALLAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985535637	\N	\N	\N	S
8600	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984462374	\N	\N	\N	S
8601	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61983192294	\N	\N	\N	S
8602	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61982256701	\N	\N	\N	S
8603	DAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185984007	\N	\N	\N	S
8604	DENIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	95719615	\N	\N	\N	S
8605	DIEGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996114409	\N	\N	\N	S
8606	ELIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993022948	\N	\N	\N	S
8607	ERIVELTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195917619	\N	\N	\N	S
8608	FABRICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61981619436	\N	\N	\N	S
8609	FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185535637	\N	\N	\N	S
8610	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992201958	\N	\N	\N	S
8611	FRANCISCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	95434985	\N	\N	\N	S
8612	GABI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985535637	\N	\N	\N	S
8613	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61981905382	\N	\N	\N	S
8614	GILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985403008	\N	\N	\N	S
8615	GLEUBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	95287562	\N	\N	\N	S
8616	GUSTAVO DIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992221237	\N	\N	\N	S
8617	HAMILTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993646557	\N	\N	\N	S
8618	HAUSLLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	84097626	\N	\N	\N	S
8619	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61981619436	\N	\N	\N	S
8620	ISIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195186438	\N	\N	\N	S
8621	JAILTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986351554	\N	\N	\N	S
8622	JENIFFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61982256701	\N	\N	\N	S
8623	JOãO PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986351554	\N	\N	\N	S
8624	JOSIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61994027247	\N	\N	\N	S
8625	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986518556	\N	\N	\N	S
8626	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986834573	\N	\N	\N	S
8627	KAUA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986345967	\N	\N	\N	S
8628	LIDIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992220341	\N	\N	\N	S
8629	LUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985013267	\N	\N	\N	S
8630	LUANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985535637	\N	\N	\N	S
8631	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985013267	\N	\N	\N	S
8632	LUIGY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61981619436	\N	\N	\N	S
8633	LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	86316914	\N	\N	\N	S
8634	MARCELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185535637	\N	\N	\N	S
8635	MARCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986518556	\N	\N	\N	S
8636	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61999428148	\N	\N	\N	S
8637	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	84962447	\N	\N	\N	S
8638	MATEUS OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985013267	\N	\N	\N	S
8639	MEURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	85139520	\N	\N	\N	S
8640	MICAELI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993022948	\N	\N	\N	S
8641	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993022948	\N	\N	\N	S
8642	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986518557	\N	\N	\N	S
8643	RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985535637	\N	\N	\N	S
8644	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993882961	\N	\N	\N	S
8645	SANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61983192294	\N	\N	\N	S
8646	SUELEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984462374	\N	\N	\N	S
8647	SUMAIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985954341	\N	\N	\N	S
8648	TATIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985197640	\N	\N	\N	S
8649	THAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195287562	\N	\N	\N	S
8650	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985702453	\N	\N	\N	S
8651	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61993027310	\N	\N	\N	S
8652	WALISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	85535637	\N	\N	\N	S
8653	YAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	95287562	\N	\N	\N	S
8654	THALYSON JONHY RIBEIRO ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995465180	\N	\N	\N	S
8655	SUZELLY APARECIDA CASSIANO DAS MERCêS GOMES	suzelly23@hotmail.com	bd5af7cd922fd2603be4ee3dc43b0b77	2017-05-01	\N	99852179187	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8656	SILVIA FALCÃO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985984007	\N	\N	\N	S
8657	ABADIA ABADIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984962447	\N	\N	\N	S
8658	ANDREIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6194175483	\N	\N	\N	S
8659	FABIO CASSIANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995187119	\N	\N	\N	S
8660	FRANCISCA MIRANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61995434985	\N	\N	\N	S
8661	JOCELAIDE DE JESUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984101909	\N	\N	\N	S
8662	KEILA LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984476841	\N	\N	\N	S
8663	MARCELLY CASSIANO DA MOTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61985631425	\N	\N	\N	S
8664	MARIA GOMES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61992174501	\N	\N	\N	S
8665	NEISVAM GOMES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61991632777	\N	\N	\N	S
8666	SAMARA CASSIANO DA MPTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986226688	\N	\N	\N	S
8667	MONALIZA RICARTT	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6199327397	\N	\N	\N	S
8668	MARCOS VINICIUS LACERDA	marcos.lacerda2000@gmail.com	ba518fc5e2f32a7e32d35e0259a558e8	2017-05-01	\N	6010863171	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8669	RENATO ALVES SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195465233	\N	\N	\N	S
8670	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6182256701	\N	\N	\N	S
8671	DIEGO CARIúS COUTINHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996500351	\N	\N	\N	S
8672	EDíLSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8673	EDINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8674	ERICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984249007	\N	\N	\N	S
8675	FABRICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8676	FERNANDINHO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8677	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8678	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6183648653	\N	\N	\N	S
8679	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6186383234	\N	\N	\N	S
8680	IGOR REIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61991903467	\N	\N	\N	S
8681	JAMERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195706034	\N	\N	\N	S
8682	JUNIOR WERNECK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6191203378	\N	\N	\N	S
8683	LUCAS 701	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6196908284	\N	\N	\N	S
8684	LUCAS FABRICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8685	LUIGGY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8686	LUIZ ALGUSTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8687	MARCELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6196908284	\N	\N	\N	S
8688	MARLENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8689	MãE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6183558009	\N	\N	\N	S
8690	NETA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6196908284	\N	\N	\N	S
8691	PAI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61984210199	\N	\N	\N	S
8692	RONALDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8693	THALYSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6192848243	\N	\N	\N	S
8694	THOMAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8695	ROBSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8696	ANTôNIO CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61996908284	\N	\N	\N	S
8697	THIEGO PINHEIRO DOS SANTOS	thiegog12@gmail.com	2218469881a18bce7e926350674682db	2017-05-01	\N	4677316163	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8698	INGRID MARTINS CARTH DOS SANTOS	ingrid.carth@gmail.com	\N	2017-05-01	\N	4019421129	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8699	JHONATA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6199838181	\N	\N	\N	S
8700	PABLO RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6192449903	\N	\N	\N	S
8701	TAINAR LAND	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6186328156	\N	\N	\N	S
8702	ANA CAROLINE FERREIRA MELO	ana.melo.pulsar@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	5321226156	\N	\N	\N	\N	15:43:04	\N	\N	\N	\N	\N	N
8703	DANIELLE GOMES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6193957074	\N	\N	\N	S
8704	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	95711690	\N	\N	\N	S
8705	ALICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6133782562	\N	\N	\N	S
8706	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195711690	\N	\N	\N	S
8707	ANIE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6185768895	\N	\N	\N	S
8708	ARIELSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	33782562	\N	\N	\N	S
8709	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6196936168	\N	\N	\N	S
8710	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195711690	\N	\N	\N	S
8711	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195711690	\N	\N	\N	S
8712	D. ESMERALDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6196936163	\N	\N	\N	S
8713	D. MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6196936163	\N	\N	\N	S
8714	D.BENVINDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	96936168	\N	\N	\N	S
8715	DANIELE LOPES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	61986048086	\N	\N	\N	S
8716	DANIELZIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	96722226	\N	\N	\N	S
8717	DéBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	33778077	\N	\N	\N	S
8718	EDIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	96936168	\N	\N	\N	S
8719	EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	6195711690	\N	\N	\N	S
8720	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	82874181	\N	\N	\N	S
8721	ERICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:43:04	\N	93304341	\N	\N	\N	S
8722	FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6133782562	\N	\N	\N	S
8723	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33782562	\N	\N	\N	S
8724	GABRIELA2	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6196936168	\N	\N	\N	S
8725	GABY 1	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6196936168	\N	\N	\N	S
8726	INGREDI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6186048086	\N	\N	\N	S
8727	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95711690	\N	\N	\N	S
8728	JOSé MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	96722226	\N	\N	\N	S
8729	JUJU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	86382416	\N	\N	\N	S
8730	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6196936168	\N	\N	\N	S
8731	LúCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	82874181	\N	\N	\N	S
8732	LUCILENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	91544876	\N	\N	\N	S
8733	LUIZ FERANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6196936168	\N	\N	\N	S
8734	M. EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95711690	\N	\N	\N	S
8735	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	81710050	\N	\N	\N	S
8736	MIKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	81710050	\N	\N	\N	S
8737	MILENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195711690	\N	\N	\N	S
8738	NATASHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95328382	\N	\N	\N	S
8739	NAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	86382416	\N	\N	\N	S
8740	NILDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	96936168	\N	\N	\N	S
8741	OSVALDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	96875	\N	\N	\N	S
8742	PADRASTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	96936168	\N	\N	\N	S
8743	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	99256371	\N	\N	\N	S
8744	POLLYANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	81515129	\N	\N	\N	S
8745	SAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195711690	\N	\N	\N	S
8746	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6196722226	\N	\N	\N	S
8747	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33782562	\N	\N	\N	S
8748	TATILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	96936168	\N	\N	\N	S
8749	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	96936168	\N	\N	\N	S
8750	VANEIDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	91614512	\N	\N	\N	S
8751	VICTORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95711690	\N	\N	\N	S
8752	WALERIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6194123980	\N	\N	\N	S
8753	WELITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6196936168	\N	\N	\N	S
8754	YASMIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	92460353	\N	\N	\N	S
8755	JULIANA BATISTA NOGUEIRA	julianabkb@gmail.com	c4ca4238a0b923820dcc509a6f75849b	2017-05-01	\N	6950556152	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8756	ADRIELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6133782562	\N	\N	\N	S
8757	DANIELA RODRIGUêS DOS SANTOS	danielarodriguesbkb@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	6948544177	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8758	THATILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61985810812	\N	\N	\N	S
8759	THIFFANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61986324707	\N	\N	\N	S
8760	ANA LUIZA BARBOSA ROCHA	aninhagdt2@gmail.com	694e6d6fee04eaba07eaae536afa540e	2017-05-01	\N	37248542504	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8761	SARA KELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8762	WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8763	ADRIELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8764	ANA LUIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8765	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8766	BARBARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8767	CATARINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8768	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8769	DANIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33357345	\N	\N	\N	S
8770	DEIZIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8771	ELISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8772	ELISA 1	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8773	ELISA 1	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8774	ELISA 1	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8775	ELISA 1	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8776	ELISA 2	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8777	ELISA 2	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8778	ELISA BRENDOON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8779	ELISA JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8780	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8781	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8782	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8783	GABRIEL DEIZI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8784	GABRIEL FERREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8785	HELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8786	HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8787	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8788	JESSICA IRMA HELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8789	JOAO VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8790	JOãO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8791	JOICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8792	KLISMAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8793	MAISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8794	MANUH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33777077	\N	\N	\N	S
8795	MARCO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8796	MARIA VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8797	MATHEUS VERISSIMO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8798	MEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8799	MISAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8800	NAMORADA DO HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195828541	\N	\N	\N	S
8801	NICOLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8802	NICOLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8803	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8804	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8805	RAQUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8806	REISIARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8807	ROMILDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33573450	\N	\N	\N	S
8808	TAISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8809	TAISA 1	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8810	TAISA 2	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8811	VICTOR NAMORADO DA BARBARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8812	VICTORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8813	VINICIUS RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8814	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	33778077	\N	\N	\N	S
8815	VIVIANE RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61995291454	\N	\N	\N	S
8816	JOãO HENRIQUE ARAúJO PEREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	86412453	\N	\N	\N	S
8817	JONATHAN GABRIEL FERREIRA RAMOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8818	MARIA DEIZIANE DUARTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8819	SARA KELLEN EVANGELISTA DIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6195291454	\N	\N	\N	S
8820	LAISA VITORIA DA ROCHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61983383006	\N	\N	\N	S
8821	DENER RIBEIRO DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61985498552	\N	\N	\N	S
8912	STEFANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8822	LAISA VITORIA DA ROCHA BEZERRA	laisa-silva2010@hotmail.com	827ccb0eea8a706c4c34a16891f84e7b	2017-05-01	\N	55359531134	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8823	RAVENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	92625677	\N	\N	\N	S
8824	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8825	BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8826	DAVI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8827	EMERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83018304	\N	\N	\N	S
8828	ESTEFANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	85897653	\N	\N	\N	S
8829	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83297734	\N	\N	\N	S
8830	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	85425778	\N	\N	\N	S
8831	FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8832	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95893064	\N	\N	\N	S
8833	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8834	JOãO VICTOR CANDIDO DA ROCHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95291454	\N	\N	\N	S
8835	JULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8836	LAIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8837	LARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8838	LéO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	95309548	\N	\N	\N	S
8839	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8840	MARIA EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8841	MENINA DA RAVENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8842	MYLENNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	92127631	\N	\N	\N	S
8843	NALANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8844	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8845	RAQUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	86516781	\N	\N	\N	S
8846	SUELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	93143972	\N	\N	\N	S
8847	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8848	THAYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	83197734	\N	\N	\N	S
8849	AYANNE BIANCA COSTA LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	85120639	\N	\N	\N	S
8850	ÍTALO DE JESUS SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	61993735469	\N	\N	\N	S
8851	FERNANDA CORTê ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	84604579	\N	\N	\N	S
8852	JOAO VICTOR CANDIDO DA ROCHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6183383006	\N	\N	\N	S
8853	JULIO CéSAR ARAúJO CORTES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	93435416	\N	\N	\N	S
8854	MAURO CéSAR DOS SANTOS VAZ DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	91291926	\N	\N	\N	S
8855	THáCILA CRISTINA MIRANDA FREIRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:44:04	\N	6185000140	\N	\N	\N	S
8856	FERNANDA CORTÊ ALVES	fernanda-corte@hotmail.com	3e43ee10cbb28e91d55e443ba8ab847e	2017-05-01	\N	6580370130	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8857	FILIPE DANTAS DA SILVA	filipe.dantah@hotmail.com	bbb16a819a3d58e30d664b1229536fe5	2017-05-01	\N	1098431189	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8858	JULIA ARAúJO DE ASSIS	juh.araujo@hotmail.com	4c184154cbfe0526259d0ec27f90281a	2017-05-01	\N	882298151	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8859	ANDERSON CALDAS DE OLIVEIRA JúNIOR	andersongdt8@gmail.com	694e6d6fee04eaba07eaae536afa540e	2017-05-01	\N	45994142123	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8860	JOYCE ALVES	joycegdt8@gmail.com	4297f44b13955235245b2497399d7a93	2017-05-01	\N	8796627190	\N	\N	\N	\N	15:44:04	\N	\N	\N	\N	\N	N
8861	JESSICA PEREIRA MARTINS	aninhagdt1@gmail.com	cbd3d0a055832793331bfc716e6e7ffe	2017-05-01	\N	77412514148	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8862	PEDRO HENRIQUE BARBOSA DE ARAúJO COSTA	pedrohenrique@gmail.com	606718bab0487154d81595a443d864ec	2017-05-01	\N	18929471102	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8863	FERNANDA CORTE ALVES	fernanda.alves@gmail.com	76b5f504b16539ba9ab08586641d7afb	2017-05-01	\N	73309074137	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8864	MARIA DEIZIANE	mariade@gmail.com	43588699f99885067ab3c784b83e543d	2017-05-01	\N	62443113027	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8865	JUNO SOARES LOPES MOREIRA	junnolopes@gmail.com	01540f319ff0cf88928c83de23127fbb	2017-05-01	\N	3524524125	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8866	DENISE NASCIMENTO DOS SANTOS	denisenascimentosdossantos@gmail.com	f63a7ef4c98342627bda17fc4edbe5e2	2017-05-01	\N	2907873199	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8867	ELSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8868	AMIGO GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6132323232	\N	\N	\N	S
8869	ANA CRISTINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8870	ANTôNIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8871	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8872	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8873	CLAUDIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8874	CRIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	33517325	\N	\N	\N	S
8875	DEISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8876	DENISE SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8877	DONA ANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8878	ERON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8879	FABIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8880	FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8881	FRANCISCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8882	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8883	HEITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8884	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8885	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8886	JENIFFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86368022	\N	\N	\N	S
8887	JESSICA LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6686029522	\N	\N	\N	S
8888	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6194010120	\N	\N	\N	S
8889	JUSCELINO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8890	KETLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8891	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8892	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6191685914	\N	\N	\N	S
8893	LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8895	MICHAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186727888	\N	\N	\N	S
8896	MICHELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	95799908	\N	\N	\N	S
8897	MICHELLE COSTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8898	MIRANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8899	PAI ANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6392022431	\N	\N	\N	S
8900	PAULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8901	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8902	PRIMO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86053485	\N	\N	\N	S
8903	RAFAEL DOMINI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81501339	\N	\N	\N	S
8904	RAFAEL SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	95765995	\N	\N	\N	S
8905	RAYZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8906	RIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8907	ROBERTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6193862781	\N	\N	\N	S
8908	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8909	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8910	SANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8911	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8913	SUZY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8914	TECA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8915	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86368022	\N	\N	\N	S
8916	VALDIRENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8917	VERA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6186029522	\N	\N	\N	S
8918	VERA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	6193862781	\N	\N	\N	S
8919	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8920	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8921	WALLACE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8922	WALLISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8923	WESLLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8924	WILLIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86029522	\N	\N	\N	S
8925	ADRIELE JOSé DE SANTANA FONSECA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	96177317	\N	\N	\N	S
8926	RICARDO SOUSA MARQUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	84591328	\N	\N	\N	S
8927	ANA CRISTINA DIAS DE OLIVEIRA	cristina@gmail.com	84b712f6997be862e79698022cee299d	2017-05-01	\N	91113725168	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8928	MICAELE DIAS ALVES	micaelle@gmail.com	bde5f08eda796ee0d515d35ddaf0e230	2017-05-01	\N	5234459110	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8929	CRISIONEIDE PENHA	crispenha011@gmail.com	ed62ec11cd6233b450c566b19b1f2ea1	2017-05-01	\N	3709484162	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8930	WESLLEY OLIVEIRA	weslleyhope@gmail.com	d82d88497250414881936939a8e2e168	2017-05-01	\N	2212258119	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8931	JESSICA NARZIRA BENTO DE MELO	jessicanarzira12@hotmail.com	995ee2976c8b1d6bfda8f5b9f92eda7a	2017-05-01	\N	3384359135	\N	\N	\N	\N	15:45:04	\N	\N	\N	\N	\N	N
8932	ÉRIKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	8006	\N	\N	\N	S
8933	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85596264	\N	\N	\N	S
8934	CAROL NERES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	82916071	\N	\N	\N	S
8935	CAROLINE FRANçA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	91281641	\N	\N	\N	S
8936	DANILA BATISTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81324567	\N	\N	\N	S
8937	ESTEFANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	95654512	\N	\N	\N	S
8938	ESTER HADASSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	98956321	\N	\N	\N	S
8939	GRACIELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85963665	\N	\N	\N	S
8940	ISABELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86775544	\N	\N	\N	S
8941	ISABELLA FRANçA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86775544	\N	\N	\N	S
8942	JARLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	93067187	\N	\N	\N	S
8943	JéSSYCA JEJE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	82008816	\N	\N	\N	S
8944	KáTIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	83507158	\N	\N	\N	S
8945	KEVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85699636	\N	\N	\N	S
8946	LETíCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	98127747	\N	\N	\N	S
8947	LUDMILLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	82622236	\N	\N	\N	S
8948	LUMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86789090	\N	\N	\N	S
8949	MAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	96698785	\N	\N	\N	S
8950	RAFAELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	99845623	\N	\N	\N	S
8951	RODRIGO NAMORADO CAROL FRANçA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86775544	\N	\N	\N	S
8952	STéPHANE ODETE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86526312	\N	\N	\N	S
8953	TATIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	95339302	\N	\N	\N	S
8954	TATIELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	93147871	\N	\N	\N	S
8955	TAYENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	93147871	\N	\N	\N	S
8956	VíTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	8006	\N	\N	\N	S
8957	VERA FRANçA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86775544	\N	\N	\N	S
8958	ANA FLAVIA CAVALCANTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86471114	\N	\N	\N	S
8959	ANA LUIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	93048014	\N	\N	\N	S
8960	ANA PAULA VIS MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	84880861	\N	\N	\N	S
8961	ANA PAULA VIS MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81357127	\N	\N	\N	S
8962	ANGéLICA JEJE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81357127	\N	\N	\N	S
8963	ANSELMO GOMES DE FREITAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85781091	\N	\N	\N	S
8964	ANTONIO ALVES SALES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81967784	\N	\N	\N	S
8965	ANTONIO FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35973874	\N	\N	\N	S
8966	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86187307	\N	\N	\N	S
8967	BRENDA N	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85030072	\N	\N	\N	S
8968	BRENO BRASIL SILVA LEITE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35226935	\N	\N	\N	S
8969	BRENO M	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	82457671	\N	\N	\N	S
8970	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35973874	\N	\N	\N	S
8971	CAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35973874	\N	\N	\N	S
8972	CARLOS  DE LOPES BARROS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	91505398	\N	\N	\N	S
8973	DAVI FONTEENELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35973874	\N	\N	\N	S
8974	DAVID VIS MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	99686466	\N	\N	\N	S
8975	DENISE GABRIELA COSTA DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	86471114	\N	\N	\N	S
8976	EDUARDA FERRAZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85969636	\N	\N	\N	S
8977	FLAVIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35973874	\N	\N	\N	S
8978	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	35973874	\N	\N	\N	S
8979	INGRID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	92645748	\N	\N	\N	S
8980	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85963663	\N	\N	\N	S
8981	LARA CELINA PEREIRA DE LIMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	93400304	\N	\N	\N	S
8982	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81780752	\N	\N	\N	S
8983	LARISSA JEJE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	81357127	\N	\N	\N	S
8984	LEONARDO COIMBRA DE OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	83175768	\N	\N	\N	S
8985	LUCAS VIS BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	33526963	\N	\N	\N	S
8986	LUCIANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	91915100	\N	\N	\N	S
8987	MARIA EDUARDA BATISTA DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	85498754	\N	\N	\N	S
8988	MARIA LUíSA VIS MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	95365067	\N	\N	\N	S
8989	MARIA PAULA VIS BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	91915898	\N	\N	\N	S
8990	MARIANA XIMENES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	84972848	\N	\N	\N	S
8991	MAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	92393850	\N	\N	\N	S
8992	MICHAEL NASCIMENTO DE SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	92618337	\N	\N	\N	S
8993	RITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	99384423	\N	\N	\N	S
8994	SéRGIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	84868723	\N	\N	\N	S
8995	VINICIUS VIS BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	33521837	\N	\N	\N	S
8996	CINTYA SOARES VIEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	61386092650	\N	\N	\N	S
8997	CRISTIANE DA SILVA COSTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:45:04	\N	981382733	\N	\N	\N	S
8998	MATHEUS MARQUES SILVA	matheusbeautiful16@hotmail.com	d82d88497250414881936939a8e2e168	2017-05-01	\N	5589678170	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
8999	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185156047	\N	\N	\N	S
9000	ELIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185156047	\N	\N	\N	S
9001	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985156047	\N	\N	\N	S
9002	LILIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185156047	\N	\N	\N	S
9003	LINDOLFO SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985156047	\N	\N	\N	S
9004	MARCUS LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185156047	\N	\N	\N	S
9005	MARIA EDNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185156047	\N	\N	\N	S
9006	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185156047	\N	\N	\N	S
9007	FILIPE MARQUES SILVA	t.marques9@hotmail.com	d82d88497250414881936939a8e2e168	2017-05-01	\N	6614782118	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9008	BRUNO RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6194221844	\N	\N	\N	S
9009	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185757768	\N	\N	\N	S
9010	DAVI MENDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185757768	\N	\N	\N	S
9011	ALEX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9012	ANA ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	93226969	\N	\N	\N	S
9013	ANSELMO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85717768	\N	\N	\N	S
9014	CRISTINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61685757768	\N	\N	\N	S
9015	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9016	DARLAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9017	EMERSON KAUê	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9018	FILIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9019	FLAVIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9020	GABRIEL BEDEF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9021	GABRIEL RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	84852626	\N	\N	\N	S
9022	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85261556	\N	\N	\N	S
9023	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9024	GUTEMBERG	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	81533517	\N	\N	\N	S
9025	ITALO PINHEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6183018241	\N	\N	\N	S
9026	JHONNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9027	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9028	KLEBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9029	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185757768	\N	\N	\N	S
9030	LUCAS LEBLON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9031	LUCCA YAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9032	MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6185757768	\N	\N	\N	S
9033	MATHEUS ELOI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	99381439	\N	\N	\N	S
9034	MOISES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	85757768	\N	\N	\N	S
9035	NAIGUER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9036	NAYLSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9037	RAFAEL RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	84852626	\N	\N	\N	S
9038	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6193231691	\N	\N	\N	S
9039	RYAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9040	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9041	TALYNE DOURADO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6186744741	\N	\N	\N	S
9042	YAGO LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9043	CARLA CAROLINE BATISTA DE BRITO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985924702	\N	\N	\N	S
9044	DARLAN FELIPE CAMILO PEIXOTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6186744741	\N	\N	\N	S
9045	DAVI MENDES SARAIVA DO NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9046	EZEQUIEL MENDES DO NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9047	GABRIEL  VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985235670	\N	\N	\N	S
9048	KAIO EDUARDO SOARES ROCHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985757768	\N	\N	\N	S
9049	MATHEUS CIRQUEIRA CARLOS DOS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61993231691	\N	\N	\N	S
9050	MAXSUEL RIBEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985156047	\N	\N	\N	S
9051	RODRIGO LEONE COSTA SANTOS	digoleone1997@gmail.com	6b90313cbcc844e849b07cd9c5967ad8	2017-05-01	\N	70210335165	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9052	MAXSUEL RIBEIRO DAMASCENO	gm_mania@hotmail.com	d82d88497250414881936939a8e2e168	2017-05-01	\N	6572675114	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9053	EZEQUIEL MENDES DO NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	67985783936	\N	\N	\N	S
9054	MATHEUS CIRQUEIRA CARLOS DOS SANTOS	godmat.santos1@hotmail.com	5c5c41153f4136ae0890af52d66555a8	2017-05-01	\N	6324194159	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9055	LUCAS MULLER	luucassmuller129@gmail.com	373e71796c13571a633c3add68eb444d	2017-05-01	\N	6456830192	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9056	SAMUEL RIBEIRO  CORRêA	samuca754@gmail.com	\N	2017-05-01	\N	7089540146	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9057	WESLEY SANTOS BRITO DA SILVA	afirmaboawesley@gmail.com	d82d88497250414881936939a8e2e168	2017-05-01	\N	4934381147	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9058	AILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9059	ALYSSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9060	ANDRé	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9061	ATHISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9062	BRUNO S.	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9063	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9064	DANILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195299523	\N	\N	\N	S
9065	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9066	DHOMINNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9067	EDMILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61995648736	\N	\N	\N	S
9068	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9069	FLAVIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9070	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9071	GEISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6193143711	\N	\N	\N	S
9072	GILVAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9073	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9074	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9075	HELIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61995648736	\N	\N	\N	S
9076	HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9077	HYAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61995648736	\N	\N	\N	S
9078	IAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9079	JEAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9080	JEAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9081	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9082	JORGE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9083	JOSé	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9084	JULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9085	KLEZIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9086	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9087	LETíCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6193298321	\N	\N	\N	S
9088	LORRANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61995648736	\N	\N	\N	S
9089	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9090	LUZIER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9091	MARCELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9092	MARIA EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9093	MATEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9094	MATEUS P.	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9095	MIRALVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9096	MURILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6184261343	\N	\N	\N	S
9097	PABLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9098	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9099	RENAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9100	RENê	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9101	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9102	SAMANTHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9103	SOCORRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9104	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6193191857	\N	\N	\N	S
9105	VINICIUS K	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9106	WAGNER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9107	WALISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9108	WALLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6184796329	\N	\N	\N	S
9109	WELKISLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6182160963	\N	\N	\N	S
9110	WESNEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9111	WISLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6195648736	\N	\N	\N	S
9112	MARCOS ANTONIO FERREIRA DE SOUSA FILHO	markinferreira130@gmail.com	d82d88497250414881936939a8e2e168	2017-05-01	\N	5115986108	\N	\N	\N	\N	15:46:04	\N	\N	\N	\N	\N	N
9113	GABRIELA NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61995364521	\N	\N	\N	S
9114	EMANUEL MEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	6199140089	\N	\N	\N	S
9115	FELIPE BARBOSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985303751	\N	\N	\N	S
9116	FERNANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985034508	\N	\N	\N	S
9117	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61995623964	\N	\N	\N	S
9118	JHONATAN FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61996458597	\N	\N	\N	S
9119	LUBERTH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61991400086	\N	\N	\N	S
9120	LUCAS DAMACENO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985458045	\N	\N	\N	S
9121	MARCUS LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985364520	\N	\N	\N	S
9122	NATALHA NASCIMENTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61996563426	\N	\N	\N	S
9123	PABLO GUIMARAES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61985357571	\N	\N	\N	S
9124	VITOR MASCARENHAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61991359787	\N	\N	\N	S
9125	WELINGTON TARGINO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61984508837	\N	\N	\N	S
9126	WENDERDON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:46:04	\N	61993781556	\N	\N	\N	S
9127	MARIANA RODRIGUES XIMENES	mariianaximenes14@gmail.com	f2d84dd57a20557341ebd778c55fbd84	2017-05-01	\N	7066789156	\N	\N	\N	\N	15:47:04	\N	\N	\N	\N	\N	N
9128	RAYNARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6198888881	\N	\N	\N	S
9129	THAYNARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6193323228	\N	\N	\N	S
9130	WELLINGTON EUGENIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	94341215	\N	\N	\N	S
9131	ALEF RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86456410	\N	\N	\N	S
9132	ANA PAULA ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	84880861	\N	\N	\N	S
9133	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6191111111	\N	\N	\N	S
9134	ESTER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	89999999	\N	\N	\N	S
9135	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6197711111	\N	\N	\N	S
9136	FERNANDA MACEDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93797743	\N	\N	\N	S
9137	FLAVIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6181111111	\N	\N	\N	S
9138	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	87111111	\N	\N	\N	S
9139	IVANI MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85164528	\N	\N	\N	S
9140	JOSé DEUZIMAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	81265863	\N	\N	\N	S
9141	LETíCIA RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86076714	\N	\N	\N	S
9142	LUíS FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61911111111	\N	\N	\N	S
9143	MARIA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6191111111	\N	\N	\N	S
9144	MARINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91111111	\N	\N	\N	S
9145	NATHANAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6189111111	\N	\N	\N	S
9146	PEDRO PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91111111	\N	\N	\N	S
9147	RODRIGO JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91111111	\N	\N	\N	S
9148	SILVIO CARDOSO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	81111111	\N	\N	\N	S
9149	SOPHIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	99111111	\N	\N	\N	S
9150	STEFHANNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91111111	\N	\N	\N	S
9151	THAIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6185511111	\N	\N	\N	S
9152	VINICIUS VITAL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	84069840	\N	\N	\N	S
9153	WALLYSSON EUGêNIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86448051	\N	\N	\N	S
9154	BIANCA RODRIGUES BRAGA	biancarbraga2001@gmail.com	bcfd3362a9be71713097a70bae725295	2017-05-01	\N	6298566139	\N	\N	\N	\N	15:47:04	\N	\N	\N	\N	\N	N
9155	HUDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9156	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9157	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9158	ANA KAROLINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9159	BIANCA 19	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9160	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9161	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6033567074	\N	\N	\N	S
9162	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6033567074	\N	\N	\N	S
9163	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9164	DANIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9165	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9166	DEBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9167	EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9168	ELISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9169	EMILY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9170	FABRíCIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9171	FERNANDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9172	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9173	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9174	IAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9175	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6633567074	\N	\N	\N	S
9176	JERONIMO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9177	JHONATAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9178	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6033567074	\N	\N	\N	S
9179	JOãO VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9180	JOSé	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9181	JULIA C	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	62985596264	\N	\N	\N	S
9182	JULIA V	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9183	KAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9184	KAYAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9185	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9186	MARCUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6033567074	\N	\N	\N	S
9187	MARIA ISABEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6033567074	\N	\N	\N	S
9188	MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9189	MARIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9190	MARLEIDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9191	MEIRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9192	RAQUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9193	RAUL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	22567074	\N	\N	\N	S
9194	RAYNARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6133567074	\N	\N	\N	S
9195	RENAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9196	RICHELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9197	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9198	TALITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9199	TAMI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985496264	\N	\N	\N	S
9200	THALES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9201	THAYNARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9202	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9203	TORó	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9204	VICTOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9205	VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	33567074	\N	\N	\N	S
9206	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9207	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9208	CARLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9209	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9210	JOELMA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9211	ANDRIELY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9212	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9213	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61985596264	\N	\N	\N	S
9214	MARIANA CORTE MEIRELLES	mcm-1997@hotmail.com	7a50aea9f0691d5e1c1e106d7fe31f57	2017-05-01	\N	3565100184	\N	\N	\N	\N	15:47:04	\N	\N	\N	\N	\N	N
9215	JéSSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93979446	\N	\N	\N	S
9216	LARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93400304	\N	\N	\N	S
9217	ANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85195846	\N	\N	\N	S
9218	ANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93400304	\N	\N	\N	S
9219	BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85030072	\N	\N	\N	S
9220	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61994022121	\N	\N	\N	S
9221	CAMILLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	984301416	\N	\N	\N	S
9222	CAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	81058429	\N	\N	\N	S
9223	DéBORA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86451972	\N	\N	\N	S
9224	DENISE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86471114	\N	\N	\N	S
9225	EMANUELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93400304	\N	\N	\N	S
9226	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61984146843	\N	\N	\N	S
9227	JENIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61995964537	\N	\N	\N	S
9228	MANUELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964537	\N	\N	\N	S
9229	MARIA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93979446	\N	\N	\N	S
9230	NATáLIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61984146843	\N	\N	\N	S
9231	NICOLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61984146843	\N	\N	\N	S
9232	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61991436159	\N	\N	\N	S
9233	RHAYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	84617700	\N	\N	\N	S
9234	RODRIGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61995964537	\N	\N	\N	S
9235	SAYURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	93391536	\N	\N	\N	S
9236	SHALANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6195964537	\N	\N	\N	S
9237	THAISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85532483	\N	\N	\N	S
9238	THAYNNARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91769865	\N	\N	\N	S
9239	HOSANA CORTE MEIRELLES	corte.m.hosana@gmail.com	2879d61cea00b18e16affc17dcfe006f	2017-05-01	\N	3565101156	\N	\N	\N	\N	15:47:04	\N	\N	\N	\N	\N	N
9240	ELCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95393854	\N	\N	\N	S
9241	THAINá	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	84367162	\N	\N	\N	S
9242	ALISSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9243	ALVEDIR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92231134	\N	\N	\N	S
9244	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	96111677	\N	\N	\N	S
9245	ANALICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9246	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9247	CAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9248	CAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86645128	\N	\N	\N	S
9249	CINEIDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9250	DENILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	995964919	\N	\N	\N	S
9251	EDI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9252	EDITE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9253	ERICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9254	FABIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9255	FABIOLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192851563	\N	\N	\N	S
9256	FáBIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9257	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	995964919	\N	\N	\N	S
9258	FLAVIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9259	FRANCISCO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	995964919	\N	\N	\N	S
9260	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9261	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85677452	\N	\N	\N	S
9262	HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95961151	\N	\N	\N	S
9263	JEFFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91296525	\N	\N	\N	S
9264	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86645128	\N	\N	\N	S
9265	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	96125162	\N	\N	\N	S
9266	JESSIKA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	91296525	\N	\N	\N	S
9267	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95963356	\N	\N	\N	S
9268	KELVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95968811	\N	\N	\N	S
9269	LAISE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9270	LAURA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	995964919	\N	\N	\N	S
9271	LUANA STEFAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92481915	\N	\N	\N	S
9272	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9273	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9274	LUIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85499754	\N	\N	\N	S
9275	MARIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	98565422	\N	\N	\N	S
9276	MATEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9277	MAURICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	81716191	\N	\N	\N	S
9278	NADIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9279	NATALIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9280	NAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9281	NIEDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	86645128	\N	\N	\N	S
9282	NILDELENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9283	PABLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9284	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9285	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	82135281	\N	\N	\N	S
9286	SISITO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9287	TAIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	84185866	\N	\N	\N	S
9288	TALITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85498754	\N	\N	\N	S
9289	VALERIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95964919	\N	\N	\N	S
9290	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9291	WELLINGTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	95634451	\N	\N	\N	S
9292	WILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	92851563	\N	\N	\N	S
9293	YURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	85654412	\N	\N	\N	S
9294	GABRIEL MARQUES DE SOUSA	gabrielmarques.gmds@gmail.com	fde49117a651f0896cfa97f59f99b44f	2017-05-01	\N	14049550628	\N	\N	\N	\N	15:47:04	\N	\N	\N	\N	\N	N
9295	FERNANDA ALVES FERREIRA	fefe19gara@hotmail.com	1682776e775d1fa84d30d360e26b5837	2017-05-01	\N	8202319161	\N	\N	\N	\N	15:47:04	\N	\N	\N	\N	\N	N
9296	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9297	ERICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9298	FABIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6193324178	\N	\N	\N	S
9299	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6191048989	\N	\N	\N	S
9300	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6196953262	\N	\N	\N	S
9301	HUGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9302	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9303	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9304	VALERIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	61995963234	\N	\N	\N	S
9305	WELLINGTON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9306	WILLSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:47:04	\N	6192444737	\N	\N	\N	S
9307	TALITA FERRAZ SCHUENCK	schuenck@outlook.com	fe0cd002010d33def4798da2cf9ddee9	2017-05-01	\N	5066121129	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9308	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9309	CAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9310	CINTYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9311	DALILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6186094096	\N	\N	\N	S
9312	JARLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9313	JULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61986094096	\N	\N	\N	S
9314	KEVELYN YASMIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9315	PEDRO PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	986094096	\N	\N	\N	S
9316	SABRINA FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9317	SABRINA FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	986094096	\N	\N	\N	S
9318	SAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	84539964	\N	\N	\N	S
9319	SAMARA FERREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6186247208	\N	\N	\N	S
9320	ALESSANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	85524124	\N	\N	\N	S
9321	ANA RUBIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9322	BRENDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86084096	\N	\N	\N	S
9323	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9324	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6186094096	\N	\N	\N	S
9325	CAMILA MOREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	984953756	\N	\N	\N	S
9326	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9327	CARLOS RIBEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9328	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9329	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	860940096	\N	\N	\N	S
9330	GABRIELA LOSCHI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9331	GABRIELE RIBEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9332	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9333	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9334	HERMóGENES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	84809927	\N	\N	\N	S
9335	IANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9336	ISABELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86064096	\N	\N	\N	S
9337	JAKSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9338	JOABE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9339	JOãO GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9340	KEVELYN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9341	LúCIA MOREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9342	LETíCIA ANIZIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86084096	\N	\N	\N	S
9343	LIZANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	986094096	\N	\N	\N	S
9344	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9345	LUCIANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9346	LUIZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86455543	\N	\N	\N	S
9347	MANDY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9348	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9349	MARCUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95170710	\N	\N	\N	S
9350	MARIA EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9351	MATEUS GALVãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6193695067	\N	\N	\N	S
9352	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9353	OSMAR JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9354	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9355	REBECA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9356	RENAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9357	RENAN SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9358	RENATO FERNANDES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9359	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9360	STHEFANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9361	TAMY OLYS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9362	TIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9363	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	986094096	\N	\N	\N	S
9364	YASMIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	86094096	\N	\N	\N	S
9365	RAYNARA GREICIANE ROSA	raynara.greiciane123@gmail.com	3822b05608a9769cafa53959b876eb0d	2017-05-01	\N	5852074101	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9366	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61985616353	\N	\N	\N	S
9367	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	998807362	\N	\N	\N	S
9368	LUIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6196026336	\N	\N	\N	S
9369	MANUELLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	983439260	\N	\N	\N	S
9370	OSMAR JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	985616353	\N	\N	\N	S
9371	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6196536949	\N	\N	\N	S
9372	RAQUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	996244849	\N	\N	\N	S
9373	RAU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	84123472	\N	\N	\N	S
9374	RICHELLE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	983141846	\N	\N	\N	S
9375	TAMY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	85826944	\N	\N	\N	S
9376	TIAGO MOREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	996536949	\N	\N	\N	S
9377	SABRINA FERNANDES DOS SANTOS	sabrinafernandes100@gmail.com	7336dcdf418a2bfd19964c148180538f	2017-05-01	\N	6571782170	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9378	STEFANNY CARDOSO DA SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	985398422	\N	\N	\N	S
9379	THAYNARA RAMOS	thaynaraa91@gmail.com	52bced136754ea43f83515bb2d618b8b	2017-05-01	\N	4700290137	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9380	EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	96157051	\N	\N	\N	S
9381	KAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	96565881	\N	\N	\N	S
9382	STEFANY ALMEIDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	83740822	\N	\N	\N	S
9383	STEFANY LORRANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	91935094	\N	\N	\N	S
9384	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	84108195	\N	\N	\N	S
9385	THAIS MARQUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	982306534	\N	\N	\N	S
9386	MARCUS VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61985859332	\N	\N	\N	S
9387	ANA PAULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61984014822	\N	\N	\N	S
9388	THAIANE RAMOS DA SILVA MORAIS	thaianeramos37@gmail.com	5ac54354d180b11ffa41d67078ecd7cf	2017-05-01	\N	4700289120	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9389	IGOR YUDI	igor_yudj_1997@hotmail.com	9adca1ea7c45e488cdfd5402760c74ec	2017-05-01	\N	70678911169	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9390	FERNANDA GOMES ALBUQUERQUE	fernanda.nanda@hotmail.com.br	81dc9bdb52d04dc20036dbd8313ed055	2017-05-01	\N	6578663185	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9391	KAMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9392	MICHELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9393	ANA BEATRIZ SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9394	ANA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9395	ANDREY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9396	ANGELO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9397	AYLINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6195497415	\N	\N	\N	S
9398	AYSLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61995497415	\N	\N	\N	S
9399	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9400	BRENDHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	995497415	\N	\N	\N	S
9401	BRENO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	66195497415	\N	\N	\N	S
9402	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9403	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9404	DEIVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9405	DHIULLYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9406	EDVAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9407	ERICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9408	EVELYN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61695497415	\N	\N	\N	S
9409	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9410	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9411	GABRIELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9412	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	995497415	\N	\N	\N	S
9413	JANAYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9414	JúLIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6195497415	\N	\N	\N	S
9415	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6195497415	\N	\N	\N	S
9416	JOSYELLEN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9417	KALIELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	995497415	\N	\N	\N	S
9418	KAMILA MORAES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9419	KAMILLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9420	KAMYLLA MOREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9421	KAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9422	KAYLANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9423	LAVIER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9424	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9425	LUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9426	LUANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9427	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9428	LUCAS SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9429	LUDIMILLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9430	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9431	MARIA AMANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9432	MARIA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9433	MATEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9434	MATEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9435	MAURíCIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9436	NATHALIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9437	PATRICK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6195497415	\N	\N	\N	S
9438	PATRYCK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6095497415	\N	\N	\N	S
9439	RAFAELLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9440	RANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9441	RONEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	995497415	\N	\N	\N	S
9442	RYAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	995497415	\N	\N	\N	S
9443	SABRYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9444	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9445	STEFHANI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9446	TATIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9447	TâNIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6195497415	\N	\N	\N	S
9448	THAYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	95497415	\N	\N	\N	S
9449	VINíCIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6095497415	\N	\N	\N	S
9450	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	995497415	\N	\N	\N	S
9451	RAYNARA RAISSA	raynaararayssa@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	5958042173	\N	\N	\N	\N	15:48:04	\N	\N	\N	\N	\N	N
9452	THAIANE RAMOS DA SILVA MORAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6186524835	\N	\N	\N	S
9453	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6199169415	\N	\N	\N	S
9454	CAROLYNE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6185479843	\N	\N	\N	S
9455	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6186329885	\N	\N	\N	S
9456	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	6185818007	\N	\N	\N	S
9457	ANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:48:04	\N	61985929352	\N	\N	\N	S
9458	STEFANY LORRANY RAMOS RIBEIRO	stefanylorrany9@gmail.com	9ae1de1b2577ef683e179502838339d3	2017-05-01	\N	7838213100	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9459	WELLITON EUGêNIO	wellingtoncamara20111@gmail.com	b7d2e0abae9d6905dab5d375a23463eb	2017-05-01	\N	6994055132	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9460	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61911111111	\N	\N	\N	S
9461	ARTHUR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6192222222	\N	\N	\N	S
9462	ISRAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6189999991	\N	\N	\N	S
9463	JANAINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6191111111	\N	\N	\N	S
9464	KLEBER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6185555555	\N	\N	\N	S
9465	LUIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6191111211	\N	\N	\N	S
9466	PABLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6187776666	\N	\N	\N	S
9467	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6191111111	\N	\N	\N	S
9468	WALLYSSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6191111111	\N	\N	\N	S
9469	FELIPE VINICIUS DA SILVA ALVES	felipe.cef405@gmail.com	f93dcbcaac273818fd21341c54455937	2017-05-01	\N	5222586111	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9470	ALAERTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61993155421	\N	\N	\N	S
9471	ANTôNIO DOS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61993155421	\N	\N	\N	S
9472	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61993155421	\N	\N	\N	S
9473	PAULO RIBEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61982269768	\N	\N	\N	S
9474	ANDRé GONçALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61993155421	\N	\N	\N	S
9475	MARCELO ROCHA TEIXEIRA	marcelo.rochat@hotmail.com	2716844cf10c1a356959d885b608b31b	2017-05-01	\N	1593243197	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9476	LUCIANA CRISTINA LIMA ROCHA	luzi_nhacris@hotmail.com	a7016390d1c717390fd75f393411269b	2017-05-01	\N	286069148	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9477	CAUÃ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6199212953	\N	\N	\N	S
9478	FELIPE RAMOM MORAES DOS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6134040454	\N	\N	\N	S
9479	LUCAS VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61985107351	\N	\N	\N	S
9480	MAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6199212953	\N	\N	\N	S
9481	UANDER CARDOSO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995241838	\N	\N	\N	S
9482	GILVAN SILVA DE OLIVEIRA	gilvan.oliveiraa@yahoo.com.br	e204c429c186b1d7e60a55726b60a8f6	2017-05-01	\N	3720953130	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9483	CLEISON SILVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195414852	\N	\N	\N	S
9484	GEISIANE OLIVEIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	93042445	\N	\N	\N	S
9485	JOAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6193073349	\N	\N	\N	S
9486	MARLEIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6193657948	\N	\N	\N	S
9487	NATANAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6192779379	\N	\N	\N	S
9488	PEDRO AFONSO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	93670043	\N	\N	\N	S
9489	VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6184665937	\N	\N	\N	S
9490	YURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6192779379	\N	\N	\N	S
9491	GUSTAVO GOMES DA SILVA	gugzx123@hotmail.com	e013489c94cde65609580a9d6b1efb01	2017-05-01	\N	6597403118	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9492	CAIO CRISTHOPHER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6186211618	\N	\N	\N	S
9493	FILIPE DOS SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6186696481	\N	\N	\N	S
9494	PEDRO HUMBERTO MENDONçA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6185446849	\N	\N	\N	S
9587	DAVI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61858263236	\N	\N	\N	S
9495	ROBERTO CARLOS MACHADO	robertocvmachado@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	47354038172	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9496	MARIA DE FATIMA PEREIRA MACHADO	fatimapmachado12@gmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	53957237149	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9497	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6168435641	\N	\N	\N	S
9498	TETE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	85186660	\N	\N	\N	S
9499	GEOVANA BLOIS	geovanablois@gmail.com	dbcaca21063b9538d01e5067bf98d53c	2017-05-01	\N	89927532172	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9500	LANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9501	MARIA VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	30414461	\N	\N	\N	S
9502	RAIMUNDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	30414461	\N	\N	\N	S
9503	DAVI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6132011127	\N	\N	\N	S
9504	ESTHER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9505	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9506	GIOVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195808343	\N	\N	\N	S
9507	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9508	GUILHERMEE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195808343	\N	\N	\N	S
9509	JENNYFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195808343	\N	\N	\N	S
9510	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195808343	\N	\N	\N	S
9511	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9512	JUNIOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195808343	\N	\N	\N	S
9513	KAREN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61982308397	\N	\N	\N	S
9514	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	85991808	\N	\N	\N	S
9515	LUISA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9516	MELISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195808343	\N	\N	\N	S
9517	PATRICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6130414461	\N	\N	\N	S
9518	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61995808343	\N	\N	\N	S
9519	LANA PONTES	lanaalvespontes10df@gmail.com	659386a027c2e6ac00250c11da92bb79	2017-05-01	\N	7312132111	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9520	IVAN LEITE DE SAO JOSE TAVARES	ivanlsjt@gmail.com	d43c5c9573d1cdb27df1295424fa45ca	2017-05-01	\N	2454888122	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9521	NUBIA DE SOUSA QUEIROZ	nubiasq@gmail.com	ac3aad2266ef8e5031b16e4207e039fb	2017-05-01	\N	3059447110	\N	\N	\N	\N	15:49:04	\N	\N	\N	\N	\N	N
9522	JONATHAN REZENDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	846548484	\N	\N	\N	S
9523	ALANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61846548484	\N	\N	\N	S
9525	ANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	94984894	\N	\N	\N	S
9526	ANALICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61854848548	\N	\N	\N	S
9527	ÍTALO CHAMPAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	19986021955	\N	\N	\N	S
9528	DALVA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6185625730	\N	\N	\N	S
9529	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6180000000	\N	\N	\N	S
9530	DORALICE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6184545301	\N	\N	\N	S
9531	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61845648484	\N	\N	\N	S
9532	ELIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61878945678	\N	\N	\N	S
9533	ELIMAR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61885465	\N	\N	\N	S
9534	EMANUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	82224455	\N	\N	\N	S
9535	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61814845481	\N	\N	\N	S
9536	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61854823484	\N	\N	\N	S
9537	GEOVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6185566375	\N	\N	\N	S
9538	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6188945648	\N	\N	\N	S
9539	ITALO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61303032132	\N	\N	\N	S
9540	JADSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61845485484	\N	\N	\N	S
9541	JAILSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61814184865	\N	\N	\N	S
9542	JOEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61845484548	\N	\N	\N	S
9543	JOERCIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6189563456	\N	\N	\N	S
9544	KETELYN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61845687898	\N	\N	\N	S
9545	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6181374249	\N	\N	\N	S
9546	LAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6198020472	\N	\N	\N	S
9547	LUANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61985754755	\N	\N	\N	S
9548	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6195309243	\N	\N	\N	S
9549	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6180000000	\N	\N	\N	S
9550	LUZIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6189498498	\N	\N	\N	S
9551	MARCUS VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6198569430	\N	\N	\N	S
9552	MAYKO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	848484848	\N	\N	\N	S
9553	MICAELY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61985475475	\N	\N	\N	S
9554	MOISES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6184548454	\N	\N	\N	S
9555	NAHRARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61987445441	\N	\N	\N	S
9556	NATHANAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	12234556	\N	\N	\N	S
9557	NAY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6181484848	\N	\N	\N	S
9558	NICOLY BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61985054011	\N	\N	\N	S
9559	PABLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61848548484	\N	\N	\N	S
9560	PAMELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61848548484	\N	\N	\N	S
9561	PAULA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	894984984	\N	\N	\N	S
9562	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	6132113030	\N	\N	\N	S
9563	POLLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	61998553200	\N	\N	\N	S
9564	PRISCILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:49:04	\N	88884444	\N	\N	\N	S
9565	RENATA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	854848548	\N	\N	\N	S
9566	RENATO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61321303000	\N	\N	\N	S
9567	SAMUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	49849848	\N	\N	\N	S
9568	SARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6195518392	\N	\N	\N	S
9569	SILVANIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6198456489	\N	\N	\N	S
9570	SIRLEIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6193838793	\N	\N	\N	S
9571	STELLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61987445144	\N	\N	\N	S
9572	VANESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61814856548	\N	\N	\N	S
9573	VANIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61894894894	\N	\N	\N	S
9574	ANA PAULA RODRIGUES DE SOUZA	ana.paula.1989@hotmail.com	c18c1f04bd3292aa67310e21f0ac4f8e	2017-05-01	\N	70276942124	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9575	JONATHAN REZENDE PIRES	jonathan22norte@gmail.com	f1e9227713d08a805220d95a130e96a8	2017-05-01	\N	4510847130	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9576	LEIDSMAR REZENDE DE LACERDA	leidsmarlacerda14@gmail.com	5eabd15506817b7a142cc2f533d3764e	2017-05-01	\N	4510778147	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9577	LUCIMAR REZENDE DE LACERDA	lucinhalacerda33@gmail.com	39562cfc31af48889ef488555c82224d	2017-05-01	\N	4510776101	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9578	EDUARDO SOARES RAFAEL FIRMINO	eduardo.conor.esf@gmail.com	a727a4ba97f3789557bb5bb6117f257e	2017-05-01	\N	7371954106	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9579	MAYKO DA SILVA COSTA	maykodasilvacosta13@gmail.com	e99a18c428cb38d5f260853678922e03	2017-05-01	\N	7786592326	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9580	LEANDRO MELGAÇO DE AGUIAR	leandrodf_17@hotmail.com	e9ccbe5f8726b38681be7353d611d111	2017-05-01	\N	3343484121	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9581	ALINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185825242	\N	\N	\N	S
9582	AMANDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9583	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9584	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9585	CLEIDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9586	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9588	DIOGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185827250	\N	\N	\N	S
9589	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9590	EMANUEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9591	EMERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9592	FABIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9593	FABRICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185854550	\N	\N	\N	S
9594	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9595	GABRIEL M NORTE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9596	GEOVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6182854250	\N	\N	\N	S
9597	HALEF	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9598	IGOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185854250	\N	\N	\N	S
9599	JAMIL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9600	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9601	JEREMIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185524250	\N	\N	\N	S
9602	JESSICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185425021	\N	\N	\N	S
9603	JIZREEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985824250	\N	\N	\N	S
9604	JOAO PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9605	JULIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9606	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9607	JUNIOR NYOT	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9608	LAVIE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9609	LEANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9610	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9611	LORRANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9612	MAICON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9613	MARCILENE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9614	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9615	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9616	MIAKEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185854250	\N	\N	\N	S
9617	NADIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9618	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9619	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9620	RAYANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9621	RONI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9622	ROSIANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9623	RUANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9624	SARAH	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9625	TALISON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9626	TATY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9627	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61999999999	\N	\N	\N	S
9628	TIO CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9629	VANESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9630	VANYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9631	VICTORIA ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61999999999	\N	\N	\N	S
9632	VITOR	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9633	VITORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9634	VITTOR FALCÃO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9635	WESLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185824250	\N	\N	\N	S
9636	CAIO HENRIQUE DOS SANTOS LOPES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61986793694	\N	\N	\N	S
9637	ROBERTO FILIPPELLI ARAUJO BIDô	roberto_filippelli@hotmail.com	81dc9bdb52d04dc20036dbd8313ed055	2017-05-01	\N	3961898146	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9638	LETICIA FRANCISCA SANTOS BEZERRA	jesuscristo1404@hotmail.com	81dc9bdb52d04dc20036dbd8313ed055	2017-05-01	\N	7260174188	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9639	ALEX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6198563214	\N	\N	\N	S
9640	ALINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985631247	\N	\N	\N	S
9641	AMANDA ALVES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61996857001	\N	\N	\N	S
9642	CAIO SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61993456802	\N	\N	\N	S
9643	DAVI LARCERDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6199941236	\N	\N	\N	S
9644	DAVID	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6196325417	\N	\N	\N	S
9645	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61996874125	\N	\N	\N	S
9646	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61998413678	\N	\N	\N	S
9647	JOAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6196588321	\N	\N	\N	S
9648	LAESTI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61984123685	\N	\N	\N	S
9649	LEANDRA SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61996584123	\N	\N	\N	S
9650	MATHEUS ALVEZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61968412378	\N	\N	\N	S
9651	PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6199854311	\N	\N	\N	S
9652	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6196321458	\N	\N	\N	S
9653	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6198136572	\N	\N	\N	S
9654	ROBERTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61998456136	\N	\N	\N	S
9655	RUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61999654712	\N	\N	\N	S
9656	SAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61995874319	\N	\N	\N	S
9657	STHEFANI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61996587123	\N	\N	\N	S
9658	VANIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61996587436	\N	\N	\N	S
9659	VITORIA BORGE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6199587631	\N	\N	\N	S
9660	WALACE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185312698	\N	\N	\N	S
9661	PAULO RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6196512354	\N	\N	\N	S
9662	LUIZ PAULO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6198531245	\N	\N	\N	S
9663	CRISTIAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6196583124	\N	\N	\N	S
9664	HUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6195316758	\N	\N	\N	S
9665	KEVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	6185631256	\N	\N	\N	S
9666	ICARO NASCIMENTO SANTANA	icarosantanaduque@outlook.com	e10adc3949ba59abbe56e057f20f883e	2017-05-01	\N	7442740103	\N	\N	\N	\N	15:50:04	\N	\N	\N	\N	\N	N
9667	ALINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9668	ESTER VIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9669	GABY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9670	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9671	JIZREEL VASCONCELOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9672	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9673	MAYCOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:50:04	\N	61985668794	\N	\N	\N	S
9674	MESSIAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61985668794	\N	\N	\N	S
9675	PEDRO HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61985668794	\N	\N	\N	S
9676	NATAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61985668794	\N	\N	\N	S
9677	VANESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61985668794	\N	\N	\N	S
9678	MATEUS MARTINS DE SOUSA	maamartins18@gmail.com	81dc9bdb52d04dc20036dbd8313ed055	2017-05-01	\N	2825869112	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9679	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9680	CAUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9681	JENNIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9682	JOYCE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9683	MARCUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548533	\N	\N	\N	S
9684	NATHANAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9685	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9686	YAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9687	YURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9688	ÁQUILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9689	SORAYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9690	VANESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9691	BRENDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9692	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9693	NAYARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9694	MARCUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9695	FELIPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991548563	\N	\N	\N	S
9696	JOAO VITOR MARTINS DA ROCHA	joaovitor.rocha1998@gmail.com	81dc9bdb52d04dc20036dbd8313ed055	2017-05-01	\N	11512535613	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9697	GABRIELA ALVES DE LIMA	gabrielaalvesdelima1@gmail.com	761a3f4f426ec2424ad53bd75c5737eb	2017-05-01	\N	4561522107	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9698	DIANA MARQUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61992622027	\N	\N	\N	S
9699	HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61993642723	\N	\N	\N	S
9700	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6195870793	\N	\N	\N	S
9701	ANDRESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6198763728	\N	\N	\N	S
9702	ASSIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61965369482	\N	\N	\N	S
9703	BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6196985497	\N	\N	\N	S
9704	BIANCA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61996012045	\N	\N	\N	S
9705	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6198654789	\N	\N	\N	S
9706	DANIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61995870793	\N	\N	\N	S
9707	DANILO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6186451535	\N	\N	\N	S
9708	DIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6195647871	\N	\N	\N	S
9709	EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61985486330	\N	\N	\N	S
9710	EMANOELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61984251304	\N	\N	\N	S
9711	ERICA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61993781868	\N	\N	\N	S
9712	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61996259131	\N	\N	\N	S
9713	GABRYELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6196259131	\N	\N	\N	S
9714	GUILHERME	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61996259131	\N	\N	\N	S
9715	ISAAC	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6134341307	\N	\N	\N	S
9716	ISABEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6134341307	\N	\N	\N	S
9717	ISABEL ALMEIDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61991605417	\N	\N	\N	S
9718	JOãO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61964464054	\N	\N	\N	S
9719	LEONARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6198765372	\N	\N	\N	S
9720	LILIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61984507808	\N	\N	\N	S
9721	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61986299018	\N	\N	\N	S
9722	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61983135090	\N	\N	\N	S
9723	LUDIMILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6195987547	\N	\N	\N	S
9724	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6196259131	\N	\N	\N	S
9725	MARCOS ANTONIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61995870793	\N	\N	\N	S
9726	MARIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6196482349	\N	\N	\N	S
9727	MARIA DA CONCEIçãO.	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61986451535	\N	\N	\N	S
9728	MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61996542049	\N	\N	\N	S
9729	PABLO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6195878517	\N	\N	\N	S
9730	PAMELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61985062888	\N	\N	\N	S
9731	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6196588721	\N	\N	\N	S
9732	RAVANELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61992006834	\N	\N	\N	S
9733	RENATO SOUZA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61995870793	\N	\N	\N	S
9734	SUIAMY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61996542049	\N	\N	\N	S
9735	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61867988799	\N	\N	\N	S
9736	THATIELY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61996259131	\N	\N	\N	S
9737	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61986299018	\N	\N	\N	S
9738	THIAGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6196953499	\N	\N	\N	S
9739	VANUZETE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61998106468	\N	\N	\N	S
9740	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61986554874	\N	\N	\N	S
9741	WALYSSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61984519430	\N	\N	\N	S
9742	YURI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61965214792	\N	\N	\N	S
9743	BRUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192006834	\N	\N	\N	S
9744	KAYLAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192006834	\N	\N	\N	S
9745	LARISSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192006834	\N	\N	\N	S
9746	MICAELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192006834	\N	\N	\N	S
9747	JENNY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61986898750	\N	\N	\N	S
9748	STHEFANY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61994328028	\N	\N	\N	S
9749	ANDERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6194134739	\N	\N	\N	S
9750	VANESSA PEREIRA DO AMARAL	vanessamaral011@gmail.com	6c7698f5401f554f20d4faec79caef7f	2017-05-01	\N	2516937164	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9751	ISABEL DE JESUS FIGUEIRA	bebel.figueira1121@gmail.com	ba6922eb4b1cecc9050c7f376059d138	2017-05-01	\N	3712383150	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9752	DANIEL ALVES SARAIVA DA CUNHA	danielkareca85@gmail.com	3b2b0a562c10a12a88099915c5cd98aa	2017-05-01	\N	7385705162	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9753	THIAGO PEREIRA DO SANTOS	castelaperera9090@gmail.com	b53dd2fde22cb8649f7e3b6e257e965f	2017-05-01	\N	6921652157	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9754	DIANA MOREIRA MARQUES	dianam.s2@hotmail.com	ca7669cfc26196d72f7d5297cf1bc606	2017-05-01	\N	4789044106	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9755	EDUARDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	99213120	\N	\N	\N	S
9756	LETíCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	992622027	\N	\N	\N	S
9757	ADRIANO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	83889755	\N	\N	\N	S
9758	ALBERT	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	98633033	\N	\N	\N	S
9759	ALEXANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92622027	\N	\N	\N	S
9760	ANNA CLARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	93345188	\N	\N	\N	S
9761	ERNESTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92675723	\N	\N	\N	S
9762	GABRIELA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61987729899	\N	\N	\N	S
9763	GABRIELA PORTO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	91442185	\N	\N	\N	S
9764	GABRIELA SANTOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	986828469	\N	\N	\N	S
9765	GIOVANNA VICTORIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	982046855	\N	\N	\N	S
9766	ISABELLA CRISTINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92622027	\N	\N	\N	S
9767	JENIFER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	992622027	\N	\N	\N	S
9768	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	96467243	\N	\N	\N	S
9769	JULIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92622027	\N	\N	\N	S
9770	KARLA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61987626777	\N	\N	\N	S
9771	KAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61987562989	\N	\N	\N	S
9772	MAIARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92622027	\N	\N	\N	S
9773	MARCIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	98322425	\N	\N	\N	S
9774	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92622027	\N	\N	\N	S
9775	MARCOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	991658791	\N	\N	\N	S
9776	PEDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192622027	\N	\N	\N	S
9777	REBECA KéSIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	95516483	\N	\N	\N	S
9778	RITA DE CASSIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	84485025	\N	\N	\N	S
9779	SHAYENE MULLER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	992675723	\N	\N	\N	S
9780	THAIS LOPES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	92622027	\N	\N	\N	S
9781	VITóRIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	83466604	\N	\N	\N	S
9782	YASMIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61987627880	\N	\N	\N	S
9783	GABRIEL MOREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6186150623	\N	\N	\N	S
9784	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6185632488	\N	\N	\N	S
9785	JEFERSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192622027	\N	\N	\N	S
9786	PEDRO LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6183547710	\N	\N	\N	S
9787	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192622027	\N	\N	\N	S
9788	GUSTAVO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192622027	\N	\N	\N	S
9789	RICARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	6192622027	\N	\N	\N	S
9790	RAVANIELI	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61992006834	\N	\N	\N	S
9791	LUIZ PHILIPPE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:51:04	\N	61982504093	\N	\N	\N	S
9792	EDUARDA FERREIRA BATISTA	eduarda.bkb10@gmail.com	0596029a1c72f9e9e6b13ec66157fd54	2017-05-01	\N	8185251509	\N	\N	\N	\N	15:51:04	\N	\N	\N	\N	\N	N
9793	GLEVISSON DE JESUS SANTOS MORAES	gjmoraes21@gmail.com	a7016390d1c717390fd75f393411269b	2017-05-01	\N	4367377105	\N	\N	\N	\N	15:52:04	\N	\N	\N	\N	\N	N
9794	IAN VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	98226544	\N	\N	\N	S
9795	BRANDO RODRIGUES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86688478	\N	\N	\N	S
9796	CARLOS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91438933	\N	\N	\N	S
9797	CAROLINA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91438933	\N	\N	\N	S
9798	GABRIEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	83191240	\N	\N	\N	S
9799	IGOR MATHEUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84933310	\N	\N	\N	S
9800	JONATHAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85778582	\N	\N	\N	S
9801	JONATHAN RUAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	6185455157	\N	\N	\N	S
9802	JULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85164630	\N	\N	\N	S
9803	JULIO MAVINIER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92954295	\N	\N	\N	S
9804	LETICIA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91225583	\N	\N	\N	S
9805	LUCAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85350598	\N	\N	\N	S
9806	MARCO TULIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	83027847	\N	\N	\N	S
9807	PAULO HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84162131	\N	\N	\N	S
9808	RENATO FERREIRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84974664	\N	\N	\N	S
9809	RICKSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	82618075	\N	\N	\N	S
9810	STHEFANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92724248	\N	\N	\N	S
9811	WELLYSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84122379	\N	\N	\N	S
9812	WESLEY MAURICIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	95307862	\N	\N	\N	S
9813	JONATHAN DE LIMA LEãO	jonathan.lleao@gmail.com	3c2a024fbee244291634ccd863ef5b9a	2017-05-01	\N	25364030849	\N	\N	\N	\N	15:52:04	\N	\N	\N	\N	\N	N
9814	JAQUELINE CUSTODIO DA CONCEICAO	jaquebola-pso@hotmail.com	9753b72aeb48f9e9dc5598ca523e5251	2017-05-01	\N	5757399163	\N	\N	\N	\N	15:52:04	\N	\N	\N	\N	\N	N
9815	ALINE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9816	ANTINIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	93862781	\N	\N	\N	S
9817	CINTHYA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91086035	\N	\N	\N	S
9818	AMIGA BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9819	AMIGO ANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9820	AMIGO MAX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92970488	\N	\N	\N	S
9821	AMPARO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9822	ANDRE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	147258369	\N	\N	\N	S
9823	ANTONIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85123530	\N	\N	\N	S
9824	BARBARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91434238	\N	\N	\N	S
9825	BRUNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84487757	\N	\N	\N	S
9826	BRUNOO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	99239526	\N	\N	\N	S
9827	CAIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	94155305	\N	\N	\N	S
9828	CARLOS EDUARDO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9829	CAROL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9830	DANIELE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	83250084	\N	\N	\N	S
9831	DAPAZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9832	DELOZIANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84951277	\N	\N	\N	S
9833	DUDU	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	99239526	\N	\N	\N	S
9834	EDICLEYDE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9835	EDNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	6092986203	\N	\N	\N	S
9836	EDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	6086029522	\N	\N	\N	S
9837	EDSON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	992910321	\N	\N	\N	S
9838	FRAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92938291	\N	\N	\N	S
9839	FRANCILEDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	93781391	\N	\N	\N	S
9840	FRANCISCO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	6086029522	\N	\N	\N	S
9841	GEOVANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91683711	\N	\N	\N	S
9842	JACK	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85642971	\N	\N	\N	S
9843	JAJA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84829724	\N	\N	\N	S
9844	JARDEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9845	JESSICA IRMAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92083849	\N	\N	\N	S
9846	JONATHAN RIBEIRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	6086029522	\N	\N	\N	S
9847	JUNO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	998729579	\N	\N	\N	S
9848	KEMILE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91128498	\N	\N	\N	S
9849	KEVEM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	93480153	\N	\N	\N	S
9850	LARRISE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85991035	\N	\N	\N	S
9851	LEANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9852	LUKAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85123530	\N	\N	\N	S
9853	MAICON	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9854	MARIA DA LUZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9855	MAX	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91086035	\N	\N	\N	S
9856	MICHELLY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	81772866	\N	\N	\N	S
9857	MILENINHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9858	MORENA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	93414446	\N	\N	\N	S
9859	NATALY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594721	\N	\N	\N	S
9860	PAULISTA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	95032321	\N	\N	\N	S
9861	PEDRO HENRIQUE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9862	PRETA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9863	PRISCILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	982088136	\N	\N	\N	S
9864	RAFAEL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	981304088	\N	\N	\N	S
9865	REJANE	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	996483699	\N	\N	\N	S
9866	SANDRA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9867	SHEILA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	60992758250	\N	\N	\N	S
9868	SILVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86029522	\N	\N	\N	S
9869	THAIS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9870	THALITA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92938291	\N	\N	\N	S
9871	THAYNA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	86469565	\N	\N	\N	S
9872	VINICIUS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	91594731	\N	\N	\N	S
9873	WANISLEY	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92917091	\N	\N	\N	S
9874	WELVEZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	994148811	\N	\N	\N	S
9875	WIIL	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	85284326	\N	\N	\N	S
9876	JONATAN IGOR MARÇAL	jonatanigor014@hotmail.com	202cb962ac59075b964b07152d234b70	2017-05-01	\N	4243241139	\N	\N	\N	\N	15:52:04	\N	\N	\N	\N	\N	N
9877	JUNO LOPES	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9878	ANTONIO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92284577	\N	\N	\N	S
9879	DEZIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	983242061	\N	\N	\N	S
9880	DIOGO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	983242061	\N	\N	\N	S
9881	DOUGLAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9882	GEOVANA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9883	JOAO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	94673642	\N	\N	\N	S
9884	JONATHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	983242061	\N	\N	\N	S
9885	KEVIN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	94364762	\N	\N	\N	S
9886	LUKAS	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9887	MARCOS DUDA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92257621	\N	\N	\N	S
9888	RAYLAN	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	83282463	\N	\N	\N	S
9889	SANDRO	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	84182261	\N	\N	\N	S
9890	SENHORINHA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9891	SOPHIA BEATRIZ	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	33750795	\N	\N	\N	S
9892	TAVISSOM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9893	THAMARA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584677	\N	\N	\N	S
9894	VANESSA	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	983242061	\N	\N	\N	S
9895	WILKER	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	983242061	\N	\N	\N	S
9896	YASMIM	\N	\N	2017-05-01	\N	\N	\N	\N	\N	\N	15:52:04	\N	92584577	\N	\N	\N	S
9897	LINDA INêS BARROS PEIXOTO	linda.ynes-2012@hotmail.com	d5fa177717d9b05deb00d21c2f8f09c3	2017-05-01	\N	61986144330	\N	\N	\N	\N	15:52:04	\N	\N	\N	\N	\N	N
9898	ALINE MARQUES PEREIRA	aline@gmail.com	4417b04ad851bbdc060d1550889ba001	2017-05-01	\N	5580550103	\N	\N	\N	\N	15:52:04	\N	\N	\N	\N	\N	N
\.


--
-- TOC entry 2353 (class 0 OID 16952)
-- Dependencies: 209
-- Data for Name: pessoa_hierarquia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pessoa_hierarquia (id, pessoa_id, hierarquia_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	1	1	2017-01-18	09:59:17.753286	\N	\N
576	7102	1	2017-05-01	15:34:04	\N	\N
577	7103	1	2017-05-01	15:34:04	\N	\N
578	7104	2	2017-05-01	15:34:04	\N	\N
579	7105	2	2017-05-01	15:34:04	\N	\N
580	7117	4	2017-05-01	15:34:04	\N	\N
581	7118	4	2017-05-01	15:34:04	\N	\N
582	7208	6	2017-05-01	15:34:04	\N	\N
583	7269	6	2017-05-01	15:34:04	\N	\N
584	7286	6	2017-05-01	15:34:04	\N	\N
585	7287	6	2017-05-01	15:34:04	\N	\N
586	7290	6	2017-05-01	15:34:04	\N	\N
587	7308	6	2017-05-01	15:35:04	\N	\N
588	7309	6	2017-05-01	15:35:04	\N	\N
589	7320	6	2017-05-01	15:35:04	\N	\N
590	7334	6	2017-05-01	15:35:04	\N	\N
591	7394	6	2017-05-01	15:35:04	\N	\N
592	7412	6	2017-05-01	15:35:04	\N	\N
593	7413	6	2017-05-01	15:35:04	\N	\N
594	7428	6	2017-05-01	15:35:04	\N	\N
595	7490	6	2017-05-01	15:36:04	\N	\N
596	7491	6	2017-05-01	15:36:04	\N	\N
597	7494	6	2017-05-01	15:36:04	\N	\N
598	7495	6	2017-05-01	15:36:04	\N	\N
599	7496	6	2017-05-01	15:36:04	\N	\N
600	7497	6	2017-05-01	15:36:04	\N	\N
601	7507	6	2017-05-01	15:36:04	\N	\N
602	7508	6	2017-05-01	15:36:04	\N	\N
603	7531	6	2017-05-01	15:36:04	\N	\N
604	7541	6	2017-05-01	15:36:04	\N	\N
605	7542	6	2017-05-01	15:36:04	\N	\N
606	7543	6	2017-05-01	15:36:04	\N	\N
607	7544	6	2017-05-01	15:36:04	\N	\N
608	7567	6	2017-05-01	15:36:04	\N	\N
609	7568	6	2017-05-01	15:37:04	\N	\N
610	7599	6	2017-05-01	15:37:04	\N	\N
611	7609	6	2017-05-01	15:37:04	\N	\N
612	7632	6	2017-05-01	15:37:04	\N	\N
613	7641	6	2017-05-01	15:37:04	\N	\N
614	7660	6	2017-05-01	15:37:04	\N	\N
615	7661	6	2017-05-01	15:37:04	\N	\N
616	7683	6	2017-05-01	15:37:04	\N	\N
617	7711	5	2017-05-01	15:38:04	\N	\N
618	7712	5	2017-05-01	15:38:04	\N	\N
619	7744	6	2017-05-01	15:38:04	\N	\N
620	7805	6	2017-05-01	15:38:04	\N	\N
621	7811	6	2017-05-01	15:38:04	\N	\N
622	7812	6	2017-05-01	15:38:04	\N	\N
623	7813	6	2017-05-01	15:38:04	\N	\N
624	7822	6	2017-05-01	15:38:04	\N	\N
625	7823	6	2017-05-01	15:38:04	\N	\N
626	7870	6	2017-05-01	15:38:04	\N	\N
627	7918	6	2017-05-01	15:39:04	\N	\N
628	7952	6	2017-05-01	15:39:04	\N	\N
629	8016	6	2017-05-01	15:39:04	\N	\N
630	8017	6	2017-05-01	15:39:04	\N	\N
631	8043	6	2017-05-01	15:39:04	\N	\N
632	8061	6	2017-05-01	15:39:04	\N	\N
633	8081	6	2017-05-01	15:40:04	\N	\N
634	8087	6	2017-05-01	15:40:04	\N	\N
635	8099	6	2017-05-01	15:40:04	\N	\N
636	8158	6	2017-05-01	15:40:04	\N	\N
637	8159	6	2017-05-01	15:40:04	\N	\N
638	8187	6	2017-05-01	15:40:04	\N	\N
639	8188	6	2017-05-01	15:40:04	\N	\N
640	8189	6	2017-05-01	15:40:04	\N	\N
641	8213	6	2017-05-01	15:40:04	\N	\N
642	8214	6	2017-05-01	15:40:04	\N	\N
643	8280	6	2017-05-01	15:41:04	\N	\N
644	8281	6	2017-05-01	15:41:04	\N	\N
645	8282	6	2017-05-01	15:41:04	\N	\N
646	8294	6	2017-05-01	15:41:04	\N	\N
647	8306	6	2017-05-01	15:41:04	\N	\N
648	8320	4	2017-05-01	15:41:04	\N	\N
649	8321	4	2017-05-01	15:41:04	\N	\N
650	8380	6	2017-05-01	15:41:04	\N	\N
651	8397	6	2017-05-01	15:41:04	\N	\N
652	8452	6	2017-05-01	15:42:04	\N	\N
653	8453	6	2017-05-01	15:42:04	\N	\N
654	8454	6	2017-05-01	15:42:04	\N	\N
655	8455	6	2017-05-01	15:42:04	\N	\N
656	8456	6	2017-05-01	15:42:04	\N	\N
657	8457	6	2017-05-01	15:42:04	\N	\N
658	8504	6	2017-05-01	15:42:04	\N	\N
659	8542	6	2017-05-01	15:42:04	\N	\N
660	8543	6	2017-05-01	15:42:04	\N	\N
661	8570	6	2017-05-01	15:43:04	\N	\N
662	8571	6	2017-05-01	15:43:04	\N	\N
663	8584	6	2017-05-01	15:43:04	\N	\N
664	8585	6	2017-05-01	15:43:04	\N	\N
665	8593	6	2017-05-01	15:43:04	\N	\N
666	8594	6	2017-05-01	15:43:04	\N	\N
667	8655	6	2017-05-01	15:43:04	\N	\N
668	8668	6	2017-05-01	15:43:04	\N	\N
669	8697	6	2017-05-01	15:43:04	\N	\N
670	8698	6	2017-05-01	15:43:04	\N	\N
671	8702	6	2017-05-01	15:43:04	\N	\N
672	8755	6	2017-05-01	15:44:04	\N	\N
673	8757	6	2017-05-01	15:44:04	\N	\N
674	8760	6	2017-05-01	15:44:04	\N	\N
675	8822	6	2017-05-01	15:44:04	\N	\N
676	8856	6	2017-05-01	15:44:04	\N	\N
677	8857	6	2017-05-01	15:44:04	\N	\N
678	8858	6	2017-05-01	15:44:04	\N	\N
679	8859	6	2017-05-01	15:44:04	\N	\N
680	8860	6	2017-05-01	15:44:04	\N	\N
681	8861	6	2017-05-01	15:45:04	\N	\N
682	8862	6	2017-05-01	15:45:04	\N	\N
683	8863	6	2017-05-01	15:45:04	\N	\N
684	8864	6	2017-05-01	15:45:04	\N	\N
685	8865	6	2017-05-01	15:45:04	\N	\N
686	8866	6	2017-05-01	15:45:04	\N	\N
687	8927	6	2017-05-01	15:45:04	\N	\N
688	8928	6	2017-05-01	15:45:04	\N	\N
689	8929	6	2017-05-01	15:45:04	\N	\N
690	8930	4	2017-05-01	15:45:04	\N	\N
691	8931	4	2017-05-01	15:45:04	\N	\N
692	8998	6	2017-05-01	15:46:04	\N	\N
693	9007	6	2017-05-01	15:46:04	\N	\N
694	9051	6	2017-05-01	15:46:04	\N	\N
695	9052	6	2017-05-01	15:46:04	\N	\N
696	9054	6	2017-05-01	15:46:04	\N	\N
697	9055	6	2017-05-01	15:46:04	\N	\N
698	9056	6	2017-05-01	15:46:04	\N	\N
699	9057	6	2017-05-01	15:46:04	\N	\N
700	9112	6	2017-05-01	15:46:04	\N	\N
701	9127	6	2017-05-01	15:47:04	\N	\N
702	9154	6	2017-05-01	15:47:04	\N	\N
703	9214	6	2017-05-01	15:47:04	\N	\N
704	9239	6	2017-05-01	15:47:04	\N	\N
705	9294	6	2017-05-01	15:47:04	\N	\N
706	9295	6	2017-05-01	15:47:04	\N	\N
707	9307	6	2017-05-01	15:48:04	\N	\N
708	9365	6	2017-05-01	15:48:04	\N	\N
709	9377	6	2017-05-01	15:48:04	\N	\N
710	9379	6	2017-05-01	15:48:04	\N	\N
711	9388	6	2017-05-01	15:48:04	\N	\N
712	9389	6	2017-05-01	15:48:04	\N	\N
713	9390	6	2017-05-01	15:48:04	\N	\N
714	9451	6	2017-05-01	15:48:04	\N	\N
715	9458	6	2017-05-01	15:49:04	\N	\N
716	9459	6	2017-05-01	15:49:04	\N	\N
717	9469	6	2017-05-01	15:49:04	\N	\N
718	9475	6	2017-05-01	15:49:04	\N	\N
719	9476	6	2017-05-01	15:49:04	\N	\N
720	9482	6	2017-05-01	15:49:04	\N	\N
721	9491	6	2017-05-01	15:49:04	\N	\N
722	9495	4	2017-05-01	15:49:04	\N	\N
723	9496	4	2017-05-01	15:49:04	\N	\N
724	9499	6	2017-05-01	15:49:04	\N	\N
725	9519	6	2017-05-01	15:49:04	\N	\N
726	9520	6	2017-05-01	15:49:04	\N	\N
727	9521	6	2017-05-01	15:49:04	\N	\N
728	9574	6	2017-05-01	15:50:04	\N	\N
729	9575	6	2017-05-01	15:50:04	\N	\N
730	9576	6	2017-05-01	15:50:04	\N	\N
731	9577	6	2017-05-01	15:50:04	\N	\N
732	9578	6	2017-05-01	15:50:04	\N	\N
733	9579	6	2017-05-01	15:50:04	\N	\N
734	9580	6	2017-05-01	15:50:04	\N	\N
735	9637	6	2017-05-01	15:50:04	\N	\N
736	9638	6	2017-05-01	15:50:04	\N	\N
737	9666	6	2017-05-01	15:50:04	\N	\N
738	9678	6	2017-05-01	15:51:04	\N	\N
739	9696	6	2017-05-01	15:51:04	\N	\N
740	9697	6	2017-05-01	15:51:04	\N	\N
741	9750	6	2017-05-01	15:51:04	\N	\N
742	9751	6	2017-05-01	15:51:04	\N	\N
743	9752	6	2017-05-01	15:51:04	\N	\N
744	9753	6	2017-05-01	15:51:04	\N	\N
745	9754	6	2017-05-01	15:51:04	\N	\N
746	9792	6	2017-05-01	15:51:04	\N	\N
747	9793	6	2017-05-01	15:52:04	\N	\N
748	9813	6	2017-05-01	15:52:04	\N	\N
749	9814	6	2017-05-01	15:52:04	\N	\N
750	9876	6	2017-05-01	15:52:04	\N	\N
751	9897	6	2017-05-01	15:52:04	\N	\N
752	9898	6	2017-05-01	15:52:04	\N	\N
\.


--
-- TOC entry 2660 (class 0 OID 0)
-- Dependencies: 208
-- Name: pessoa_hierarquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pessoa_hierarquia_id_seq', 752, true);


--
-- TOC entry 2661 (class 0 OID 0)
-- Dependencies: 170
-- Name: pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pessoa_id_seq', 9916, true);


--
-- TOC entry 2345 (class 0 OID 16868)
-- Dependencies: 201
-- Data for Name: situacao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY situacao (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	ATIVO	2016-10-27	15:44:01.16761	\N	\N
\.


--
-- TOC entry 2662 (class 0 OID 0)
-- Dependencies: 200
-- Name: situacao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('situacao_id_seq', 1, false);


--
-- TOC entry 2341 (class 0 OID 16817)
-- Dependencies: 197
-- Data for Name: turma; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY turma (id, data_revisao, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	2017-01-18	2017-01-18	11:11:02.598036	\N	\N
\.


--
-- TOC entry 2343 (class 0 OID 16835)
-- Dependencies: 199
-- Data for Name: turma_aluno; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY turma_aluno (id, turma_id, pessoa_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	1	156	2017-01-18	11:11:30.606484	\N	\N
2	1	165	2017-01-20	13:58:31.990478	\N	\N
\.


--
-- TOC entry 2663 (class 0 OID 0)
-- Dependencies: 196
-- Name: turma_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('turma_id_seq', 1, false);


--
-- TOC entry 2664 (class 0 OID 0)
-- Dependencies: 198
-- Name: turma_pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('turma_pessoa_id_seq', 7, true);


--
-- TOC entry 2156 (class 2606 OID 16904)
-- Name: pk_aluno_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT pk_aluno_situacao PRIMARY KEY (id);


--
-- TOC entry 2169 (class 2606 OID 17060)
-- Name: pk_dimensao; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT pk_dimensao PRIMARY KEY (id);


--
-- TOC entry 2171 (class 2606 OID 17083)
-- Name: pk_dimensao_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY dimensao_tipo
    ADD CONSTRAINT pk_dimensao_tipo PRIMARY KEY (id);


--
-- TOC entry 2132 (class 2606 OID 16463)
-- Name: pk_entidade; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT pk_entidade PRIMARY KEY (id);


--
-- TOC entry 2134 (class 2606 OID 16472)
-- Name: pk_entidade_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY entidade_tipo
    ADD CONSTRAINT pk_entidade_tipo PRIMARY KEY (id);


--
-- TOC entry 2136 (class 2606 OID 16632)
-- Name: pk_evento; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT pk_evento PRIMARY KEY (id);


--
-- TOC entry 2140 (class 2606 OID 16645)
-- Name: pk_evento_celula; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evento_celula
    ADD CONSTRAINT pk_evento_celula PRIMARY KEY (id);


--
-- TOC entry 2148 (class 2606 OID 16754)
-- Name: pk_evento_frequencia; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT pk_evento_frequencia PRIMARY KEY (id);


--
-- TOC entry 2176 (class 2606 OID 17410)
-- Name: pk_fato_celula; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY fato_celula
    ADD CONSTRAINT pk_fato_celula PRIMARY KEY (id);


--
-- TOC entry 2167 (class 2606 OID 17018)
-- Name: pk_fato_ciclo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY fato_ciclo
    ADD CONSTRAINT pk_fato_ciclo PRIMARY KEY (id);


--
-- TOC entry 2174 (class 2606 OID 17351)
-- Name: pk_fato_lider; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY fato_lider
    ADD CONSTRAINT pk_fato_lider PRIMARY KEY (id);


--
-- TOC entry 2128 (class 2606 OID 16455)
-- Name: pk_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT pk_grupo PRIMARY KEY (id);


--
-- TOC entry 2158 (class 2606 OID 16931)
-- Name: pk_grupo_aluno; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT pk_grupo_aluno PRIMARY KEY (id);


--
-- TOC entry 2164 (class 2606 OID 16997)
-- Name: pk_grupo_atendimento; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_atendimento
    ADD CONSTRAINT pk_grupo_atendimento PRIMARY KEY (id);


--
-- TOC entry 2178 (class 2606 OID 17746)
-- Name: pk_grupo_cv; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_cv
    ADD CONSTRAINT pk_grupo_cv PRIMARY KEY (id);


--
-- TOC entry 2142 (class 2606 OID 16675)
-- Name: pk_grupo_evento; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT pk_grupo_evento PRIMARY KEY (id);


--
-- TOC entry 2126 (class 2606 OID 16445)
-- Name: pk_grupo_pai_filho; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT pk_grupo_pai_filho PRIMARY KEY (id);


--
-- TOC entry 2144 (class 2606 OID 16717)
-- Name: pk_grupo_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT pk_grupo_pessoa PRIMARY KEY (id);


--
-- TOC entry 2146 (class 2606 OID 16729)
-- Name: pk_grupo_pessoa_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_pessoa_tipo
    ADD CONSTRAINT pk_grupo_pessoa_tipo PRIMARY KEY (id);


--
-- TOC entry 2124 (class 2606 OID 16437)
-- Name: pk_grupo_responsavel; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT pk_grupo_responsavel PRIMARY KEY (id);


--
-- TOC entry 2160 (class 2606 OID 16949)
-- Name: pk_hierarquia; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY hierarquia
    ADD CONSTRAINT pk_hierarquia PRIMARY KEY (id);


--
-- TOC entry 2122 (class 2606 OID 16447)
-- Name: pk_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (id);


--
-- TOC entry 2162 (class 2606 OID 16959)
-- Name: pk_pessoa_hierarquia; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT pk_pessoa_hierarquia PRIMARY KEY (id);


--
-- TOC entry 2154 (class 2606 OID 16873)
-- Name: pk_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY situacao
    ADD CONSTRAINT pk_situacao PRIMARY KEY (id);


--
-- TOC entry 2150 (class 2606 OID 16822)
-- Name: pk_turma; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY turma
    ADD CONSTRAINT pk_turma PRIMARY KEY (id);


--
-- TOC entry 2665 (class 0 OID 0)
-- Dependencies: 2150
-- Name: CONSTRAINT pk_turma ON turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma ON turma IS 'Chave primaria da turma';


--
-- TOC entry 2152 (class 2606 OID 16860)
-- Name: pk_turma_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT pk_turma_pessoa PRIMARY KEY (id);


--
-- TOC entry 2138 (class 2606 OID 16625)
-- Name: primary_key_evento_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evento_tipo
    ADD CONSTRAINT primary_key_evento_tipo PRIMARY KEY (id);


--
-- TOC entry 2129 (class 1259 OID 16498)
-- Name: fki_entidade_grupo_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_entidade_grupo_id ON entidade USING btree (grupo_id);


--
-- TOC entry 2130 (class 1259 OID 16504)
-- Name: fki_entidade_tipo_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_entidade_tipo_id ON entidade USING btree (tipo_id);


--
-- TOC entry 2165 (class 1259 OID 17330)
-- Name: index_fato_ciclo_numero_identificador; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX index_fato_ciclo_numero_identificador ON fato_ciclo USING btree (numero_identificador);


--
-- TOC entry 2172 (class 1259 OID 17368)
-- Name: index_fato_lider_numero_identificador; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX index_fato_lider_numero_identificador ON fato_lider USING btree (numero_identificador);


--
-- TOC entry 2666 (class 0 OID 0)
-- Dependencies: 2172
-- Name: INDEX index_fato_lider_numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_fato_lider_numero_identificador IS 'Index do número identificador do grupo';


--
-- TOC entry 2119 (class 1259 OID 16429)
-- Name: index_pessoa_data_nascimento; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX index_pessoa_data_nascimento ON pessoa USING btree (data_nascimento);


--
-- TOC entry 2667 (class 0 OID 0)
-- Dependencies: 2119
-- Name: INDEX index_pessoa_data_nascimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_pessoa_data_nascimento IS 'Index para recuperar email de acesso atraves da data de nascimento';


--
-- TOC entry 2120 (class 1259 OID 16428)
-- Name: index_pessoa_documento; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX index_pessoa_documento ON pessoa USING btree (documento);


--
-- TOC entry 2668 (class 0 OID 0)
-- Dependencies: 2120
-- Name: INDEX index_pessoa_documento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_pessoa_documento IS 'Index para recuperar email de acesso atravez do documento';


--
-- TOC entry 2196 (class 2606 OID 16911)
-- Name: fk_aluno_situacao_situacao; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT fk_aluno_situacao_situacao FOREIGN KEY (situacao_id) REFERENCES situacao(id);


--
-- TOC entry 2197 (class 2606 OID 16916)
-- Name: fk_aluno_situacao_turma_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT fk_aluno_situacao_turma_aluno FOREIGN KEY (turma_aluno_id) REFERENCES turma_aluno(id);


--
-- TOC entry 2204 (class 2606 OID 17084)
-- Name: fk_dimensao_dimensao_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT fk_dimensao_dimensao_tipo_id FOREIGN KEY (dimensao_tipo_id) REFERENCES dimensao_tipo(id);


--
-- TOC entry 2203 (class 2606 OID 17061)
-- Name: fk_dimensao_fato_ciclo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT fk_dimensao_fato_ciclo_id FOREIGN KEY (fato_ciclo_id) REFERENCES fato_ciclo(id);


--
-- TOC entry 2183 (class 2606 OID 16493)
-- Name: fk_entidade_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT fk_entidade_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2669 (class 0 OID 0)
-- Dependencies: 2183
-- Name: CONSTRAINT fk_entidade_grupo_id ON entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_entidade_grupo_id ON entidade IS 'Chave estrangeira da entidade com grupo';


--
-- TOC entry 2184 (class 2606 OID 16499)
-- Name: fk_entidade_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT fk_entidade_tipo_id FOREIGN KEY (tipo_id) REFERENCES entidade_tipo(id);


--
-- TOC entry 2670 (class 0 OID 0)
-- Dependencies: 2184
-- Name: CONSTRAINT fk_entidade_tipo_id ON entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_entidade_tipo_id ON entidade IS 'Chave estrangeira de entidade com tipo da entidade';


--
-- TOC entry 2186 (class 2606 OID 16661)
-- Name: fk_evento_celula_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula
    ADD CONSTRAINT fk_evento_celula_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- TOC entry 2185 (class 2606 OID 16633)
-- Name: fk_evento_evento_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT fk_evento_evento_tipo_id FOREIGN KEY (tipo_id) REFERENCES evento_tipo(id);


--
-- TOC entry 2192 (class 2606 OID 16755)
-- Name: fk_evento_frequencia_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT fk_evento_frequencia_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- TOC entry 2193 (class 2606 OID 16760)
-- Name: fk_evento_frequencia_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT fk_evento_frequencia_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2205 (class 2606 OID 17431)
-- Name: fk_fato_celula_fato_ciclo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula
    ADD CONSTRAINT fk_fato_celula_fato_ciclo FOREIGN KEY (fato_ciclo_id) REFERENCES fato_ciclo(id);


--
-- TOC entry 2198 (class 2606 OID 16932)
-- Name: fk_grupo_aluno_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT fk_grupo_aluno_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2199 (class 2606 OID 16937)
-- Name: fk_grupo_aluno_turma_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT fk_grupo_aluno_turma_aluno FOREIGN KEY (turma_aluno_id) REFERENCES turma_aluno(id);


--
-- TOC entry 2202 (class 2606 OID 16998)
-- Name: fk_grupo_atendimento_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento
    ADD CONSTRAINT fk_grupo_atendimento_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2206 (class 2606 OID 17751)
-- Name: fk_grupo_cv_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv
    ADD CONSTRAINT fk_grupo_cv_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2188 (class 2606 OID 16699)
-- Name: fk_grupo_evento_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT fk_grupo_evento_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- TOC entry 2187 (class 2606 OID 16690)
-- Name: fk_grupo_evento_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT fk_grupo_evento_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2181 (class 2606 OID 16483)
-- Name: fk_grupo_pai_filho_filho_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT fk_grupo_pai_filho_filho_id FOREIGN KEY (filho_id) REFERENCES grupo(id);


--
-- TOC entry 2182 (class 2606 OID 16488)
-- Name: fk_grupo_pai_filho_pai_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT fk_grupo_pai_filho_pai_id FOREIGN KEY (pai_id) REFERENCES grupo(id);


--
-- TOC entry 2189 (class 2606 OID 16730)
-- Name: fk_grupo_pessoa_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2190 (class 2606 OID 16735)
-- Name: fk_grupo_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2191 (class 2606 OID 16740)
-- Name: fk_grupo_pessoa_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_tipo_id FOREIGN KEY (tipo_id) REFERENCES grupo_pessoa_tipo(id);


--
-- TOC entry 2180 (class 2606 OID 16478)
-- Name: fk_grupo_responsavel_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT fk_grupo_responsavel_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2179 (class 2606 OID 16473)
-- Name: fk_grupo_responsavel_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT fk_grupo_responsavel_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2201 (class 2606 OID 16965)
-- Name: fk_pessoa_hierarquia_hierarquia; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT fk_pessoa_hierarquia_hierarquia FOREIGN KEY (hierarquia_id) REFERENCES hierarquia(id);


--
-- TOC entry 2200 (class 2606 OID 16960)
-- Name: fk_pessoa_hierarquia_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT fk_pessoa_hierarquia_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2194 (class 2606 OID 16854)
-- Name: fk_turma_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT fk_turma_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2195 (class 2606 OID 16861)
-- Name: fk_turma_pessoa_turma_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT fk_turma_pessoa_turma_id FOREIGN KEY (turma_id) REFERENCES turma(id);


--
-- TOC entry 2374 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2017-05-22 10:56:39

--
-- PostgreSQL database dump complete
--

