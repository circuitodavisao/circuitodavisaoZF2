--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-11-27 10:13:08

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
-- TOC entry 228 (class 1259 OID 19396)
-- Name: solicitacao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solicitacao (
    id integer NOT NULL,
    solicitante_id integer NOT NULL,
    solicitacao_tipo_id integer NOT NULL,
    objeto1 integer NOT NULL,
    objeto2 integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    numero integer,
    nome character varying(25)
);


ALTER TABLE solicitacao OWNER TO postgres;

--
-- TOC entry 2148 (class 0 OID 0)
-- Dependencies: 228
-- Name: TABLE solicitacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE solicitacao IS 'Tabela associativa do grupo com a solicitacao';


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.id IS 'Identificação da associação da grupo com a hierarquia';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.solicitante_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.solicitante_id IS 'Identificação da pessoa solicitante';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.solicitacao_tipo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.solicitacao_tipo_id IS 'Identificação da solicitacao tipo';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.data_criacao IS 'Data de criação da associação grupo com a solicitacao';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.hora_criacao IS 'Hora de criação da associação grupo com a solicitacao';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.data_inativacao IS 'Data da inativação da associação grupo com a solicitacao';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN solicitacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao.hora_inativacao IS 'Hora da inativação da associativa grupo com a solicitacao';


--
-- TOC entry 227 (class 1259 OID 19394)
-- Name: solicitacao_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE solicitacao_id_seq OWNER TO postgres;

--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 227
-- Name: solicitacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitacao_id_seq OWNED BY solicitacao.id;


--
-- TOC entry 2031 (class 2604 OID 19399)
-- Name: solicitacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao ALTER COLUMN id SET DEFAULT nextval('solicitacao_id_seq'::regclass);


--
-- TOC entry 2035 (class 2606 OID 19403)
-- Name: solicitacao pk_solicitacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao
    ADD CONSTRAINT pk_solicitacao PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 19432)
-- Name: solicitacao fk_solicitacao_solicitacao_tipo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao
    ADD CONSTRAINT fk_solicitacao_solicitacao_tipo_id FOREIGN KEY (solicitacao_tipo_id) REFERENCES solicitacao_tipo(id);


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 2036
-- Name: CONSTRAINT fk_solicitacao_solicitacao_tipo_id ON solicitacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_solicitacao_solicitacao_tipo_id ON solicitacao IS 'Chave estrangeira da solicitação com o tipo';


-- Completed on 2017-11-27 10:13:24

--
-- PostgreSQL database dump complete
--

