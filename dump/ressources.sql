--
-- PostgreSQL database dump
--

-- Dumped from database version 15.6
-- Dumped by pg_dump version 15.6

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
-- Name: relation_type; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.relation_type (
    id integer NOT NULL,
    parent_id integer,
    type character varying(255) NOT NULL
);


ALTER TABLE public.relation_type OWNER TO pedro;

--
-- Name: relation_type_id_seq; Type: SEQUENCE; Schema: public; Owner: pedro
--

CREATE SEQUENCE public.relation_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.relation_type_id_seq OWNER TO pedro;

--
-- Name: resource; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.resource (
    id integer NOT NULL,
    user_data_id integer NOT NULL,
    file_name character varying(255) NOT NULL,
    shared_status character varying(255) NOT NULL,
    category character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    modification_date timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT resource_category_check CHECK (((category)::text = ANY ((ARRAY['communication'::character varying, 'culture'::character varying, 'developpement_personnel'::character varying, 'intelligence_emotionnelle'::character varying, 'loisirs'::character varying, 'monde_professionnel'::character varying, 'parentalite'::character varying, 'qualite_de_vie'::character varying, 'recherche_de_sens'::character varying, 'sante_physique'::character varying, 'sante_psychique'::character varying, 'spiritualite'::character varying, 'vie_affective'::character varying])::text[]))),
    CONSTRAINT resource_shared_status_check CHECK (((shared_status)::text = ANY ((ARRAY['public'::character varying, 'shared'::character varying, 'private'::character varying])::text[]))),
    CONSTRAINT resource_type_check CHECK (((type)::text = ANY ((ARRAY['article'::character varying, 'carte_defi'::character varying, 'cours_pdf'::character varying, 'excercice'::character varying, 'fiche_lecture'::character varying, 'video'::character varying, 'audio'::character varying, 'game'::character varying])::text[])))
);


ALTER TABLE public.resource OWNER TO pedro;

--
-- Name: COLUMN resource.shared_status; Type: COMMENT; Schema: public; Owner: pedro
--

COMMENT ON COLUMN public.resource.shared_status IS '(DC2Type:resourceSharedStatusType)';


--
-- Name: COLUMN resource.category; Type: COMMENT; Schema: public; Owner: pedro
--

COMMENT ON COLUMN public.resource.category IS '(DC2Type:resourceCategoryType)';


--
-- Name: COLUMN resource.type; Type: COMMENT; Schema: public; Owner: pedro
--

COMMENT ON COLUMN public.resource.type IS '(DC2Type:resourceTypeType)';


--
-- Name: COLUMN resource.creation_date; Type: COMMENT; Schema: public; Owner: pedro
--

COMMENT ON COLUMN public.resource.creation_date IS '(DC2Type:datetime_immutable)';


--
-- Name: resource_id_seq; Type: SEQUENCE; Schema: public; Owner: pedro
--

CREATE SEQUENCE public.resource_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.resource_id_seq OWNER TO pedro;

--
-- Name: resource_metadata; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.resource_metadata (
    id integer NOT NULL,
    resource_id integer NOT NULL,
    title character varying(255) NOT NULL,
    duration integer,
    format character varying(255) DEFAULT NULL::character varying,
    author character varying(255) DEFAULT NULL::character varying,
    album character varying(255) DEFAULT NULL::character varying,
    genre character varying(255) DEFAULT NULL::character varying,
    release_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    creation_date timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    modification_date timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.resource_metadata OWNER TO pedro;

--
-- Name: COLUMN resource_metadata.creation_date; Type: COMMENT; Schema: public; Owner: pedro
--

COMMENT ON COLUMN public.resource_metadata.creation_date IS '(DC2Type:datetime_immutable)';


--
-- Name: resource_metadata_id_seq; Type: SEQUENCE; Schema: public; Owner: pedro
--

CREATE SEQUENCE public.resource_metadata_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.resource_metadata_id_seq OWNER TO pedro;

--
-- Name: resource_relation_type; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.resource_relation_type (
    resource_id integer NOT NULL,
    relation_type_id integer NOT NULL
);


ALTER TABLE public.resource_relation_type OWNER TO pedro;

--
-- Name: user; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    account_enabled boolean DEFAULT true NOT NULL,
    role character varying(255) DEFAULT 'citoyen connecté'::character varying NOT NULL,
    CONSTRAINT user_role_check CHECK (((role)::text = ANY ((ARRAY['citoyen connecté'::character varying, 'modérateur'::character varying, 'administrateur'::character varying, 'super administrateur'::character varying])::text[])))
);


