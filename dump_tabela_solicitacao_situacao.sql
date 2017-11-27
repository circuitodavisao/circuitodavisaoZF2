--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-11-27 10:13:44

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
-- TOC entry 230 (class 1259 OID 19416)
-- Name: solicitacao_situacao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solicitacao_situacao (
    id integer NOT NULL,
    solicitacao_id integer NOT NULL,
    situacao_id integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    extra character varying(100)
);


ALTER TABLE solicitacao_situacao OWNER TO postgres;

--
-- TOC entry 2147 (class 0 OID 0)
-- Dependencies: 230
-- Name: TABLE solicitacao_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE solicitacao_situacao IS 'Associação entre solicitação e situação';


--
-- TOC entry 2148 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN solicitacao_situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_situacao.id IS 'identificação';


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN solicitacao_situacao.solicitacao_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_situacao.solicitacao_id IS 'Identificação da solicitacao';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN solicitacao_situacao.situacao_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_situacao.situacao_id IS 'Identificação da sistuacao';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN solicitacao_situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_situacao.data_criacao IS 'Data de criação';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN solicitacao_situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_situacao.hora_criacao IS 'Hora de criação';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN solicitacao_situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN solicitacao_situacao.data_inativacao IS 'Data de inativação';


--
-- TOC entry 229 (class 1259 OID 19414)
-- Name: solicitacao_situacao_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitacao_situacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE solicitacao_situacao_id_seq OWNER TO postgres;

--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 229
-- Name: solicitacao_situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitacao_situacao_id_seq OWNED BY solicitacao_situacao.id;


--
-- TOC entry 2031 (class 2604 OID 19419)
-- Name: solicitacao_situacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao_situacao ALTER COLUMN id SET DEFAULT nextval('solicitacao_situacao_id_seq'::regclass);


--
-- TOC entry 2033 (class 2606 OID 19421)
-- Name: solicitacao_situacao pk_solicitacao_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao_situacao
    ADD CONSTRAINT pk_solicitacao_situacao PRIMARY KEY (id);


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 2033
-- Name: CONSTRAINT pk_solicitacao_situacao ON solicitacao_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_solicitacao_situacao ON solicitacao_situacao IS 'Chave primaria da associação entre solicitação e situação';


--
-- TOC entry 2035 (class 2606 OID 19427)
-- Name: solicitacao_situacao fk_solicitacao_situacao_situacao_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao_situacao
    ADD CONSTRAINT fk_solicitacao_situacao_situacao_id FOREIGN KEY (situacao_id) REFERENCES situacao(id);


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 2035
-- Name: CONSTRAINT fk_solicitacao_situacao_situacao_id ON solicitacao_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_solicitacao_situacao_situacao_id ON solicitacao_situacao IS 'Chave estrangeira da identificação da situação';


--
-- TOC entry 2034 (class 2606 OID 19422)
-- Name: solicitacao_situacao fk_solicitacao_situacao_solicitacao_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitacao_situacao
    ADD CONSTRAINT fk_solicitacao_situacao_solicitacao_id FOREIGN KEY (solicitacao_id) REFERENCES solicitacao(id);


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 2034
-- Name: CONSTRAINT fk_solicitacao_situacao_solicitacao_id ON solicitacao_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_solicitacao_situacao_solicitacao_id ON solicitacao_situacao IS 'Chave estrangeira da identificação da solicitação';


-- Completed on 2017-11-27 10:13:59

--
-- PostgreSQL database dump complete
--

