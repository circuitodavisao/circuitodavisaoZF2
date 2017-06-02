--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 14:56:42

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 11791)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2320 (class 0 OID 0)
-- Dependencies: 1
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
-- TOC entry 203 (class 1259 OID 16874)
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
-- TOC entry 2321 (class 0 OID 0)
-- Dependencies: 203
-- Name: TABLE aluno_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE aluno_situacao IS 'Associação entre o aluno e sua situacao';


--
-- TOC entry 2322 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.id IS 'Identificação da associação do aluno com a situação';


--
-- TOC entry 2323 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.situacao_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.situacao_id IS 'Identificação da situação';


--
-- TOC entry 2324 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.turma_aluno_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.turma_aluno_id IS 'Identificação (matricula) da turma aluno';


--
-- TOC entry 2325 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.data_criacao IS 'Data de criação da associação da situação com o aluno';


--
-- TOC entry 2326 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.hora_criacao IS 'Hora de criação da associação da situação com o aluno';


--
-- TOC entry 2327 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.data_inativacao IS 'Data de inativação da associação da situação com aluno';


--
-- TOC entry 2328 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN aluno_situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aluno_situacao.hora_inativacao IS 'Hora da inativação da associação situação com aluno';


--
-- TOC entry 204 (class 1259 OID 16897)
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
-- TOC entry 2329 (class 0 OID 0)
-- Dependencies: 204
-- Name: aluno_situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE aluno_situacao_id_seq OWNED BY aluno_situacao.id;


--
-- TOC entry 216 (class 1259 OID 17052)
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
-- TOC entry 2330 (class 0 OID 0)
-- Dependencies: 216
-- Name: TABLE dimensao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE dimensao IS 'Dimensão dos dados';


--
-- TOC entry 2331 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.id IS 'Identificação';


--
-- TOC entry 2332 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.fato_ciclo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.fato_ciclo_id IS 'Identificação do fato ciclo';


--
-- TOC entry 2333 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.dimensao_tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.dimensao_tipo_id IS 'Identificação do tipo dos dados';


--
-- TOC entry 2334 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.visitante; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.visitante IS 'Número de visitantes lançados';


--
-- TOC entry 2335 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.consolidacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.consolidacao IS 'Número de consolidações lançadas';


--
-- TOC entry 2336 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.membro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.membro IS 'Número de membros lançados';


--
-- TOC entry 2337 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.data_criacao IS 'Data de criação da dimensão';


--
-- TOC entry 2338 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.hora_criacao IS 'Hora de criação da dimensão';


--
-- TOC entry 2339 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.data_inativacao IS 'Data de inativação da dimensão';


--
-- TOC entry 2340 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.hora_inativacao IS 'Hora da inativação da dimensão';


--
-- TOC entry 2341 (class 0 OID 0)
-- Dependencies: 216
-- Name: COLUMN dimensao.lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao.lider IS 'Número de líderes lançados';


--
-- TOC entry 215 (class 1259 OID 17050)
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
-- TOC entry 2342 (class 0 OID 0)
-- Dependencies: 215
-- Name: dimensao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dimensao_id_seq OWNED BY dimensao.id;


--
-- TOC entry 218 (class 1259 OID 17078)
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
-- TOC entry 2343 (class 0 OID 0)
-- Dependencies: 218
-- Name: TABLE dimensao_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE dimensao_tipo IS 'Tabela com os tipos de dimensões';


--
-- TOC entry 2344 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN dimensao_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.id IS 'Identificação';


--
-- TOC entry 2345 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN dimensao_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.nome IS 'Nome tipo da dimensão';


--
-- TOC entry 2346 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN dimensao_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.data_criacao IS 'Data de criação do tipo da dimensão';


--
-- TOC entry 2347 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN dimensao_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.hora_criacao IS 'Hora de criação do tipo da dimensão';


--
-- TOC entry 2348 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN dimensao_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.data_inativacao IS 'Data de inativação do tipo da dimensão';


--
-- TOC entry 2349 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN dimensao_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dimensao_tipo.hora_inativacao IS 'Hora de inativação do tipo da dimensão';


--
-- TOC entry 217 (class 1259 OID 17076)
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
-- TOC entry 2350 (class 0 OID 0)
-- Dependencies: 217
-- Name: dimensao_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dimensao_tipo_id_seq OWNED BY dimensao_tipo.id;


--
-- TOC entry 180 (class 1259 OID 16458)
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
-- TOC entry 2351 (class 0 OID 0)
-- Dependencies: 180
-- Name: TABLE entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entidade IS 'Tabela que armazena os dados das diversas entidades com número, nomes, telefone e endereços';


--
-- TOC entry 2352 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.id IS 'Identificação da entidade';


--
-- TOC entry 2353 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.data_criacao IS 'Data de criação da entidade';


--
-- TOC entry 2354 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.hora_criacao IS 'Hora de criação da entidade';


--
-- TOC entry 2355 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.nome IS 'Nome para as entidades: igreja, equipes';


--
-- TOC entry 2356 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.numero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.numero IS 'Número para as entidades: região, coordenação e subs';


--
-- TOC entry 2357 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.data_inativacao IS 'Data de inativação da entidade';


--
-- TOC entry 2358 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.hora_inativacao IS 'Hora da inativação da entidade';


--
-- TOC entry 2359 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.tipo_id IS 'Indetificação do tipo de entidade';


--
-- TOC entry 2360 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN entidade.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 179 (class 1259 OID 16456)
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
-- TOC entry 2361 (class 0 OID 0)
-- Dependencies: 179
-- Name: entidade_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entidade_id_seq OWNED BY entidade.id;


--
-- TOC entry 182 (class 1259 OID 16467)
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
-- TOC entry 2362 (class 0 OID 0)
-- Dependencies: 182
-- Name: TABLE entidade_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entidade_tipo IS 'Tabela com os tipo de entidades';


--
-- TOC entry 2363 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN entidade_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.id IS 'Identificação do tipo de entidade';


--
-- TOC entry 2364 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN entidade_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.nome IS 'Nome da entidade';


--
-- TOC entry 2365 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN entidade_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.data_criacao IS 'Data de criação do tipo da entidade';


--
-- TOC entry 2366 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN entidade_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.hora_criacao IS 'Hora de criação do tipo da entidade';


--
-- TOC entry 2367 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN entidade_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.data_inativacao IS 'Data de inativação do tipo da entidade';


--
-- TOC entry 2368 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN entidade_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN entidade_tipo.hora_inativacao IS 'Hora inativação do tipo da entidade';


