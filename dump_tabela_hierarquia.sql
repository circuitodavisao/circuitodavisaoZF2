--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2018-01-03 14:45:51

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
-- TOC entry 2143 (class 0 OID 19664)
-- Dependencies: 213
-- Data for Name: hierarquia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY hierarquia (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao, sigla) FROM stdin;
6	LIDER DE CELULA	2017-06-26	09:44:40.217473	\N	\N	LC
3	MISSIONARIO	2017-06-26	11:56:33.903698	\N	\N	MS
7	LIDER EM TREINAMENTO	2017-08-28	21:27:00.089465	\N	\N	LT
4	DIACONO	2017-06-26	11:56:33.903698	\N	\N	DC
5	OBREIRO	2017-06-26	11:56:33.903698	\N	\N	OB
2	PASTOR	2017-06-26	11:56:33.903698	\N	\N	PR
1	BISPO	2017-06-26	11:56:33.903698	\N	\N	BP
\.


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 214
-- Name: hierarquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('hierarquia_id_seq', 6, true);


-- Completed on 2018-01-03 14:46:07

--
-- PostgreSQL database dump complete
--

