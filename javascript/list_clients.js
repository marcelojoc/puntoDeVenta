var todo=null;


// si no estoy en esta pagina, declaro el array  y hago la peticion ajax a Clientes
 $(document).ready(function(){

	if( !$('#formSelClient').length== 0){
		
        get_MyClient();
		
	}

localStorage.removeItem('tmpStock');
	// if(localStorage.getItem('tmpStock') === true ){ // compruebo la existencia de LS para tmpStock

	// 	// si esta creada, entonces la elimino
		
	// 	localStorage.removeItem('tmpStock');
	// }
geoCliente.requestPosition();

});

function asignarCodigo(valor){

	$('#hiddenCode').val( $('#selCliente').val())

	var codClient= $('#selCliente').val();

	rellenarPanel (codClient);

};



function rellenarPanel(datos){    // funcion encargada de lipiar el panel y re escribir los datos

	limpiarPanel();
		var panelString ="";

		$.each(todo, function(id,value){

			if( datos == value.id_cliente ){

				panelString += '<p><i class="glyphicon glyphicon-tags"></i> codigo: ' + value.cod_cliente;

				panelString += ' </p>   <p><i class="glyphicon glyphicon-user"></i> Nombre: '+ value.nombre;
				panelString += '</p>  <p><i class="glyphicon glyphicon-home"></i> Direccion: '+ value.direccion +'</p>';

				if(value.lat === null || value.lon === null){

					panelString += '<input type="hidden" name="set_lat" id="set_lat" value ="0">';
					panelString += '<input type="hidden" name="set_lat" id="set_lon" value ="0">';

				}else{

					panelString += '<input type="hidden" name="set_lat" id="set_lat" value ="'+ value.lat +'">';
					panelString += '<input type="hidden" name="set_lon" id="set_lon" value ="'+ value.lon +'">';

				}

			}
				
		});


	$('#datosPanel').html(panelString);

}


function limpiarPanel(){

		 $('#datosPanel').html('<p><i class="glyphicon glyphicon-tags"></i> codigo: </p> <p><i class="glyphicon glyphicon-user"></i> Nombre: </p> <p><i class="glyphicon glyphicon-home"></i> Direccion: </p>');                       
	                                                
}



function get_MyClient(param= null){
    
	$.ajax(
			{
			url : 'ajax_query.php',
			type: "POST",
			data : {
					consulta: 'getAll'
					},
			dataType: 'JSON',

			success : function(json) {
				    todo=json;
			//loadComponent(json);
			},

			// código a ejecutar si la petición falla;
			// son pasados como argumentos a la función
			// el objeto de la petición en crudo y código de estatus de la petición
			error : function(xhr, status) {
				alert('Disculpe, existió un problema '+ status + xhr );
			},

			// código a ejecutar sin importar si la petición falló o no
			complete : function(xhr, status) {
				cargarComponent(todo);
			}

	})

}



function loadComponent( filtro){

	if(todo != null){

	resultado=$.grep(todo, function (n){return !n.cod_cliente.indexOf(filtro)});
	var select =$('#selCliente');
	select.html('');
		$.each(resultado, function(id,value){
				select.append('<option value="'+value.id_cliente+'">'+value.cod_cliente + ' - '+value.nombre+'</option>');
				});
		}

    
}


// recibo el parametro a buscar y lo paso por el filtro
// esta funcion crea a partir de todos los clientes una lista temporal acorde a los criterios de busqueda
function filtrar (filtro){

var lista= [];
var buscar= filtro.toUpperCase();
var tmpValor= "";

		$.each(todo, function(id,value){
		/**
		 * el valor debo pasarlo a String 
		 * Luego a mayuscula
		 * busco en el arreglo el criterios
		 * si se cumple lo guardo al objeto en la lista
		 * si no se cumple borro la variable temporal String y paso al siguiente registro
		 * 
		 */

			tmpValor=JSON.stringify(value);
			tmpValor.toUpperCase();
			var pp = tmpValor.search(buscar);

				if (pp != -1){

					// tengo que volver a generar el objeto
					// y guardarlo en el array  lista[]
					lista.push(JSON.parse(tmpValor));

				}

		});
	limpiarPanel();     // limpio el panel de abajo
	cargarComponent(lista) // cargo el select con los datos nuevos

}


function cargarComponent(listaTmp){

	var select =$('#selCliente');
	select.html('');
		$.each(listaTmp, function(id,value){
				select.append('<option value="'+value.id_cliente+'">'+value.cod_cliente + ' - '+value.nombre+ ' - '+value.direccion+'</option>');
		});
	
}