--
-- TOC entry 181 (class 1259 OID 16465)
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
-- TOC entry 2369 (class 0 OID 0)
-- Dependencies: 181
-- Name: entidade_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entidade_tipo_id_seq OWNED BY entidade_tipo.id;


--
-- TOC entry 184 (class 1259 OID 16592)
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
-- TOC entry 2370 (class 0 OID 0)
-- Dependencies: 184
-- Name: TABLE evento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento IS 'Tabela que armazena dados dos eventos em geral';


--
-- TOC entry 2371 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.id IS 'Identificação do evento';


--
-- TOC entry 2372 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.dia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.dia IS 'Dia da semana que ocorre o evento.
1 - domingo
7 - sabado';


--
-- TOC entry 2373 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora IS 'Hora que ocorre o evento';


--
-- TOC entry 2374 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data_criacao IS 'Data de criação do evento';


--
-- TOC entry 2375 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora_criacao IS 'Hora de criação do evento';


--
-- TOC entry 2376 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data_inativacao IS 'Data de inativação do evento';


--
-- TOC entry 2377 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.hora_inativacao IS 'Hora de inativação do evento';


--
-- TOC entry 2378 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.tipo_id IS 'Identificação do tipo do evento';


--
-- TOC entry 2379 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.nome IS 'Nome do evento';


--
-- TOC entry 2380 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN evento.data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento.data IS 'Data do evento';


--
-- TOC entry 188 (class 1259 OID 16640)
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
-- TOC entry 2381 (class 0 OID 0)
-- Dependencies: 188
-- Name: TABLE evento_celula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_celula IS 'Tabela para amarzenas dados do evento tipo célula';


--
-- TOC entry 2382 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.id IS 'Identificação do dados do evento tipo célula';


--
-- TOC entry 2383 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.nome_hospedeiro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.nome_hospedeiro IS 'Nome do hospedeiro da célula';


--
-- TOC entry 2384 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.telefone_hospedeiro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.telefone_hospedeiro IS 'Telefone do hospedeiro com 9 digitos com DDD';


--
-- TOC entry 2385 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.logradouro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.logradouro IS 'Logradouro da célula';


--
-- TOC entry 2386 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.complemento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.complemento IS 'Complemento do endereço da célula';


--
-- TOC entry 2387 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.evento_id IS 'Identificação do evento';


--
-- TOC entry 2388 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.bairro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.bairro IS 'Bairro do local da célula';


--
-- TOC entry 2389 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.cidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.cidade IS 'Cidade do local da célula';


--
-- TOC entry 2390 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.cep; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.cep IS 'CEP do local da célula';


--
-- TOC entry 2391 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN evento_celula.uf; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_celula.uf IS 'Unidade Federativa da célula';


--
-- TOC entry 187 (class 1259 OID 16638)
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
-- TOC entry 2392 (class 0 OID 0)
-- Dependencies: 187
-- Name: evento_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_celula_id_seq OWNED BY evento_celula.id;


--
-- TOC entry 196 (class 1259 OID 16747)
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
    ciclo smallint DEFAULT 1 NOT NULL,
    mes smallint NOT NULL,
    ano smallint NOT NULL
);


ALTER TABLE evento_frequencia OWNER TO postgres;

--
-- TOC entry 2393 (class 0 OID 0)
-- Dependencies: 196
-- Name: TABLE evento_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_frequencia IS 'Tabela associativa da pessoa no evento';


--
-- TOC entry 2394 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.id IS 'Identificação da frequência no evento';


--
-- TOC entry 2395 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.evento_id IS 'Identificação do evento';


--
-- TOC entry 2396 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2397 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2398 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.hora_criacao IS 'Hora de criação da associativa';


--
-- TOC entry 2399 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.data_inativacao IS 'Data da inativação da associativa';


--
-- TOC entry 2400 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 2401 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.frequencia IS 'Frequência da pessoa no evento';


--
-- TOC entry 2402 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.ciclo IS 'Ciclo da frequência do evento';


--
-- TOC entry 2403 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.mes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.mes IS 'Mês que ocorre a frequência do evento';


--
-- TOC entry 2404 (class 0 OID 0)
-- Dependencies: 196
-- Name: COLUMN evento_frequencia.ano; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_frequencia.ano IS 'Ano que ocorre a frequência do evento';


--
-- TOC entry 195 (class 1259 OID 16745)
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
-- TOC entry 2405 (class 0 OID 0)
-- Dependencies: 195
-- Name: evento_frequencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_frequencia_id_seq OWNED BY evento_frequencia.id;


--
-- TOC entry 183 (class 1259 OID 16590)
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
-- TOC entry 2406 (class 0 OID 0)
-- Dependencies: 183
-- Name: evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_id_seq OWNED BY evento.id;


--
-- TOC entry 186 (class 1259 OID 16620)
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
-- TOC entry 2407 (class 0 OID 0)
-- Dependencies: 186
-- Name: TABLE evento_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE evento_tipo IS 'Tipo de evento';


--
-- TOC entry 2408 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN evento_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.id IS 'Identificação dos tipos de evento';


--
-- TOC entry 2409 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN evento_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.nome IS 'Nome do tipo de evento';


--
-- TOC entry 2410 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN evento_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.data_criacao IS 'Data de criação do tipo do evento';


--
-- TOC entry 2411 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN evento_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.hora_criacao IS 'Hora de criação do tipo de evento';


--
-- TOC entry 2412 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN evento_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.data_inativacao IS 'Data de inativação do tipo de evento';


--
-- TOC entry 2413 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN evento_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_tipo.hora_inativacao IS 'Hora de inativação do tipo de evento';


--
-- TOC entry 185 (class 1259 OID 16618)
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
-- TOC entry 2414 (class 0 OID 0)
-- Dependencies: 185
-- Name: evento_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_tipo_id_seq OWNED BY evento_tipo.id;


--
-- TOC entry 222 (class 1259 OID 17405)
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
-- TOC entry 2415 (class 0 OID 0)
-- Dependencies: 222
-- Name: TABLE fato_celula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_celula IS 'Tabela com a quantidade de celulas atuais e realizadas no ciclo';


--
-- TOC entry 2416 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.id IS 'Identificação do relatório';


--
-- TOC entry 2417 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.data_criacao IS 'Data de criação do relatório';


--
-- TOC entry 2418 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.hora_criacao IS 'Hora da criação do relatório';


--
-- TOC entry 2419 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.data_inativacao IS 'Data da inativação do relatório';


--
-- TOC entry 2420 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.hora_inativacao IS 'Hora da inativação do relatório';