ALTER TABLE public."user" OWNER TO pedro;

--
-- Name: COLUMN "user".role; Type: COMMENT; Schema: public; Owner: pedro
--

COMMENT ON COLUMN public."user".role IS '(DC2Type:userRoleType)';


--
-- Name: user_data; Type: TABLE; Schema: public; Owner: pedro
--

CREATE TABLE public.user_data (
    id integer NOT NULL,
    user_id integer NOT NULL,
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
DoctrineMigrations\\Version20240208222614	2024-03-18 21:52:02	71
DoctrineMigrations\\Version20240311140314	2024-03-18 21:52:02	136
DoctrineMigrations\\Version20240313142256	2024-03-18 21:52:02	11
\.


--
-- Data for Name: relation_type; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.relation_type (id, parent_id, type) FROM stdin;
1	\N	Soi
2	\N	Conjoints
3	\N	Famille
4	\N	Professionnel
5	\N	Amis et communautés
6	\N	Inconnus
7	3	Enfants
8	3	Parents
9	3	Fratrie
10	4	Collègues
11	4	Collaborateurs
12	4	Managers
\.


--
-- Data for Name: resource; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.resource (id, user_data_id, file_name, shared_status, category, type, creation_date, modification_date) FROM stdin;
1	5	Extrait - La Boétie.pdf	public	recherche_de_sens	cours_pdf	2024-03-18 21:52:02	2024-03-18 21:52:02
2	5	Manuel d'Epictète.pdf	public	developpement_personnel	fiche_lecture	2024-03-18 21:52:02	2024-03-18 21:52:02
3	5	Le Loup des Steppes.pdf	public	developpement_personnel	fiche_lecture	2024-03-18 21:52:02	2024-03-18 21:52:02
\.


--
-- Data for Name: resource_metadata; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.resource_metadata (id, resource_id, title, duration, format, author, album, genre, release_date, creation_date, modification_date) FROM stdin;
1	1	Discours de la servitude volontaire	\N	\N	Etienne de La Boétie	\N	\N	\N	2024-03-18 21:52:02	2024-03-18 21:52:02
2	2	Manuel d'Epictète	\N	\N	Epictète	\N	\N	\N	2024-03-18 21:52:02	2024-03-18 21:52:02
3	3	Le Loup des Steppes	\N	\N	Herman Hesse	\N	\N	\N	2024-03-18 21:52:02	2024-03-18 21:52:02
\.


--
-- Data for Name: resource_relation_type; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.resource_relation_type (resource_id, relation_type_id) FROM stdin;
1	1
1	10
2	1
3	1
3	5
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public."user" (id, username, account_enabled, role) FROM stdin;
1	Pedro	t	citoyen connecté
2	Maria	t	citoyen connecté
3	UserForDeleteTest	t	citoyen connecté
4	Alberto	t	administrateur
5	Sofia	t	super administrateur
\.


--
-- Data for Name: user_data; Type: TABLE DATA; Schema: public; Owner: pedro
--

COPY public.user_data (id, user_id, email_encrypted, email_hash) FROM stdin;
1	1	9RiA1iLwrzFwGM/E45V8swdw0dQCvPKGs9/5OQhgQGdZ8kBKm4Zlrw7NIDZf3HZi	6b02958be1505abf91d5a12dc8a97cd41254a1f17d08503048faef77c1a569ae
2	2	mZhTA1q/KhjLltgxh9UXn3hRNkhm1nMSwaohh5Ix5WWXSJt4zv1hjtgX+UUm9gPK	10ef04a5a1acd81d18a0c61fdd354a063da07223720a1d8760aa5c2afa5e8ee0
3	3	FnFbcypAOBl0+s/IVr8KOlFL83Re6dMnOBOVMNAlBcTjWv6xtjNJkDRiM6Djd8wy	446e262b19dd0c3389a73d12a717a3179af1eb778c0217e9cb26900b32a0e872
4	4	5JurgouueiTNMtRrbwHUZ6flH6gxiScOo8FeV/XEusncUbLtoDPYq8oNvzw48E0X	d4cf6ddc2ffd0613fe888f0fb050b7d03a21f9e914018ba3615182c78d90737d
5	5	R80wH9Cka9KewU5+WIU2EnLFAbAqSFAz2+fshumzJifMLfIbkABuvK2quaViETG9	9c9a2b4703917f82fe0261e52f280a480f0f5ae44f4f00b1d22d2b46597f0e1c
\.


--
-- Name: relation_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.relation_type_id_seq', 12, true);


--
-- Name: resource_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.resource_id_seq', 3, true);


--
-- Name: resource_metadata_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.resource_metadata_id_seq', 3, true);


--
-- Name: user_data_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.user_data_id_seq', 5, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pedro
--

SELECT pg_catalog.setval('public.user_id_seq', 5, true);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: relation_type relation_type_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.relation_type
    ADD CONSTRAINT relation_type_pkey PRIMARY KEY (id);


--
-- Name: resource_metadata resource_metadata_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource_metadata
    ADD CONSTRAINT resource_metadata_pkey PRIMARY KEY (id);


--
-- Name: resource resource_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT resource_pkey PRIMARY KEY (id);


--
-- Name: resource_relation_type resource_relation_type_pkey; Type: CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource_relation_type
    ADD CONSTRAINT resource_relation_type_pkey PRIMARY KEY (resource_id, relation_type_id);


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
-- Name: idx_3bf454a4727aca70; Type: INDEX; Schema: public; Owner: pedro
--

CREATE INDEX idx_3bf454a4727aca70 ON public.relation_type USING btree (parent_id);


--
-- Name: idx_7218726289329d25; Type: INDEX; Schema: public; Owner: pedro
--

CREATE INDEX idx_7218726289329d25 ON public.resource_relation_type USING btree (resource_id);


--
-- Name: idx_72187262dc379ee2; Type: INDEX; Schema: public; Owner: pedro
--

CREATE INDEX idx_72187262dc379ee2 ON public.resource_relation_type USING btree (relation_type_id);


--
-- Name: idx_bc91f4166ff8bf36; Type: INDEX; Schema: public; Owner: pedro
--

CREATE INDEX idx_bc91f4166ff8bf36 ON public.resource USING btree (user_data_id);


--
-- Name: uniq_d772bfaaa76ed395; Type: INDEX; Schema: public; Owner: pedro
--

CREATE UNIQUE INDEX uniq_d772bfaaa76ed395 ON public.user_data USING btree (user_id);


--
-- Name: uniq_e198feb989329d25; Type: INDEX; Schema: public; Owner: pedro
--

CREATE UNIQUE INDEX uniq_e198feb989329d25 ON public.resource_metadata USING btree (resource_id);


--
-- Name: username; Type: INDEX; Schema: public; Owner: pedro
--

CREATE UNIQUE INDEX username ON public."user" USING btree (username);


--
-- Name: relation_type fk_3bf454a4727aca70; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.relation_type
    ADD CONSTRAINT fk_3bf454a4727aca70 FOREIGN KEY (parent_id) REFERENCES public.relation_type(id);


--
-- Name: resource_relation_type fk_7218726289329d25; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource_relation_type
    ADD CONSTRAINT fk_7218726289329d25 FOREIGN KEY (resource_id) REFERENCES public.resource(id) ON DELETE CASCADE;


--
-- Name: resource_relation_type fk_72187262dc379ee2; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource_relation_type
    ADD CONSTRAINT fk_72187262dc379ee2 FOREIGN KEY (relation_type_id) REFERENCES public.relation_type(id) ON DELETE CASCADE;


--
-- Name: resource fk_bc91f4166ff8bf36; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT fk_bc91f4166ff8bf36 FOREIGN KEY (user_data_id) REFERENCES public.user_data(id) ON DELETE CASCADE;


--
-- Name: user_data fk_d772bfaaa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.user_data
    ADD CONSTRAINT fk_d772bfaaa76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: resource_metadata fk_e198feb989329d25; Type: FK CONSTRAINT; Schema: public; Owner: pedro
--

ALTER TABLE ONLY public.resource_metadata
    ADD CONSTRAINT fk_e198feb989329d25 FOREIGN KEY (resource_id) REFERENCES public.resource(id);


--
-- PostgreSQL database dump complete
--

