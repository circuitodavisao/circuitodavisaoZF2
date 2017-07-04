--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 15:31:21

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
-- TOC entry 2114 (class 0 OID 16868)
-- Dependencies: 202
-- Data for Name: situacao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY situacao (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	ATIVO	2016-10-27	15:44:01.16761	\N	\N
\.


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 201
-- Name: situacao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('situacao_id_seq', 1, false);


-- Completed on 2017-06-02 15:31:55

--
-- PostgreSQL database dump complete
--