--
-- TOC entry 2421 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.realizada; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.realizada IS 'Realizada 1 ou 0';


--
-- TOC entry 2422 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.fato_ciclo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.fato_ciclo_id IS 'Identificação do fato ciclo';


--
-- TOC entry 2423 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN fato_celula.evento_celula_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_celula.evento_celula_id IS 'Identificação do evento célula';


--
-- TOC entry 221 (class 1259 OID 17403)
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
-- TOC entry 2424 (class 0 OID 0)
-- Dependencies: 221
-- Name: fato_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_celula_id_seq OWNED BY fato_celula.id;


--
-- TOC entry 214 (class 1259 OID 17013)
-- Name: fato_ciclo; Type: TABLE; Schema: public; Owner: postgres
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
-- TOC entry 2425 (class 0 OID 0)
-- Dependencies: 214
-- Name: TABLE fato_ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_ciclo IS 'Tabela com os dados consolidados do lançamento de dados das igrejas, equipes e subs';


--
-- TOC entry 2426 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.id IS 'Identificação';


--
-- TOC entry 2427 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.numero_identificador IS 'Número para saber de qual igreja pertenço
8 espaços para cada posição';


--
-- TOC entry 2428 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.mes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.mes IS 'Mês do relatório';


--
-- TOC entry 2429 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.ano; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.ano IS 'Ano do relatório';


--
-- TOC entry 2430 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.ciclo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.ciclo IS 'Ciclo do relatório';


--
-- TOC entry 2431 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.data_criacao IS 'Data de criação do fato';


--
-- TOC entry 2432 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2433 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.hora_inativacao IS 'Hora de inativação do fato';


--
-- TOC entry 2434 (class 0 OID 0)
-- Dependencies: 214
-- Name: COLUMN fato_ciclo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_ciclo.hora_criacao IS 'Hora de criação do fato';


--
-- TOC entry 213 (class 1259 OID 17011)
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
-- TOC entry 2435 (class 0 OID 0)
-- Dependencies: 213
-- Name: fato_ciclo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_ciclo_id_seq OWNED BY fato_ciclo.id;


--
-- TOC entry 219 (class 1259 OID 17341)
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
-- TOC entry 2436 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE fato_lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_lider IS 'Tabela com a quantidade de lideres por número identificador';


--
-- TOC entry 2437 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.id IS 'Identificação do fato lider';


--
-- TOC entry 2438 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.numero_identificador IS 'Número identificador do grupo';


--
-- TOC entry 2439 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.lideres; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.lideres IS 'Quantidade de lideres';


--
-- TOC entry 2440 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.data_criacao IS 'Data de criação do relatório';


--
-- TOC entry 2441 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.hora_criacao IS 'Hora de criação do relatório';


--
-- TOC entry 2442 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.data_inativacao IS 'Data de inativação do relatório';


--
-- TOC entry 2443 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN fato_lider.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fato_lider.hora_inativacao IS 'Hora de inativação do relatório';


--
-- TOC entry 220 (class 1259 OID 17344)
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
-- TOC entry 2444 (class 0 OID 0)
-- Dependencies: 220
-- Name: fato_lider_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_lider_id_seq OWNED BY fato_lider.id;


--
-- TOC entry 178 (class 1259 OID 16450)
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
-- TOC entry 2445 (class 0 OID 0)
-- Dependencies: 178
-- Name: TABLE grupo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo IS 'Tabela com os grupos ';


--
-- TOC entry 2446 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN grupo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.id IS 'Identificação do grupo';


--
-- TOC entry 2447 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN grupo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.data_criacao IS 'Data de criação do grupo';


--
-- TOC entry 2448 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN grupo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.hora_criacao IS 'Hora de criação do grupo';


--
-- TOC entry 2449 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN grupo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.data_inativacao IS 'Data de inativação do grupo';


--
-- TOC entry 2450 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN grupo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo.hora_inativacao IS 'Hora de inativação do grupo';


--
-- TOC entry 206 (class 1259 OID 16924)
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
-- TOC entry 2451 (class 0 OID 0)
-- Dependencies: 206
-- Name: TABLE grupo_aluno; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_aluno IS 'Tabela associativa do aluno com grupo';


--
-- TOC entry 2452 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.id IS 'Identificação da associativa aluno com o grupo';


--
-- TOC entry 2453 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2454 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.turma_aluno_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.turma_aluno_id IS 'Identificação (matricula) da turma aluno';


--
-- TOC entry 2455 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2456 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.hora_criacao IS 'Hora de criação da associação';


--
-- TOC entry 2457 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.data_inativacao IS 'Data da inativação da associação';


--
-- TOC entry 2458 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN grupo_aluno.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_aluno.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 205 (class 1259 OID 16922)
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
-- TOC entry 2459 (class 0 OID 0)
-- Dependencies: 205
-- Name: grupo_aluno_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_aluno_id_seq OWNED BY grupo_aluno.id;


--
-- TOC entry 212 (class 1259 OID 16989)
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
-- TOC entry 2460 (class 0 OID 0)
-- Dependencies: 212
-- Name: TABLE grupo_atendimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_atendimento IS 'Tabela associativa do grupo com atendimento';


--
-- TOC entry 2461 (class 0 OID 0)
-- Dependencies: 212
-- Name: COLUMN grupo_atendimento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.id IS 'Identificação da associação';


--
-- TOC entry 2462 (class 0 OID 0)
-- Dependencies: 212
-- Name: COLUMN grupo_atendimento.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2463 (class 0 OID 0)
-- Dependencies: 212
-- Name: COLUMN grupo_atendimento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2464 (class 0 OID 0)
-- Dependencies: 212
-- Name: COLUMN grupo_atendimento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.hora_criacao IS 'Hora de criação da associação';


--
-- TOC entry 2465 (class 0 OID 0)
-- Dependencies: 212
-- Name: COLUMN grupo_atendimento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2466 (class 0 OID 0)
-- Dependencies: 212
-- Name: COLUMN grupo_atendimento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_atendimento.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 211 (class 1259 OID 16987)
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
-- TOC entry 2467 (class 0 OID 0)
-- Dependencies: 211
-- Name: grupo_atendimento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_atendimento_id_seq OWNED BY grupo_atendimento.id;


--
-- TOC entry 224 (class 1259 OID 17741)
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
-- TOC entry 2468 (class 0 OID 0)
-- Dependencies: 224
-- Name: TABLE grupo_cv; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_cv IS 'Tabela associativa com o sistema antigo';


