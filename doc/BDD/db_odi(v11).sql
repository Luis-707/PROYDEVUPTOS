/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     08/12/2025 11:02:32 a.m.                     */
/*==============================================================*/


drop index AREA_OCUPACIONAL_PK;

drop table AREA_OCUPACIONAL;

drop index CARGOS_EVALUADORES_PK;

drop table CARGOS_EVALUADORES;

drop index TIENE_TIPO_FK;

drop index CARGOS_EVALUADOS_PK;

drop table CARGOS_EVALUADOS;

drop index CARGOS_SUPERVISORES_PK;

drop table CARGOS_SUPERVISORES;

drop index COMPETENCIAS_PK;

drop table COMPETENCIAS;

drop index CONTIENE2_FK;

drop index CONTIENE_FK;

drop index CONTIENE_PK;

drop table CONTIENE;

drop index AGRUPA_FK;

drop index CRITERIOS_PK;

drop table CRITERIOS;

drop index ASOCIA_FK;

drop index DESEMPENO_EXCEPCIONAL_PK;

drop table DESEMPENO_EXCEPCIONAL;

drop index TIENE_CRITERIO_FK;

drop index PERTENECE_FK;

drop index DETALLES_EVALUACION_OBREROS_PK;

drop table DETALLES_EVALUACION_OBREROS;

drop index TIENE_EVAL_ADMIN_FK;

drop index TIENE_FK;

drop index CREAR_FK;

drop index EVALUACION_ADMINISTRATIVOS_PK;

drop table EVALUACION_ADMINISTRATIVOS;

drop index ADQUIERE_FK;

drop index POSEE_FK;

drop index EVALUACION_COMPETENCIAS_PK;

drop table EVALUACION_COMPETENCIAS;

drop index IDENTIFICA_FK;

drop index OBTIENE_FK;

drop index EVALUACION_OBJETIVOS_PK;

drop table EVALUACION_OBJETIVOS;

drop index TIENE_EVAL_OBRERO_FK;

drop index INCLUYE_FK;

drop index REALIZA_FK;

drop index EVALUACION_OBREROS_PK;

drop table EVALUACION_OBREROS;

drop index SUPERVISA_FK;

drop index TIENE_CARGO_EVALUADOR_FK;

drop index PUEDE_EVALUAR_FK;

drop index EVALUADORES_PK;

drop table EVALUADORES;

drop index EVALUA_FK;

drop index PERTENECE_UF_FK;

drop index PERTENECE_AO_FK;

drop index TIENE_EVALUACION_FK;

drop index TIENE_CARGO_EVALUADO_FK;

drop index EVALUADOS_PK;

drop table EVALUADOS;

drop index FACTORES_PK;

drop table FACTORES;

drop index INDICADORES_PK;

drop table INDICADORES;

drop index REGISTRA_FK;

drop index AGREGA_FK;

drop index INDICADORES_EXTRA_PK;

drop table INDICADORES_EXTRA;

drop index TIENE_INDICADOR_FK;

drop index TIENE_MOTIVOS_FK;

drop index MOTIVOS_PK;

drop table MOTIVOS;

drop index OBJ_DESEMP_INDIV_PK;

drop table OBJETIVOS_DESEMPENO_INDIVIDUAL;

drop index PERMISOS_PK;

drop table PERMISOS;

drop index POSEE_PERMISOS2_FK;

drop index POSEE_PERMISOS_FK;

drop index POSEE_PERMISOS_PK;

drop table POSEE_PERMISOS;

drop index RANGOS_CALIFICACION_PK;

drop table RANGOS_CALIFICACION;

drop index RANGO_ACTUACION_PK;

drop table RANGO_ACTUACION;

drop index ROLES_SISTEMA_PK;

drop table ROLES_SISTEMA;

drop index TIENE_CARGO_SUPERV_FK;

drop index PUEDE_SUPERVISAR_FK;

drop index SUPERVISORES_PK;

drop table SUPERVISORES;

drop index TIPO_CARGO_PK;

drop table TIPO_CARGO;

drop index UBICACION_FISICA_PK;

drop table UBICACION_FISICA;

drop index POSEE_ROL_FK;

drop index USUARIOS_PK;

drop table USUARIOS;

/*==============================================================*/
/* Table: AREA_OCUPACIONAL                                      */
/*==============================================================*/
create table AREA_OCUPACIONAL (
   ID_AO                SERIAL               not null,
   NOMBRE_AO            VARCHAR(255)         not null,
   constraint PK_AREA_OCUPACIONAL primary key (ID_AO)
);

/*==============================================================*/
/* Index: AREA_OCUPACIONAL_PK                                   */
/*==============================================================*/
create unique index AREA_OCUPACIONAL_PK on AREA_OCUPACIONAL (
ID_AO
);

/*==============================================================*/
/* Table: CARGOS_EVALUADORES                                    */
/*==============================================================*/
create table CARGOS_EVALUADORES (
   ID_CARGO_EVALUADOR   SERIAL               not null,
   CARGO_EVALUADOR      VARCHAR(255)         not null,
   constraint PK_CARGOS_EVALUADORES primary key (ID_CARGO_EVALUADOR)
);

