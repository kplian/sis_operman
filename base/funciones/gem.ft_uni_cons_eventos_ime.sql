CREATE OR REPLACE FUNCTION "gem"."ft_uni_cons_eventos_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Mantenimiento Industrial
 FUNCION: 		gem.ft_uni_cons_eventos_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'gem.tuni_cons_eventos'
 AUTOR: 		 (admin)
 FECHA:	        04-05-2017 02:46:39
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
	v_id	integer;
			    
BEGIN

    v_nombre_funcion = 'gem.ft_uni_cons_eventos_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'GM_UCOEVE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		04-05-2017 02:46:39
	***********************************/

	if(p_transaccion='GM_UCOEVE_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into gem.tuni_cons_eventos(
			id_equipo_medicion,
			tipo,
			atributos,
			estado_reg,
			geofenceid,
			servertime,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_equipo_medicion,
			v_parametros.tipo,
			v_parametros.atributos,
			'activo',
			v_parametros.geofenceid,
			v_parametros.servertime,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id into v_id;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Eventos almacenado(a) con exito (id'||v_id||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id',v_id::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'GM_UCOEVE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		04-05-2017 02:46:39
	***********************************/

	elsif(p_transaccion='GM_UCOEVE_MOD')then

		begin
			--Sentencia de la modificacion
			update gem.tuni_cons_eventos set
			id_equipo_medicion = v_parametros.id_equipo_medicion,
			tipo = v_parametros.tipo,
			atributos = v_parametros.atributos,
			geofenceid = v_parametros.geofenceid,
			servertime = v_parametros.servertime,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id=v_parametros.id;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Eventos modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id',v_parametros.id::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'GM_UCOEVE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		04-05-2017 02:46:39
	***********************************/

	elsif(p_transaccion='GM_UCOEVE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from gem.tuni_cons_eventos
            where id=v_parametros.id;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Eventos eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id',v_parametros.id::varchar);
              
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
ALTER FUNCTION "gem"."ft_uni_cons_eventos_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
