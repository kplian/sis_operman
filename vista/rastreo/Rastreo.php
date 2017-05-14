<?php
/**
 *@package pXP
 *@file gen-Depto.php
 *@author  )
 *@date 24-11-2011 15:52:20
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Ext.define('Phx.vista.Rastreo',{		
	extend: 'Ext.util.Observable',
	
	constructor: function(config) {
		
		Ext.apply(this, config);
		var me = this;		
		this.callParent(arguments);
		
	    this.panel = Ext.getCmp(this.idContenedor);
	    
	    this.tb = new Ext.Toolbar({
				        items:[{
						            text: 'New',
						            iconCls: 'album-btn',
						            scope: this,
						            handler: function(){alert('boton...')}				            
				          }
				          
				          
				          ]
				        });
				        
				        
	    
	    this.panelMapa = new Ext.Panel({  
    		    padding: '0 0 0 0',
    		    tbar: this.tb,
    		    html:'<div id="map-'+this.idContenedor +'"></div>',
    		    region:'center',
    		    split: true, 
    		    layout:  'fit' });
    		    
    	this.Border = new Ext.Container({
	        layout:'border',
	        items:[  this.panelMapa]
	    });	
	    
	        
	    
	    this.panel.add(this.Border);
	    this.panel.doLayout();
	    this.addEvents('init');	   
    	
    
    		    
	    var route = [
			      [-68.131096, -16.514822],
			      [-68.131155, -16.514339],
			      [-68.130651, -16.514051],
			      [-68.130597, -16.513516],
			      [-68.131134, -16.512745],
			      [-68.131499, -16.511222],
			      [-68.131692, -16.510554],
			      [-68.132572, -16.509258],
			      [-68.132378, -16.508393],
			      [-68.132872, -16.506706],
			      [-68.133087, -16.506861],
			      [-68.133923, -16.506418],
			      [-68.134170, -16.506737],
			      [-68.135179, -16.506737]
			    ];
			    
		//Create empty vector
	    var vectorSource = new ol.source.Vector({
	      
	    });	
	    
	    //Create a bunch of icons and add to source vector
	    for (var i=0;i<route.length;i++){
	        var iconFeature = new ol.Feature({
	          geometry: new ol.geom.Point(ol.proj.transform(route[i], 'EPSG:4326',   'EPSG:3857')),
	          name: 'Null Island ' + i,
	          population: 4000,
	          rainfall: 500
	        });
	        vectorSource.addFeature(iconFeature);
	    }   
	    
	    //create the style
	    var iconStyle = new ol.style.Style({
	      image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
	        anchor: [0.5, 46],
	        anchorXUnits: 'fraction',
	        anchorYUnits: 'pixels',
	        opacity: 0.75,
	        src: 'http://openlayers.org/en/v3.9.0/examples/data/icon.png'
	      }))
	    });
	    
	    //add the feature vector to the layer vector, and apply a style to whole layer
	    var vectorLayer = new ol.layer.Vector({
	      source: vectorSource,
	      style: iconStyle
	    });
	
	    var map = new ol.Map({
	      layers: [new ol.layer.Tile({ source: new ol.source.OSM() }), vectorLayer],
	      target:  document.getElementById('map-'+this.idContenedor), //this.panelMapa.getId(),
	      view: new ol.View({
	        center: ol.proj.fromLonLat(route[0]),
	        zoom: 17
	      })
	    });
	    
	    var utils = {
		      getNearest: function(coord){
		        var coord4326 = utils.to4326(coord);    
		        return new Promise(function(resolve, reject) {
		          //make sure the coord is on street
		          fetch(url_osrm_nearest + coord4326.join()).then(function(response) { 
		            // Convert to JSON
		            return response.json();
		          }).then(function(json) {
		            if (json.code === 'Ok') resolve(json.waypoints[0].location);
		            else reject();
		          });
		        });
		      },
			      createFeature: function(coord) {
			        var feature = new ol.Feature({
			          type: 'place',
			          geometry: new ol.geom.Point(ol.proj.fromLonLat(coord))
			        });
			        feature.setStyle(styles.icon);
			        vectorSource.addFeature(feature);
			      },
			      createRoute: function(polyline) {
			        // route is ol.geom.LineString
			        var route = new ol.format.Polyline({
			          factor: 1e5
			        }).readGeometry(polyline, {
			          dataProjection: 'EPSG:4326',
			          featureProjection: 'EPSG:3857'
			        });
			        var feature = new ol.Feature({
			          type: 'route',
			          geometry: route
			        });
			        feature.setStyle(styles.route);
			        vectorSource.addFeature(feature);
			      },
			      to4326: function(coord) {
			        return ol.proj.transform([
			          parseFloat(coord[0]), parseFloat(coord[1])
			        ], 'EPSG:3857', 'EPSG:4326');
			      }
	    };
	    
     
	       
	    
	     
	    
	   
			
			
	}
	
	
	
	
});
</script>