/*==============================================================*/
/* Index: CARGOS_EVALUADORES_PK                                 */
/*==============================================================*/
create unique index CARGOS_EVALUADORES_PK on CARGOS_EVALUADORES (
ID_CARGO_EVALUADOR
);

/*==============================================================*/
/* Table: CARGOS_EVALUADOS                                      */
/*==============================================================*/
create table CARGOS_EVALUADOS (
   ID_CARGO_EVALUADO    SERIAL               not null,
   ID_TIPO_CARGO        INT4                 not null,
   CARGO_EVALUADO       VARCHAR(255)         not null,
   constraint PK_CARGOS_EVALUADOS primary key (ID_CARGO_EVALUADO)
);

/*==============================================================*/
/* Index: CARGOS_EVALUADOS_PK                                   */
/*==============================================================*/
create unique index CARGOS_EVALUADOS_PK on CARGOS_EVALUADOS (
ID_CARGO_EVALUADO
);

/*==============================================================*/
/* Index: TIENE_TIPO_FK                                         */
/*==============================================================*/
create  index TIENE_TIPO_FK on CARGOS_EVALUADOS (
ID_TIPO_CARGO
);

/*==============================================================*/
/* Table: CARGOS_SUPERVISORES                                   */
/*==============================================================*/
create table CARGOS_SUPERVISORES (
   ID_CARGO_SUPERVISOR  SERIAL               not null,
   CARGO_SUPERVISOR     VARCHAR(255)         not null,
   constraint PK_CARGOS_SUPERVISORES primary key (ID_CARGO_SUPERVISOR)
);

/*==============================================================*/
/* Index: CARGOS_SUPERVISORES_PK                                */
/*==============================================================*/
create unique index CARGOS_SUPERVISORES_PK on CARGOS_SUPERVISORES (
ID_CARGO_SUPERVISOR
);

/*==============================================================*/
/* Table: COMPETENCIAS                                          */
/*==============================================================*/
create table COMPETENCIAS (
   ID_COMPETENCIA       SERIAL               not null,
   NOMBRE_COMPETENCIA   VARCHAR(255)         not null,
   PESO_COMPETENCIA     INT4                 not null,
   ESTADO_COMPETENCIA   VARCHAR(255)         not null,
   constraint PK_COMPETENCIAS primary key (ID_COMPETENCIA)
);

/*==============================================================*/
/* Index: COMPETENCIAS_PK                                       */
/*==============================================================*/
create unique index COMPETENCIAS_PK on COMPETENCIAS (
ID_COMPETENCIA
);

/*==============================================================*/
/* Table: CONTIENE                                              */
/*==============================================================*/
create table CONTIENE (
   ID_EVAL_ADMIN        INT4                 not null,
   ID_ODI               INT4                 not null,
   constraint PK_CONTIENE primary key (ID_EVAL_ADMIN, ID_ODI)
);

/*==============================================================*/
/* Index: CONTIENE_PK                                           */
/*==============================================================*/
create unique index CONTIENE_PK on CONTIENE (
ID_EVAL_ADMIN,
ID_ODI
);

/*==============================================================*/
/* Index: CONTIENE_FK                                           */
/*==============================================================*/
create  index CONTIENE_FK on CONTIENE (
ID_EVAL_ADMIN
);

/*==============================================================*/
/* Index: CONTIENE2_FK                                          */
/*==============================================================*/
create  index CONTIENE2_FK on CONTIENE (
ID_ODI
);

/*==============================================================*/
/* Table: CRITERIOS                                             */
/*==============================================================*/
create table CRITERIOS (
   CRITERIO_ID          SERIAL               not null,
   FACTOR_ID            INT4                 not null,
   CODIGO_CRITERIO      VARCHAR(3)           not null,
   DESCRIPCION_CRITERIO VARCHAR(255)         not null,
   constraint PK_CRITERIOS primary key (CRITERIO_ID)
);

/*==============================================================*/
/* Index: CRITERIOS_PK                                          */
/*==============================================================*/
create unique index CRITERIOS_PK on CRITERIOS (
CRITERIO_ID
);

/*==============================================================*/
/* Index: AGRUPA_FK                                             */
/*==============================================================*/
create  index AGRUPA_FK on CRITERIOS (
FACTOR_ID
);

/*==============================================================*/
/* Table: DESEMPENO_EXCEPCIONAL                                 */
/*==============================================================*/
create table DESEMPENO_EXCEPCIONAL (
   ID_DESEMP_EXCEPCIONAL SERIAL               not null,
   ID_EVAL_ADMIN        INT4                 not null,
   PERIODO              VARCHAR(255)         not null,
   FECHA                DATE                 not null,
   constraint PK_DESEMPENO_EXCEPCIONAL primary key (ID_DESEMP_EXCEPCIONAL)
);

/*==============================================================*/
/* Index: DESEMPENO_EXCEPCIONAL_PK                              */
/*==============================================================*/
create unique index DESEMPENO_EXCEPCIONAL_PK on DESEMPENO_EXCEPCIONAL (
ID_DESEMP_EXCEPCIONAL
);

/*==============================================================*/
/* Index: ASOCIA_FK                                             */
/*==============================================================*/
create  index ASOCIA_FK on DESEMPENO_EXCEPCIONAL (
ID_EVAL_ADMIN
);

