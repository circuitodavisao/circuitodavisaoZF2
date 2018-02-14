--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-02-09 19:14:15

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
-- TOC entry 240 (class 1259 OID 20696)
-- Name: turma_pessoa_frequencia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma_pessoa_frequencia (
    id integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    turma_pessoa_id integer NOT NULL,
    data date NOT NULL,
    hora time without time zone NOT NULL
);


ALTER TABLE turma_pessoa_frequencia OWNER TO postgres;

--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 240
-- Name: TABLE turma_pessoa_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_pessoa_frequencia IS 'Frequencia vindo do app';


--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.id IS 'Identificacao';


--
-- TOC entry 2160 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.data_criacao IS 'Data de criação';


--
-- TOC entry 2161 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.hora_criacao IS 'Hora de criação';


--
-- TOC entry 2162 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2163 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.hora_inativacao IS 'Hora de inativação';


--
-- TOC entry 2164 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.turma_pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.turma_pessoa_id IS 'identficação da turmapessoa';


--
-- TOC entry 2165 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.data IS 'Data real';


--
-- TOC entry 2166 (class 0 OID 0)
-- Dependencies: 240
-- Name: COLUMN turma_pessoa_frequencia.hora; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_frequencia.hora IS 'Hora real';


--
-- TOC entry 239 (class 1259 OID 20694)
-- Name: turma_pessoa_frequencia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE turma_pessoa_frequencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE turma_pessoa_frequencia_id_seq OWNER TO postgres;

--
-- TOC entry 2167 (class 0 OID 0)
-- Dependencies: 239
-- Name: turma_pessoa_frequencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_pessoa_frequencia_id_seq OWNED BY turma_pessoa_frequencia.id;


--
-- TOC entry 2041 (class 2604 OID 20699)
-- Name: turma_pessoa_frequencia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_frequencia ALTER COLUMN id SET DEFAULT nextval('turma_pessoa_frequencia_id_seq'::regclass);


--
-- TOC entry 2153 (class 0 OID 20696)
-- Dependencies: 240
-- Data for Name: turma_pessoa_frequencia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY turma_pessoa_frequencia (id, data_criacao, hora_criacao, data_inativacao, hora_inativacao, turma_pessoa_id, data, hora) FROM stdin;
\.


--
-- TOC entry 2168 (class 0 OID 0)
-- Dependencies: 239
-- Name: turma_pessoa_frequencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('turma_pessoa_frequencia_id_seq', 1, false);


--
-- TOC entry 2043 (class 2606 OID 20701)
-- Name: turma_pessoa_frequencia pk_turma_pessoa_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_frequencia
    ADD CONSTRAINT pk_turma_pessoa_id PRIMARY KEY (id);


--
-- TOC entry 2169 (class 0 OID 0)
-- Dependencies: 2043
-- Name: CONSTRAINT pk_turma_pessoa_id ON turma_pessoa_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma_pessoa_id ON turma_pessoa_frequencia IS 'Chave primaria do turma pessoa frequencia';


--
-- TOC entry 2044 (class 2606 OID 20702)
-- Name: turma_pessoa_frequencia fk_turma_pessoa_frequencia_turma_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_frequencia
    ADD CONSTRAINT fk_turma_pessoa_frequencia_turma_pessoa_id FOREIGN KEY (turma_pessoa_id) REFERENCES turma_pessoa(id);


--
-- TOC entry 2170 (class 0 OID 0)
-- Dependencies: 2044
-- Name: CONSTRAINT fk_turma_pessoa_frequencia_turma_pessoa_id ON turma_pessoa_frequencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_pessoa_frequencia_turma_pessoa_id ON turma_pessoa_frequencia IS 'Chave estrangeira da turma pessoa frequencia com a turma pessoa';


-- Completed on 2018-02-09 19:14:35

--
-- PostgreSQL database dump complete
--

