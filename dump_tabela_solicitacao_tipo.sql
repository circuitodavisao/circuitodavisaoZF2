--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-11-27 11:16:17

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 226 (class 1259 OID 19386)
-- Name: solicitacao_tipo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solicitacao_tipo (
    id integer NOT NULL,
    nome character varying(80) NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE solicitacao_tipo OWNER TO postgres;

--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 226
-- Name: TABLE solicitacao_tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE solicitacao_tipo IS 'Tabela com os tipos de dimensões';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN solicitacao_tipo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_tipo.id IS 'Identificação';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN solicitacao_tipo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_tipo.nome IS 'Nome tipo da solicitacao';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN solicitacao_tipo.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_tipo.data_criacao IS 'Data de criação do tipo da solicitacao';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN solicitacao_tipo.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_tipo.hora_criacao IS 'Hora de criação do tipo da solicitacao';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN solicitacao_tipo.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_tipo.data_inativacao IS 'Data de inativação do tipo da solicitacao';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN solicitacao_tipo.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_tipo.hora_inativacao IS 'Hora de inativação do tipo da solicitacao';


--
-- TOC entry 225 (class 1259 OID 19384)
-- Name: solicitacao_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitacao_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE solicitacao_tipo_id_seq OWNER TO postgres;

--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 225
-- Name: solicitacao_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitacao_tipo_id_seq OWNED BY solicitacao_tipo.id;


--
-- TOC entry 2031 (class 2604 OID 19389)
-- Name: solicitacao_tipo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao_tipo ALTER COLUMN id SET DEFAULT nextval('solicitacao_tipo_id_seq'::regclass);


--
-- TOC entry 2144 (class 0 OID 19386)
-- Dependencies: 226
-- Data for Name: solicitacao_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitacao_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	TRANSFERÊNCIA DE LÍDER NA PRÓPRIA EQUIPE	2017-11-03	09:10:16.268684	\N	\N
\.


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 225
-- Name: solicitacao_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitacao_tipo_id_seq', 1, false);


--
-- TOC entry 2035 (class 2606 OID 19393)
-- Name: solicitacao_tipo pk_solicitacao_tipo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao_tipo
    ADD CONSTRAINT pk_solicitacao_tipo PRIMARY KEY (id);


-- Completed on 2017-11-27 11:16:34

--
-- PostgreSQL database dump complete
--

