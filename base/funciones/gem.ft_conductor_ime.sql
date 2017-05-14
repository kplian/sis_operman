CREATE OR REPLACE FUNCTION "gem"."ft_conductor_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Mantenimiento Industrial
 FUNCION: 		gem.ft_conductor_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'gem.tconductor'
 AUTOR: 		 (admin)
 FECHA:	        16-04-2017 21:02:18
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
	v_id_conductor	integer;
			    
BEGIN

    v_nombre_funcion = 'gem.ft_conductor_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'GM_GEMCON_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-04-2017 21:02:18
	***********************************/

	if(p_transaccion='GM_GEMCON_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into gem.tconductor(
			codigo,
			estado_reg,
			id_empleado,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.codigo,
			'activo',
			v_parametros.id_empleado,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_conductor into v_id_conductor;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Conductores almacenado(a) con exito (id_conductor'||v_id_conductor||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conductor',v_id_conductor::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'GM_GEMCON_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-04-2017 21:02:18
	***********************************/

	elsif(p_transaccion='GM_GEMCON_MOD')then

		begin
			--Sentencia de la modificacion
			update gem.tconductor set
			codigo = v_parametros.codigo,
			id_empleado = v_parametros.id_empleado,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_conductor=v_parametros.id_conductor;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Conductores modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conductor',v_parametros.id_conductor::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'GM_GEMCON_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-04-2017 21:02:18
	***********************************/

	elsif(p_transaccion='GM_GEMCON_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from gem.tconductor
            where id_conductor=v_parametros.id_conductor;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Conductores eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conductor',v_parametros.id_conductor::varchar);
              
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
ALTER FUNCTION "gem"."ft_conductor_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
