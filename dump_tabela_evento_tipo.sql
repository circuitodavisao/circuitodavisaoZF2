--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 15:29:26

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
-- TOC entry 2114 (class 0 OID 16620)
-- Dependencies: 186
-- Data for Name: evento_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	CELULA	2016-04-27	16:21:13.863352	\N	\N
2	CULTO	2016-04-27	16:21:19.935154	\N	\N
3	REVISAO	2017-01-05	13:48:27.604821	\N	\N
\.


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 185
-- Name: evento_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_tipo_id_seq', 1, false);


-- Completed on 2017-06-02 15:29:53

--
-- PostgreSQL database dump complete
--