/*==============================================================*/
/* Table: DETALLES_EVALUACION_OBREROS                           */
/*==============================================================*/
create table DETALLES_EVALUACION_OBREROS (
   DETALLE_OBRERO_ID    SERIAL               not null,
   CRITERIO_ID          INT4                 not null,
   ID_EVAL_OBREROS      INT4                 not null,
   PUNTAJE_OBTENIDO     INT4                 not null,
   COMENTARIO_EVALUADOR VARCHAR(255)         null,
   constraint PK_DETALLES_EVALUACION_OBREROS primary key (DETALLE_OBRERO_ID)
);

/*==============================================================*/
/* Index: DETALLES_EVALUACION_OBREROS_PK                        */
/*==============================================================*/
create unique index DETALLES_EVALUACION_OBREROS_PK on DETALLES_EVALUACION_OBREROS (
DETALLE_OBRERO_ID
);

/*==============================================================*/
/* Index: PERTENECE_FK                                          */
/*==============================================================*/
create  index PERTENECE_FK on DETALLES_EVALUACION_OBREROS (
ID_EVAL_OBREROS
);

/*==============================================================*/
/* Index: TIENE_CRITERIO_FK                                     */
/*==============================================================*/
create  index TIENE_CRITERIO_FK on DETALLES_EVALUACION_OBREROS (
CRITERIO_ID
);

/*==============================================================*/
/* Table: EVALUACION_ADMINISTRATIVOS                            */
/*==============================================================*/
create table EVALUACION_ADMINISTRATIVOS (
   ID_EVAL_ADMIN        SERIAL               not null,
   ID_USUARIO           INT4                 not null,
   ID_EVALUADO          INT4                 not null,
   ID_RANGO             INT4                 null,
   PERIODO_EVALUADO     VARCHAR(255)         not null,
   FECHA_INICIO         DATE                 not null,
   FECHA_CIERRE         DATE                 not null,
   COMENTARIO_SUPERVISOR VARCHAR(255)         null,
   COMENTARIO_EVALUADO  VARCHAR(255)         null,
   PUNTAJE_FINAL        INT4                 null,
   CONFORMIDAD          VARCHAR(255)         null,
   ESTADO_EVAL_ADMIN    VARCHAR(255)         null,
   constraint PK_EVALUACION_ADMINISTRATIVOS primary key (ID_EVAL_ADMIN)
);

/*==============================================================*/
/* Index: EVALUACION_ADMINISTRATIVOS_PK                         */
/*==============================================================*/
create unique index EVALUACION_ADMINISTRATIVOS_PK on EVALUACION_ADMINISTRATIVOS (
ID_EVAL_ADMIN
);

/*==============================================================*/
/* Index: CREAR_FK                                              */
/*==============================================================*/
create  index CREAR_FK on EVALUACION_ADMINISTRATIVOS (
ID_USUARIO
);

/*==============================================================*/
/* Index: TIENE_FK                                              */
/*==============================================================*/
create  index TIENE_FK on EVALUACION_ADMINISTRATIVOS (
ID_RANGO
);

/*==============================================================*/
/* Index: TIENE_EVAL_ADMIN_FK                                   */
/*==============================================================*/
create  index TIENE_EVAL_ADMIN_FK on EVALUACION_ADMINISTRATIVOS (
ID_EVALUADO
);

/*==============================================================*/
/* Table: EVALUACION_COMPETENCIAS                               */
/*==============================================================*/
create table EVALUACION_COMPETENCIAS (
   ID_COMP_RESULT       SERIAL               not null,
   ID_EVAL_ADMIN        INT4                 not null,
   ID_COMPETENCIA       INT4                 not null,
   RANGO_COMP           INT4                 not null,
   PESOXRANGO_COMP      INT4                 not null,
   constraint PK_EVALUACION_COMPETENCIAS primary key (ID_COMP_RESULT)
);

/*==============================================================*/
/* Index: EVALUACION_COMPETENCIAS_PK                            */
/*==============================================================*/
create unique index EVALUACION_COMPETENCIAS_PK on EVALUACION_COMPETENCIAS (
ID_COMP_RESULT
);

/*==============================================================*/
/* Index: POSEE_FK                                              */
/*==============================================================*/
create  index POSEE_FK on EVALUACION_COMPETENCIAS (
ID_EVAL_ADMIN
);

/*==============================================================*/
/* Index: ADQUIERE_FK                                           */
/*==============================================================*/
create  index ADQUIERE_FK on EVALUACION_COMPETENCIAS (
ID_COMPETENCIA
);

/*==============================================================*/
/* Table: EVALUACION_OBJETIVOS                                  */
/*==============================================================*/
create table EVALUACION_OBJETIVOS (
   ID_OBJ_RESULT        SERIAL               not null,
   ID_ODI               INT4                 not null,
   ID_EVAL_ADMIN        INT4                 not null,
   RANGO_OBJ            INT4                 not null,
   PESOXRANGO_OBJ       INT4                 not null,
   constraint PK_EVALUACION_OBJETIVOS primary key (ID_OBJ_RESULT)
);

/*==============================================================*/
/* Index: EVALUACION_OBJETIVOS_PK                               */
/*==============================================================*/
create unique index EVALUACION_OBJETIVOS_PK on EVALUACION_OBJETIVOS (
ID_OBJ_RESULT
);