--
-- TOC entry 223 (class 1259 OID 17739)
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
-- TOC entry 2469 (class 0 OID 0)
-- Dependencies: 223
-- Name: grupo_cv_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_cv_id_seq OWNED BY grupo_cv.id;


--
-- TOC entry 190 (class 1259 OID 16668)
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
-- TOC entry 2470 (class 0 OID 0)
-- Dependencies: 190
-- Name: TABLE grupo_evento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_evento IS 'Tabela de eventos que o grupo participa';


--
-- TOC entry 2471 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.id IS 'Identificação dos eventos do grupo';


--
-- TOC entry 2472 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2473 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.evento_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.evento_id IS 'Identificação do evento';


--
-- TOC entry 2474 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2475 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.hora_criacao IS 'Hora de criação da associação';


--
-- TOC entry 2476 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2477 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN grupo_evento.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_evento.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 189 (class 1259 OID 16666)
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
-- TOC entry 2478 (class 0 OID 0)
-- Dependencies: 189
-- Name: grupo_evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_evento_id_seq OWNED BY grupo_evento.id;


--
-- TOC entry 177 (class 1259 OID 16448)
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
-- TOC entry 2479 (class 0 OID 0)
-- Dependencies: 177
-- Name: grupo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_id_seq OWNED BY grupo.id;


--
-- TOC entry 176 (class 1259 OID 16440)
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
-- TOC entry 2480 (class 0 OID 0)
-- Dependencies: 176
-- Name: TABLE grupo_pai_filho; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pai_filho IS 'Tabela associativa entre grupos';


--
-- TOC entry 2481 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.id IS 'Identificação da associação entre grupos';


--
-- TOC entry 2482 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2483 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.hora_criacao IS 'Hora da criação da associação';


--
-- TOC entry 2484 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.pai_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.pai_id IS 'Identificação do grupo pai';


--
-- TOC entry 2485 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.filho_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.filho_id IS 'Identificação do grupo filho';


--
-- TOC entry 2486 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2487 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN grupo_pai_filho.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pai_filho.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 175 (class 1259 OID 16438)
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
-- TOC entry 2488 (class 0 OID 0)
-- Dependencies: 175
-- Name: grupo_pai_filho_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pai_filho_id_seq OWNED BY grupo_pai_filho.id;


--
-- TOC entry 192 (class 1259 OID 16710)
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
-- TOC entry 2489 (class 0 OID 0)
-- Dependencies: 192
-- Name: TABLE grupo_pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pessoa IS 'Tabela associativa do grupo com as pessoas volateis';


--
-- TOC entry 2490 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.id IS 'Identificação da associação grupo pessoa volatél';


--
-- TOC entry 2491 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2492 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2493 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2494 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.hora_criacao IS 'Hora de criação da associativa';


--
-- TOC entry 2495 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.data_inativacao IS 'Data de inativacao da associação';


--
-- TOC entry 2496 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 2497 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.tipo_id IS 'Identificação do tipo da pessoa volatél';


--
-- TOC entry 2498 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.transferido; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.transferido IS 'Identificação para saber se foi transferido ou não';


--
-- TOC entry 2499 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN grupo_pessoa.nucleo_perfeito; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa.nucleo_perfeito IS 'Enumeração para co líder ou líder em treinamento';


--
-- TOC entry 191 (class 1259 OID 16708)
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
-- TOC entry 2500 (class 0 OID 0)
-- Dependencies: 191
-- Name: grupo_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pessoa_id_seq OWNED BY grupo_pessoa.id;


--
-- TOC entry 194 (class 1259 OID 16721)
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
-- TOC entry 2501 (class 0 OID 0)
-- Dependencies: 194
-- Name: TABLE grupo_pessoa_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_pessoa_tipo IS 'Tabela com o tipo de pessoa volatél';


--
-- TOC entry 2502 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN grupo_pessoa_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.id IS 'Identificação do tipo de pessoa volatél';


--
-- TOC entry 2503 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN grupo_pessoa_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.nome IS 'Nome do tipo de pessoa volatél';


--
-- TOC entry 2504 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN grupo_pessoa_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.data_criacao IS 'Data de criação do tipo da pessoa';


--
-- TOC entry 2505 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN grupo_pessoa_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.hora_criacao IS 'Hora de criação do tipo da pessoa';


--
-- TOC entry 2506 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN grupo_pessoa_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.data_inativacao IS 'Data de inativação do tipo da pessoa';


--
-- TOC entry 2507 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN grupo_pessoa_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_pessoa_tipo.hora_inativacao IS 'Hora da inativação do tipo da pessoa';


--
-- TOC entry 193 (class 1259 OID 16719)
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
-- TOC entry 2508 (class 0 OID 0)
-- Dependencies: 193
-- Name: grupo_pessoa_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_pessoa_tipo_id_seq OWNED BY grupo_pessoa_tipo.id;


--
-- TOC entry 174 (class 1259 OID 16432)
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
-- TOC entry 2509 (class 0 OID 0)
-- Dependencies: 174
-- Name: TABLE grupo_responsavel; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE grupo_responsavel IS 'Tabela associativa do grupo com as pessoas';


--
-- TOC entry 2510 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.id IS 'Identificação do responsavel pelo grupo';


--
-- TOC entry 2511 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.data_criacao IS 'Data de criação da responsabilidade';


--
-- TOC entry 2512 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.hora_criacao IS 'Hora que foi criada a responsabilidade';


--
-- TOC entry 2513 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2514 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2515 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.data_inativacao IS 'Data de inativação da responsabilidade';


--
-- TOC entry 2516 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN grupo_responsavel.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN grupo_responsavel.hora_inativacao IS 'Hora da inativação da responsabilidade';


--
-- TOC entry 173 (class 1259 OID 16430)
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
-- TOC entry 2517 (class 0 OID 0)
-- Dependencies: 173
-- Name: grupo_responsavel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE grupo_responsavel_id_seq OWNED BY grupo_responsavel.id;


--
-- TOC entry 208 (class 1259 OID 16944)
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
-- TOC entry 2518 (class 0 OID 0)
-- Dependencies: 208
-- Name: TABLE hierarquia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE hierarquia IS 'Tabela com as hierarquia';


--
-- TOC entry 2519 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN hierarquia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.id IS 'Identificador das hierarquia';


--
-- TOC entry 2520 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN hierarquia.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.nome IS 'Nome da hierarquia';


--
-- TOC entry 2521 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN hierarquia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.data_criacao IS 'Data de criação da hierarquia';


--
-- TOC entry 2522 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN hierarquia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.hora_criacao IS 'Hora de criação da hierarquia';


