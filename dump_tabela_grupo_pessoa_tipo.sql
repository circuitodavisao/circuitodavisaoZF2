--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 15:30:18

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- TOC entry 2114 (class 0 OID 16721)
-- Dependencies: 194
-- Data for Name: grupo_pessoa_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY grupo_pessoa_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	VISITOR	2016-05-02	11:29:45.44539	\N	\N
2	CONSOLIDATION	2016-05-02	11:29:45.44539	\N	\N
3	MEMBER	2016-05-02	11:29:45.44539	\N	\N
\.


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 193
-- Name: grupo_pessoa_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_pessoa_tipo_id_seq', 2, true);


-- Completed on 2017-06-02 15:30:45

--
-- PostgreSQL database dump complete
--

