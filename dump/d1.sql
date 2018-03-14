--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-03-14 18:08:31

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


-- Completed on 2018-03-14 18:08:50

--
-- PostgreSQL database dump complete
--

