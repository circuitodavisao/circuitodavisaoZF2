--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:24:14

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
-- TOC entry 226 (class 1259 OID 19717)
-- Name: turma_pessoa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma_pessoa (
    id integer NOT NULL,
    turma_id integer NOT NULL,
    pessoa_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE turma_pessoa OWNER TO postgres;

--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 226
-- Name: TABLE turma_pessoa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_pessoa IS 'Associação entre a turma e as pessoas';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.id IS 'Identificação da associação turma com o aluno';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.turma_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.turma_id IS 'Identificação da turma';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.data_criacao IS 'Data de criação da associação';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.hora_criacao IS 'Hora da criação da associação';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.data_inativacao IS 'Data de inativação da associação';


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN turma_pessoa.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa.hora_inativacao IS 'Hora da inativação da associação';


--
-- TOC entry 228 (class 1259 OID 19724)
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
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 228
-- Name: turma_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_pessoa_id_seq OWNED BY turma_pessoa.id;


--
-- TOC entry 2033 (class 2604 OID 19755)
-- Name: turma_pessoa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa ALTER COLUMN id SET DEFAULT nextval('turma_pessoa_id_seq'::regclass);


--
-- TOC entry 2035 (class 2606 OID 19815)
-- Name: turma_pessoa pk_turma_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa
    ADD CONSTRAINT pk_turma_pessoa PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 19971)
-- Name: turma_pessoa fk_turma_pessoa_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa
    ADD CONSTRAINT fk_turma_pessoa_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2037 (class 2606 OID 19976)
-- Name: turma_pessoa fk_turma_pessoa_turma_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa
    ADD CONSTRAINT fk_turma_pessoa_turma_id FOREIGN KEY (turma_id) REFERENCES turma(id);


-- Completed on 2018-01-05 16:24:30

--
-- PostgreSQL database dump complete
--