--
-- TOC entry 2523 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN hierarquia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.data_inativacao IS 'Data de inativação da hierarquia';


--
-- TOC entry 2524 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN hierarquia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN hierarquia.hora_inativacao IS 'Hora da inativação';


--
-- TOC entry 207 (class 1259 OID 16942)
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
-- TOC entry 2525 (class 0 OID 0)
-- Dependencies: 207
-- Name: hierarquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE hierarquia_id_seq OWNED BY hierarquia.id;


--
-- TOC entry 172 (class 1259 OID 16388)
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
-- TOC entry 2526 (class 0 OID 0)
-- Dependencies: 172
-- Name: TABLE pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa IS 'Tabela pessoa com dados pessoais';


--
-- TOC entry 2527 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.id IS 'Identificação da pessoa';


--
-- TOC entry 2528 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.nome IS 'Nome Completo da pessoa';


--
-- TOC entry 2529 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.email IS 'Email de acesso ';


--
-- TOC entry 2530 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.senha; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.senha IS 'Senha de acesso em MD5';


--
-- TOC entry 2531 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_criacao IS 'Data de criação da pessoa';


--
-- TOC entry 2532 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_inativacao IS 'Data de inativação da pessoa';


--
-- TOC entry 2533 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.documento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.documento IS 'Documento da pessoa, pode ser CPF ou caso estrangeiro um documento aberto';


--
-- TOC entry 2534 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.data_nascimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_nascimento IS 'Data de nascimento da pessoa';


--
-- TOC entry 2535 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.token; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token IS 'Token de recuperacao de senha';


--
-- TOC entry 2536 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.token_data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token_data IS 'Data para validação do token';


--
-- TOC entry 2537 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.token_hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.token_hora IS 'Hora para validação do token';


--
-- TOC entry 2538 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.hora_criacao IS 'Hora de criação da pessoa';


--
-- TOC entry 2539 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.hora_inativacao IS 'Hora de inativação da pessoa';


--
-- TOC entry 2540 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.telefone; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.telefone IS 'Telefone com 9 digitos e DDD';


--
-- TOC entry 2541 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.foto; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.foto IS 'Nome do arquivo com a foto da pessoa';


--
-- TOC entry 2542 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.data_revisao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.data_revisao IS 'Data que a pessoa foi ao revisão de vidas';


--
-- TOC entry 2543 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.sexo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.sexo IS 'Sexo da pessoa';


--
-- TOC entry 2544 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN pessoa.atualizar_dados; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa.atualizar_dados IS 'Variável para verificar se precisa de atualização de dados';


--
-- TOC entry 210 (class 1259 OID 16952)
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
-- TOC entry 2545 (class 0 OID 0)
-- Dependencies: 210
-- Name: TABLE pessoa_hierarquia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa_hierarquia IS 'Tabela associativa da pessoa com a hierarquia';


--
-- TOC entry 2546 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.id IS 'Identificação da associação da pessoa com a hierarquia';


--
-- TOC entry 2547 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2548 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.hierarquia_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hierarquia_id IS 'Identificação da hierarquia';


--
-- TOC entry 2549 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.data_criacao IS 'Data de criação da associação pessoa com a hierarquia';


--
-- TOC entry 2550 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hora_criacao IS 'Hora de criação da associação pessoa com a hierarquia';


--
-- TOC entry 2551 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.data_inativacao IS 'Data da inativação da associação pessoa com a hierarquia';


--
-- TOC entry 2552 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN pessoa_hierarquia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_hierarquia.hora_inativacao IS 'Hora da inativação da associativa pessoa com a hierarquia';


--
-- TOC entry 209 (class 1259 OID 16950)
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
-- TOC entry 2553 (class 0 OID 0)
-- Dependencies: 209
-- Name: pessoa_hierarquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_hierarquia_id_seq OWNED BY pessoa_hierarquia.id;


--
-- TOC entry 171 (class 1259 OID 16386)
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
-- TOC entry 2554 (class 0 OID 0)
-- Dependencies: 171
-- Name: pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_id_seq OWNED BY pessoa.id;


--
-- TOC entry 202 (class 1259 OID 16868)
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
-- TOC entry 2555 (class 0 OID 0)
-- Dependencies: 202
-- Name: TABLE situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE situacao IS 'Tabela com as situações do aluno';


--
-- TOC entry 2556 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.id IS 'Identificação da situação';


--
-- TOC entry 2557 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN situacao.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.nome IS 'Nome da situação do aluno';


--
-- TOC entry 2558 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.data_criacao IS 'Data de criação da situação';


--
-- TOC entry 2559 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.hora_criacao IS 'Hora da criação da situação';


--
-- TOC entry 2560 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.data_inativacao IS 'Data da inativação da situação';


--
-- TOC entry 2561 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN situacao.hora_inativacao IS 'Hora da inativação da situação';


--
-- TOC entry 201 (class 1259 OID 16866)
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
-- TOC entry 2562 (class 0 OID 0)
-- Dependencies: 201
-- Name: situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE situacao_id_seq OWNED BY situacao.id;


--
-- TOC entry 198 (class 1259 OID 16817)
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
-- TOC entry 2563 (class 0 OID 0)
-- Dependencies: 198
-- Name: TABLE turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma IS 'Tabela com os revisão de vidas ou turma do instituto de vencedores';


--
-- TOC entry 2564 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN turma.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.id IS 'Identificação da turma';


--
-- TOC entry 2565 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN turma.data_revisao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_revisao IS 'Data de inicio do revisão de vidas';


--
-- TOC entry 2566 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN turma.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_criacao IS 'Data de criação da turma';


--
-- TOC entry 2567 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN turma.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_criacao IS 'Hora de criação da turma';


--
-- TOC entry 2568 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN turma.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_inativacao IS 'Data de inativação da turma';


--
-- TOC entry 2569 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN turma.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_inativacao IS 'Hora de inativação da turma';


--
-- TOC entry 200 (class 1259 OID 16835)
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
-- TOC entry 2570 (class 0 OID 0)
-- Dependencies: 200
-- Name: TABLE turma_aluno; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_aluno IS 'Associação entre a turma e os alunos';


--
-- TOC entry 2571 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.id IS 'Identificação da associação turma com o aluno';


--
-- TOC entry 2572 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.turma_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.turma_id IS 'Identificação da turma';


--
-- TOC entry 2573 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2574 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2575 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.hora_criacao IS 'Hora da criação da associação';


