CREATE OR REPLACE FUNCTION gem.f_mediciones_get_cols (
  p_id integer,--si el tipo es uc es id_uni_cons, caso contrario es id_localizacion
  p_fecha_ini date,
  p_fecha_fin date,
  p_tipo varchar, --loc o uc
  p_solo_un_registro varchar = 'no'::character varying
)
RETURNS varchar AS
$body$
/**************************************************************************
SISTEMA: SISTEMA DE GESTION DE MANTENIMIENTO
FUNCION: gem.f_mediciones_get_cols
DESCRIPCION: Devuelve las columnas (variables) de las mediciones en fila de las uni_cons a partir de un punto de localizacion
AUTOR: RCM
FECHA: 17/06/2017
COMENTARIOS:
***************************************************************************
HISTORIAL DE MODIFICACIONES:

DESCRIPCION:
AUTOR:
FECHA:
***************************************************************************/

DECLARE

    v_nombre_funcion text;
    v_resp varchar;
    v_ids varchar;
    v_sql varchar;
    v_cols varchar;
    v_rec record;

BEGIN

    v_nombre_funcion = 'gem.f_mediciones_get_cols';
    
    --Verificar existencia del id
    /*if not exists(select 1 from gem.tlocalizacion
                where id_localizacion = p_id) then
        raise exception 'No se encuentran registros';
    end if;*/

    if p_tipo = 'uc' then
        v_ids = p_id;
    else
        --Obtencion recursiva de ids
        v_ids = gem.f_get_id_localizaciones(p_id);
    end if;

    --Inicializacion
    v_cols='';
    
    --Obtencion de los tipos de variables registrados
    v_sql = 'select distinct
            tv.id_tipo_variable, tv.nombre
            from gem.tequipo_medicion em
            inner join gem.tequipo_variable ev
            on ev.id_equipo_variable = em.id_equipo_variable
            inner join gem.tuni_cons uc
            on uc.id_uni_cons = ev.id_uni_cons
            inner join gem.ttipo_variable tv
            on tv.id_tipo_variable = ev.id_tipo_variable
            where uc.id_localizacion in ('||v_ids||')';

    if p_tipo = 'uc' then
        v_sql = v_sql ||'uc.id_uni_cons = '||v_ids;
    else
        v_sql = v_sql ||'uc.id_localizacion in ('||v_ids||')';
    end if;

    if p_solo_un_registro = 'si' then
        v_sql = v_sql || ' and em.fecha_medicion <= '''||p_fecha_fin||'''';
    else
        v_sql = v_sql || ' and em.fecha_medicion between '''||p_fecha_ini||''' and '''||p_fecha_fin||'''';
    end if;

    for v_rec in execute(v_sql) loop
        v_cols = v_cols || lower(v_rec.nombre) || ' numeric'|| ',';
    end loop;

    --Verifica si no hay mediciones, y predefine a latitud y longitud
    if v_cols is null or v_cols '' then
        v_cols = longitud numeric, latitud numeric;
    else
        v_cols = substr(v_cols,0,length(v_cols));
    end if;
    
    
    
    --Finaliza la sentencia de creacion de tabla temporal
    return v_cols;

EXCEPTION
    
    WHEN OTHERS THEN
    v_resp='';
    v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
    v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
    v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
    raise exception '%',v_resp; 

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;