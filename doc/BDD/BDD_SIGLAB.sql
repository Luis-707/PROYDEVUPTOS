--
-- PostgreSQL database dump
--

-- Dumped from database version 15.7 (Debian 15.7-0+deb12u1)
-- Dumped by pg_dump version 15.7 (Debian 15.7-0+deb12u1)

-- Started on 2025-04-03 09:50:37 -04

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
-- TOC entry 214 (class 1259 OID 41083)
-- Name: almacen_g; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.almacen_g (
    codigo_almacen_g character varying(10) NOT NULL,
    usuario character varying(35) NOT NULL,
    ubicacion_alma_g character varying(35),
    encargado_almacen_g character varying(35)
);


ALTER TABLE public.almacen_g OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 41090)
-- Name: auditoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auditoria (
    codigo_aud integer NOT NULL,
    codigo_caso integer NOT NULL,
    usuario character varying(35) NOT NULL,
    descripcion_aud text,
    fecha_aud date,
    hora_aud time without time zone,
    accion_aud character varying(35),
    sql_aud character varying(35)
);


ALTER TABLE public.auditoria OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 41100)
-- Name: caso_uso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caso_uso (
    codigo_caso integer NOT NULL,
    actividades text
);


ALTER TABLE public.caso_uso OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 41109)
-- Name: comunidad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comunidad (
    id_comunidad integer NOT NULL,
    id_parroquia integer NOT NULL,
    nombre_comunidad character varying(50) NOT NULL,
    descripcion_comunidad character varying(75)
);


ALTER TABLE public.comunidad OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 41108)
-- Name: comunidad_id_comunidad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comunidad_id_comunidad_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidad_id_comunidad_seq OWNER TO postgres;

--
-- TOC entry 3835 (class 0 OID 0)
-- Dependencies: 217
-- Name: comunidad_id_comunidad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comunidad_id_comunidad_seq OWNED BY public.comunidad.id_comunidad;


--
-- TOC entry 220 (class 1259 OID 41118)
-- Name: configuracion_max_min; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.configuracion_max_min (
    id_config integer NOT NULL,
    codigo_insumo character varying(10) NOT NULL,
    codigo_lab character varying(10) NOT NULL,
    codigo_almacen_g character varying(10) NOT NULL,
    minimo_ins integer NOT NULL,
    maximo_ins integer NOT NULL,
    fecha_config date
);


ALTER TABLE public.configuracion_max_min OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 41117)
-- Name: configuracion_max_min_id_config_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.configuracion_max_min_id_config_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.configuracion_max_min_id_config_seq OWNER TO postgres;

--
-- TOC entry 3836 (class 0 OID 0)
-- Dependencies: 219
-- Name: configuracion_max_min_id_config_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.configuracion_max_min_id_config_seq OWNED BY public.configuracion_max_min.id_config;


--
-- TOC entry 222 (class 1259 OID 41129)
-- Name: consumo_examen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.consumo_examen (
    id_examen integer NOT NULL,
    codigo_insumo character varying(10) NOT NULL,
    id_consumo integer NOT NULL,
    cantidad numeric(4,2)
);


ALTER TABLE public.consumo_examen OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 41128)
-- Name: consumo_examen_id_consumo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.consumo_examen_id_consumo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.consumo_examen_id_consumo_seq OWNER TO postgres;

--
-- TOC entry 3837 (class 0 OID 0)
-- Dependencies: 221
-- Name: consumo_examen_id_consumo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.consumo_examen_id_consumo_seq OWNED BY public.consumo_examen.id_consumo;


--
-- TOC entry 223 (class 1259 OID 41138)
-- Name: despacho_insumo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.despacho_insumo (
    id_despacho_insumo integer NOT NULL,
    codigo_lab character varying(10) NOT NULL,
    numerro_despacho_insumo character varying(10) NOT NULL,
    fecha_despacho date NOT NULL,
    n_solicitud_ins character varying(10) NOT NULL,
    observacion_despacho text
);


ALTER TABLE public.despacho_insumo OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 41148)
-- Name: detalle_despacho; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalle_despacho (
    id_despacho_insumo integer NOT NULL,
    cantidad_despacho numeric(4,2) NOT NULL
);


ALTER TABLE public.detalle_despacho OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 41155)
-- Name: detalle_solicitud_insumo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalle_solicitud_insumo (
    id_detalle_ins integer NOT NULL,
    codigo_insumo character varying(10) NOT NULL,
    cantidad_consumo numeric(4,2)
);


ALTER TABLE public.detalle_solicitud_insumo OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 41154)
-- Name: detalle_solicitud_insumo_id_detalle_ins_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.detalle_solicitud_insumo_id_detalle_ins_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.detalle_solicitud_insumo_id_detalle_ins_seq OWNER TO postgres;

--
-- TOC entry 3838 (class 0 OID 0)
-- Dependencies: 225
-- Name: detalle_solicitud_insumo_id_detalle_ins_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.detalle_solicitud_insumo_id_detalle_ins_seq OWNED BY public.detalle_solicitud_insumo.id_detalle_ins;


--
-- TOC entry 228 (class 1259 OID 41164)
-- Name: devoluciones_insumo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.devoluciones_insumo (
    id_devolucion integer NOT NULL,
    id_detalle integer NOT NULL,
    id_movimiebto_ins integer NOT NULL,
    fecha_devolucion date NOT NULL
);


ALTER TABLE public.devoluciones_insumo OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 41163)
-- Name: devoluciones_insumo_id_devolucion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.devoluciones_insumo_id_devolucion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.devoluciones_insumo_id_devolucion_seq OWNER TO postgres;

--
-- TOC entry 3839 (class 0 OID 0)
-- Dependencies: 227
-- Name: devoluciones_insumo_id_devolucion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.devoluciones_insumo_id_devolucion_seq OWNED BY public.devoluciones_insumo.id_devolucion;


--
-- TOC entry 230 (class 1259 OID 41174)
-- Name: direccion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.direccion (
    id_direccion integer NOT NULL,
    cedula character varying(10) NOT NULL,
    id_sector integer NOT NULL,
    referencia character varying(35)
);


ALTER TABLE public.direccion OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 41173)
-- Name: direccion_id_direccion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.direccion_id_direccion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.direccion_id_direccion_seq OWNER TO postgres;

--
-- TOC entry 3840 (class 0 OID 0)
-- Dependencies: 229
-- Name: direccion_id_direccion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.direccion_id_direccion_seq OWNED BY public.direccion.id_direccion;


--
-- TOC entry 232 (class 1259 OID 41184)
-- Name: disponibilidad_examen_lab; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.disponibilidad_examen_lab (
    id_disponibilida integer NOT NULL,
    id_examen integer NOT NULL,
    codigo_lab character varying(10) NOT NULL,
    id_detalle integer NOT NULL,
    estado_dis character varying(10),
    condicion character varying(15),
    fecha_disponibilidad date
);


ALTER TABLE public.disponibilidad_examen_lab OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 41183)
-- Name: disponibilidad_examen_lab_id_disponibilida_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.disponibilidad_examen_lab_id_disponibilida_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.disponibilidad_examen_lab_id_disponibilida_seq OWNER TO postgres;

--
-- TOC entry 3841 (class 0 OID 0)
-- Dependencies: 231
-- Name: disponibilidad_examen_lab_id_disponibilida_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.disponibilidad_examen_lab_id_disponibilida_seq OWNED BY public.disponibilidad_examen_lab.id_disponibilida;


--
-- TOC entry 233 (class 1259 OID 41194)
-- Name: empleado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.empleado (
    cedula character varying(10) NOT NULL,
    codigo_empleado character varying(10) NOT NULL,
    usuario character varying(35) NOT NULL,
    direccion_emp text,
    otros_datos_emp text,
    cargo character varying(35),
    foto character(254),
    fecha_ini date,
    fecha_fin date
);


ALTER TABLE public.empleado OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 41204)
-- Name: especialidad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.especialidad (
    codigo_esp character varying(10) NOT NULL,
    nombre_esp character varying(35) NOT NULL
);


ALTER TABLE public.especialidad OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 41211)
-- Name: estado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.estado (
    id_estado integer NOT NULL,
    nombre_estado character varying(50) NOT NULL
);


ALTER TABLE public.estado OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 41210)
-- Name: estado_id_estado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.estado_id_estado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estado_id_estado_seq OWNER TO postgres;

--
-- TOC entry 3842 (class 0 OID 0)
-- Dependencies: 235
-- Name: estado_id_estado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.estado_id_estado_seq OWNED BY public.estado.id_estado;


--
-- TOC entry 238 (class 1259 OID 41219)
-- Name: examen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.examen (
    id_examen integer NOT NULL,
    id_tipo integer NOT NULL,
    nombre_exam character varying(35) NOT NULL,
    abreviacion_exam character varying(10) NOT NULL
);


ALTER TABLE public.examen OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 41218)
-- Name: examen_id_examen_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.examen_id_examen_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.examen_id_examen_seq OWNER TO postgres;

--
-- TOC entry 3843 (class 0 OID 0)
-- Dependencies: 237
-- Name: examen_id_examen_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.examen_id_examen_seq OWNED BY public.examen.id_examen;


--
-- TOC entry 239 (class 1259 OID 41227)
-- Name: grupos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.grupos (
    codigo_grupo integer NOT NULL,
    descripcion_gru text
);


