--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:25:27

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
-- TOC entry 234 (class 1259 OID 20013)
-- Name: aula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE aula (
    id integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    nome character varying(80) NOT NULL,
    posicao integer NOT NULL,
    disciplina_id integer NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE aula OWNER TO postgres;

--
-- TOC entry 2146 (class 0 OID 0)
-- Dependencies: 234
-- Name: TABLE aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE aula IS 'Tabela de aulas';


--
-- TOC entry 2147 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.id IS 'Chave primária da tabela aula';


--
-- TOC entry 2148 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.data_criacao IS 'Data da criação da aula';


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.hora_criacao IS 'Hora da criação da aula ';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.nome IS 'Nome da aula';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.posicao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.posicao IS 'Posição da aula dentro da disciplina';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.disciplina_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.disciplina_id IS 'Chave estrangeira que conecta a tabela disciplina';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.data_inativacao IS 'Data de inativação da aula';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 234
-- Name: COLUMN aula.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN aula.hora_inativacao IS 'Hora da inativação da aula';


--
-- TOC entry 233 (class 1259 OID 20011)
-- Name: aula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE aula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE aula_id_seq OWNER TO postgres;

--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 233
-- Name: aula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE aula_id_seq OWNED BY aula.id;


--
-- TOC entry 2031 (class 2604 OID 20016)
-- Name: aula id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aula ALTER COLUMN id SET DEFAULT nextval('aula_id_seq'::regclass);


--
-- TOC entry 2033 (class 2606 OID 20018)
-- Name: aula pk_aula; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aula
    ADD CONSTRAINT pk_aula PRIMARY KEY (id);


--
-- TOC entry 2034 (class 2606 OID 20019)
-- Name: aula fk_disciplina; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aula
    ADD CONSTRAINT fk_disciplina FOREIGN KEY (disciplina_id) REFERENCES disciplina(id);


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 2034
-- Name: CONSTRAINT fk_disciplina ON aula; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_disciplina ON aula IS 'chave estrangeira que conecta aula à uma disciplina.';


-- Completed on 2018-01-05 16:25:43

--
-- PostgreSQL database dump complete
--

