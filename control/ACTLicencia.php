<?php
/**
*@package pXP
*@file gen-ACTLicencia.php
*@author  (admin)
*@date 17-04-2017 03:18:41
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLicencia extends ACTbase{    
			
	function listarLicencia(){
		$this->objParam->defecto('ordenacion','id_licencia');
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('id_conductor')!=''){
			$this->objParam->addFiltro("gemlic.id_conductor = ".$this->objParam->getParametro('id_conductor'));	
		}

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLicencia','listarLicencia');
		} else{
			$this->objFunc=$this->create('MODLicencia');
			
			$this->res=$this->objFunc->listarLicencia($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarLicencia(){
		$this->objFunc=$this->create('MODLicencia');	
		if($this->objParam->insertar('id_licencia')){
			$this->res=$this->objFunc->insertarLicencia($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarLicencia($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarLicencia(){
			$this->objFunc=$this->create('MODLicencia');	
		$this->res=$this->objFunc->eliminarLicencia($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>