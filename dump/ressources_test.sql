--
-- PostgreSQL database dump
--

-- Dumped from database version 15.5
-- Dumped by pg_dump version 15.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO pedro;

--
-- Name: user; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    user_data_id integer,
    user_name character varying(255) NOT NULL,
    account_enabled boolean DEFAULT true NOT NULL
);


ALTER TABLE public."user" OWNER TO pedro;

--
-- Name: user_data; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.user_data (
    id integer NOT NULL,
    email_encrypted character varying(255) NOT NULL,
    email_hash character varying(255) NOT NULL
);


ALTER TABLE public.user_data OWNER TO pedro;

--
-- Name: user_data_id_seq; Type: SEQUENCE; Schema: public; Owner: pedro
--

CREATE SEQUENCE public.user_data_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_data_id_seq OWNER TO pedro;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: pedro
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO pedro;

--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20240207235532	2024-02-08 00:15:43	57
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public."user" (id, user_data_id, user_name, account_enabled) FROM stdin;
\.


--
-- Data for Name: user_data; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.user_data (id, email_encrypted, email_hash) FROM stdin;
\.


--
-- Name: user_data_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.user_data_id_seq', 1, false);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.user_id_seq', 1, false);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: user_data user_data_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.user_data
    ADD CONSTRAINT user_data_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: email_hash; Type: INDEX; Schema: public; Owner: pedro
--

CREATE UNIQUE INDEX email_hash ON public.user_data USING btree (email_hash);


--
-- Name: uniq_8d93d6496ff8bf36; Type: INDEX; Schema: public; Owner: pedro
--

CREATE UNIQUE INDEX uniq_8d93d6496ff8bf36 ON public."user" USING btree (user_data_id);


--
-- Name: user_name; Type: INDEX; Schema: public; Owner: pedro
--

CREATE UNIQUE INDEX user_name ON public."user" USING btree (user_name);


--
-- Name: user fk_8d93d6496ff8bf36; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT fk_8d93d6496ff8bf36 FOREIGN KEY (user_data_id) REFERENCES public.user_data(id);


--
-- PostgreSQL database dump complete
--

