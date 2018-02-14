--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-02-14 17:03:10

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
-- TOC entry 242 (class 1259 OID 20710)
-- Name: turma_pessoa_aula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma_pessoa_aula (
    id integer NOT NULL,
    turma_pessoa_id integer NOT NULL,
    aula_id integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE turma_pessoa_aula OWNER TO postgres;

--
-- TOC entry 2162 (class 0 OID 0)
-- Dependencies: 242
-- Name: TABLE turma_pessoa_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_pessoa_aula IS 'Tabela para frequencia do aluno com a aula';


--
-- TOC entry 2163 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.id IS 'Identificação';


--
-- TOC entry 2164 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.turma_pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.turma_pessoa_id IS 'Identificação turma pessoa';


--
-- TOC entry 2165 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.aula_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.aula_id IS 'Identificação da aula';


--
-- TOC entry 2166 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.data_criacao IS 'Data de criação';


--
-- TOC entry 2167 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.hora_criacao IS 'Hora de criação';


--
-- TOC entry 2168 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2169 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN turma_pessoa_aula.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_aula.hora_inativacao IS 'Hora de inativacao';


--
-- TOC entry 241 (class 1259 OID 20708)
-- Name: turma_pessoa_aula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE turma_pessoa_aula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE turma_pessoa_aula_id_seq OWNER TO postgres;

--
-- TOC entry 2170 (class 0 OID 0)
-- Dependencies: 241
-- Name: turma_pessoa_aula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_pessoa_aula_id_seq OWNED BY turma_pessoa_aula.id;


--
-- TOC entry 2046 (class 2604 OID 20713)
-- Name: turma_pessoa_aula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_aula ALTER COLUMN id SET DEFAULT nextval('turma_pessoa_aula_id_seq'::regclass);


--
-- TOC entry 2048 (class 2606 OID 20715)
-- Name: turma_pessoa_aula pk_turma_pessoa_aula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_aula
    ADD CONSTRAINT pk_turma_pessoa_aula PRIMARY KEY (id);


--
-- TOC entry 2171 (class 0 OID 0)
-- Dependencies: 2048
-- Name: CONSTRAINT pk_turma_pessoa_aula ON turma_pessoa_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma_pessoa_aula ON turma_pessoa_aula IS 'Chave primaria';


--
-- TOC entry 2050 (class 2606 OID 20721)
-- Name: turma_pessoa_aula fk_turma_pessoa_aula_aula_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_aula
    ADD CONSTRAINT fk_turma_pessoa_aula_aula_id FOREIGN KEY (aula_id) REFERENCES aula(id);


--
-- TOC entry 2172 (class 0 OID 0)
-- Dependencies: 2050
-- Name: CONSTRAINT fk_turma_pessoa_aula_aula_id ON turma_pessoa_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_pessoa_aula_aula_id ON turma_pessoa_aula IS 'Chave estrangeira da turma pessoa aula com a aula ';


--
-- TOC entry 2049 (class 2606 OID 20716)
-- Name: turma_pessoa_aula fk_turma_pessoa_aula_turma_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_aula
    ADD CONSTRAINT fk_turma_pessoa_aula_turma_pessoa_id FOREIGN KEY (turma_pessoa_id) REFERENCES turma_pessoa(id);


--
-- TOC entry 2173 (class 0 OID 0)
-- Dependencies: 2049
-- Name: CONSTRAINT fk_turma_pessoa_aula_turma_pessoa_id ON turma_pessoa_aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_pessoa_aula_turma_pessoa_id ON turma_pessoa_aula IS 'Chave estrangeira da tuma pessoa aula com a turma pessoa';


-- Completed on 2018-02-14 17:03:26

--
-- PostgreSQL database dump complete
--

