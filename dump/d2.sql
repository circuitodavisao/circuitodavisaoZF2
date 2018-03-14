--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-03-14 18:10:30

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
-- TOC entry 246 (class 1259 OID 30192)
-- Name: pessoa_curso_acesso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE pessoa_curso_acesso (
    id integer NOT NULL,
    pessoa_id integer NOT NULL,
    curso_acesso_id integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    grupo_id integer NOT NULL
);


ALTER TABLE pessoa_curso_acesso OWNER TO postgres;

--
-- TOC entry 2173 (class 0 OID 0)
-- Dependencies: 246
-- Name: TABLE pessoa_curso_acesso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE pessoa_curso_acesso IS 'Associação da pessoa com os perfis de acesso';


--
-- TOC entry 2174 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.id IS 'Identificação';


--
-- TOC entry 2175 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.pessoa_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.pessoa_id IS 'Identificação da pessoa';


--
-- TOC entry 2176 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.curso_acesso_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.curso_acesso_id IS 'Identificação do curso_acesso';


--
-- TOC entry 2177 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.data_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.data_criacao IS 'Data de criação';


--
-- TOC entry 2178 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.hora_criacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.hora_criacao IS 'Hora de criação';


--
-- TOC entry 2179 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.data_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.data_inativacao IS 'Data de inativação';


--
-- TOC entry 2180 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.hora_inativacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.hora_inativacao IS 'Hora de inativação';


--
-- TOC entry 2181 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN pessoa_curso_acesso.grupo_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pessoa_curso_acesso.grupo_id IS 'Identificação da igreja';


--
-- TOC entry 245 (class 1259 OID 30190)
-- Name: pessoa_curso_acesso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_curso_acesso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_curso_acesso_id_seq OWNER TO postgres;

--
-- TOC entry 2182 (class 0 OID 0)
-- Dependencies: 245
-- Name: pessoa_curso_acesso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_curso_acesso_id_seq OWNED BY pessoa_curso_acesso.id;


--
-- TOC entry 2056 (class 2604 OID 30195)
-- Name: pessoa_curso_acesso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_curso_acesso ALTER COLUMN id SET DEFAULT nextval('pessoa_curso_acesso_id_seq'::regclass);


--
-- TOC entry 2058 (class 2606 OID 30197)
-- Name: pessoa_curso_acesso pk_pessoa_curso_acesso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_curso_acesso
    ADD CONSTRAINT pk_pessoa_curso_acesso PRIMARY KEY (id);


--
-- TOC entry 2183 (class 0 OID 0)
-- Dependencies: 2058
-- Name: CONSTRAINT pk_pessoa_curso_acesso ON pessoa_curso_acesso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT pk_pessoa_curso_acesso ON pessoa_curso_acesso IS 'Chave primaria da pessoa com curso acesso';


--
-- TOC entry 2060 (class 2606 OID 30203)
-- Name: pessoa_curso_acesso fk_pessoa_curso_acesso_curso_acesso_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_curso_acesso
    ADD CONSTRAINT fk_pessoa_curso_acesso_curso_acesso_id FOREIGN KEY (curso_acesso_id) REFERENCES curso_acesso(id);


--
-- TOC entry 2184 (class 0 OID 0)
-- Dependencies: 2060
-- Name: CONSTRAINT fk_pessoa_curso_acesso_curso_acesso_id ON pessoa_curso_acesso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_pessoa_curso_acesso_curso_acesso_id ON pessoa_curso_acesso IS 'Chave estrangeira da pessoa curso acesso com curso acesso';


--
-- TOC entry 2061 (class 2606 OID 30208)
-- Name: pessoa_curso_acesso fk_pessoa_curso_acesso_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_curso_acesso
    ADD CONSTRAINT fk_pessoa_curso_acesso_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2185 (class 0 OID 0)
-- Dependencies: 2061
-- Name: CONSTRAINT fk_pessoa_curso_acesso_grupo_id ON pessoa_curso_acesso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_pessoa_curso_acesso_grupo_id ON pessoa_curso_acesso IS 'Chave estrangeira do acesso a igreja';


--
-- TOC entry 2059 (class 2606 OID 30198)
-- Name: pessoa_curso_acesso fk_pessoa_curso_acesso_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_curso_acesso
    ADD CONSTRAINT fk_pessoa_curso_acesso_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- TOC entry 2186 (class 0 OID 0)
-- Dependencies: 2059
-- Name: CONSTRAINT fk_pessoa_curso_acesso_pessoa_id ON pessoa_curso_acesso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_pessoa_curso_acesso_pessoa_id ON pessoa_curso_acesso IS 'Chave estrangeira do pessoa curso acesso com a pessoa';


-- Completed on 2018-03-14 18:10:48

--
-- PostgreSQL database dump complete
--

