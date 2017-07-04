--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 15:28:09

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
-- TOC entry 2114 (class 0 OID 17078)
-- Dependencies: 218
-- Data for Name: dimensao_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY dimensao_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	CELULA	2017-01-31	14:26:06.077929	\N	\N
2	CULTO	2017-01-31	14:26:12.678174	\N	\N
3	ARENA	2017-01-31	14:26:17.535224	\N	\N
4	DOMINGO	2017-01-31	14:26:21.580287	\N	\N
\.


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 217
-- Name: dimensao_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('dimensao_tipo_id_seq', 1, false);


-- Completed on 2017-06-02 15:28:37

--
-- PostgreSQL database dump complete
--

