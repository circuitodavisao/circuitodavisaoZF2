--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-02-16 15:08:13

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
-- TOC entry 2159 (class 0 OID 20758)
-- Dependencies: 173
-- Data for Name: curso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY curso (id, nome, pessoa_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
2	INSTITUTO DE VENCEDORES	1	2018-02-16	11:35:51	\N	\N
\.


--
-- TOC entry 2165 (class 0 OID 0)
-- Dependencies: 174
-- Name: curso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('curso_id_seq', 2, true);


-- Completed on 2018-02-16 15:08:29

--
-- PostgreSQL database dump complete
--