--
-- TOC entry 2576 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2577 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN turma_aluno.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aluno.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 197 (class 1259 OID 16815)
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
-- TOC entry 2578 (class 0 OID 0)
-- Dependencies: 197
-- Name: turma_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_id_seq OWNED BY turma.id;


--
-- TOC entry 199 (class 1259 OID 16833)
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
-- TOC entry 2579 (class 0 OID 0)
-- Dependencies: 199
-- Name: turma_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_pessoa_id_seq OWNED BY turma_aluno.id;


--
-- TOC entry 2081 (class 2604 OID 16899)
-- Name: aluno_situacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao ALTER COLUMN id SET DEFAULT nextval('aluno_situacao_id_seq'::regclass);


--
-- TOC entry 2099 (class 2604 OID 17055)
-- Name: dimensao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao ALTER COLUMN id SET DEFAULT nextval('dimensao_id_seq'::regclass);


--
-- TOC entry 2106 (class 2604 OID 17081)
-- Name: dimensao_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao_tipo ALTER COLUMN id SET DEFAULT nextval('dimensao_tipo_id_seq'::regclass);


--
-- TOC entry 2045 (class 2604 OID 16461)
-- Name: entidade id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade ALTER COLUMN id SET DEFAULT nextval('entidade_id_seq'::regclass);


--
-- TOC entry 2048 (class 2604 OID 16470)
-- Name: entidade_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade_tipo ALTER COLUMN id SET DEFAULT nextval('entidade_tipo_id_seq'::regclass);


--
-- TOC entry 2051 (class 2604 OID 16595)
-- Name: evento id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento ALTER COLUMN id SET DEFAULT nextval('evento_id_seq'::regclass);


--
-- TOC entry 2057 (class 2604 OID 16643)
-- Name: evento_celula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula ALTER COLUMN id SET DEFAULT nextval('evento_celula_id_seq'::regclass);


--
-- TOC entry 2067 (class 2604 OID 16750)
-- Name: evento_frequencia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia ALTER COLUMN id SET DEFAULT nextval('evento_frequencia_id_seq'::regclass);


--
-- TOC entry 2054 (class 2604 OID 16623)
-- Name: evento_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_tipo ALTER COLUMN id SET DEFAULT nextval('evento_tipo_id_seq'::regclass);


--
-- TOC entry 2113 (class 2604 OID 17408)
-- Name: fato_celula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula ALTER COLUMN id SET DEFAULT nextval('fato_celula_id_seq'::regclass);


--
-- TOC entry 2096 (class 2604 OID 17016)
-- Name: fato_ciclo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ciclo ALTER COLUMN id SET DEFAULT nextval('fato_ciclo_id_seq'::regclass);


--
-- TOC entry 2109 (class 2604 OID 17346)
-- Name: fato_lider id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_lider ALTER COLUMN id SET DEFAULT nextval('fato_lider_id_seq'::regclass);


--
-- TOC entry 2042 (class 2604 OID 16453)
-- Name: grupo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo ALTER COLUMN id SET DEFAULT nextval('grupo_id_seq'::regclass);


--
-- TOC entry 2084 (class 2604 OID 16927)
-- Name: grupo_aluno id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno ALTER COLUMN id SET DEFAULT nextval('grupo_aluno_id_seq'::regclass);


--
-- TOC entry 2093 (class 2604 OID 16992)
-- Name: grupo_atendimento id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento ALTER COLUMN id SET DEFAULT nextval('grupo_atendimento_id_seq'::regclass);


--
-- TOC entry 2117 (class 2604 OID 17744)
-- Name: grupo_cv id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv ALTER COLUMN id SET DEFAULT nextval('grupo_cv_id_seq'::regclass);


--
-- TOC entry 2058 (class 2604 OID 16671)
-- Name: grupo_evento id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento ALTER COLUMN id SET DEFAULT nextval('grupo_evento_id_seq'::regclass);


--
-- TOC entry 2039 (class 2604 OID 16443)
-- Name: grupo_pai_filho id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho ALTER COLUMN id SET DEFAULT nextval('grupo_pai_filho_id_seq'::regclass);


--
-- TOC entry 2061 (class 2604 OID 16713)
-- Name: grupo_pessoa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa ALTER COLUMN id SET DEFAULT nextval('grupo_pessoa_id_seq'::regclass);


--
-- TOC entry 2064 (class 2604 OID 16724)
-- Name: grupo_pessoa_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa_tipo ALTER COLUMN id SET DEFAULT nextval('grupo_pessoa_tipo_id_seq'::regclass);


--
-- TOC entry 2036 (class 2604 OID 16435)
-- Name: grupo_responsavel id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel ALTER COLUMN id SET DEFAULT nextval('grupo_responsavel_id_seq'::regclass);


--
-- TOC entry 2087 (class 2604 OID 16947)
-- Name: hierarquia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hierarquia ALTER COLUMN id SET DEFAULT nextval('hierarquia_id_seq'::regclass);


--
-- TOC entry 2032 (class 2604 OID 16391)
-- Name: pessoa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa ALTER COLUMN id SET DEFAULT nextval('pessoa_id_seq'::regclass);


--
-- TOC entry 2092 (class 2604 OID 16970)
-- Name: pessoa_hierarquia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia ALTER COLUMN id SET DEFAULT nextval('pessoa_hierarquia_id_seq'::regclass);


--
-- TOC entry 2078 (class 2604 OID 16871)
-- Name: situacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY situacao ALTER COLUMN id SET DEFAULT nextval('situacao_id_seq'::regclass);


--
-- TOC entry 2072 (class 2604 OID 16820)
-- Name: turma id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma ALTER COLUMN id SET DEFAULT nextval('turma_id_seq'::regclass);


--
-- TOC entry 2075 (class 2604 OID 16838)
-- Name: turma_aluno id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno ALTER COLUMN id SET DEFAULT nextval('turma_pessoa_id_seq'::regclass);


--
-- TOC entry 2155 (class 2606 OID 16904)
-- Name: aluno_situacao pk_aluno_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT pk_aluno_situacao PRIMARY KEY (id);


--
-- TOC entry 2168 (class 2606 OID 17060)
-- Name: dimensao pk_dimensao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT pk_dimensao PRIMARY KEY (id);


--
-- TOC entry 2170 (class 2606 OID 17083)
-- Name: dimensao_tipo pk_dimensao_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao_tipo
    ADD CONSTRAINT pk_dimensao_tipo PRIMARY KEY (id);


--
-- TOC entry 2131 (class 2606 OID 16463)
-- Name: entidade pk_entidade; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT pk_entidade PRIMARY KEY (id);


