
CREATE TABLE public.usuario (
    usuario character varying(35) NOT NULL,
    clave character varying(36) NOT NULL,
    cedula_usu character varying(9) NOT NULL,
    nombres_usu character varying(35) NOT NULL,
    apellidos_usu character varying(35) NOT NULL,
    correo_usu character varying(35) NOT NULL,
    foto_usu character(254),
    estatus_usu character varying(15) NOT NULL
);

--
-- TOC entry 3570 (class 2606 OID 41441)
-- Name: usuario pk_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (usuario);


--
-- TOC entry 3829 (class 0 OID 41437)
-- Dependencies: 275
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuario VALUES ('Luis', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');
INSERT INTO public.usuario VALUES ('joseqqqqqqq', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');
INSERT INTO public.usuario VALUES ('joseq', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');
INSERT INTO public.usuario VALUES ('joseqr', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');


