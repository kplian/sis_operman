<?php
/**
*@package pXP
*@file gen-ACTLocalizacion.php
*@author  (rac)
*@date 14-06-2012 03:46:45
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLocalizacion extends ACTbase{    
			
	function listarLocalizacion(){
		$this->objParam->defecto('ordenacion','id_localizacion');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);

			$this->res = $this->objReporte->generarReporteListado('FuncionesMantenimiento','listarLocalizacion');
		} else{
			
			$this->objFunc=$this->create('MODLocalizacion');
			$this->res=$this->objFunc->listarLocalizacion($this->objParam);

		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarLocalizacionArb(){

		
		//obtiene el parametro nodo enviado por la vista
		$node=$this->objParam->getParametro('node');

		$id_localizacion=$this->objParam->getParametro('id_localizacion');
		$tipo_nodo=$this->objParam->getParametro('tipo_nodo');
		//RCM: solo por requerimiento de YPFB, no mostrar vehiculos
		/*if($this->objParam->getParametro('vista')=='loc'){
			$this->objParam->addFiltro("loc.codigo != ''VEH'' ");
		} else if($this->objParam->getParametro('vista')=='vehiculos'){
			$this->objParam->addFiltro("loc.codigo = ''VEH'' ");
		}*/  
		
	
		if($tipo_nodo != 'uni_cons' && $tipo_nodo != 'uni_cons_f'){
			
					if($node=='id'){
						$this->objParam->addParametro('id_padre','%');
					}
					else {
						$this->objParam->addParametro('id_padre',$id_localizacion);
					}

					
		            
		            $this->objFunc=$this->create('MODLocalizacion');
					$this->res=$this->objFunc->listarLocalizacionArb($this->objParam);
					$this->res->setTipoRespuestaArbol();
					
					$arreglo=array();
					
					array_push($arreglo,array('nombre'=>'id','valor'=>'id_localizacion'));
					array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_localizacion_fk'));
					
					array_push($arreglo,array('nombre'=>'text','valor'=>'texto'));
					array_push($arreglo,array('nombre'=>'cls','valor'=>'nombre'));
					array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #codigo#</b><br> #nombre#'));
					
					
					$this->res->addNivelArbol('tipo_nodo','raiz',array('leaf'=>false,
																	'allowDelete'=>true,
																	'allowEdit'=>true,
					 												'cls'=>'folder',
					 												'tipo_nodo'=>'raiz',
					 												'icon'=>'../../../lib/imagenes/a_form.png'),
					 												$arreglo);
					 
					/*se ande un nivel al arbol incluyendo con tido de nivel carpeta con su arreglo de equivalencias
					  es importante que entre los resultados devueltos por la base exista la variable\
					  tipo_dato que tenga el valor en texto = 'hoja' */
					 														
			
					 $this->res->addNivelArbol('tipo_nodo','hijo',array(
																	'leaf'=>false,
																	'allowDelete'=>true,
																	'allowEdit'=>true,
					 												'tipo_nodo'=>'hijo',
					 												'icon'=>'../../../lib/imagenes/a_form.png'),
					 												$arreglo);
																	
																	
						/*Agregar unidades constructivas a la loccalizacion*/
					$arreglo=array();
						
					array_push($arreglo,array('nombre'=>'id','valor'=>'id_localizacion'));
					array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_localizacion_fk'));
					
					array_push($arreglo,array('nombre'=>'text','valores'=>'<b> [#codigo#] </b>- #nombre#'));
					array_push($arreglo,array('nombre'=>'cls','valor'=>'nombre'));
					array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #codigo#</b><br> #nombre#'));
					
					
					$this->res->addNivelArbol('tipo_nodo','uni_cons',array('leaf'=>false,
																	'allowDelete'=>false,
																	'allowEdit'=>false,
					 												'cls'=>'folder',
					 												
					 												'icon'=>'../../../lib/imagenes/otros/tuc.png'
					 												),
					 												$arreglo);
				
				   $this->res->addNivelArbol('tipo_nodo','uni_cons_f',array('leaf'=>false,
																	'allowDelete'=>false,
																	'allowEdit'=>false,
					 												'cls'=>'folder',
					 												
					 												'icon'=>'../../../lib/imagenes/otros/tucred.png'
					 												),
					 												$arreglo);
				
				
				//Se imprime el arbol en formato JSON
		       $this->res->imprimirRespuesta($this->res->generarJson());	 
																
		 	}
		else
			{
				//si el padre es una unidad contructiva busca las partes
				$id_uni_cons=$this->objParam->getParametro('id_uni_cons');
				$this->objParam->addParametro('id_padre',$id_uni_cons);
				$this->objParam->addParametro('tipo','uc');

				$this->objFunc=$this->create('MODUniCons');
				//echo 'fuck';exit;
				$this->res=$this->objFunc->listarUniCons($this->objParam);

				$this->res->setTipoRespuestaArbol();
				
				$arreglo=array();


				//$this->listarUniCons();
				//exit;
				
				array_push($arreglo,array('nombre'=>'id','valor'=>'id_uni_loc'));
				array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_unic_cons_padre'));
				//array_push($arreglo,array('nombre'=>'cls','valor'=>'descripcion'));
			
				
				
				
				$this->res->addNivelArbol('incluir_calgen','false',array(
															'leaf'=>true,
															'allowDelete'=>true,
															'allowEdit'=>true,
			 												'icon'=>'../../../lib/imagenes/otros/gear_red.png'),
			 												$arreglo);
				
				
				$this->res->addNivelArbol('incluir_calgen','true',array(
															'leaf'=>true,
															'allowDelete'=>true,
															'allowEdit'=>true,
			 												'icon'=>'../../../lib/imagenes/gear.png'),
			 												$arreglo);
				
				//Se imprime el arbol en formato JSON
			     $this->res->imprimirRespuesta($this->res->generarJson());	


			     /*

			     $this->objFunc=$this->create('MODLocalizacion');
					$this->res=$this->objFunc->listarLocalizacionArb($this->objParam);
					$this->res->setTipoRespuestaArbol();
					
					$arreglo=array();
					
					array_push($arreglo,array('nombre'=>'id','valor'=>'id_localizacion'));
					array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_localizacion_fk'));
					
					array_push($arreglo,array('nombre'=>'text','valor'=>'texto'));
					array_push($arreglo,array('nombre'=>'cls','valor'=>'nombre'));
					array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #codigo#</b><br> #nombre#'));
					
					
					$this->res->addNivelArbol('tipo_nodo','raiz',array('leaf'=>false,
																	'allowDelete'=>true,
																	'allowEdit'=>true,
					 												'cls'=>'folder',
					 												'tipo_nodo'=>'raiz',
					 												'icon'=>'../../../lib/imagenes/a_form.png'),
					 												$arreglo);
					 
																			
			
					 $this->res->addNivelArbol('tipo_nodo','hijo',array(
																	'leaf'=>false,
																	'allowDelete'=>true,
																	'allowEdit'=>true,
					 												'tipo_nodo'=>'hijo',
					 												'icon'=>'../../../lib/imagenes/a_form.png'),
					 												$arreglo);
																	
																	
						//Agregar unidades constructivas a la loccalizacion
					$arreglo=array();
						
					array_push($arreglo,array('nombre'=>'id','valor'=>'id_localizacion'));
					array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_localizacion_fk'));
					
					array_push($arreglo,array('nombre'=>'text','valores'=>'<b> [#codigo#] </b>- #nombre#'));
					array_push($arreglo,array('nombre'=>'cls','valor'=>'nombre'));
					array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #codigo#</b><br> #nombre#'));
					
					
					$this->res->addNivelArbol('tipo_nodo','uni_cons',array('leaf'=>false,
																	'allowDelete'=>false,
																	'allowEdit'=>false,
					 												'cls'=>'folder',
					 												
					 												'icon'=>'../../../lib/imagenes/otros/tuc.png'
					 												),
					 												$arreglo);
				
				   $this->res->addNivelArbol('tipo_nodo','uni_cons_f',array('leaf'=>false,
																	'allowDelete'=>false,
																	'allowEdit'=>false,
					 												'cls'=>'folder',
					 												
					 												'icon'=>'../../../lib/imagenes/otros/tucred.png'
					 												),
					 												$arreglo);
				
				
				//Se imprime el arbol en formato JSON
		       $this->res->imprimirRespuesta($this->res->generarJson());	 */
				
			}														
			

		
	}
	
				
	function insertarLocalizacion(){

		 $this->objFunc=$this->create('MODLocalizacion');

		if($this->objParam->insertar('id_localizacion')){
			$this->res=$this->objFunc->insertarLocalizacion();			
		} else{			
			$this->res=$this->objFunc->modificarLocalizacion();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarLocalizacion(){
		if($this->objParam->getParametro('tipo_meta')=='uni_cons'||$this->objParam->getParametro('tipo_meta')=='uni_cons_f'){
			$this->objParam->addParametro('id_uni_cons',$this->objParam->getParametro('id_localizacion'));
			$this->objFunc=$this->create('MODUniCons');
			$this->res=$this->objFunc->inactivarUniCons($this->objParam);
		} elseif ($this->objParam->getParametro('tipo_meta')=='rama'||$this->objParam->getParametro('tipo_meta')=='raiz_registrado') {
			$this->objParam->addParametro('id_uni_cons_comp',$this->objParam->getParametro('id_localizacion'));
			$this->objFunc=$this->create('MODUniCons');
			$this->res=$this->objFunc->eliminarUniConsComp($this->objParam);
		} else {
			$this->objFunc=$this->create('MODLocalizacion');
			$this->res=$this->objFunc->eliminarLocalizacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function SincronizarUsuarios (){
		$this->objFunc=$this->create('MODLocalizacion');
		$this->res=$this->objFunc->SincronizarUsuarios();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarUniCons(){
		

		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		//$this->objFunSeguridad=$this->create('MODUniCons');
		echo 'entra';exit;
		//obtiene el parametro nodo enviado por la vista
		$node=$this->objParam->getParametro('node');
		$id_uni_cons=$this->objParam->getParametro('id_uni_cons');
		
		if($node=='id'){
				$this->objParam->addParametro('id_padre','%');
			}
			else {
				$this->objParam->addParametro('id_padre',$id_uni_cons);
			}
		
		if($this->objParam->getParametro('filtro')=='activo' && $node=='id')
		{
				////////////////////
				$count=0;
				$pri=1;
		        $json='';
		
				$count_temporal =0;
		        //$criterio_filtro = $this->objParam->getParametro('valor_filtro');
				
				if($node=='id'){
				   $this->objParam->addParametro('id_padre','%');
				}
				else {
					$this->objParam->addParametro('id_padre',$id_uni_cons);
				}
			$this->objFunSeguridad=$this->create('MODUniCons');
			$this->res=$this->objFunSeguridad->listarUniConsFiltro($this->objParam);
				 
			if($this->res)
				{
					foreach ($this->res->datos as $f)
					{
						//var_dump($f);
						if($pri==1){
		
							//guardo el nivel
							$niveles[$count]=$f["niveles"];
							//suponemos que el nivel inicial no tiene hijos
							$hijos[$count]=0;
							$pri=0;
							//prepara nodo
							$json= '[{';
							$json=$json.$this->asignar($json,$f);
						}
						else{
							//este nodo es hijo del anterior nodo??
							//$posicion = strpos($f["niveles"],$niveles[$count].'_');
							$posicion = strpos($f["niveles"],$niveles[$count]);
							//var_dump($posicion);
							//var_dump($f["niveles"]);
							if($posicion !== false ){
		
								//echo "ENTRA";
								//var_dump($posicion);
		
								//pregunta mos si este el primer hijo del nivel padre
								if($hijos[$count]==0){
		
		
									//si es el primero iniciamos las llaves
									$json =$json.',children:[{' ;
								}
								else {
									//si no es el primero cerramos el hijo anterior y preparamos sllavez para el siguiente
									$json =$json.'},{' ;
								}
								//llenamos el nodo
								$json=$json.$this->asignar($json,$f);
		
		
								//si el primer hijo incrementamos el nivel
								if($hijos[$count]==0){
									//se incrementa el nivel
									$count++;
									//suponemos que este nuevo nivel no tiene hijos
									$hijos[$count]=0;
								}
								//se incrementa un hijo en el anterior nivel
								$hijos[$count-1]++;
								//almacena el identificador del actual nivel
								$niveles[$count]=$f["niveles"];
							}
							else{
								//si el nodo no es hijo del anterio nivel
								//buscamos mas arriba hasta encontrar un padre o la raiz
								//en el camino vamos cerrando llavez
								$sw_tmp=0; // sw temporal
								$count_temporal =0;
								while ($sw_tmp==0){
		
									$hijos[$count]=0;
									$count--;
		
									$count_temporal++;
									if($count_temporal==1){
		
										//$json =$json.' * ('.$count.')';
		
									}
									else{
										$json =$json.'}]';
									}
		
									//$posicion = strpos($f["niveles"],$niveles[$count].'_');
									$posicion = strpos($f["niveles"],$niveles[$count]);
									if ($posicion !== false){
		
										$sw_tmp =1;
									}
									else {
		
										//si revisamos el ultimo nivel
										if($count<=-1){
											$sw_tmp =1;
										}
									}
								}
								$json = $json.'},{';
								$json =$json.$this->asignar($json,$f);
		
								//se incrementa un hijo en el anterior nivel
								$hijos[$count]++;
								//almacena el identificador del actual nivel
								$count ++;
								$niveles[$count]=$f["niveles"];
		
							}
						}
					}
		
					while ($count>0){
		
						$count--;
						$json =$json.'}]';
		
		
					}
					if($pri==0){
						$json =$json.'}]';
					}
					else{
						$json =$json.'[]';
		
					}
		           header("Content-Type:text/json; charset=".$_SESSION["CODIFICACION_HEADER"]);
					//echo utf8_encode
					echo($json);
					exit;
		
				}
						
				
				
				/////////////////////////
			}
            else {
			
			
			$this->objFunSeguridad=$this->create('MODUniCons');
			$this->res=$this->objFunSeguridad->listarUniCons($this->objParam);
			
			$this->res->setTipoRespuestaArbol();
			
			$arreglo=array();
			//array_push($arreglo,array('nombre'=>'id','valor'=>'id_gui'));
			
			array_push($arreglo,array('nombre'=>'id','valor'=>'id_uni_cons'));
			array_push($arreglo,array('nombre'=>'id_p','valor'=>'id_unic_cons_padre'));
		   // array_push($arreglo,array('nombre'=>'text','valor'=>'text'));
			
					
			
		/*se ande un nivel al arbol incluyendo con tido de nivel carpeta con su arreglo de equivalencias
			  es importante que entre los resultados devueltos por la base exista la variable\
			  tipo_dato que tenga el valor en texto = 'carpeta' */
			
			$this->res->addNivelArbol('tipo_nodo','base',array('leaf'=>false,
															'allowDelete'=>true,
															'allowEdit'=>true,
			 												'cls'=>'folder'),
			 												$arreglo);
															
	   													
	      
	      
	      $this->res->addNivelArbol('tipo_nodo','raiz_borrador',array('leaf'=>false,
															'allowDelete'=>true,
															'allowEdit'=>true,
															'icon'=>'../../../lib/imagenes/otros/tuc_edit.png'),
			 												$arreglo);
															
		  $this->res->addNivelArbol('tipo_nodo','raiz_aprobado',array('leaf'=>false,
															'allowDelete'=>true,
															'allowEdit'=>true,
			 												'icon'=>'../../../lib/imagenes/otros/tuc.png'),
			 												$arreglo);													
			 
			 
		$this->res->addNivelArbol('tipo_nodo','raiz_registrado',array('leaf'=>false,
															'allowDelete'=>true,
															'allowEdit'=>true,
			 												'icon'=>'../../../lib/imagenes/otros/tuc.png'),
			 												$arreglo);
			
			array_push($arreglo,array('nombre'=>'cls','valor'=>'descripcion'));
			/*se ande un nivel al arbol incluyendo con tido de nivel carpeta con su arreglo de equivalencias
			  es importante que entre los resultados devueltos por la base exista la variable\
			  tipo_dato que tenga el valor en texto = 'hoja' */
			 														
	
			 $this->res->addNivelArbol('tipo_nodo','rama',array(
															'leaf'=>false,
															'allowDelete'=>true,
															'allowEdit'=>true,
			 												'icon'=>'../../../lib/imagenes/a_form.png'),
			 												$arreglo);
			 												
					//Se imprime el arbol en formato JSON
			$this->res->imprimirRespuesta($this->res->generarJson());
			
			}
	


	}
			
}

?>