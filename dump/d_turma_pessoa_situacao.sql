--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-05 16:24:27

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
-- TOC entry 171 (class 1259 OID 19511)
-- Name: turma_pessoa_situacao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE turma_pessoa_situacao (
    id integer NOT NULL,
    situacao_id integer NOT NULL,
    turma_pessoa_id integer NOT NULL,
    data_criacao date DEFAULT ('now'::text)::date NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone
);


ALTER TABLE turma_pessoa_situacao OWNER TO postgres;

--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 171
-- Name: TABLE turma_pessoa_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE turma_pessoa_situacao IS 'Associação entre a matricula e sua situacao';


--
-- TOC entry 2150 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.id IS 'Identificação da associação do aluno com a situação';


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.situacao_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.situacao_id IS 'Identificação da situação';


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.turma_pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.turma_pessoa_id IS 'Identificação (matricula) da turma pessoa';


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.data_criacao IS 'Data de criação da associação da situação com o aluno';


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.hora_criacao IS 'Hora de criação da associação da situação com o aluno';


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.data_inativacao IS 'Data de inativação da associação da situação com aluno';


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN turma_pessoa_situacao.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN turma_pessoa_situacao.hora_inativacao IS 'Hora da inativação da associação situação com aluno';


--
-- TOC entry 172 (class 1259 OID 19516)
-- Name: aluno_situacao_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE aluno_situacao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE aluno_situacao_id_seq OWNER TO postgres;

--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 172
-- Name: aluno_situacao_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE aluno_situacao_id_seq OWNED BY turma_pessoa_situacao.id;


--
-- TOC entry 2031 (class 2604 OID 19726)
-- Name: turma_pessoa_situacao id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_situacao ALTER COLUMN id SET DEFAULT nextval('aluno_situacao_id_seq'::regclass);


--
-- TOC entry 2035 (class 2606 OID 19757)
-- Name: turma_pessoa_situacao pk_aluno_situacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_situacao
    ADD CONSTRAINT pk_aluno_situacao PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 19826)
-- Name: turma_pessoa_situacao fk_turma_pessoa_situacao_situacao_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_situacao
    ADD CONSTRAINT fk_turma_pessoa_situacao_situacao_id FOREIGN KEY (situacao_id) REFERENCES situacao(id);


--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 2036
-- Name: CONSTRAINT fk_turma_pessoa_situacao_situacao_id ON turma_pessoa_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_pessoa_situacao_situacao_id ON turma_pessoa_situacao IS 'Chave estrangeira da situacao com turma pessoa situacao';


--
-- TOC entry 2037 (class 2606 OID 19831)
-- Name: turma_pessoa_situacao fk_turma_pessoa_situacao_turma_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY turma_pessoa_situacao
    ADD CONSTRAINT fk_turma_pessoa_situacao_turma_pessoa_id FOREIGN KEY (turma_pessoa_id) REFERENCES turma_pessoa(id);


--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 2037
-- Name: CONSTRAINT fk_turma_pessoa_situacao_turma_pessoa_id ON turma_pessoa_situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_turma_pessoa_situacao_turma_pessoa_id ON turma_pessoa_situacao IS 'Chave estrangeira da turma pessoa com turma pessoa situacao';


-- Completed on 2018-01-05 16:24:43

--
-- PostgreSQL database dump complete
--

