--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:25:05

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
-- TOC entry 232 (class 1259 OID 19998)
-- Name: disciplina; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE disciplina (
    id integer NOT NULL,
    posicao integer NOT NULL,
    nome character varying(80) NOT NULL,
    curso_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE disciplina OWNER TO postgres;

--
-- TOC entry 2148 (class 0 OID 0)
-- Dependencies: 232
-- Name: TABLE disciplina; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE disciplina IS 'Tabela das Disciplina dos cursos';


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.id IS 'Identificação';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.posicao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.posicao IS 'Ordenacao da disciplina';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.nome IS 'Nome da disciplina';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.curso_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.curso_id IS 'Identificação do curso';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.data_criacao IS 'Data de criação da disciplina';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.hora_criacao IS 'Hora de criação da disciplina';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.data_inativacao IS 'Data de inativação da disciplina';


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 232
-- Name: COLUMN disciplina.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN disciplina.hora_inativacao IS 'Hora da inativação da disciplina';


--
-- TOC entry 231 (class 1259 OID 19996)
-- Name: disciplina_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE disciplina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE disciplina_id_seq OWNER TO postgres;

--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 231
-- Name: disciplina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE disciplina_id_seq OWNED BY disciplina.id;


--
-- TOC entry 2031 (class 2604 OID 20001)
-- Name: disciplina id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY disciplina ALTER COLUMN id SET DEFAULT nextval('disciplina_id_seq'::regclass);


--
-- TOC entry 2035 (class 2606 OID 20005)
-- Name: disciplina pk_disciplina; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY disciplina
    ADD CONSTRAINT pk_disciplina PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 20006)
-- Name: disciplina fk_disciplina_curso_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY disciplina
    ADD CONSTRAINT fk_disciplina_curso_id FOREIGN KEY (curso_id) REFERENCES curso(id);


-- Completed on 2018-01-05 16:25:21

--
-- PostgreSQL database dump complete
--