/*==============================================================*/
/* Index: OBTIENE_FK                                            */
/*==============================================================*/
create  index OBTIENE_FK on EVALUACION_OBJETIVOS (
ID_ODI
);

/*==============================================================*/
/* Index: IDENTIFICA_FK                                         */
/*==============================================================*/
create  index IDENTIFICA_FK on EVALUACION_OBJETIVOS (
ID_EVAL_ADMIN
);

/*==============================================================*/
/* Table: EVALUACION_OBREROS                                    */
/*==============================================================*/
create table EVALUACION_OBREROS (
   ID_EVAL_OBREROS      SERIAL               not null,
   ID_EVALUADO          INT4                 not null,
   RANGO_ID             INT4                 not null,
   ID_USUARIO           INT4                 not null,
   PERIODO_EVALUACION   VARCHAR(255)         not null,
   FECHA_INICIO         DATE                 not null,
   FECHA_CIERRE         DATE                 not null,
   PUNTAJE_TOTAL        INT4                 null,
   COMENTARIO_EVALUADO  VARCHAR(255)         null,
   constraint PK_EVALUACION_OBREROS primary key (ID_EVAL_OBREROS)
);

/*==============================================================*/
/* Index: EVALUACION_OBREROS_PK                                 */
/*==============================================================*/
create unique index EVALUACION_OBREROS_PK on EVALUACION_OBREROS (
ID_EVAL_OBREROS
);

/*==============================================================*/
/* Index: REALIZA_FK                                            */
/*==============================================================*/
create  index REALIZA_FK on EVALUACION_OBREROS (
ID_USUARIO
);

/*==============================================================*/
/* Index: INCLUYE_FK                                            */
/*==============================================================*/
create  index INCLUYE_FK on EVALUACION_OBREROS (
RANGO_ID
);

/*==============================================================*/
/* Index: TIENE_EVAL_OBRERO_FK                                  */
/*==============================================================*/
create  index TIENE_EVAL_OBRERO_FK on EVALUACION_OBREROS (
ID_EVALUADO
);

/*==============================================================*/
/* Table: EVALUADORES                                           */
/*==============================================================*/
create table EVALUADORES (
   ID_EVALUADOR         SERIAL               not null,
   ID_USUARIO           INT4                 not null,
   ID_CARGO_EVALUADOR   INT4                 not null,
   ID_SUPERVISOR        INT4                 not null,
   constraint PK_EVALUADORES primary key (ID_EVALUADOR)
);

/*==============================================================*/
/* Index: EVALUADORES_PK                                        */
/*==============================================================*/
create unique index EVALUADORES_PK on EVALUADORES (
ID_EVALUADOR
);

/*==============================================================*/
/* Index: PUEDE_EVALUAR_FK                                      */
/*==============================================================*/
create  index PUEDE_EVALUAR_FK on EVALUADORES (
ID_USUARIO
);

/*==============================================================*/
/* Index: TIENE_CARGO_EVALUADOR_FK                              */
/*==============================================================*/
create  index TIENE_CARGO_EVALUADOR_FK on EVALUADORES (
ID_CARGO_EVALUADOR
);

/*==============================================================*/
/* Index: SUPERVISA_FK                                          */
/*==============================================================*/
create  index SUPERVISA_FK on EVALUADORES (
ID_SUPERVISOR
);

/*==============================================================*/
/* Table: EVALUADOS                                             */
/*==============================================================*/
create table EVALUADOS (
   ID_EVALUADO          SERIAL               not null,
   ID_AO                INT4                 null,
   ID_UF                INT4                 null,
   ID_CARGO_EVALUADO    INT4                 not null,
   ID_USUARIO           INT4                 not null,
   ID_EVALUADOR         INT4                 not null,
   constraint PK_EVALUADOS primary key (ID_EVALUADO)
);

/*==============================================================*/
/* Index: EVALUADOS_PK                                          */
/*==============================================================*/
create unique index EVALUADOS_PK on EVALUADOS (
ID_EVALUADO
);

/*==============================================================*/
/* Index: TIENE_CARGO_EVALUADO_FK                               */
/*==============================================================*/
create  index TIENE_CARGO_EVALUADO_FK on EVALUADOS (
ID_CARGO_EVALUADO
);

/*==============================================================*/
/* Index: TIENE_EVALUACION_FK                                   */
/*==============================================================*/
create  index TIENE_EVALUACION_FK on EVALUADOS (
ID_USUARIO
);

/*==============================================================*/
/* Index: PERTENECE_AO_FK                                       */
/*==============================================================*/
create  index PERTENECE_AO_FK on EVALUADOS (
ID_AO
);

/*==============================================================*/
/* Index: PERTENECE_UF_FK                                       */
/*==============================================================*/
create  index PERTENECE_UF_FK on EVALUADOS (
ID_UF
);

/*==============================================================*/
/* Index: EVALUA_FK                                             */
/*==============================================================*/
create  index EVALUA_FK on EVALUADOS (
ID_EVALUADOR
);

