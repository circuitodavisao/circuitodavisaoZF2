--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.17
-- Dumped by pg_dump version 9.6.3

-- Started on 2017-11-21 17:52:31

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
-- TOC entry 2144 (class 0 OID 19386)
-- Dependencies: 226
-- Data for Name: solicitacao_tipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitacao_tipo (id, nome, data_criacao, hora_criacao, data_inativacao, hora_inativacao) FROM stdin;
1	TRANSFERÊNCIA DE LÍDER NA PRÓPRIA EQUIPE	2017-11-03	09:10:16.268684	\N	\N
\.


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 225
-- Name: solicitacao_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitacao_tipo_id_seq', 1, false);


-- Completed on 2017-11-21 17:52:47

--
-- PostgreSQL database dump complete
--

