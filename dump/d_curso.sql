--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:24:46

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
-- TOC entry 230 (class 1259 OID 19983)
-- Name: curso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE curso (
    id integer NOT NULL,
    nome character varying(80) NOT NULL,
    pessoa_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE curso OWNER TO postgres;

--
-- TOC entry 2148 (class 0 OID 0)
-- Dependencies: 230
-- Name: TABLE curso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE curso IS 'Tabela de Cursos';


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.id IS 'Identificação';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.nome IS 'Nome do curso';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.data_criacao IS 'Data de criação da curso';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.hora_criacao IS 'Hora de criação da curso';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.data_inativacao IS 'Data de inativação da curso';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN curso.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso.hora_inativacao IS 'Hora da inativação da curso';


--
-- TOC entry 229 (class 1259 OID 19981)
-- Name: curso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE curso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE curso_id_seq OWNER TO postgres;

--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 229
-- Name: curso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE curso_id_seq OWNED BY curso.id;


--
-- TOC entry 2031 (class 2604 OID 19986)
-- Name: curso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso ALTER COLUMN id SET DEFAULT nextval('curso_id_seq'::regclass);


--
-- TOC entry 2035 (class 2606 OID 19990)
-- Name: curso pk_curso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso
    ADD CONSTRAINT pk_curso PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 19991)
-- Name: curso fk_curso_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso
    ADD CONSTRAINT fk_curso_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


-- Completed on 2018-01-05 16:25:03

--
-- PostgreSQL database dump complete
--

