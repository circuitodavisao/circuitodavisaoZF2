--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-02-09 17:26:11

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
-- TOC entry 238 (class 1259 OID 20678)
-- Name: turma_aula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma_aula (
    id integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    turma_id integer NOT NULL,
    aula_id integer NOT NULL
);


ALTER TABLE turma_aula OWNER TO postgres;

--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 238
-- Name: TABLE turma_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_aula IS 'Associação entre turma e a aula aberta';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.id IS 'Identificação';


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.data_criacao IS 'Data de criação';


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.hora_criacao IS 'Hora de criação';


--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.hora_inativacao IS 'Hora inativação';


--
-- TOC entry 2160 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.turma_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.turma_id IS 'Identificação da turma';


--
-- TOC entry 2161 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN turma_aula.aula_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_aula.aula_id IS 'Identificação da aula';


--
-- TOC entry 237 (class 1259 OID 20676)
-- Name: turma_aula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE turma_aula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE turma_aula_id_seq OWNER TO postgres;

--
-- TOC entry 2162 (class 0 OID 0)
-- Dependencies: 237
-- Name: turma_aula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_aula_id_seq OWNED BY turma_aula.id;


--
-- TOC entry 2036 (class 2604 OID 20681)
-- Name: turma_aula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aula ALTER COLUMN id SET DEFAULT nextval('turma_aula_id_seq'::regclass);


--
-- TOC entry 2149 (class 0 OID 20678)
-- Dependencies: 238
-- Data for Name: turma_aula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY turma_aula (id, data_criacao, hora_criacao, data_inativacao, hora_inativacao, turma_id, aula_id) FROM stdin;
\.


--
-- TOC entry 2163 (class 0 OID 0)
-- Dependencies: 237
-- Name: turma_aula_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('turma_aula_id_seq', 1, false);


--
-- TOC entry 2038 (class 2606 OID 20683)
-- Name: turma_aula pk_turma_aula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aula
    ADD CONSTRAINT pk_turma_aula PRIMARY KEY (id);


--
-- TOC entry 2164 (class 0 OID 0)
-- Dependencies: 2038
-- Name: CONSTRAINT pk_turma_aula ON turma_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma_aula ON turma_aula IS 'Chave primaria da turma aula';


--
-- TOC entry 2040 (class 2606 OID 20689)
-- Name: turma_aula fk_turma_aula_aula_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aula
    ADD CONSTRAINT fk_turma_aula_aula_id FOREIGN KEY (aula_id) REFERENCES aula(id);


--
-- TOC entry 2165 (class 0 OID 0)
-- Dependencies: 2040
-- Name: CONSTRAINT fk_turma_aula_aula_id ON turma_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_aula_aula_id ON turma_aula IS 'Chave estrangeira da turma aula com a aula';


--
-- TOC entry 2039 (class 2606 OID 20684)
-- Name: turma_aula fk_turma_aula_turma; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_aula
    ADD CONSTRAINT fk_turma_aula_turma FOREIGN KEY (turma_id) REFERENCES turma(id);


--
-- TOC entry 2166 (class 0 OID 0)
-- Dependencies: 2039
-- Name: CONSTRAINT fk_turma_aula_turma ON turma_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_aula_turma ON turma_aula IS 'Chave estrangeira da turma aula com a aula';


-- Completed on 2018-02-09 17:26:30

--
-- PostgreSQL database dump complete
--

