--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-10 16:54:58

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
-- TOC entry 238 (class 1259 OID 20044)
-- Name: fato_ranking; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fato_ranking (
    id integer NOT NULL,
    ranking_membresia integer NOT NULL,
    ranking_celula integer NOT NULL,
    membresia real NOT NULL,
    culto integer NOT NULL,
    arena integer NOT NULL,
    domingo integer NOT NULL,
    celula integer NOT NULL,
    data_criacao date NOT NULL,
    hora_criacao time without time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    grupo_id integer NOT NULL
);


ALTER TABLE fato_ranking OWNER TO postgres;

--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 238
-- Name: TABLE fato_ranking; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fato_ranking IS 'Tabela com o Ranking de membresia e celula';


--
-- TOC entry 237 (class 1259 OID 20042)
-- Name: fato_ranking_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fato_ranking_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fato_ranking_id_seq OWNER TO postgres;

--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 237
-- Name: fato_ranking_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fato_ranking_id_seq OWNED BY fato_ranking.id;


--
-- TOC entry 2036 (class 2604 OID 20047)
-- Name: fato_ranking id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ranking ALTER COLUMN id SET DEFAULT nextval('fato_ranking_id_seq'::regclass);


--
-- TOC entry 2038 (class 2606 OID 20052)
-- Name: fato_ranking fato_ranking_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ranking
    ADD CONSTRAINT fato_ranking_pkey PRIMARY KEY (id);


--
-- TOC entry 2039 (class 2606 OID 20057)
-- Name: fato_ranking fk_fato_ranking_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fato_ranking
    ADD CONSTRAINT fk_fato_ranking_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 2039
-- Name: CONSTRAINT fk_fato_ranking_grupo_id ON fato_ranking; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT fk_fato_ranking_grupo_id ON fato_ranking IS 'Associação do grupo com o ranking';


-- Completed on 2018-01-10 16:55:27

--
-- PostgreSQL database dump complete
--