/*==============================================================*/
/* Table: FACTORES                                              */
/*==============================================================*/
create table FACTORES (
   FACTOR_ID            SERIAL               not null,
   NOMBRE_FACTOR        VARCHAR(255)         not null,
   DESCRIPCION_FACTOR   VARCHAR(255)         not null,
   constraint PK_FACTORES primary key (FACTOR_ID)
);

/*==============================================================*/
/* Index: FACTORES_PK                                           */
/*==============================================================*/
create unique index FACTORES_PK on FACTORES (
FACTOR_ID
);

/*==============================================================*/
/* Table: INDICADORES                                           */
/*==============================================================*/
create table INDICADORES (
   INDICADOR_ID         SERIAL               not null,
   INDICADOR            VARCHAR(255)         not null,
   constraint PK_INDICADORES primary key (INDICADOR_ID)
);

/*==============================================================*/
/* Index: INDICADORES_PK                                        */
/*==============================================================*/
create unique index INDICADORES_PK on INDICADORES (
INDICADOR_ID
);

/*==============================================================*/
/* Table: INDICADORES_EXTRA                                     */
/*==============================================================*/
create table INDICADORES_EXTRA (
   ID_INDIC_EXTRA       SERIAL               not null,
   ID_USUARIO           INT4                 not null,
   ID_DESEMP_EXCEPCIONAL INT4                 not null,
   DESCRIPCION          VARCHAR(100)         not null,
   constraint PK_INDICADORES_EXTRA primary key (ID_INDIC_EXTRA)
);

/*==============================================================*/
/* Index: INDICADORES_EXTRA_PK                                  */
/*==============================================================*/
create unique index INDICADORES_EXTRA_PK on INDICADORES_EXTRA (
ID_INDIC_EXTRA
);

/*==============================================================*/
/* Index: AGREGA_FK                                             */
/*==============================================================*/
create  index AGREGA_FK on INDICADORES_EXTRA (
ID_DESEMP_EXCEPCIONAL
);

/*==============================================================*/
/* Index: REGISTRA_FK                                           */
/*==============================================================*/
create  index REGISTRA_FK on INDICADORES_EXTRA (
ID_USUARIO
);

/*==============================================================*/
/* Table: MOTIVOS                                               */
/*==============================================================*/
create table MOTIVOS (
   MOTIVO_ID            SERIAL               not null,
   INDICADOR_ID         INT4                 not null,
   ID_DESEMP_EXCEPCIONAL INT4                 not null,
   MOTIVO               VARCHAR(255)         not null,
   constraint PK_MOTIVOS primary key (MOTIVO_ID)
);

/*==============================================================*/
/* Index: MOTIVOS_PK                                            */
/*==============================================================*/
create unique index MOTIVOS_PK on MOTIVOS (
MOTIVO_ID
);

/*==============================================================*/
/* Index: TIENE_MOTIVOS_FK                                      */
/*==============================================================*/
create  index TIENE_MOTIVOS_FK on MOTIVOS (
ID_DESEMP_EXCEPCIONAL
);

/*==============================================================*/
/* Index: TIENE_INDICADOR_FK                                    */
/*==============================================================*/
create  index TIENE_INDICADOR_FK on MOTIVOS (
INDICADOR_ID
);

/*==============================================================*/
/* Table: OBJETIVOS_DESEMPENO_INDIVIDUAL                        */
/*==============================================================*/
create table OBJETIVOS_DESEMPENO_INDIVIDUAL (
   ID_ODI               SERIAL               not null,
   NOMBRE_OBJETIVO      VARCHAR(255)         not null,
   PESO_OBJETIVO        INT4                 not null,
   constraint PK_OBJETIVOS_DESEMPENO_INDIVID primary key (ID_ODI)
);

/*==============================================================*/
/* Index: OBJ_DESEMP_INDIV_PK                                   */
/*==============================================================*/
create unique index OBJ_DESEMP_INDIV_PK on OBJETIVOS_DESEMPENO_INDIVIDUAL (
ID_ODI
);

/*==============================================================*/
/* Table: PERMISOS                                              */
/*==============================================================*/
create table PERMISOS (
   PERMISOS_ID          SERIAL               not null,
   NOMBRE_PERMISO       VARCHAR(255)         not null,
   constraint PK_PERMISOS primary key (PERMISOS_ID)
);

/*==============================================================*/
/* Index: PERMISOS_PK                                           */
/*==============================================================*/
create unique index PERMISOS_PK on PERMISOS (
PERMISOS_ID
);

/*==============================================================*/
/* Table: POSEE_PERMISOS                                        */
/*==============================================================*/
create table POSEE_PERMISOS (
   ID_USUARIO           INT4                 not null,
   PERMISOS_ID          INT4                 not null,
   constraint PK_POSEE_PERMISOS primary key (ID_USUARIO, PERMISOS_ID)
);

/*==============================================================*/
/* Index: POSEE_PERMISOS_PK                                     */
/*==============================================================*/
create unique index POSEE_PERMISOS_PK on POSEE_PERMISOS (
ID_USUARIO,
PERMISOS_ID
);

/*==============================================================*/
/* Index: POSEE_PERMISOS_FK                                     */
/*==============================================================*/
create  index POSEE_PERMISOS_FK on POSEE_PERMISOS (
ID_USUARIO
);