ALTER TABLE public.grupos OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 41235)
-- Name: insumo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.insumo (
    codigo_insumo character varying(10) NOT NULL,
    codigo_tipo integer NOT NULL,
    nombre_insumo character varying(35) NOT NULL,
    descripcion_insumo text,
    unidad_medida character varying(15) NOT NULL,
    forma_de_conservacion character varying(35) NOT NULL,
    foto_insumo character(254)
);


ALTER TABLE public.insumo OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 41244)
-- Name: laboratorio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.laboratorio (
    codigo_lab character varying(10) NOT NULL,
    usuario character varying(35) NOT NULL,
    nombre_lab character varying(25) NOT NULL,
    ubicacion_lab text,
    encargado_lab character varying(35) NOT NULL
);


ALTER TABLE public.laboratorio OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 41254)
-- Name: lote; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lote (
    id_lote integer NOT NULL,
    codigo_insumo character varying(10) NOT NULL,
    fecha_entrada date NOT NULL,
    cantidad_inic numeric(4,2) NOT NULL,
    cantidad_actual numeric(4,2),
    marca_ins character varying(35) NOT NULL,
    fecha_vencimiento date
);


ALTER TABLE public.lote OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 41253)
-- Name: lote_id_lote_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lote_id_lote_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lote_id_lote_seq OWNER TO postgres;

--
-- TOC entry 3844 (class 0 OID 0)
-- Dependencies: 242
-- Name: lote_id_lote_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lote_id_lote_seq OWNED BY public.lote.id_lote;


--
-- TOC entry 244 (class 1259 OID 41262)
-- Name: medico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.medico (
    cedula character varying(10) NOT NULL,
    codigo_mpps character varying(20) NOT NULL,
    codigo_esp character varying(10) NOT NULL,
    nom_centro_medico character varying(35),
    direccion_med text
);


ALTER TABLE public.medico OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 41273)
-- Name: movimiento_insumo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.movimiento_insumo (
    id_movimiebto_ins integer NOT NULL,
    codigo_lab character varying(10) NOT NULL,
    id_detalle integer NOT NULL,
    numero_documento integer NOT NULL,
    tipo_movimiento_ins character varying(15) NOT NULL,
    fecha_movimiento_ins date NOT NULL,
    cantidad_ins numeric(4,2) NOT NULL,
    cantidad_stock_ins numeric(4,2) NOT NULL,
    fecha_vencimiento_ins date NOT NULL
);


ALTER TABLE public.movimiento_insumo OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 41272)
-- Name: movimiento_insumo_id_movimiebto_ins_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.movimiento_insumo_id_movimiebto_ins_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.movimiento_insumo_id_movimiebto_ins_seq OWNER TO postgres;

--
-- TOC entry 3845 (class 0 OID 0)
-- Dependencies: 245
-- Name: movimiento_insumo_id_movimiebto_ins_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.movimiento_insumo_id_movimiebto_ins_seq OWNED BY public.movimiento_insumo.id_movimiebto_ins;


--
-- TOC entry 248 (class 1259 OID 41283)
-- Name: movimiento_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.movimiento_inventario (
    id_moviento_inv integer NOT NULL,
    id_inventario integer NOT NULL,
    id_detalle_ins integer NOT NULL,
    id_despacho_insumo integer NOT NULL,
    tipo_movimiento_inv character varying(10) NOT NULL,
    fecha_movi date NOT NULL,
    cantidad_sal numeric(4,2) NOT NULL
);


ALTER TABLE public.movimiento_inventario OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 41282)
-- Name: movimiento_inventario_id_moviento_inv_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.movimiento_inventario_id_moviento_inv_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.movimiento_inventario_id_moviento_inv_seq OWNER TO postgres;

--
-- TOC entry 3846 (class 0 OID 0)
-- Dependencies: 247
-- Name: movimiento_inventario_id_moviento_inv_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.movimiento_inventario_id_moviento_inv_seq OWNED BY public.movimiento_inventario.id_moviento_inv;


--
-- TOC entry 249 (class 1259 OID 41293)
-- Name: movimiento_lotes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.movimiento_lotes (
    id_moviento_inv integer NOT NULL,
    id_lote integer NOT NULL
);


ALTER TABLE public.movimiento_lotes OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 41302)
-- Name: municipio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.municipio (
    id_municipio integer NOT NULL,
    id_estado integer NOT NULL,
    nombre_municipio character varying(50) NOT NULL
);


ALTER TABLE public.municipio OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 41301)
-- Name: municipio_id_municipio_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.municipio_id_municipio_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.municipio_id_municipio_seq OWNER TO postgres;

--
-- TOC entry 3847 (class 0 OID 0)
-- Dependencies: 250
-- Name: municipio_id_municipio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.municipio_id_municipio_seq OWNED BY public.municipio.id_municipio;


--
-- TOC entry 253 (class 1259 OID 41311)
-- Name: paciente; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.paciente (
    cedula character varying(10) NOT NULL,
    id_paciente2 integer NOT NULL,
    fecha_ingreso date
);


ALTER TABLE public.paciente OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 41310)
-- Name: paciente_id_paciente2_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.paciente_id_paciente2_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.paciente_id_paciente2_seq OWNER TO postgres;

--
-- TOC entry 3848 (class 0 OID 0)
-- Dependencies: 252
-- Name: paciente_id_paciente2_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.paciente_id_paciente2_seq OWNED BY public.paciente.id_paciente2;


--
-- TOC entry 255 (class 1259 OID 41320)
-- Name: parametros_examen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.parametros_examen (
    id_parametros integer NOT NULL,
    valor_minimo double precision NOT NULL,
    valor_maximo double precision NOT NULL
);


ALTER TABLE public.parametros_examen OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 41319)
-- Name: parametros_examen_id_parametros_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.parametros_examen_id_parametros_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parametros_examen_id_parametros_seq OWNER TO postgres;

--
-- TOC entry 3849 (class 0 OID 0)
-- Dependencies: 254
-- Name: parametros_examen_id_parametros_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.parametros_examen_id_parametros_seq OWNED BY public.parametros_examen.id_parametros;


--
-- TOC entry 257 (class 1259 OID 41328)
-- Name: parroquia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.parroquia (
    id_parroquia integer NOT NULL,
    id_municipio integer NOT NULL,
    nombre_parroquia character varying(50) NOT NULL
);


ALTER TABLE public.parroquia OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 41327)
-- Name: parroquia_id_parroquia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.parroquia_id_parroquia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parroquia_id_parroquia_seq OWNER TO postgres;

--
-- TOC entry 3850 (class 0 OID 0)
-- Dependencies: 256
-- Name: parroquia_id_parroquia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.parroquia_id_parroquia_seq OWNED BY public.parroquia.id_parroquia;


--
-- TOC entry 258 (class 1259 OID 41336)
-- Name: permiso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permiso (
    usuario character varying(35) NOT NULL,
    codigo_grupo integer NOT NULL,
    codigo_caso integer NOT NULL,
    mostrar_usu integer,
    insertar_usu integer,
    actualizar_usu integer,
    eliminar_usu integer,
    fechain_usu date
);


ALTER TABLE public.permiso OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 41345)
-- Name: persona; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.persona (
    cedula character varying(10) NOT NULL,
    nacionalida character(256) NOT NULL,
    nombres character varying(30) NOT NULL,
    apellidos character varying(30) NOT NULL,
    sexo character varying(10) NOT NULL,
    estado_civil character varying(15),
    fecha_nac date,
    correo_elect character varying(35),
    telefono_per integer,
    otro_datos text
);


ALTER TABLE public.persona OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 41353)
-- Name: pertenese; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pertenese (
    usuario character varying(35) NOT NULL,
    codigo_grupo integer NOT NULL
);


ALTER TABLE public.pertenese OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 41362)
-- Name: resultado_examen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.resultado_examen (
    id_resultado integer NOT NULL,
    usuario character varying(35) NOT NULL,
    id_parametros integer NOT NULL,
    valor_resultado double precision NOT NULL,
    fecha_resultado date,
    observacion_res text
);


ALTER TABLE public.resultado_examen OWNER TO postgres;

--
-- TOC entry 261 (class 1259 OID 41361)
-- Name: resultado_examen_id_resultado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.resultado_examen_id_resultado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.resultado_examen_id_resultado_seq OWNER TO postgres;

--
-- TOC entry 3851 (class 0 OID 0)
-- Dependencies: 261
-- Name: resultado_examen_id_resultado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.resultado_examen_id_resultado_seq OWNED BY public.resultado_examen.id_resultado;


--
-- TOC entry 264 (class 1259 OID 41374)
-- Name: sector; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sector (
    id_sector integer NOT NULL,
    id_comunidad integer NOT NULL,
    nombre_sector character varying(30) NOT NULL,
    descripcion_sector character varying(75)
);


ALTER TABLE public.sector OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 41373)
-- Name: sector_id_sector_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sector_id_sector_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sector_id_sector_seq OWNER TO postgres;

--
-- TOC entry 3852 (class 0 OID 0)
-- Dependencies: 263
-- Name: sector_id_sector_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sector_id_sector_seq OWNED BY public.sector.id_sector;


--
-- TOC entry 266 (class 1259 OID 41383)
-- Name: solicitud_examen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.solicitud_examen (
    id_solicitud integer NOT NULL,
    med_cedula character varying(10) NOT NULL,
    codigo_mpps character varying(20) NOT NULL,
    cedula character varying(10) NOT NULL,
    id_paciente2 integer NOT NULL,
    usuario character varying(35),
    fecha_solicitud date
);


ALTER TABLE public.solicitud_examen OWNER TO postgres;

