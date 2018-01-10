--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:23:59

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
-- TOC entry 225 (class 1259 OID 19712)
-- Name: turma; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma (
    id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    mes integer,
    ano integer,
    observacao character varying(200),
    grupo_id integer NOT NULL,
    curso_id integer NOT NULL
);


ALTER TABLE turma OWNER TO postgres;

--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 225
-- Name: TABLE turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma IS 'Tabela com as turma dos cursos';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.id IS 'Identificação da turma';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_criacao IS 'Data de criação da turma';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_criacao IS 'Hora de criação da turma';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.data_inativacao IS 'Data de inativação da turma';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.hora_inativacao IS 'Hora de inativação da turma';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.mes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.mes IS 'Mês de referência da turma';


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.ano; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.ano IS 'Ano de referência da turma';


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.observacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.observacao IS 'Observação da turma';


--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.grupo_id IS 'Identificação do grupo';


--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN turma.curso_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma.curso_id IS 'Identificação do curso';


--
-- TOC entry 227 (class 1259 OID 19722)
-- Name: turma_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE turma_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE turma_id_seq OWNER TO postgres;

--
-- TOC entry 2160 (class 0 OID 0)
-- Dependencies: 227
-- Name: turma_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE turma_id_seq OWNED BY turma.id;


--
-- TOC entry 2033 (class 2604 OID 19754)
-- Name: turma id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma ALTER COLUMN id SET DEFAULT nextval('turma_id_seq'::regclass);


--
-- TOC entry 2035 (class 2606 OID 19813)
-- Name: turma pk_turma; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma
    ADD CONSTRAINT pk_turma PRIMARY KEY (id);


--
-- TOC entry 2161 (class 0 OID 0)
-- Dependencies: 2035
-- Name: CONSTRAINT pk_turma ON turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_turma ON turma IS 'Chave primaria da turma';


--
-- TOC entry 2037 (class 2606 OID 20029)
-- Name: turma fk_turma_curso_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma
    ADD CONSTRAINT fk_turma_curso_id FOREIGN KEY (curso_id) REFERENCES curso(id);


--
-- TOC entry 2162 (class 0 OID 0)
-- Dependencies: 2037
-- Name: CONSTRAINT fk_turma_curso_id ON turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_curso_id ON turma IS 'Chave estrangeira da turma com o curso';


--
-- TOC entry 2036 (class 2606 OID 20024)
-- Name: turma fk_turma_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma
    ADD CONSTRAINT fk_turma_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2163 (class 0 OID 0)
-- Dependencies: 2036
-- Name: CONSTRAINT fk_turma_grupo_id ON turma; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_grupo_id ON turma IS 'Chave estrangeira da turma com a igreja';


-- Completed on 2018-01-05 16:24:15

--
-- PostgreSQL database dump complete
--