/*==============================================================*/
/* Index: POSEE_PERMISOS2_FK                                    */
/*==============================================================*/
create  index POSEE_PERMISOS2_FK on POSEE_PERMISOS (
PERMISOS_ID
);

/*==============================================================*/
/* Table: RANGOS_CALIFICACION                                   */
/*==============================================================*/
create table RANGOS_CALIFICACION (
   RANGO_ID             SERIAL               not null,
   NOMBRE_RANGO         VARCHAR(255)         not null,
   PUNTAJE_MIN          INT4                 not null,
   PUNTAJE_MAX          INT4                 not null,
   constraint PK_RANGOS_CALIFICACION primary key (RANGO_ID)
);

/*==============================================================*/
/* Index: RANGOS_CALIFICACION_PK                                */
/*==============================================================*/
create unique index RANGOS_CALIFICACION_PK on RANGOS_CALIFICACION (
RANGO_ID
);

/*==============================================================*/
/* Table: RANGO_ACTUACION                                       */
/*==============================================================*/
create table RANGO_ACTUACION (
   ID_RANGO             SERIAL               not null,
   RANGO_ACTUACION      VARCHAR(255)         not null,
   PUNTAJE_MINIMO       INT4                 not null,
   PUNTAJE_MAXIMO       INT4                 not null,
   constraint PK_RANGO_ACTUACION primary key (ID_RANGO)
);

/*==============================================================*/
/* Index: RANGO_ACTUACION_PK                                    */
/*==============================================================*/
create unique index RANGO_ACTUACION_PK on RANGO_ACTUACION (
ID_RANGO
);

/*==============================================================*/
/* Table: ROLES_SISTEMA                                         */
/*==============================================================*/
create table ROLES_SISTEMA (
   ROL_ID               SERIAL               not null,
   ROL                  VARCHAR(255)         not null,
   constraint PK_ROLES_SISTEMA primary key (ROL_ID)
);

/*==============================================================*/
/* Index: ROLES_SISTEMA_PK                                      */
/*==============================================================*/
create unique index ROLES_SISTEMA_PK on ROLES_SISTEMA (
ROL_ID
);

/*==============================================================*/
/* Table: SUPERVISORES                                          */
/*==============================================================*/
create table SUPERVISORES (
   ID_SUPERVISOR        SERIAL               not null,
   ID_USUARIO           INT4                 not null,
   ID_CARGO_SUPERVISOR  INT4                 not null,
   constraint PK_SUPERVISORES primary key (ID_SUPERVISOR)
);

/*==============================================================*/
/* Index: SUPERVISORES_PK                                       */
/*==============================================================*/
create unique index SUPERVISORES_PK on SUPERVISORES (
ID_SUPERVISOR
);

/*==============================================================*/
/* Index: PUEDE_SUPERVISAR_FK                                   */
/*==============================================================*/
create  index PUEDE_SUPERVISAR_FK on SUPERVISORES (
ID_USUARIO
);

/*==============================================================*/
/* Index: TIENE_CARGO_SUPERV_FK                                 */
/*==============================================================*/
create  index TIENE_CARGO_SUPERV_FK on SUPERVISORES (
ID_CARGO_SUPERVISOR
);

/*==============================================================*/
/* Table: TIPO_CARGO                                            */
/*==============================================================*/
create table TIPO_CARGO (
   ID_TIPO_CARGO        SERIAL               not null,
   TIPO_CARGO           VARCHAR(255)         not null,
   constraint PK_TIPO_CARGO primary key (ID_TIPO_CARGO)
);

/*==============================================================*/
/* Index: TIPO_CARGO_PK                                         */
/*==============================================================*/
create unique index TIPO_CARGO_PK on TIPO_CARGO (
ID_TIPO_CARGO
);

/*==============================================================*/
/* Table: UBICACION_FISICA                                      */
/*==============================================================*/
create table UBICACION_FISICA (
   ID_UF                SERIAL               not null,
   NOMBRE_UF            VARCHAR(255)         not null,
   constraint PK_UBICACION_FISICA primary key (ID_UF)
);

/*==============================================================*/
/* Index: UBICACION_FISICA_PK                                   */
/*==============================================================*/
create unique index UBICACION_FISICA_PK on UBICACION_FISICA (
ID_UF
);

/*==============================================================*/
/* Table: USUARIOS                                              */
/*==============================================================*/
create table USUARIOS (
   ID_USUARIO           SERIAL               not null,
   ROL_ID               INT4                 not null,
   CLAVE                VARCHAR(255)         not null,
   CEDULA_USUARIO       INT4                 not null,
   NOMBRE_COMPLETO      VARCHAR(255)         not null,
   UBICACION_ADMINISTRATIVA VARCHAR(255)         not null,
   TIPO_EMPLEADO        VARCHAR(255)         not null,
   ESTADO_USUARIO       VARCHAR(255)         not null,
   constraint PK_USUARIOS primary key (ID_USUARIO)
);

/*==============================================================*/
/* Index: USUARIOS_PK                                           */
/*==============================================================*/
create unique index USUARIOS_PK on USUARIOS (
ID_USUARIO
);

/*==============================================================*/
/* Index: POSEE_ROL_FK                                          */
/*==============================================================*/
create  index POSEE_ROL_FK on USUARIOS (
ROL_ID
);

