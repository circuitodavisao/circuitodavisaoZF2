--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 15:28:52

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
-- TOC entry 2114 (class 0 OID 16467)
-- Dependencies: 182
-- Data for Name: entidade_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY entidade_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	PRESIDENCIAL	2016-04-08	13:55:25.563914	\N	\N
4	COORDENAÇÃO	2016-04-08	13:56:03.885107	\N	\N
2	NACIONAL	2016-04-08	13:55:44.659838	\N	\N
3	REGIÃO	2016-04-08	13:55:53.47643	\N	\N
5	IGREJA	2016-04-08	13:56:28.421487	\N	\N
6	EQUIPE	2016-04-08	13:57:02.622016	\N	\N
7	SUBEQUIPE	2016-04-08	13:57:15.798285	\N	\N
\.


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 181
-- Name: entidade_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('entidade_tipo_id_seq', 1, true);


-- Completed on 2017-06-02 15:29:23

--
-- PostgreSQL database dump complete
--