--
-- TOC entry 2133 (class 2606 OID 16472)
-- Name: entidade_tipo pk_entidade_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade_tipo
    ADD CONSTRAINT pk_entidade_tipo PRIMARY KEY (id);


--
-- TOC entry 2135 (class 2606 OID 16632)
-- Name: evento pk_evento; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT pk_evento PRIMARY KEY (id);


--
-- TOC entry 2139 (class 2606 OID 16645)
-- Name: evento_celula pk_evento_celula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula
    ADD CONSTRAINT pk_evento_celula PRIMARY KEY (id);


--
-- TOC entry 2147 (class 2606 OID 16754)
-- Name: evento_frequencia pk_evento_frequencia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT pk_evento_frequencia PRIMARY KEY (id);


--
-- TOC entry 2175 (class 2606 OID 17410)
-- Name: fato_celula pk_fato_celula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula
    ADD CONSTRAINT pk_fato_celula PRIMARY KEY (id);


--
-- TOC entry 2166 (class 2606 OID 17018)
-- Name: fato_ciclo pk_fato_ciclo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ciclo
    ADD CONSTRAINT pk_fato_ciclo PRIMARY KEY (id);


--
-- TOC entry 2173 (class 2606 OID 17351)
-- Name: fato_lider pk_fato_lider; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_lider
    ADD CONSTRAINT pk_fato_lider PRIMARY KEY (id);


--
-- TOC entry 2127 (class 2606 OID 16455)
-- Name: grupo pk_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT pk_grupo PRIMARY KEY (id);


--
-- TOC entry 2157 (class 2606 OID 16931)
-- Name: grupo_aluno pk_grupo_aluno; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT pk_grupo_aluno PRIMARY KEY (id);


--
-- TOC entry 2163 (class 2606 OID 16997)
-- Name: grupo_atendimento pk_grupo_atendimento; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento
    ADD CONSTRAINT pk_grupo_atendimento PRIMARY KEY (id);


--
-- TOC entry 2177 (class 2606 OID 17746)
-- Name: grupo_cv pk_grupo_cv; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv
    ADD CONSTRAINT pk_grupo_cv PRIMARY KEY (id);


--
-- TOC entry 2141 (class 2606 OID 16675)
-- Name: grupo_evento pk_grupo_evento; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT pk_grupo_evento PRIMARY KEY (id);


--
-- TOC entry 2125 (class 2606 OID 16445)
-- Name: grupo_pai_filho pk_grupo_pai_filho; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT pk_grupo_pai_filho PRIMARY KEY (id);


--
-- TOC entry 2143 (class 2606 OID 16717)
-- Name: grupo_pessoa pk_grupo_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT pk_grupo_pessoa PRIMARY KEY (id);


--
-- TOC entry 2145 (class 2606 OID 16729)
-- Name: grupo_pessoa_tipo pk_grupo_pessoa_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa_tipo
    ADD CONSTRAINT pk_grupo_pessoa_tipo PRIMARY KEY (id);


--
-- TOC entry 2123 (class 2606 OID 16437)
-- Name: grupo_responsavel pk_grupo_responsavel; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT pk_grupo_responsavel PRIMARY KEY (id);


--
-- TOC entry 2159 (class 2606 OID 16949)
-- Name: hierarquia pk_hierarquia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hierarquia
    ADD CONSTRAINT pk_hierarquia PRIMARY KEY (id);


--
-- TOC entry 2121 (class 2606 OID 16447)
-- Name: pessoa pk_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (id);


--
-- TOC entry 2161 (class 2606 OID 16959)
-- Name: pessoa_hierarquia pk_pessoa_hierarquia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT pk_pessoa_hierarquia PRIMARY KEY (id);


--
-- TOC entry 2153 (class 2606 OID 16873)
-- Name: situacao pk_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY situacao
    ADD CONSTRAINT pk_situacao PRIMARY KEY (id);


--
-- TOC entry 2149 (class 2606 OID 16822)
-- Name: turma pk_turma; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma
    ADD CONSTRAINT pk_turma PRIMARY KEY (id);


--
-- TOC entry 2580 (class 0 OID 0)
-- Dependencies: 2149
-- Name: CONSTRAINT pk_turma ON turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma ON turma IS 'Chave primaria da turma';


--
-- TOC entry 2151 (class 2606 OID 16860)
-- Name: turma_aluno pk_turma_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT pk_turma_pessoa PRIMARY KEY (id);


--
-- TOC entry 2137 (class 2606 OID 16625)
-- Name: evento_tipo primary_key_evento_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_tipo
    ADD CONSTRAINT primary_key_evento_tipo PRIMARY KEY (id);


--
-- TOC entry 2128 (class 1259 OID 16498)
-- Name: fki_entidade_grupo_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_entidade_grupo_id ON entidade USING btree (grupo_id);


--
-- TOC entry 2129 (class 1259 OID 16504)
-- Name: fki_entidade_tipo_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_entidade_tipo_id ON entidade USING btree (tipo_id);


--
-- TOC entry 2164 (class 1259 OID 17330)
-- Name: index_fato_ciclo_numero_identificador; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_fato_ciclo_numero_identificador ON fato_ciclo USING btree (numero_identificador);


--
-- TOC entry 2171 (class 1259 OID 17368)
-- Name: index_fato_lider_numero_identificador; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_fato_lider_numero_identificador ON fato_lider USING btree (numero_identificador);


--
-- TOC entry 2581 (class 0 OID 0)
-- Dependencies: 2171
-- Name: INDEX index_fato_lider_numero_identificador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_fato_lider_numero_identificador IS 'Index do número identificador do grupo';


--
-- TOC entry 2118 (class 1259 OID 16429)
-- Name: index_pessoa_data_nascimento; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_pessoa_data_nascimento ON pessoa USING btree (data_nascimento);


--
-- TOC entry 2582 (class 0 OID 0)
-- Dependencies: 2118
-- Name: INDEX index_pessoa_data_nascimento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_pessoa_data_nascimento IS 'Index para recuperar email de acesso atraves da data de nascimento';


--
-- TOC entry 2119 (class 1259 OID 16428)
-- Name: index_pessoa_documento; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_pessoa_documento ON pessoa USING btree (documento);


--
-- TOC entry 2583 (class 0 OID 0)
-- Dependencies: 2119
-- Name: INDEX index_pessoa_documento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON INDEX index_pessoa_documento IS 'Index para recuperar email de acesso atravez do documento';


