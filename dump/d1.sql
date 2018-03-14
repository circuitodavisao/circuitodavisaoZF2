--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-03-14 18:20:25

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
-- TOC entry 244 (class 1259 OID 30183)
-- Name: curso_acesso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE curso_acesso (
    id integer NOT NULL,
    nome character varying(30) NOT NULL,
    hora_criacao time without time zone DEFAULT ('now'::text)::time with time zone NOT NULL,
    data_inativacao date,
    hora_inativacao time without time zone,
    data_criacao date NOT NULL
);


ALTER TABLE curso_acesso OWNER TO postgres;

--
-- TOC entry 2173 (class 0 OID 0)
-- Dependencies: 244
-- Name: TABLE curso_acesso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE curso_acesso IS 'Tabela com os perfis de acesso';


--
-- TOC entry 243 (class 1259 OID 30181)
-- Name: curso_acesso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE curso_acesso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE curso_acesso_id_seq OWNER TO postgres;

--
-- TOC entry 2174 (class 0 OID 0)
-- Dependencies: 243
-- Name: curso_acesso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE curso_acesso_id_seq OWNED BY curso_acesso.id;


--
-- TOC entry 2056 (class 2604 OID 30186)
-- Name: curso_acesso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_acesso ALTER COLUMN id SET DEFAULT nextval('curso_acesso_id_seq'::regclass);


--
-- TOC entry 2168 (class 0 OID 30183)
-- Dependencies: 244
-- Data for Name: curso_acesso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY curso_acesso (id, nome, hora_criacao, data_inativacao, hora_inativacao, data_criacao) FROM stdin;
1	COORDENADOR	10:22:09.777046	\N	\N	2018-03-08
2	SUPERVISOR	10:22:19.525734	\N	\N	2018-03-08
3	AUXILIAR	10:22:31.190421	\N	\N	2018-03-08
\.


--
-- TOC entry 2175 (class 0 OID 0)
-- Dependencies: 243
-- Name: curso_acesso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('curso_acesso_id_seq', 4, true);


--
-- TOC entry 2059 (class 2606 OID 30189)
-- Name: curso_acesso pk_curso_acesso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_acesso
    ADD CONSTRAINT pk_curso_acesso PRIMARY KEY (id);


-- Completed on 2018-03-14 18:20:44

--
-- PostgreSQL database dump complete
--

