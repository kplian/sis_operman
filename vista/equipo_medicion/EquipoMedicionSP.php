<?php
/**
*@package pXP
*@file EquipoMedicion.php
*@author  RCM
*@date 21-05-2017 21:46:02
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.EquipoMedicionSP=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.EquipoMedicionSP.superclass.constructor.call(this,config);   
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_equipo_medicion'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_equipo_variable'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_uni_cons'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_variable'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'fecha_medicion',
				fieldLabel: 'fecha_medicion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y', 
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'eqmesp.fecha_medicion',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'medicion',
				fieldLabel: 'medicion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650
			},
				type:'NumberField',
				filters:{pfiltro:'eqmesp.medicion',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'hora',
				fieldLabel: 'hora',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:8
			},
				type:'TextField',
				filters:{pfiltro:'eqmesp.hora',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'observaciones',
				fieldLabel: 'observaciones',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:2000
			},
				type:'TextField',
				filters:{pfiltro:'eqmesp.observaciones',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'eqmesp.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'codigo',
				fieldLabel: 'Codigo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:8
			},
				type:'TextField',
				filters:{pfiltro:'uc.codigo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tipo_variable',
				fieldLabel: 'Tipo variable',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:8
			},
				type:'TextField',
				filters:{pfiltro:'tp.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'vehiculo',
				fieldLabel: 'Vehiculo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:8
			},
				type:'TextField',
				filters:{pfiltro:'uc.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		}

	],
	tam_pag:50,	
	title:'Mediciones sin procesar',
	ActSave:'../../sis_mantenimiento/control/EquipoMedicion/insertarEquipoMedicion',
	ActDel:'../../sis_mantenimiento/control/EquipoMedicion/eliminarEquipoMedicion',
	ActList:'../../sis_mantenimiento/control/EquipoMedicion/listarMedicionesSP',
	id_store:'id_equipo_medicion',
	fields: [
		{name:'id_equipo_medicion', type: 'numeric'},
		{name:'id_equipo_variable', type: 'numeric'},
		{name:'id_uni_cons', type: 'numeric'},
		{name:'id_tipo_variable', type: 'numeric'},
		{name:'fecha_medicion', type: 'date'},
		{name:'medicion', type: 'numeric'},
		{name:'hora', type: 'date'},
		{name:'observaciones', type: 'string'},
		{name:'fecha_reg', type: 'date'},
		{name:'codigo', type: 'string'},
		{name:'vehiculo', type: 'numeric'},
		{name:'tipo_variable',type: 'numeric'}
		
	],
	sortInfo:{
		field: 'id_equipo_medicion',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		