alter table CARGOS_EVALUADOS
   add constraint FK_CARGOS_E_TIENE_TIP_TIPO_CAR foreign key (ID_TIPO_CARGO)
      references TIPO_CARGO (ID_TIPO_CARGO)
      on delete restrict on update restrict;

alter table CONTIENE
   add constraint FK_CONTIENE_CONTIENE_EVALUACI foreign key (ID_EVAL_ADMIN)
      references EVALUACION_ADMINISTRATIVOS (ID_EVAL_ADMIN)
      on delete restrict on update restrict;

alter table CONTIENE
   add constraint FK_CONTIENE_CONTIENE2_OBJETIVO foreign key (ID_ODI)
      references OBJETIVOS_DESEMPENO_INDIVIDUAL (ID_ODI)
      on delete restrict on update restrict;

alter table CRITERIOS
   add constraint FK_CRITERIO_AGRUPA_FACTORES foreign key (FACTOR_ID)
      references FACTORES (FACTOR_ID)
      on delete restrict on update restrict;

alter table DESEMPENO_EXCEPCIONAL
   add constraint FK_DESEMPEN_ASOCIA_EVALUACI foreign key (ID_EVAL_ADMIN)
      references EVALUACION_ADMINISTRATIVOS (ID_EVAL_ADMIN)
      on delete restrict on update restrict;

alter table DETALLES_EVALUACION_OBREROS
   add constraint FK_DETALLES_PERTENECE_EVALUACI foreign key (ID_EVAL_OBREROS)
      references EVALUACION_OBREROS (ID_EVAL_OBREROS)
      on delete restrict on update restrict;

alter table DETALLES_EVALUACION_OBREROS
   add constraint FK_DETALLES_TIENE_CRI_CRITERIO foreign key (CRITERIO_ID)
      references CRITERIOS (CRITERIO_ID)
      on delete restrict on update restrict;

alter table EVALUACION_ADMINISTRATIVOS
   add constraint FK_EVALUACI_CREAR_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table EVALUACION_ADMINISTRATIVOS
   add constraint FK_EVALUACI_TIENE_RANGO_AC foreign key (ID_RANGO)
      references RANGO_ACTUACION (ID_RANGO)
      on delete restrict on update restrict;

alter table EVALUACION_ADMINISTRATIVOS
   add constraint FK_EVALUACI_TIENE_EVA_EVALUADO foreign key (ID_EVALUADO)
      references EVALUADOS (ID_EVALUADO)
      on delete restrict on update restrict;

alter table EVALUACION_COMPETENCIAS
   add constraint FK_EVALUACI_ADQUIERE_COMPETEN foreign key (ID_COMPETENCIA)
      references COMPETENCIAS (ID_COMPETENCIA)
      on delete restrict on update restrict;

alter table EVALUACION_COMPETENCIAS
   add constraint FK_EVALUACI_POSEE_EVALUACI foreign key (ID_EVAL_ADMIN)
      references EVALUACION_ADMINISTRATIVOS (ID_EVAL_ADMIN)
      on delete restrict on update restrict;

alter table EVALUACION_OBJETIVOS
   add constraint FK_EVALUACI_IDENTIFIC_EVALUACI foreign key (ID_EVAL_ADMIN)
      references EVALUACION_ADMINISTRATIVOS (ID_EVAL_ADMIN)
      on delete restrict on update restrict;

alter table EVALUACION_OBJETIVOS
   add constraint FK_EVALUACI_OBTIENE_OBJETIVO foreign key (ID_ODI)
      references OBJETIVOS_DESEMPENO_INDIVIDUAL (ID_ODI)
      on delete restrict on update restrict;

alter table EVALUACION_OBREROS
   add constraint FK_EVALUACI_INCLUYE_RANGOS_C foreign key (RANGO_ID)
      references RANGOS_CALIFICACION (RANGO_ID)
      on delete restrict on update restrict;

alter table EVALUACION_OBREROS
   add constraint FK_EVALUACI_REALIZA_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table EVALUACION_OBREROS
   add constraint FK_EVALUACI_TIENE_EVA_EVALUADO foreign key (ID_EVALUADO)
      references EVALUADOS (ID_EVALUADO)
      on delete restrict on update restrict;

alter table EVALUADORES
   add constraint FK_EVALUADO_PUEDE_EVA_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table EVALUADORES
   add constraint FK_EVALUADO_SUPERVISA_SUPERVIS foreign key (ID_SUPERVISOR)
      references SUPERVISORES (ID_SUPERVISOR)
      on delete restrict on update restrict;

alter table EVALUADORES
   add constraint FK_EVALUADO_TIENE_CAR_CARGOS_E foreign key (ID_CARGO_EVALUADOR)
      references CARGOS_EVALUADORES (ID_CARGO_EVALUADOR)
      on delete restrict on update restrict;

alter table EVALUADOS
   add constraint FK_EVALUADO_EVALUA_EVALUADO foreign key (ID_EVALUADOR)
      references EVALUADORES (ID_EVALUADOR)
      on delete restrict on update restrict;

alter table EVALUADOS
   add constraint FK_EVALUADO_PERTENECE_AREA_OCU foreign key (ID_AO)
      references AREA_OCUPACIONAL (ID_AO)
      on delete restrict on update restrict;