--
-- TOC entry 268 (class 1259 OID 41394)
-- Name: solicitud_examen_detalle; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.solicitud_examen_detalle (
    id_detalle integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_examen integer NOT NULL,
    estado character varying(15)
);


ALTER TABLE public.solicitud_examen_detalle OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 41393)
-- Name: solicitud_examen_detalle_id_detalle_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.solicitud_examen_detalle_id_detalle_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_examen_detalle_id_detalle_seq OWNER TO postgres;

--
-- TOC entry 3853 (class 0 OID 0)
-- Dependencies: 267
-- Name: solicitud_examen_detalle_id_detalle_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.solicitud_examen_detalle_id_detalle_seq OWNED BY public.solicitud_examen_detalle.id_detalle;


--
-- TOC entry 265 (class 1259 OID 41382)
-- Name: solicitud_examen_id_solicitud_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.solicitud_examen_id_solicitud_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_examen_id_solicitud_seq OWNER TO postgres;

--
-- TOC entry 3854 (class 0 OID 0)
-- Dependencies: 265
-- Name: solicitud_examen_id_solicitud_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.solicitud_examen_id_solicitud_seq OWNED BY public.solicitud_examen.id_solicitud;


--
-- TOC entry 269 (class 1259 OID 41403)
-- Name: solicitud_insumos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.solicitud_insumos (
    codigo_lab character varying(10) NOT NULL,
    codigo_almacen_g character varying(10) NOT NULL,
    id_detalle_ins integer NOT NULL,
    numero_solicitud character varying(10) NOT NULL,
    fecha_sol_ins date NOT NULL,
    descripcion_soli_ins text,
    estado_solicitud character varying(15) NOT NULL
);


ALTER TABLE public.solicitud_insumos OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 41415)
-- Name: stock_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.stock_inventario (
    id_inventario integer NOT NULL,
    codigo_almacen_g character varying(10) NOT NULL,
    stock_inventario numeric(4,2) NOT NULL,
    fecha_inv date NOT NULL
);


ALTER TABLE public.stock_inventario OWNER TO postgres;

--
-- TOC entry 270 (class 1259 OID 41414)
-- Name: stock_inventario_id_inventario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.stock_inventario_id_inventario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stock_inventario_id_inventario_seq OWNER TO postgres;

--
-- TOC entry 3855 (class 0 OID 0)
-- Dependencies: 270
-- Name: stock_inventario_id_inventario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.stock_inventario_id_inventario_seq OWNED BY public.stock_inventario.id_inventario;


--
-- TOC entry 273 (class 1259 OID 41424)
-- Name: tipo_examenes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_examenes (
    id_tipo integer NOT NULL,
    nombre_tipo_exa character varying(35) NOT NULL
);


ALTER TABLE public.tipo_examenes OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 41423)
-- Name: tipo_examenes_id_tipo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_examenes_id_tipo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_examenes_id_tipo_seq OWNER TO postgres;

--
-- TOC entry 3856 (class 0 OID 0)
-- Dependencies: 272
-- Name: tipo_examenes_id_tipo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_examenes_id_tipo_seq OWNED BY public.tipo_examenes.id_tipo;


--
-- TOC entry 274 (class 1259 OID 41431)
-- Name: tipo_insumo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_insumo (
    codigo_tipo integer NOT NULL,
    descripcion_tipo character varying(50) NOT NULL
);


ALTER TABLE public.tipo_insumo OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 41437)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

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


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 3376 (class 2604 OID 41112)
-- Name: comunidad id_comunidad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comunidad ALTER COLUMN id_comunidad SET DEFAULT nextval('public.comunidad_id_comunidad_seq'::regclass);


--
-- TOC entry 3377 (class 2604 OID 41121)
-- Name: configuracion_max_min id_config; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.configuracion_max_min ALTER COLUMN id_config SET DEFAULT nextval('public.configuracion_max_min_id_config_seq'::regclass);


--
-- TOC entry 3378 (class 2604 OID 41132)
-- Name: consumo_examen id_consumo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consumo_examen ALTER COLUMN id_consumo SET DEFAULT nextval('public.consumo_examen_id_consumo_seq'::regclass);


--
-- TOC entry 3379 (class 2604 OID 41158)
-- Name: detalle_solicitud_insumo id_detalle_ins; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_solicitud_insumo ALTER COLUMN id_detalle_ins SET DEFAULT nextval('public.detalle_solicitud_insumo_id_detalle_ins_seq'::regclass);


--
-- TOC entry 3380 (class 2604 OID 41167)
-- Name: devoluciones_insumo id_devolucion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.devoluciones_insumo ALTER COLUMN id_devolucion SET DEFAULT nextval('public.devoluciones_insumo_id_devolucion_seq'::regclass);


--
-- TOC entry 3381 (class 2604 OID 41177)
-- Name: direccion id_direccion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.direccion ALTER COLUMN id_direccion SET DEFAULT nextval('public.direccion_id_direccion_seq'::regclass);


--
-- TOC entry 3382 (class 2604 OID 41187)
-- Name: disponibilidad_examen_lab id_disponibilida; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.disponibilidad_examen_lab ALTER COLUMN id_disponibilida SET DEFAULT nextval('public.disponibilidad_examen_lab_id_disponibilida_seq'::regclass);


--
-- TOC entry 3383 (class 2604 OID 41214)
-- Name: estado id_estado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.estado ALTER COLUMN id_estado SET DEFAULT nextval('public.estado_id_estado_seq'::regclass);


--
-- TOC entry 3384 (class 2604 OID 41222)
-- Name: examen id_examen; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.examen ALTER COLUMN id_examen SET DEFAULT nextval('public.examen_id_examen_seq'::regclass);


--
-- TOC entry 3385 (class 2604 OID 41257)
-- Name: lote id_lote; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lote ALTER COLUMN id_lote SET DEFAULT nextval('public.lote_id_lote_seq'::regclass);


--
-- TOC entry 3386 (class 2604 OID 41276)
-- Name: movimiento_insumo id_movimiebto_ins; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_insumo ALTER COLUMN id_movimiebto_ins SET DEFAULT nextval('public.movimiento_insumo_id_movimiebto_ins_seq'::regclass);


--
-- TOC entry 3387 (class 2604 OID 41286)
-- Name: movimiento_inventario id_moviento_inv; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_inventario ALTER COLUMN id_moviento_inv SET DEFAULT nextval('public.movimiento_inventario_id_moviento_inv_seq'::regclass);


--
-- TOC entry 3388 (class 2604 OID 41305)
-- Name: municipio id_municipio; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.municipio ALTER COLUMN id_municipio SET DEFAULT nextval('public.municipio_id_municipio_seq'::regclass);


--
-- TOC entry 3389 (class 2604 OID 41314)
-- Name: paciente id_paciente2; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paciente ALTER COLUMN id_paciente2 SET DEFAULT nextval('public.paciente_id_paciente2_seq'::regclass);


--
-- TOC entry 3390 (class 2604 OID 41323)
-- Name: parametros_examen id_parametros; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parametros_examen ALTER COLUMN id_parametros SET DEFAULT nextval('public.parametros_examen_id_parametros_seq'::regclass);


--
-- TOC entry 3391 (class 2604 OID 41331)
-- Name: parroquia id_parroquia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parroquia ALTER COLUMN id_parroquia SET DEFAULT nextval('public.parroquia_id_parroquia_seq'::regclass);


--
-- TOC entry 3392 (class 2604 OID 41365)
-- Name: resultado_examen id_resultado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultado_examen ALTER COLUMN id_resultado SET DEFAULT nextval('public.resultado_examen_id_resultado_seq'::regclass);


--
-- TOC entry 3393 (class 2604 OID 41377)
-- Name: sector id_sector; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sector ALTER COLUMN id_sector SET DEFAULT nextval('public.sector_id_sector_seq'::regclass);


--
-- TOC entry 3394 (class 2604 OID 41386)
-- Name: solicitud_examen id_solicitud; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen ALTER COLUMN id_solicitud SET DEFAULT nextval('public.solicitud_examen_id_solicitud_seq'::regclass);


--
-- TOC entry 3395 (class 2604 OID 41397)
-- Name: solicitud_examen_detalle id_detalle; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen_detalle ALTER COLUMN id_detalle SET DEFAULT nextval('public.solicitud_examen_detalle_id_detalle_seq'::regclass);


--
-- TOC entry 3396 (class 2604 OID 41418)
-- Name: stock_inventario id_inventario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stock_inventario ALTER COLUMN id_inventario SET DEFAULT nextval('public.stock_inventario_id_inventario_seq'::regclass);


--
-- TOC entry 3397 (class 2604 OID 41427)
-- Name: tipo_examenes id_tipo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_examenes ALTER COLUMN id_tipo SET DEFAULT nextval('public.tipo_examenes_id_tipo_seq'::regclass);


