--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.11
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-06-02 15:30:50

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
-- TOC entry 2114 (class 0 OID 16944)
-- Dependencies: 208
-- Data for Name: hierarquia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY hierarquia (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	BISPO	2016-11-03	11:56:33.903698	\N	\N
4	DIACONO	2016-11-03	11:56:33.903698	\N	\N
5	OBREIRO	2016-11-03	11:56:33.903698	\N	\N
6	LIDER DE CELULA	2016-11-28	09:44:40.217473	\N	\N
2	PASTOR	2016-11-03	11:56:33.903698	\N	\N
3	MISSIONARIO	2016-11-03	11:56:33.903698	\N	\N
7	ALTERNO	2016-11-03	11:56:33.903698	\N	\N
\.


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 207
-- Name: hierarquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('hierarquia_id_seq', 6, true);


-- Completed on 2017-06-02 15:31:29

--
-- PostgreSQL database dump complete
--

