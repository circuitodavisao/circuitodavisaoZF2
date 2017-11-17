--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-11-16 17:27:48

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
-- TOC entry 2128 (class 0 OID 19132)
-- Dependencies: 219
-- Data for Name: situacao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY situacao (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	ATIVO	2017-06-26	15:44:01.16761	\N	\N
2	PENDENTE DE ACEITAÇÃO	2017-11-16	14:24:26.933478	\N	\N
3	ACEITO/AGENDADO	2017-11-16	14:24:45.519842	\N	\N
4	RECUSADO	2017-11-16	14:25:31.142888	\N	\N
5	CONCLUÍDO	2017-11-16	14:25:56.970311	\N	\N
\.


--
-- TOC entry 2134 (class 0 OID 0)
-- Dependencies: 220
-- Name: situacao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('situacao_id_seq', 1, false);


-- Completed on 2017-11-16 17:28:04

--
-- PostgreSQL database dump complete
--