--
-- TOC entry 3768 (class 0 OID 41083)
-- Dependencies: 214
-- Data for Name: almacen_g; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3769 (class 0 OID 41090)
-- Dependencies: 215
-- Data for Name: auditoria; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3770 (class 0 OID 41100)
-- Dependencies: 216
-- Data for Name: caso_uso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3772 (class 0 OID 41109)
-- Dependencies: 218
-- Data for Name: comunidad; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3774 (class 0 OID 41118)
-- Dependencies: 220
-- Data for Name: configuracion_max_min; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3776 (class 0 OID 41129)
-- Dependencies: 222
-- Data for Name: consumo_examen; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3777 (class 0 OID 41138)
-- Dependencies: 223
-- Data for Name: despacho_insumo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3778 (class 0 OID 41148)
-- Dependencies: 224
-- Data for Name: detalle_despacho; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3780 (class 0 OID 41155)
-- Dependencies: 226
-- Data for Name: detalle_solicitud_insumo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3782 (class 0 OID 41164)
-- Dependencies: 228
-- Data for Name: devoluciones_insumo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3784 (class 0 OID 41174)
-- Dependencies: 230
-- Data for Name: direccion; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3786 (class 0 OID 41184)
-- Dependencies: 232
-- Data for Name: disponibilidad_examen_lab; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3787 (class 0 OID 41194)
-- Dependencies: 233
-- Data for Name: empleado; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3788 (class 0 OID 41204)
-- Dependencies: 234
-- Data for Name: especialidad; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3790 (class 0 OID 41211)
-- Dependencies: 236
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3792 (class 0 OID 41219)
-- Dependencies: 238
-- Data for Name: examen; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3793 (class 0 OID 41227)
-- Dependencies: 239
-- Data for Name: grupos; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3794 (class 0 OID 41235)
-- Dependencies: 240
-- Data for Name: insumo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3795 (class 0 OID 41244)
-- Dependencies: 241
-- Data for Name: laboratorio; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3797 (class 0 OID 41254)
-- Dependencies: 243
-- Data for Name: lote; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3798 (class 0 OID 41262)
-- Dependencies: 244
-- Data for Name: medico; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3800 (class 0 OID 41273)
-- Dependencies: 246
-- Data for Name: movimiento_insumo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3802 (class 0 OID 41283)
-- Dependencies: 248
-- Data for Name: movimiento_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3803 (class 0 OID 41293)
-- Dependencies: 249
-- Data for Name: movimiento_lotes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3805 (class 0 OID 41302)
-- Dependencies: 251
-- Data for Name: municipio; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3807 (class 0 OID 41311)
-- Dependencies: 253
-- Data for Name: paciente; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3809 (class 0 OID 41320)
-- Dependencies: 255
-- Data for Name: parametros_examen; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3811 (class 0 OID 41328)
-- Dependencies: 257
-- Data for Name: parroquia; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3812 (class 0 OID 41336)
-- Dependencies: 258
-- Data for Name: permiso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3813 (class 0 OID 41345)
-- Dependencies: 259
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3814 (class 0 OID 41353)
-- Dependencies: 260
-- Data for Name: pertenese; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3816 (class 0 OID 41362)
-- Dependencies: 262
-- Data for Name: resultado_examen; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3818 (class 0 OID 41374)
-- Dependencies: 264
-- Data for Name: sector; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3820 (class 0 OID 41383)
-- Dependencies: 266
-- Data for Name: solicitud_examen; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3822 (class 0 OID 41394)
-- Dependencies: 268
-- Data for Name: solicitud_examen_detalle; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3823 (class 0 OID 41403)
-- Dependencies: 269
-- Data for Name: solicitud_insumos; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3825 (class 0 OID 41415)
-- Dependencies: 271
-- Data for Name: stock_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3827 (class 0 OID 41424)
-- Dependencies: 273
-- Data for Name: tipo_examenes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3828 (class 0 OID 41431)
-- Dependencies: 274
-- Data for Name: tipo_insumo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3829 (class 0 OID 41437)
-- Dependencies: 275
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuario VALUES ('Luis', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');
INSERT INTO public.usuario VALUES ('joseqqqqqqq', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');
INSERT INTO public.usuario VALUES ('joseq', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');
INSERT INTO public.usuario VALUES ('joseqr', 'b4b14cabace7c83adf7106f283b194cb', '1000111', 'jose', 'perez', 'jose@gmail.com', '                                                                                                                                                                                                                                                              ', 'ACTIVO');


--
-- TOC entry 3857 (class 0 OID 0)
-- Dependencies: 217
-- Name: comunidad_id_comunidad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comunidad_id_comunidad_seq', 1, false);


--
-- TOC entry 3858 (class 0 OID 0)
-- Dependencies: 219
-- Name: configuracion_max_min_id_config_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.configuracion_max_min_id_config_seq', 1, false);


--
-- TOC entry 3859 (class 0 OID 0)
-- Dependencies: 221
-- Name: consumo_examen_id_consumo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.consumo_examen_id_consumo_seq', 1, false);


--
-- TOC entry 3860 (class 0 OID 0)
-- Dependencies: 225
-- Name: detalle_solicitud_insumo_id_detalle_ins_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.detalle_solicitud_insumo_id_detalle_ins_seq', 1, false);


--
-- TOC entry 3861 (class 0 OID 0)
-- Dependencies: 227
-- Name: devoluciones_insumo_id_devolucion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.devoluciones_insumo_id_devolucion_seq', 1, false);


--
-- TOC entry 3862 (class 0 OID 0)
-- Dependencies: 229
-- Name: direccion_id_direccion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.direccion_id_direccion_seq', 1, false);


--
-- TOC entry 3863 (class 0 OID 0)
-- Dependencies: 231
-- Name: disponibilidad_examen_lab_id_disponibilida_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.disponibilidad_examen_lab_id_disponibilida_seq', 1, false);


--
-- TOC entry 3864 (class 0 OID 0)
-- Dependencies: 235
-- Name: estado_id_estado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.estado_id_estado_seq', 1, false);


--
-- TOC entry 3865 (class 0 OID 0)
-- Dependencies: 237
-- Name: examen_id_examen_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.examen_id_examen_seq', 1, false);


--
-- TOC entry 3866 (class 0 OID 0)
-- Dependencies: 242
-- Name: lote_id_lote_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lote_id_lote_seq', 1, false);


--
-- TOC entry 3867 (class 0 OID 0)
-- Dependencies: 245
-- Name: movimiento_insumo_id_movimiebto_ins_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.movimiento_insumo_id_movimiebto_ins_seq', 1, false);


--
-- TOC entry 3868 (class 0 OID 0)
-- Dependencies: 247
-- Name: movimiento_inventario_id_moviento_inv_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.movimiento_inventario_id_moviento_inv_seq', 1, false);


--
-- TOC entry 3869 (class 0 OID 0)
-- Dependencies: 250
-- Name: municipio_id_municipio_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.municipio_id_municipio_seq', 1, false);


--
-- TOC entry 3870 (class 0 OID 0)
-- Dependencies: 252
-- Name: paciente_id_paciente2_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.paciente_id_paciente2_seq', 1, false);


--
-- TOC entry 3871 (class 0 OID 0)
-- Dependencies: 254
-- Name: parametros_examen_id_parametros_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.parametros_examen_id_parametros_seq', 1, false);


--
-- TOC entry 3872 (class 0 OID 0)
-- Dependencies: 256
-- Name: parroquia_id_parroquia_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.parroquia_id_parroquia_seq', 1, false);


--
-- TOC entry 3873 (class 0 OID 0)
-- Dependencies: 261
-- Name: resultado_examen_id_resultado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.resultado_examen_id_resultado_seq', 1, false);


--
-- TOC entry 3874 (class 0 OID 0)
-- Dependencies: 263
-- Name: sector_id_sector_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sector_id_sector_seq', 1, false);


--
-- TOC entry 3875 (class 0 OID 0)
-- Dependencies: 267
-- Name: solicitud_examen_detalle_id_detalle_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.solicitud_examen_detalle_id_detalle_seq', 1, false);


--
-- TOC entry 3876 (class 0 OID 0)
-- Dependencies: 265
-- Name: solicitud_examen_id_solicitud_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.solicitud_examen_id_solicitud_seq', 1, false);


--
-- TOC entry 3877 (class 0 OID 0)
-- Dependencies: 270
-- Name: stock_inventario_id_inventario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.stock_inventario_id_inventario_seq', 1, false);


--
-- TOC entry 3878 (class 0 OID 0)
-- Dependencies: 272
-- Name: tipo_examenes_id_tipo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_examenes_id_tipo_seq', 1, false);


--
-- TOC entry 3401 (class 2606 OID 41087)
-- Name: almacen_g pk_almacen_g; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.almacen_g
    ADD CONSTRAINT pk_almacen_g PRIMARY KEY (codigo_almacen_g);


--
-- TOC entry 3405 (class 2606 OID 41096)
-- Name: auditoria pk_auditoria; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT pk_auditoria PRIMARY KEY (codigo_aud);


--
-- TOC entry 3409 (class 2606 OID 41106)
-- Name: caso_uso pk_caso_uso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caso_uso
    ADD CONSTRAINT pk_caso_uso PRIMARY KEY (codigo_caso);


--
-- TOC entry 3412 (class 2606 OID 41114)
-- Name: comunidad pk_comunidad; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comunidad
    ADD CONSTRAINT pk_comunidad PRIMARY KEY (id_comunidad);


--
-- TOC entry 3419 (class 2606 OID 41123)
-- Name: configuracion_max_min pk_configuracion_max_min; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.configuracion_max_min
    ADD CONSTRAINT pk_configuracion_max_min PRIMARY KEY (id_config);


--
-- TOC entry 3424 (class 2606 OID 41134)
-- Name: consumo_examen pk_consumo_examen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consumo_examen
    ADD CONSTRAINT pk_consumo_examen PRIMARY KEY (id_examen, codigo_insumo);


--
-- TOC entry 3429 (class 2606 OID 41144)
-- Name: despacho_insumo pk_despacho_insumo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_insumo
    ADD CONSTRAINT pk_despacho_insumo PRIMARY KEY (id_despacho_insumo, codigo_lab);


--
-- TOC entry 3432 (class 2606 OID 41152)
-- Name: detalle_despacho pk_detalle_despacho; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_despacho
    ADD CONSTRAINT pk_detalle_despacho PRIMARY KEY (id_despacho_insumo);


--
-- TOC entry 3436 (class 2606 OID 41160)
-- Name: detalle_solicitud_insumo pk_detalle_solicitud_insumo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_solicitud_insumo
    ADD CONSTRAINT pk_detalle_solicitud_insumo PRIMARY KEY (id_detalle_ins);


--
-- TOC entry 3440 (class 2606 OID 41169)
-- Name: devoluciones_insumo pk_devoluciones_insumo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.devoluciones_insumo
    ADD CONSTRAINT pk_devoluciones_insumo PRIMARY KEY (id_devolucion);


--
-- TOC entry 3444 (class 2606 OID 41179)
-- Name: direccion pk_direccion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.direccion
    ADD CONSTRAINT pk_direccion PRIMARY KEY (id_direccion);


--
-- TOC entry 3452 (class 2606 OID 41189)
-- Name: disponibilidad_examen_lab pk_disponibilidad_examen_lab; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.disponibilidad_examen_lab
    ADD CONSTRAINT pk_disponibilidad_examen_lab PRIMARY KEY (id_disponibilida);


--
-- TOC entry 3455 (class 2606 OID 41200)
-- Name: empleado pk_empleado; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empleado
    ADD CONSTRAINT pk_empleado PRIMARY KEY (cedula, codigo_empleado);


--
-- TOC entry 3460 (class 2606 OID 41208)
-- Name: especialidad pk_especialidad; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.especialidad
    ADD CONSTRAINT pk_especialidad PRIMARY KEY (codigo_esp);


--
-- TOC entry 3463 (class 2606 OID 41216)
-- Name: estado pk_estado; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.estado
    ADD CONSTRAINT pk_estado PRIMARY KEY (id_estado);


--
-- TOC entry 3467 (class 2606 OID 41224)
-- Name: examen pk_examen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.examen
    ADD CONSTRAINT pk_examen PRIMARY KEY (id_examen);


--
-- TOC entry 3470 (class 2606 OID 41233)
-- Name: grupos pk_grupos; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.grupos
    ADD CONSTRAINT pk_grupos PRIMARY KEY (codigo_grupo);


--
-- TOC entry 3474 (class 2606 OID 41241)
-- Name: insumo pk_insumo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.insumo
    ADD CONSTRAINT pk_insumo PRIMARY KEY (codigo_insumo);


--
-- TOC entry 3477 (class 2606 OID 41250)
-- Name: laboratorio pk_laboratorio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laboratorio
    ADD CONSTRAINT pk_laboratorio PRIMARY KEY (codigo_lab);


--
-- TOC entry 3482 (class 2606 OID 41259)
-- Name: lote pk_lote; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lote
    ADD CONSTRAINT pk_lote PRIMARY KEY (id_lote);


--
-- TOC entry 3486 (class 2606 OID 41268)
-- Name: medico pk_medico; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.medico
    ADD CONSTRAINT pk_medico PRIMARY KEY (cedula, codigo_mpps);


--
-- TOC entry 3490 (class 2606 OID 41278)
-- Name: movimiento_insumo pk_movimiento_insumo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_insumo
    ADD CONSTRAINT pk_movimiento_insumo PRIMARY KEY (id_movimiebto_ins);


--
-- TOC entry 3496 (class 2606 OID 41288)
-- Name: movimiento_inventario pk_movimiento_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_inventario
    ADD CONSTRAINT pk_movimiento_inventario PRIMARY KEY (id_moviento_inv);


--
-- TOC entry 3503 (class 2606 OID 41297)
-- Name: movimiento_lotes pk_movimiento_lotes; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_lotes
    ADD CONSTRAINT pk_movimiento_lotes PRIMARY KEY (id_moviento_inv, id_lote);


--
-- TOC entry 3507 (class 2606 OID 41307)
-- Name: municipio pk_municipio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.municipio
    ADD CONSTRAINT pk_municipio PRIMARY KEY (id_municipio);


--
-- TOC entry 3510 (class 2606 OID 41316)
-- Name: paciente pk_paciente; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paciente
    ADD CONSTRAINT pk_paciente PRIMARY KEY (cedula, id_paciente2);


--
-- TOC entry 3514 (class 2606 OID 41325)
-- Name: parametros_examen pk_parametros_examen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parametros_examen
    ADD CONSTRAINT pk_parametros_examen PRIMARY KEY (id_parametros);


--
-- TOC entry 3517 (class 2606 OID 41333)
-- Name: parroquia pk_parroquia; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parroquia
    ADD CONSTRAINT pk_parroquia PRIMARY KEY (id_parroquia);


--
-- TOC entry 3524 (class 2606 OID 41340)
-- Name: permiso pk_permiso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT pk_permiso PRIMARY KEY (usuario, codigo_grupo, codigo_caso);


--
-- TOC entry 3527 (class 2606 OID 41351)
-- Name: persona pk_persona; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona
    ADD CONSTRAINT pk_persona PRIMARY KEY (cedula);


--
-- TOC entry 3532 (class 2606 OID 41357)
-- Name: pertenese pk_pertenese; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pertenese
    ADD CONSTRAINT pk_pertenese PRIMARY KEY (usuario, codigo_grupo);


--
-- TOC entry 3534 (class 2606 OID 41369)
-- Name: resultado_examen pk_resultado_examen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultado_examen
    ADD CONSTRAINT pk_resultado_examen PRIMARY KEY (id_resultado);


--
-- TOC entry 3539 (class 2606 OID 41379)
-- Name: sector pk_sector; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sector
    ADD CONSTRAINT pk_sector PRIMARY KEY (id_sector);


--
-- TOC entry 3546 (class 2606 OID 41388)
-- Name: solicitud_examen pk_solicitud_examen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen
    ADD CONSTRAINT pk_solicitud_examen PRIMARY KEY (id_solicitud);


--
-- TOC entry 3551 (class 2606 OID 41399)
-- Name: solicitud_examen_detalle pk_solicitud_examen_detalle; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen_detalle
    ADD CONSTRAINT pk_solicitud_examen_detalle PRIMARY KEY (id_detalle);


--
-- TOC entry 3554 (class 2606 OID 41409)
-- Name: solicitud_insumos pk_solicitud_insumos; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_insumos
    ADD CONSTRAINT pk_solicitud_insumos PRIMARY KEY (codigo_lab, codigo_almacen_g, id_detalle_ins);


--
-- TOC entry 3561 (class 2606 OID 41420)
-- Name: stock_inventario pk_stock_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stock_inventario
    ADD CONSTRAINT pk_stock_inventario PRIMARY KEY (id_inventario);


--
-- TOC entry 3564 (class 2606 OID 41429)
-- Name: tipo_examenes pk_tipo_examenes; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_examenes
    ADD CONSTRAINT pk_tipo_examenes PRIMARY KEY (id_tipo);


--
-- TOC entry 3567 (class 2606 OID 41435)
-- Name: tipo_insumo pk_tipo_insumo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_insumo
    ADD CONSTRAINT pk_tipo_insumo PRIMARY KEY (codigo_tipo);


--
-- TOC entry 3570 (class 2606 OID 41441)
-- Name: usuario pk_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (usuario);


--
-- TOC entry 3398 (class 1259 OID 41088)
-- Name: almacen_g_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX almacen_g_pk ON public.almacen_g USING btree (codigo_almacen_g);


--
-- TOC entry 3542 (class 1259 OID 41392)
-- Name: anota_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX anota_fk ON public.solicitud_examen USING btree (usuario);


--
-- TOC entry 3471 (class 1259 OID 41243)
-- Name: asociado_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX asociado_fk ON public.insumo USING btree (codigo_tipo);


--
-- TOC entry 3399 (class 1259 OID 41089)
-- Name: atiende_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX atiende_fk ON public.almacen_g USING btree (usuario);


--
-- TOC entry 3402 (class 1259 OID 41097)
-- Name: auditoria_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX auditoria_pk ON public.auditoria USING btree (codigo_aud);


--
-- TOC entry 3407 (class 1259 OID 41107)
-- Name: caso_uso_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX caso_uso_pk ON public.caso_uso USING btree (codigo_caso);


--
-- TOC entry 3447 (class 1259 OID 41193)
-- Name: chequea_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX chequea_fk ON public.disponibilidad_examen_lab USING btree (id_detalle);


--
-- TOC entry 3464 (class 1259 OID 41226)
-- Name: clasifica_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX clasifica_fk ON public.examen USING btree (id_tipo);


--
-- TOC entry 3410 (class 1259 OID 41115)
-- Name: comunidad_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX comunidad_pk ON public.comunidad USING btree (id_comunidad);


--
-- TOC entry 3414 (class 1259 OID 41124)
-- Name: configuracion_max_min_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX configuracion_max_min_pk ON public.configuracion_max_min USING btree (id_config);


--
-- TOC entry 3504 (class 1259 OID 41309)
-- Name: conforma_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX conforma_fk ON public.municipio USING btree (id_estado);


--
-- TOC entry 3420 (class 1259 OID 41136)
-- Name: consumo_examen2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX consumo_examen2_fk ON public.consumo_examen USING btree (id_examen);


--
-- TOC entry 3421 (class 1259 OID 41137)
-- Name: consumo_examen_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX consumo_examen_fk ON public.consumo_examen USING btree (codigo_insumo);


--
-- TOC entry 3422 (class 1259 OID 41135)
-- Name: consumo_examen_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX consumo_examen_pk ON public.consumo_examen USING btree (id_examen, codigo_insumo);


--
-- TOC entry 3548 (class 1259 OID 41401)
-- Name: contiene_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contiene_fk ON public.solicitud_examen_detalle USING btree (id_solicitud);


--
-- TOC entry 3559 (class 1259 OID 41422)
-- Name: controla_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX controla_fk ON public.stock_inventario USING btree (codigo_almacen_g);


--
-- TOC entry 3448 (class 1259 OID 41191)
-- Name: depende_de_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX depende_de_fk ON public.disponibilidad_examen_lab USING btree (id_examen);


--
-- TOC entry 3493 (class 1259 OID 41292)
-- Name: descuenta_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX descuenta_fk ON public.movimiento_inventario USING btree (id_despacho_insumo);


--
-- TOC entry 3425 (class 1259 OID 41146)
-- Name: despacho_insumo2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX despacho_insumo2_fk ON public.despacho_insumo USING btree (id_despacho_insumo);


--
-- TOC entry 3426 (class 1259 OID 41147)
-- Name: despacho_insumo_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX despacho_insumo_fk ON public.despacho_insumo USING btree (codigo_lab);


--
-- TOC entry 3427 (class 1259 OID 41145)
-- Name: despacho_insumo_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX despacho_insumo_pk ON public.despacho_insumo USING btree (id_despacho_insumo, codigo_lab);


--
-- TOC entry 3549 (class 1259 OID 41402)
-- Name: detalla_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX detalla_fk ON public.solicitud_examen_detalle USING btree (id_examen);


--
-- TOC entry 3430 (class 1259 OID 41153)
-- Name: detalle_despacho_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX detalle_despacho_pk ON public.detalle_despacho USING btree (id_despacho_insumo);


--
-- TOC entry 3433 (class 1259 OID 41161)
-- Name: detalle_solicitud_insumo_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX detalle_solicitud_insumo_pk ON public.detalle_solicitud_insumo USING btree (id_detalle_ins);


--
-- TOC entry 3437 (class 1259 OID 41170)
-- Name: devoluciones_insumo_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX devoluciones_insumo_pk ON public.devoluciones_insumo USING btree (id_devolucion);


--
-- TOC entry 3438 (class 1259 OID 41172)
-- Name: devolver_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX devolver_fk ON public.devoluciones_insumo USING btree (id_movimiebto_ins);


--
-- TOC entry 3442 (class 1259 OID 41180)
-- Name: direccion_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX direccion_pk ON public.direccion USING btree (id_direccion);


--
-- TOC entry 3449 (class 1259 OID 41190)
-- Name: disponibilidad_examen_lab_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX disponibilidad_examen_lab_pk ON public.disponibilidad_examen_lab USING btree (id_disponibilida);


--
-- TOC entry 3483 (class 1259 OID 41270)
-- Name: ejerce_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ejerce_fk ON public.medico USING btree (codigo_esp);


--
-- TOC entry 3453 (class 1259 OID 41201)
-- Name: empleado_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX empleado_pk ON public.empleado USING btree (cedula, codigo_empleado);


--
-- TOC entry 3458 (class 1259 OID 41209)
-- Name: especialidad_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX especialidad_pk ON public.especialidad USING btree (codigo_esp);


--
-- TOC entry 3415 (class 1259 OID 41126)
-- Name: establece_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX establece_fk ON public.configuracion_max_min USING btree (codigo_lab);


--
-- TOC entry 3461 (class 1259 OID 41217)
-- Name: estado_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX estado_pk ON public.estado USING btree (id_estado);


--
-- TOC entry 3465 (class 1259 OID 41225)
-- Name: examen_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX examen_pk ON public.examen USING btree (id_examen);


--
-- TOC entry 3479 (class 1259 OID 41261)
-- Name: existe_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX existe_fk ON public.lote USING btree (codigo_insumo);


--
-- TOC entry 3403 (class 1259 OID 41099)
-- Name: gestiona_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX gestiona_fk ON public.auditoria USING btree (usuario);


--
-- TOC entry 3468 (class 1259 OID 41234)
-- Name: grupos_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX grupos_pk ON public.grupos USING btree (codigo_grupo);


--
-- TOC entry 3450 (class 1259 OID 41192)
-- Name: identifica_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX identifica_fk ON public.disponibilidad_examen_lab USING btree (codigo_lab);


--
-- TOC entry 3416 (class 1259 OID 41127)
-- Name: implementa_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX implementa_fk ON public.configuracion_max_min USING btree (codigo_almacen_g);


--
-- TOC entry 3472 (class 1259 OID 41242)
-- Name: insumo_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX insumo_pk ON public.insumo USING btree (codigo_insumo);


--
-- TOC entry 3475 (class 1259 OID 41251)
-- Name: laboratorio_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX laboratorio_pk ON public.laboratorio USING btree (codigo_lab);


--
-- TOC entry 3480 (class 1259 OID 41260)
-- Name: lote_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX lote_pk ON public.lote USING btree (id_lote);


--
-- TOC entry 3417 (class 1259 OID 41125)
-- Name: max_min_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX max_min_fk ON public.configuracion_max_min USING btree (codigo_insumo);


--
-- TOC entry 3484 (class 1259 OID 41269)
-- Name: medico_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX medico_pk ON public.medico USING btree (cedula, codigo_mpps);


--
-- TOC entry 3488 (class 1259 OID 41279)
-- Name: movimiento_insumo_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX movimiento_insumo_pk ON public.movimiento_insumo USING btree (id_movimiebto_ins);


--
-- TOC entry 3494 (class 1259 OID 41289)
-- Name: movimiento_inventario_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX movimiento_inventario_pk ON public.movimiento_inventario USING btree (id_moviento_inv);


--
-- TOC entry 3499 (class 1259 OID 41299)
-- Name: movimiento_lotes2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX movimiento_lotes2_fk ON public.movimiento_lotes USING btree (id_lote);


--
-- TOC entry 3500 (class 1259 OID 41300)
-- Name: movimiento_lotes_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX movimiento_lotes_fk ON public.movimiento_lotes USING btree (id_moviento_inv);


--
-- TOC entry 3501 (class 1259 OID 41298)
-- Name: movimiento_lotes_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX movimiento_lotes_pk ON public.movimiento_lotes USING btree (id_moviento_inv, id_lote);


--
-- TOC entry 3505 (class 1259 OID 41308)
-- Name: municipio_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX municipio_pk ON public.municipio USING btree (id_municipio);


--
-- TOC entry 3543 (class 1259 OID 41391)
-- Name: obtiene_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX obtiene_fk ON public.solicitud_examen USING btree (cedula, id_paciente2);


--
-- TOC entry 3544 (class 1259 OID 41390)
-- Name: ordena_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ordena_fk ON public.solicitud_examen USING btree (med_cedula, codigo_mpps);


--
-- TOC entry 3508 (class 1259 OID 41317)
-- Name: paciente_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX paciente_pk ON public.paciente USING btree (cedula, id_paciente2);


--
-- TOC entry 3512 (class 1259 OID 41326)
-- Name: parametros_examen_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX parametros_examen_pk ON public.parametros_examen USING btree (id_parametros);


--
-- TOC entry 3515 (class 1259 OID 41334)
-- Name: parroquia_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX parroquia_pk ON public.parroquia USING btree (id_parroquia);


--
-- TOC entry 3519 (class 1259 OID 41342)
-- Name: permiso2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX permiso2_fk ON public.permiso USING btree (usuario);


--
-- TOC entry 3520 (class 1259 OID 41343)
-- Name: permiso3_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX permiso3_fk ON public.permiso USING btree (codigo_grupo);


--
-- TOC entry 3521 (class 1259 OID 41344)
-- Name: permiso_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX permiso_fk ON public.permiso USING btree (codigo_caso);


--
-- TOC entry 3522 (class 1259 OID 41341)
-- Name: permiso_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX permiso_pk ON public.permiso USING btree (usuario, codigo_grupo, codigo_caso);


--
-- TOC entry 3525 (class 1259 OID 41352)
-- Name: persona_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX persona_pk ON public.persona USING btree (cedula);


--
-- TOC entry 3434 (class 1259 OID 41162)
-- Name: pertenece_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX pertenece_fk ON public.detalle_solicitud_insumo USING btree (codigo_insumo);


--
-- TOC entry 3528 (class 1259 OID 41359)
-- Name: pertenese2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX pertenese2_fk ON public.pertenese USING btree (codigo_grupo);


--
-- TOC entry 3529 (class 1259 OID 41360)
-- Name: pertenese_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX pertenese_fk ON public.pertenese USING btree (usuario);


--
-- TOC entry 3530 (class 1259 OID 41358)
-- Name: pertenese_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX pertenese_pk ON public.pertenese USING btree (usuario, codigo_grupo);


--
-- TOC entry 3441 (class 1259 OID 41171)
-- Name: puede_generar_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX puede_generar_fk ON public.devoluciones_insumo USING btree (id_detalle);


--
-- TOC entry 3497 (class 1259 OID 41290)
-- Name: reduce_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX reduce_fk ON public.movimiento_inventario USING btree (id_inventario);


--
-- TOC entry 3535 (class 1259 OID 41371)
-- Name: registra_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX registra_fk ON public.resultado_examen USING btree (usuario);


--
-- TOC entry 3406 (class 1259 OID 41098)
-- Name: relacionado_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX relacionado_fk ON public.auditoria USING btree (codigo_caso);


--
-- TOC entry 3536 (class 1259 OID 41372)
-- Name: representa_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX representa_fk ON public.resultado_examen USING btree (id_parametros);


--
-- TOC entry 3498 (class 1259 OID 41291)
-- Name: requiere_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX requiere_fk ON public.movimiento_inventario USING btree (id_detalle_ins);


--
-- TOC entry 3537 (class 1259 OID 41370)
-- Name: resultado_examen_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX resultado_examen_pk ON public.resultado_examen USING btree (id_resultado);


--
-- TOC entry 3518 (class 1259 OID 41335)
-- Name: se_divide_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX se_divide_fk ON public.parroquia USING btree (id_municipio);


--
-- TOC entry 3413 (class 1259 OID 41116)
-- Name: se_encuentra_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX se_encuentra_fk ON public.comunidad USING btree (id_parroquia);


--
-- TOC entry 3540 (class 1259 OID 41381)
-- Name: se_ubica_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX se_ubica_fk ON public.sector USING btree (id_comunidad);


--
-- TOC entry 3541 (class 1259 OID 41380)
-- Name: sector_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX sector_pk ON public.sector USING btree (id_sector);


--
-- TOC entry 3445 (class 1259 OID 41182)
-- Name: senala_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX senala_fk ON public.direccion USING btree (id_sector);


--
-- TOC entry 3487 (class 1259 OID 41271)
-- Name: sera2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sera2_fk ON public.medico USING btree (cedula);


--
-- TOC entry 3456 (class 1259 OID 41203)
-- Name: sera3_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sera3_fk ON public.empleado USING btree (cedula);


--
-- TOC entry 3511 (class 1259 OID 41318)
-- Name: sera_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sera_fk ON public.paciente USING btree (cedula);


--
-- TOC entry 3491 (class 1259 OID 41281)
-- Name: solicita_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solicita_fk ON public.movimiento_insumo USING btree (id_detalle);


--
-- TOC entry 3552 (class 1259 OID 41400)
-- Name: solicitud_examen_detalle_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX solicitud_examen_detalle_pk ON public.solicitud_examen_detalle USING btree (id_detalle);


--
-- TOC entry 3547 (class 1259 OID 41389)
-- Name: solicitud_examen_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX solicitud_examen_pk ON public.solicitud_examen USING btree (id_solicitud);


--
-- TOC entry 3555 (class 1259 OID 41411)
-- Name: solicitud_insumos2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solicitud_insumos2_fk ON public.solicitud_insumos USING btree (codigo_lab);


--
-- TOC entry 3556 (class 1259 OID 41412)
-- Name: solicitud_insumos3_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solicitud_insumos3_fk ON public.solicitud_insumos USING btree (codigo_almacen_g);


--
-- TOC entry 3557 (class 1259 OID 41413)
-- Name: solicitud_insumos_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solicitud_insumos_fk ON public.solicitud_insumos USING btree (id_detalle_ins);


--
-- TOC entry 3558 (class 1259 OID 41410)
-- Name: solicitud_insumos_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX solicitud_insumos_pk ON public.solicitud_insumos USING btree (codigo_lab, codigo_almacen_g, id_detalle_ins);


--
-- TOC entry 3562 (class 1259 OID 41421)
-- Name: stock_inventario_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX stock_inventario_pk ON public.stock_inventario USING btree (id_inventario);


--
-- TOC entry 3492 (class 1259 OID 41280)
-- Name: supervisa_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX supervisa_fk ON public.movimiento_insumo USING btree (codigo_lab);


--
-- TOC entry 3446 (class 1259 OID 41181)
-- Name: tiene_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX tiene_fk ON public.direccion USING btree (cedula);


--
-- TOC entry 3565 (class 1259 OID 41430)
-- Name: tipo_examenes_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX tipo_examenes_pk ON public.tipo_examenes USING btree (id_tipo);


--
-- TOC entry 3568 (class 1259 OID 41436)
-- Name: tipo_insumo_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX tipo_insumo_pk ON public.tipo_insumo USING btree (codigo_tipo);


--
-- TOC entry 3478 (class 1259 OID 41252)
-- Name: trabaja_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX trabaja_fk ON public.laboratorio USING btree (usuario);


--
-- TOC entry 3457 (class 1259 OID 41202)
-- Name: usa_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX usa_fk ON public.empleado USING btree (usuario);


--
-- TOC entry 3571 (class 1259 OID 41442)
-- Name: usuario_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX usuario_pk ON public.usuario USING btree (usuario);


--
-- TOC entry 3572 (class 2606 OID 41443)
-- Name: almacen_g fk_almacen__atiende_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.almacen_g
    ADD CONSTRAINT fk_almacen__atiende_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3573 (class 2606 OID 41448)
-- Name: auditoria fk_auditori_gestiona_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT fk_auditori_gestiona_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3574 (class 2606 OID 41453)
-- Name: auditoria fk_auditori_relaciona_caso_uso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT fk_auditori_relaciona_caso_uso FOREIGN KEY (codigo_caso) REFERENCES public.caso_uso(codigo_caso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3575 (class 2606 OID 41458)
-- Name: comunidad fk_comunida_se_encuen_parroqui; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comunidad
    ADD CONSTRAINT fk_comunida_se_encuen_parroqui FOREIGN KEY (id_parroquia) REFERENCES public.parroquia(id_parroquia) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3576 (class 2606 OID 41463)
-- Name: configuracion_max_min fk_configur_establece_laborato; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.configuracion_max_min
    ADD CONSTRAINT fk_configur_establece_laborato FOREIGN KEY (codigo_lab) REFERENCES public.laboratorio(codigo_lab) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3577 (class 2606 OID 41468)
-- Name: configuracion_max_min fk_configur_implement_almacen_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.configuracion_max_min
    ADD CONSTRAINT fk_configur_implement_almacen_ FOREIGN KEY (codigo_almacen_g) REFERENCES public.almacen_g(codigo_almacen_g) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3578 (class 2606 OID 41473)
-- Name: configuracion_max_min fk_configur_max_min_insumo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.configuracion_max_min
    ADD CONSTRAINT fk_configur_max_min_insumo FOREIGN KEY (codigo_insumo) REFERENCES public.insumo(codigo_insumo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3579 (class 2606 OID 41483)
-- Name: consumo_examen fk_consumo__consumo_e_examen; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consumo_examen
    ADD CONSTRAINT fk_consumo__consumo_e_examen FOREIGN KEY (id_examen) REFERENCES public.examen(id_examen) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3580 (class 2606 OID 41478)
-- Name: consumo_examen fk_consumo__consumo_e_insumo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consumo_examen
    ADD CONSTRAINT fk_consumo__consumo_e_insumo FOREIGN KEY (codigo_insumo) REFERENCES public.insumo(codigo_insumo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3581 (class 2606 OID 41493)
-- Name: despacho_insumo fk_despacho_despacho__detalle_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_insumo
    ADD CONSTRAINT fk_despacho_despacho__detalle_ FOREIGN KEY (id_despacho_insumo) REFERENCES public.detalle_despacho(id_despacho_insumo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3582 (class 2606 OID 41488)
-- Name: despacho_insumo fk_despacho_despacho__laborato; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_insumo
    ADD CONSTRAINT fk_despacho_despacho__laborato FOREIGN KEY (codigo_lab) REFERENCES public.laboratorio(codigo_lab) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3583 (class 2606 OID 41498)
-- Name: detalle_solicitud_insumo fk_detalle__pertenece_insumo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_solicitud_insumo
    ADD CONSTRAINT fk_detalle__pertenece_insumo FOREIGN KEY (codigo_insumo) REFERENCES public.insumo(codigo_insumo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3584 (class 2606 OID 41503)
-- Name: devoluciones_insumo fk_devoluci_devolver_movimien; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.devoluciones_insumo
    ADD CONSTRAINT fk_devoluci_devolver_movimien FOREIGN KEY (id_movimiebto_ins) REFERENCES public.movimiento_insumo(id_movimiebto_ins) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3585 (class 2606 OID 41508)
-- Name: devoluciones_insumo fk_devoluci_puede_gen_solicitu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.devoluciones_insumo
    ADD CONSTRAINT fk_devoluci_puede_gen_solicitu FOREIGN KEY (id_detalle) REFERENCES public.solicitud_examen_detalle(id_detalle) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3586 (class 2606 OID 41513)
-- Name: direccion fk_direccio_senala_sector; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.direccion
    ADD CONSTRAINT fk_direccio_senala_sector FOREIGN KEY (id_sector) REFERENCES public.sector(id_sector) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3587 (class 2606 OID 41518)
-- Name: direccion fk_direccio_tiene_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.direccion
    ADD CONSTRAINT fk_direccio_tiene_persona FOREIGN KEY (cedula) REFERENCES public.persona(cedula) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3588 (class 2606 OID 41523)
-- Name: disponibilidad_examen_lab fk_disponib_chequea_solicitu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.disponibilidad_examen_lab
    ADD CONSTRAINT fk_disponib_chequea_solicitu FOREIGN KEY (id_detalle) REFERENCES public.solicitud_examen_detalle(id_detalle) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3589 (class 2606 OID 41528)
-- Name: disponibilidad_examen_lab fk_disponib_depende_d_examen; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.disponibilidad_examen_lab
    ADD CONSTRAINT fk_disponib_depende_d_examen FOREIGN KEY (id_examen) REFERENCES public.examen(id_examen) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3590 (class 2606 OID 41533)
-- Name: disponibilidad_examen_lab fk_disponib_identific_laborato; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.disponibilidad_examen_lab
    ADD CONSTRAINT fk_disponib_identific_laborato FOREIGN KEY (codigo_lab) REFERENCES public.laboratorio(codigo_lab) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3591 (class 2606 OID 41538)
-- Name: empleado fk_empleado_sera3_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empleado
    ADD CONSTRAINT fk_empleado_sera3_persona FOREIGN KEY (cedula) REFERENCES public.persona(cedula) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3592 (class 2606 OID 41543)
-- Name: empleado fk_empleado_usa_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empleado
    ADD CONSTRAINT fk_empleado_usa_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3593 (class 2606 OID 41548)
-- Name: examen fk_examen_clasifica_tipo_exa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.examen
    ADD CONSTRAINT fk_examen_clasifica_tipo_exa FOREIGN KEY (id_tipo) REFERENCES public.tipo_examenes(id_tipo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3594 (class 2606 OID 41553)
-- Name: insumo fk_insumo_asociado_tipo_ins; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.insumo
    ADD CONSTRAINT fk_insumo_asociado_tipo_ins FOREIGN KEY (codigo_tipo) REFERENCES public.tipo_insumo(codigo_tipo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3595 (class 2606 OID 41558)
-- Name: laboratorio fk_laborato_trabaja_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laboratorio
    ADD CONSTRAINT fk_laborato_trabaja_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3596 (class 2606 OID 41563)
-- Name: lote fk_lote_existe_insumo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lote
    ADD CONSTRAINT fk_lote_existe_insumo FOREIGN KEY (codigo_insumo) REFERENCES public.insumo(codigo_insumo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3597 (class 2606 OID 41568)
-- Name: medico fk_medico_ejerce_especial; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.medico
    ADD CONSTRAINT fk_medico_ejerce_especial FOREIGN KEY (codigo_esp) REFERENCES public.especialidad(codigo_esp) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3598 (class 2606 OID 41573)
-- Name: medico fk_medico_sera2_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.medico
    ADD CONSTRAINT fk_medico_sera2_persona FOREIGN KEY (cedula) REFERENCES public.persona(cedula) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3601 (class 2606 OID 41588)
-- Name: movimiento_inventario fk_movimien_descuenta_detalle_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_inventario
    ADD CONSTRAINT fk_movimien_descuenta_detalle_ FOREIGN KEY (id_despacho_insumo) REFERENCES public.detalle_despacho(id_despacho_insumo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3604 (class 2606 OID 41608)
-- Name: movimiento_lotes fk_movimien_movimient_lote; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_lotes
    ADD CONSTRAINT fk_movimien_movimient_lote FOREIGN KEY (id_lote) REFERENCES public.lote(id_lote) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3605 (class 2606 OID 41603)
-- Name: movimiento_lotes fk_movimien_movimient_movimien; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_lotes
    ADD CONSTRAINT fk_movimien_movimient_movimien FOREIGN KEY (id_moviento_inv) REFERENCES public.movimiento_inventario(id_moviento_inv) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3602 (class 2606 OID 41593)
-- Name: movimiento_inventario fk_movimien_reduce_stock_in; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_inventario
    ADD CONSTRAINT fk_movimien_reduce_stock_in FOREIGN KEY (id_inventario) REFERENCES public.stock_inventario(id_inventario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3603 (class 2606 OID 41598)
-- Name: movimiento_inventario fk_movimien_requiere_detalle_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_inventario
    ADD CONSTRAINT fk_movimien_requiere_detalle_ FOREIGN KEY (id_detalle_ins) REFERENCES public.detalle_solicitud_insumo(id_detalle_ins) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3599 (class 2606 OID 41578)
-- Name: movimiento_insumo fk_movimien_solicita_solicitu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_insumo
    ADD CONSTRAINT fk_movimien_solicita_solicitu FOREIGN KEY (id_detalle) REFERENCES public.solicitud_examen_detalle(id_detalle) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3600 (class 2606 OID 41583)
-- Name: movimiento_insumo fk_movimien_supervisa_laborato; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.movimiento_insumo
    ADD CONSTRAINT fk_movimien_supervisa_laborato FOREIGN KEY (codigo_lab) REFERENCES public.laboratorio(codigo_lab) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3606 (class 2606 OID 41613)
-- Name: municipio fk_municipi_conforma_estado; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.municipio
    ADD CONSTRAINT fk_municipi_conforma_estado FOREIGN KEY (id_estado) REFERENCES public.estado(id_estado) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3607 (class 2606 OID 41618)
-- Name: paciente fk_paciente_sera_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paciente
    ADD CONSTRAINT fk_paciente_sera_persona FOREIGN KEY (cedula) REFERENCES public.persona(cedula) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3608 (class 2606 OID 41623)
-- Name: parroquia fk_parroqui_se_divide_municipi; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parroquia
    ADD CONSTRAINT fk_parroqui_se_divide_municipi FOREIGN KEY (id_municipio) REFERENCES public.municipio(id_municipio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3609 (class 2606 OID 41633)
-- Name: permiso fk_permiso_permiso2_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT fk_permiso_permiso2_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3610 (class 2606 OID 41638)
-- Name: permiso fk_permiso_permiso3_grupos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT fk_permiso_permiso3_grupos FOREIGN KEY (codigo_grupo) REFERENCES public.grupos(codigo_grupo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3611 (class 2606 OID 41628)
-- Name: permiso fk_permiso_permiso_caso_uso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT fk_permiso_permiso_caso_uso FOREIGN KEY (codigo_caso) REFERENCES public.caso_uso(codigo_caso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3612 (class 2606 OID 41648)
-- Name: pertenese fk_pertenes_pertenese_grupos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pertenese
    ADD CONSTRAINT fk_pertenes_pertenese_grupos FOREIGN KEY (codigo_grupo) REFERENCES public.grupos(codigo_grupo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3613 (class 2606 OID 41643)
-- Name: pertenese fk_pertenes_pertenese_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pertenese
    ADD CONSTRAINT fk_pertenes_pertenese_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3614 (class 2606 OID 41653)
-- Name: resultado_examen fk_resultad_registra_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultado_examen
    ADD CONSTRAINT fk_resultad_registra_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3615 (class 2606 OID 41658)
-- Name: resultado_examen fk_resultad_represent_parametr; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultado_examen
    ADD CONSTRAINT fk_resultad_represent_parametr FOREIGN KEY (id_parametros) REFERENCES public.parametros_examen(id_parametros) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3616 (class 2606 OID 41663)
-- Name: sector fk_sector_se_ubica_comunida; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sector
    ADD CONSTRAINT fk_sector_se_ubica_comunida FOREIGN KEY (id_comunidad) REFERENCES public.comunidad(id_comunidad) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3617 (class 2606 OID 41668)
-- Name: solicitud_examen fk_solicitu_anota_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen
    ADD CONSTRAINT fk_solicitu_anota_usuario FOREIGN KEY (usuario) REFERENCES public.usuario(usuario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3620 (class 2606 OID 41683)
-- Name: solicitud_examen_detalle fk_solicitu_contiene_solicitu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen_detalle
    ADD CONSTRAINT fk_solicitu_contiene_solicitu FOREIGN KEY (id_solicitud) REFERENCES public.solicitud_examen(id_solicitud) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3621 (class 2606 OID 41688)
-- Name: solicitud_examen_detalle fk_solicitu_detalla_examen; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen_detalle
    ADD CONSTRAINT fk_solicitu_detalla_examen FOREIGN KEY (id_examen) REFERENCES public.examen(id_examen) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3618 (class 2606 OID 41673)
-- Name: solicitud_examen fk_solicitu_obtiene_paciente; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen
    ADD CONSTRAINT fk_solicitu_obtiene_paciente FOREIGN KEY (cedula, id_paciente2) REFERENCES public.paciente(cedula, id_paciente2) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3619 (class 2606 OID 41678)
-- Name: solicitud_examen fk_solicitu_ordena_medico; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_examen
    ADD CONSTRAINT fk_solicitu_ordena_medico FOREIGN KEY (med_cedula, codigo_mpps) REFERENCES public.medico(cedula, codigo_mpps) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3622 (class 2606 OID 41703)
-- Name: solicitud_insumos fk_solicitu_solicitud_almacen_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_insumos
    ADD CONSTRAINT fk_solicitu_solicitud_almacen_ FOREIGN KEY (codigo_almacen_g) REFERENCES public.almacen_g(codigo_almacen_g) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3623 (class 2606 OID 41693)
-- Name: solicitud_insumos fk_solicitu_solicitud_detalle_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_insumos
    ADD CONSTRAINT fk_solicitu_solicitud_detalle_ FOREIGN KEY (id_detalle_ins) REFERENCES public.detalle_solicitud_insumo(id_detalle_ins) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3624 (class 2606 OID 41698)
-- Name: solicitud_insumos fk_solicitu_solicitud_laborato; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solicitud_insumos
    ADD CONSTRAINT fk_solicitu_solicitud_laborato FOREIGN KEY (codigo_lab) REFERENCES public.laboratorio(codigo_lab) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3625 (class 2606 OID 41708)
-- Name: stock_inventario fk_stock_in_controla_almacen_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stock_inventario
    ADD CONSTRAINT fk_stock_in_controla_almacen_ FOREIGN KEY (codigo_almacen_g) REFERENCES public.almacen_g(codigo_almacen_g) ON UPDATE RESTRICT ON DELETE RESTRICT;


-- Completed on 2025-04-03 09:50:38 -04

--
-- PostgreSQL database dump complete
--

