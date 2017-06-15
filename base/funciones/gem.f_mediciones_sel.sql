CREATE OR REPLACE FUNCTION gem.f_mediciones_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
SISTEMA: SISTEMA DE GESTION DE MANTENIMIENTO
FUNCION: gem.f_mediciones_sel
DESCRIPCION: Devuelve las mediciones en fila de las uni_cons a partir de un punto de localizacion
AUTOR: RCM
FECHA: 15/06/2017
COMENTARIOS:
***************************************************************************
HISTORIAL DE MODIFICACIONES:

DESCRIPCION:
AUTOR:
FECHA:
***************************************************************************/

DECLARE

    v_consulta varchar;
    v_parametros record;
    v_nombre_funcion text;
    v_resp varchar;
    v_ids varchar;
    v_cols varchar;

BEGIN

    v_nombre_funcion = 'gem.f_mediciones_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
    #TRANSACCION: 'GEM_MEDFIL_SEL'
    #DESCRIPCION: Consulta de datos
    #AUTOR: RCM
    #FECHA: 15/06/2017
    ***********************************/

  if(p_transaccion='GEM_MEDFIL_SEL')then
     
    begin
     
        --Verificar existencia del id
        /*if not exists(select 1 from gem.tlocalizacion
                    where id_localizacion = v_parametros.id) then
            raise exception 'No se encuentran registros';
        end if;*/

        --Verifica si se envio id_uni_cons que prevalece sobre el id_localizacion
        if v_parametros.tipo = 'uc' then

          v_ids = v_parametros.id_uni_cons;
          --Obtencion de los tipos de variables registrados

          v_cols = gem.f_mediciones_get_cols(v_parametros.id_uni_cons, v_parametros.fecha_ini,v_parametros.fecha_fin,v_parametros.tipo,v_parametros.solo_un_registro);
          raise notice 'QQ: %',v_cols;
          --Sentencia de la consulta

          v_consulta:='select
                      *
                      from gem.f_mediciones_en_fila(
                      '''||v_parametros.fecha_ini||''',
                      '''||v_parametros.fecha_fin||''',
                      '''||v_ids||''',
                      '||v_parametros.id_uni_cons||',
                      '''||v_parametros.tipo||''',
                      '''||v_parametros.solo_un_registro||''') 
                      as (id_uni_cons integer, codigo varchar, descripcion varchar, fecha_medicion date,hora time';
                      
          if v_cols = '' then
            v_consulta = v_consulta||') where ';
          else
            v_consulta = v_consulta||','||v_cols||') where ';
          end if;

        else
          --Obtencion recursiva de ids
          v_ids = gem.f_get_id_localizaciones(v_parametros.id_localizacion);
          --Obtencion de los tipos de variables registrados
          v_cols = gem.f_mediciones_get_cols(v_parametros.id_localizacion, v_parametros.fecha_ini,v_parametros.fecha_fin,v_parametros.tipo,v_parametros.solo_un_registro);
          
          --Sentencia de la consulta
          v_consulta:='select
                      *
                      from gem.f_mediciones_en_fila(
                      '''||v_parametros.fecha_ini||''',
                      '''||v_parametros.fecha_fin||''',
                      '''||v_ids||''',
                      '||v_parametros.id_localizacion||',
                      '''||v_parametros.tipo||''',
                      '''||v_parametros.solo_un_registro||''') 
                      as (id_uni_cons integer, codigo varchar, descripcion varchar, fecha_medicion date,hora time';
          
          if v_cols = '' then
            v_consulta = v_consulta||') where ';
          else
            v_consulta = v_consulta||','||v_cols||') where ';
          end if;
          
        end if;

        

        --Definicion de la respuesta
        v_consulta:=v_consulta||v_parametros.filtro;
        v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

        --Devuelve la respuesta
        return v_consulta;

    end;

    /*********************************
    #TRANSACCION: 'GEM_MEDFIL_CONT'
    #DESCRIPCION: Conteo de registros
    #AUTOR: RCM
    #FECHA: 15/06/2017
    ***********************************/

    elsif(p_transaccion='GEM_MEDFIL_CONT')then

        begin

          --Verificar existencia del id
          /*if not exists(select 1 from gem.tlocalizacion
                      where id_localizacion = v_parametros.id) then
              raise exception 'No se encuentran registros';
          end if;*/

          --Verifica si se envio id_uni_cons que prevalece sobre el id_localizacion
          if v_parametros.tipo = 'uc' then
            v_ids = v_parametros.id_uni_cons;
            --Obtencion de los tipos de variables registrados
            v_cols = gem.f_mediciones_get_cols(v_parametros.id_uni_cons, v_parametros.fecha_ini,v_parametros.fecha_fin,v_parametros.tipo,v_parametros.solo_un_registro);
            --Sentencia de la consulta de conteo de registros
            v_consulta:='select count(1)
                      from gem.f_mediciones_en_fila(
                      '''||v_parametros.fecha_ini||''',
                      '''||v_parametros.fecha_fin||''',
                      '''||v_ids||''',
                      '||v_parametros.id_uni_cons||',
                      '''||v_parametros.tipo||''',
                      '''||v_parametros.solo_un_registro||''') 
                      as (id_uni_cons integer, codigo varchar, nombre varchar, fecha_medicion date,hora time';
                      
            if v_cols = '' then
              v_consulta = v_consulta||') where ';
            else
              v_consulta = v_consulta||','||v_cols||') where ';
            end if;
            
          else
            --Obtencion recursiva de ids
            v_ids = gem.f_get_id_localizaciones(v_parametros.id_localizacion);
            --Obtencion de los tipos de variables registrados
            v_cols = gem.f_mediciones_get_cols(v_parametros.id_localizacion, v_parametros.fecha_ini,v_parametros.fecha_fin,v_parametros.tipo,v_parametros.solo_un_registro);
            raise notice 'tttt %',v_cols;
             --Sentencia de la consulta de conteo de registros
            v_consulta:='select count(1)
                      from gem.f_mediciones_en_fila(
                      '''||v_parametros.fecha_ini||''',
                      '''||v_parametros.fecha_fin||''',
                      '''||v_ids||''',
                      '||v_parametros.id_localizacion||',
                      '''||v_parametros.tipo||''',
                      '''||v_parametros.solo_un_registro||''') 
                      as (id_uni_cons integer, codigo varchar, nombre varchar, fecha_medicion date,hora time';
                      
            if v_cols = '' then
              v_consulta = v_consulta||') where ';
            else
              v_consulta = v_consulta||','||v_cols||') where ';
            end if;
          end if;

        
          --Definicion de la respuesta
          v_consulta:=v_consulta||v_parametros.filtro;
          RAISE NOTICE 'ww: %',v_consulta;

          --Devuelve la respuesta
          return v_consulta;

    end;


  else

    raise exception 'Transaccion inexistente';

  end if;

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