CREATE OR REPLACE FUNCTION "gem"."ft_conductor_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Mantenimiento Industrial
 FUNCION: 		gem.ft_conductor_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'gem.tconductor'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'gem.ft_conductor_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'GM_GEMCON_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		16-04-2017 21:02:18
	***********************************/

	if(p_transaccion='GM_GEMCON_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						gemcon.id_conductor,
						gemcon.codigo,
						gemcon.estado_reg,
						gemcon.id_empleado,
						gemcon.id_usuario_ai,
						gemcon.id_usuario_reg,
						gemcon.fecha_reg,
						gemcon.usuario_ai,
						gemcon.id_usuario_mod,
						gemcon.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						fun.desc_funcionario1 as conductor
						from gem.tconductor gemcon
						inner join segu.tusuario usu1 on usu1.id_usuario = gemcon.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = gemcon.id_usuario_mod
						inner join orga.vfuncionario fun
						on fun.id_funcionario = gemcon.id_empleado
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'GM_GEMCON_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		16-04-2017 21:02:18
	***********************************/

	elsif(p_transaccion='GM_GEMCON_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_conductor)
					    from gem.tconductor gemcon
					    inner join segu.tusuario usu1 on usu1.id_usuario = gemcon.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = gemcon.id_usuario_mod
						inner join orga.vfuncionario fun
						on fun.id_funcionario = gemcon.id_empleado
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "gem"."ft_conductor_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
