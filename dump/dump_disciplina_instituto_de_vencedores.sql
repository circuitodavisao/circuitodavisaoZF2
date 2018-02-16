--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-02-16 15:07:13

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
-- TOC entry 2159 (class 0 OID 20783)
-- Dependencies: 179
-- Data for Name: disciplina; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY disciplina (id, posicao, nome, curso_id, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
5	1	PÓS REVISÃO	2	2018-02-16	11:08:52	\N	\N
6	2	MÓDULO 1	2	2018-02-16	11:37:52	\N	\N
7	3	MÓDULO 2	2	2018-02-16	11:50:52	\N	\N
8	4	MÓDULO 3	2	2018-02-16	11:02:53	\N	\N
\.


--
-- TOC entry 2165 (class 0 OID 0)
-- Dependencies: 180
-- Name: disciplina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('disciplina_id_seq', 8, true);


-- Completed on 2018-02-16 15:07:29

--
-- PostgreSQL database dump complete
--