alter table EVALUADOS
   add constraint FK_EVALUADO_PERTENECE_UBICACIO foreign key (ID_UF)
      references UBICACION_FISICA (ID_UF)
      on delete restrict on update restrict;

alter table EVALUADOS
   add constraint FK_EVALUADO_TIENE_CAR_CARGOS_E foreign key (ID_CARGO_EVALUADO)
      references CARGOS_EVALUADOS (ID_CARGO_EVALUADO)
      on delete restrict on update restrict;

alter table EVALUADOS
   add constraint FK_EVALUADO_TIENE_EVA_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table INDICADORES_EXTRA
   add constraint FK_INDICADO_AGREGA_DESEMPEN foreign key (ID_DESEMP_EXCEPCIONAL)
      references DESEMPENO_EXCEPCIONAL (ID_DESEMP_EXCEPCIONAL)
      on delete restrict on update restrict;

alter table INDICADORES_EXTRA
   add constraint FK_INDICADO_REGISTRA_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table MOTIVOS
   add constraint FK_MOTIVOS_TIENE_IND_INDICADO foreign key (INDICADOR_ID)
      references INDICADORES (INDICADOR_ID)
      on delete restrict on update restrict;

alter table MOTIVOS
   add constraint FK_MOTIVOS_TIENE_MOT_DESEMPEN foreign key (ID_DESEMP_EXCEPCIONAL)
      references DESEMPENO_EXCEPCIONAL (ID_DESEMP_EXCEPCIONAL)
      on delete restrict on update restrict;

alter table POSEE_PERMISOS
   add constraint FK_POSEE_PE_POSEE_PER_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table POSEE_PERMISOS
   add constraint FK_POSEE_PE_POSEE_PER_PERMISOS foreign key (PERMISOS_ID)
      references PERMISOS (PERMISOS_ID)
      on delete restrict on update restrict;

alter table SUPERVISORES
   add constraint FK_SUPERVIS_PUEDE_SUP_USUARIOS foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO)
      on delete restrict on update restrict;

alter table SUPERVISORES
   add constraint FK_SUPERVIS_TIENE_CAR_CARGOS_S foreign key (ID_CARGO_SUPERVISOR)
      references CARGOS_SUPERVISORES (ID_CARGO_SUPERVISOR)
      on delete restrict on update restrict;

alter table USUARIOS
   add constraint FK_USUARIOS_POSEE_ROL_ROLES_SI foreign key (ROL_ID)
      references ROLES_SISTEMA (ROL_ID)
      on delete restrict on update restrict;

/*Insertar datos en tablas claves para iniciar*/

INSERT INTO ROLES_SISTEMA (ROL) VALUES
('Administrador'),
('Supervisor del evaluador'),
('Evaluador'),
('Evaluado');

INSERT INTO USUARIOS (ROL_ID, CLAVE, CEDULA_USUARIO, NOMBRE_COMPLETO, UBICACION_ADMINISTRATIVA, TIPO_EMPLEADO, ESTADO_USUARIO)
VALUES (1, 'admin', 15550077, 'Janet Maican', 'Oficina de gestion humana', 'Administrativo', 'Activo');

INSERT INTO PERMISOS (NOMBRE_PERMISO) VALUES
('Gestion de Usuarios'),
('Gestion de Evaluadores'),
('Gestion de Supervisores'),
('Gestion de Evaluados'),
('Evaluaciones'),
('Evaluacion Administrativos'),
('Gestion de Objetivos'),
('Gestion de Competencias'),
('Cargos de Evaluados'),
('Reportes'),
('Comentarios');

INSERT INTO POSEE_PERMISOS (ID_USUARIO, PERMISOS_ID) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 10),
(1, 8);

INSERT INTO RANGO_ACTUACION (RANGO_ACTUACION, PUNTAJE_MINIMO, PUNTAJE_MAXIMO) VALUES
('Aun no ha sido evaluado', 0, 0),
('Actuacion muy por debajo de lo esperado', 1, 179),
('Actuacion por debajo de lo esperado', 180, 259),
('Actuacion dentro de lo esperado', 260, 339),
('Actuacion sobre lo esperado', 340, 419),
('Desempeño excepcional', 420, 500);

INSERT INTO TIPO_CARGO(TIPO_CARGO) VALUES
('Administrativo'),
('Obrero');

INSERT INTO CARGOS_EVALUADOS (ID_TIPO_CARGO, CARGO_EVALUADO) VALUES
(1, 'Secretario'),
(1, 'Contador'),
(1, 'Jefe de departamento de talento humano'),
(1, 'Administrativo-contratado');

INSERT INTO CARGOS_EVALUADORES (CARGO_EVALUADOR) VALUES
('Jefe de oficina de gestión humana'),
('Jefe de oficina de bienes nacionales'),
('Jefe de unidad de servicios generales'),
('Jefe de departamento de informática'),
('Jefe de unidad de mantenimiento y planta física');

INSERT INTO CARGOS_SUPERVISORES (CARGO_SUPERVISOR) VALUES
('Vicerrector académico'),
('Vicerrector territorial'),
('Coordinador de secretaria general');