--
-- TOC entry 2195 (class 2606 OID 16911)
-- Name: aluno_situacao fk_aluno_situacao_situacao; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT fk_aluno_situacao_situacao FOREIGN KEY (situacao_id) REFERENCES situacao(id);


--
-- TOC entry 2196 (class 2606 OID 16916)
-- Name: aluno_situacao fk_aluno_situacao_turma_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno_situacao
    ADD CONSTRAINT fk_aluno_situacao_turma_aluno FOREIGN KEY (turma_aluno_id) REFERENCES turma_aluno(id);


--
-- TOC entry 2203 (class 2606 OID 17084)
-- Name: dimensao fk_dimensao_dimensao_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT fk_dimensao_dimensao_tipo_id FOREIGN KEY (dimensao_tipo_id) REFERENCES dimensao_tipo(id);


--
-- TOC entry 2202 (class 2606 OID 17061)
-- Name: dimensao fk_dimensao_fato_ciclo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dimensao
    ADD CONSTRAINT fk_dimensao_fato_ciclo_id FOREIGN KEY (fato_ciclo_id) REFERENCES fato_ciclo(id);


--
-- TOC entry 2182 (class 2606 OID 16493)
-- Name: entidade fk_entidade_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT fk_entidade_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2584 (class 0 OID 0)
-- Dependencies: 2182
-- Name: CONSTRAINT fk_entidade_grupo_id ON entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_entidade_grupo_id ON entidade IS 'Chave estrangeira da entidade com grupo';


--
-- TOC entry 2183 (class 2606 OID 16499)
-- Name: entidade fk_entidade_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entidade
    ADD CONSTRAINT fk_entidade_tipo_id FOREIGN KEY (tipo_id) REFERENCES entidade_tipo(id);


--
-- TOC entry 2585 (class 0 OID 0)
-- Dependencies: 2183
-- Name: CONSTRAINT fk_entidade_tipo_id ON entidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_entidade_tipo_id ON entidade IS 'Chave estrangeira de entidade com tipo da entidade';


--
-- TOC entry 2185 (class 2606 OID 16661)
-- Name: evento_celula fk_evento_celula_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_celula
    ADD CONSTRAINT fk_evento_celula_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- TOC entry 2184 (class 2606 OID 16633)
-- Name: evento fk_evento_evento_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT fk_evento_evento_tipo_id FOREIGN KEY (tipo_id) REFERENCES evento_tipo(id);


--
-- TOC entry 2191 (class 2606 OID 16755)
-- Name: evento_frequencia fk_evento_frequencia_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT fk_evento_frequencia_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- TOC entry 2192 (class 2606 OID 16760)
-- Name: evento_frequencia fk_evento_frequencia_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_frequencia
    ADD CONSTRAINT fk_evento_frequencia_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2204 (class 2606 OID 17431)
-- Name: fato_celula fk_fato_celula_fato_ciclo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_celula
    ADD CONSTRAINT fk_fato_celula_fato_ciclo FOREIGN KEY (fato_ciclo_id) REFERENCES fato_ciclo(id);


--
-- TOC entry 2197 (class 2606 OID 16932)
-- Name: grupo_aluno fk_grupo_aluno_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT fk_grupo_aluno_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2198 (class 2606 OID 16937)
-- Name: grupo_aluno fk_grupo_aluno_turma_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_aluno
    ADD CONSTRAINT fk_grupo_aluno_turma_aluno FOREIGN KEY (turma_aluno_id) REFERENCES turma_aluno(id);


--
-- TOC entry 2201 (class 2606 OID 16998)
-- Name: grupo_atendimento fk_grupo_atendimento_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_atendimento
    ADD CONSTRAINT fk_grupo_atendimento_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2205 (class 2606 OID 17751)
-- Name: grupo_cv fk_grupo_cv_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_cv
    ADD CONSTRAINT fk_grupo_cv_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2187 (class 2606 OID 16699)
-- Name: grupo_evento fk_grupo_evento_evento_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT fk_grupo_evento_evento_id FOREIGN KEY (evento_id) REFERENCES evento(id);


--
-- TOC entry 2186 (class 2606 OID 16690)
-- Name: grupo_evento fk_grupo_evento_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_evento
    ADD CONSTRAINT fk_grupo_evento_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2180 (class 2606 OID 16483)
-- Name: grupo_pai_filho fk_grupo_pai_filho_filho_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT fk_grupo_pai_filho_filho_id FOREIGN KEY (filho_id) REFERENCES grupo(id);


--
-- TOC entry 2181 (class 2606 OID 16488)
-- Name: grupo_pai_filho fk_grupo_pai_filho_pai_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pai_filho
    ADD CONSTRAINT fk_grupo_pai_filho_pai_id FOREIGN KEY (pai_id) REFERENCES grupo(id);


--
-- TOC entry 2188 (class 2606 OID 16730)
-- Name: grupo_pessoa fk_grupo_pessoa_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2189 (class 2606 OID 16735)
-- Name: grupo_pessoa fk_grupo_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2190 (class 2606 OID 16740)
-- Name: grupo_pessoa fk_grupo_pessoa_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_pessoa
    ADD CONSTRAINT fk_grupo_pessoa_tipo_id FOREIGN KEY (tipo_id) REFERENCES grupo_pessoa_tipo(id);


--
-- TOC entry 2179 (class 2606 OID 16478)
-- Name: grupo_responsavel fk_grupo_responsavel_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT fk_grupo_responsavel_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2178 (class 2606 OID 16473)
-- Name: grupo_responsavel fk_grupo_responsavel_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo_responsavel
    ADD CONSTRAINT fk_grupo_responsavel_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2200 (class 2606 OID 16965)
-- Name: pessoa_hierarquia fk_pessoa_hierarquia_hierarquia; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT fk_pessoa_hierarquia_hierarquia FOREIGN KEY (hierarquia_id) REFERENCES hierarquia(id);


--
-- TOC entry 2199 (class 2606 OID 16960)
-- Name: pessoa_hierarquia fk_pessoa_hierarquia_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_hierarquia
    ADD CONSTRAINT fk_pessoa_hierarquia_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2193 (class 2606 OID 16854)
-- Name: turma_aluno fk_turma_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT fk_turma_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2194 (class 2606 OID 16861)
-- Name: turma_aluno fk_turma_pessoa_turma_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aluno
    ADD CONSTRAINT fk_turma_pessoa_turma_id FOREIGN KEY (turma_id) REFERENCES turma(id);


--
-- TOC entry 2319 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2017-06-02 14:58:36

--
-- PostgreSQL database dump complete
--

