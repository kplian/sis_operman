CREATE OR REPLACE FUNCTION gem.f_mediciones_en_fila (
  p_fecha_ini date,
  p_fecha_fin date,
  p_ids_loc varchar,
  p_id integer,
  p_solo_un_registro varchar = 'no'::character varying
)
RETURNS SETOF record AS
$body$
DECLARE

    v_rec record;
    v_rec1 record;
    v_sql varchar;
    v_sql1 varchar;
    v_cols varchar;
    v_nombre_funcion text;
    v_resp varchar;
    v_tmp varchar;

BEGIN

    v_nombre_funcion = 'gem.f_mediciones_en_fila';
    
    --Variable para la creacion de la tabla temporal
    --  v_sql = 'create temp table tt_geodata (
    v_sql = 'id_uni_cons integer,
            codigo varchar,
            nombre varchar,
            fecha_medicion date,
            hora time,';
    
    --Obtencion de los tipos de variables registrados
    v_cols = gem.f_mediciones_get_cols(p_id, p_fecha_ini, p_fecha_fin,p_solo_un_registro);
    
    --Finaliza la sentencia de creacion de tabla temporal
    v_sql = v_sql || v_cols || ') on commit drop;';
    --Crea tabla para procesamiento
    v_tmp = 'create temp table tt_geodata ('||v_sql;
    execute(v_tmp);
    --Crea tabla de salida
    v_tmp = 'create temp table tt_geodata_resp ('||v_sql;
    execute(v_tmp);
    
    v_sql1 = 'select distinct
            tv.id_tipo_variable, tv.nombre
            from gem.tequipo_medicion em
            inner join gem.tequipo_variable ev
            on ev.id_equipo_variable = em.id_equipo_variable
            inner join gem.tuni_cons uc
            on uc.id_uni_cons = ev.id_uni_cons
            inner join gem.ttipo_variable tv
            on tv.id_tipo_variable = ev.id_tipo_variable
            where uc.id_localizacion in ('||p_ids_loc||')';

    if p_solo_un_registro = 'si' then
        v_sql1 = v_sql1 || ' and em.fecha_medicion <= '''||p_fecha_fin||'''';
    else
        v_sql1 = v_sql1 || ' and em.fecha_medicion between '''||p_fecha_ini||''' and '''||p_fecha_fin||'''';
    end if;
            

    --Obtencion de la geo data
    for v_rec in execute(v_sql1) loop

        v_sql = 'select
                em.fecha_medicion, em.hora, em.medicion, uc.id_uni_cons
                from gem.tequipo_medicion em
                inner join gem.tequipo_variable ev
                on ev.id_equipo_variable = em.id_equipo_variable
                inner join gem.tuni_cons uc
                on uc.id_uni_cons = ev.id_uni_cons
                where uc.id_localizacion in ('||p_ids_loc||')
                and ev.id_tipo_variable = '|| v_rec.id_tipo_variable;

        if p_solo_un_registro = 'si' then
            v_sql = v_sql || ' and em.fecha_medicion <= '''||p_fecha_fin||'''';
        else
            v_sql = v_sql || ' and em.fecha_medicion between '''||p_fecha_ini||''' and '''||p_fecha_fin||'''';
        end if;
                
                
        for v_rec1 in execute(v_sql) loop
            if exists (select 1 from tt_geodata
                        where id_uni_cons = v_rec1.id_uni_cons
                        and fecha_medicion = v_rec1.fecha_medicion
                        and hora = v_rec1.hora) then
                v_sql1 = 'update tt_geodata set '|| v_rec.nombre ||'='||v_rec1.medicion||' 
                where id_uni_cons = '||v_rec1.id_uni_cons||' and fecha_medicion ='''||v_rec1.fecha_medicion||''' and hora = '''||v_rec1.hora||'''';
                execute(v_sql1);
            else
                v_sql1 = 'insert into tt_geodata (id_uni_cons,fecha_medicion,hora,'||v_rec.nombre||')
                        values ('||v_rec1.id_uni_cons||','''||v_rec1.fecha_medicion||''','''||v_rec1.hora||''','||v_rec1.medicion||')';
                execute(v_sql1);
            end if;
        end loop;

    end loop;

    --Obtiene datos del uni_cons
    update tt_geodata set
    codigo = uc.codigo,
    nombre = uc.nombre
    from gem.tuni_cons uc
    where uc.id_uni_cons = tt_geodata.id_uni_cons;
    
    if p_solo_un_registro = 'si' then
    
        for v_rec1 in select id_uni_cons, max(hora) as hora, max(fecha_medicion) as fecha
                      from tt_geodata
                      group by id_uni_cons loop
        
            insert into tt_geodata_resp
            select * from tt_geodata
            where id_uni_cons = v_rec1.id_uni_cons and fecha_medicion = v_rec1.fecha
            and hora = v_rec1.hora;
        
        end loop;
        
        v_sql1 = 'select *
                from tt_geodata_resp';
    else
        v_sql1 = 'select * from tt_geodata';
    end if;
    
    for v_rec in execute(v_sql1)  loop
        return next v_rec;
    end loop;
    
    return;
    
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
COST 100 ROWS 1000;