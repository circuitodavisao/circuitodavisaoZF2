--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:23:40

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
-- TOC entry 236 (class 1259 OID 20036)
-- Name: matricula_frequencia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE matricula_frequencia (
    id integer NOT NULL,
    matricula integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE matricula_frequencia OWNER TO postgres;

--
-- TOC entry 2145 (class 0 OID 0)
-- Dependencies: 236
-- Name: TABLE matricula_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE matricula_frequencia IS 'Tabela com as frequencias vidas dos aplicativos';


--
-- TOC entry 2146 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN matricula_frequencia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matricula_frequencia.id IS 'Identificação';


--
-- TOC entry 2147 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN matricula_frequencia.matricula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matricula_frequencia.matricula IS 'Matricula do aluno';


--
-- TOC entry 2148 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN matricula_frequencia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matricula_frequencia.data_criacao IS 'Data de criação';


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN matricula_frequencia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matricula_frequencia.hora_criacao IS 'Hora da criação';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN matricula_frequencia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matricula_frequencia.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN matricula_frequencia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matricula_frequencia.hora_inativacao IS 'Hora de inativação';


--
-- TOC entry 235 (class 1259 OID 20034)
-- Name: matricula_frequencia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE matricula_frequencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE matricula_frequencia_id_seq OWNER TO postgres;

--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 235
-- Name: matricula_frequencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE matricula_frequencia_id_seq OWNED BY matricula_frequencia.id;


--
-- TOC entry 2031 (class 2604 OID 20039)
-- Name: matricula_frequencia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY matricula_frequencia ALTER COLUMN id SET DEFAULT nextval('matricula_frequencia_id_seq'::regclass);


--
-- TOC entry 2033 (class 2606 OID 20041)
-- Name: matricula_frequencia pk_matricula_frequencia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY matricula_frequencia
    ADD CONSTRAINT pk_matricula_frequencia PRIMARY KEY (id);


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 2033
-- Name: CONSTRAINT pk_matricula_frequencia ON matricula_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_matricula_frequencia ON matricula_frequencia IS 'Chave primaria da matricua frequencia vindo dos aplicativos';


-- Completed on 2018-01-05 16:23:55

--
-- PostgreSQL database dump complete
--

