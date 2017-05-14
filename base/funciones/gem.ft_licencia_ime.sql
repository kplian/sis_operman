CREATE OR REPLACE FUNCTION "gem"."ft_licencia_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Mantenimiento Industrial
 FUNCION: 		gem.ft_licencia_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'gem.tlicencia'
 AUTOR: 		 (admin)
 FECHA:	        17-04-2017 03:18:41
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_licencia	integer;
			    
BEGIN

    v_nombre_funcion = 'gem.ft_licencia_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'GM_GEMLIC_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		17-04-2017 03:18:41
	***********************************/

	if(p_transaccion='GM_GEMLIC_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into gem.tlicencia(
			calificacion_curso,
			id_conductor,
			nro_licencia,
			tipo,
			fecha_exp,
			fecha_autoriz,
			estado_reg,
			fecha_curso,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.calificacion_curso,
			v_parametros.id_conductor,
			v_parametros.nro_licencia,
			v_parametros.tipo,
			v_parametros.fecha_exp,
			v_parametros.fecha_autoriz,
			'activo',
			v_parametros.fecha_curso,
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_licencia into v_id_licencia;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Licencia almacenado(a) con exito (id_licencia'||v_id_licencia||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_licencia',v_id_licencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'GM_GEMLIC_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		17-04-2017 03:18:41
	***********************************/

	elsif(p_transaccion='GM_GEMLIC_MOD')then

		begin
			--Sentencia de la modificacion
			update gem.tlicencia set
			calificacion_curso = v_parametros.calificacion_curso,
			id_conductor = v_parametros.id_conductor,
			nro_licencia = v_parametros.nro_licencia,
			tipo = v_parametros.tipo,
			fecha_exp = v_parametros.fecha_exp,
			fecha_autoriz = v_parametros.fecha_autoriz,
			fecha_curso = v_parametros.fecha_curso,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_licencia=v_parametros.id_licencia;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Licencia modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_licencia',v_parametros.id_licencia::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'GM_GEMLIC_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		17-04-2017 03:18:41
	***********************************/

	elsif(p_transaccion='GM_GEMLIC_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from gem.tlicencia
            where id_licencia=v_parametros.id_licencia;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Licencia eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_licencia',v_parametros.id_licencia::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "gem"."ft_licencia_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
