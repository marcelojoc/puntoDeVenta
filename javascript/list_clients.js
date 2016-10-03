var todo=null;

$('#txtcodigo').on('keyup', loadComponent($('#txtcodigo').val()) );

$('#selCliente').on('change', loadComponent($('#txtcodigo').val()) );
// si no estoy en esta pagina, declaro el array  y hago la peticion ajax a Clientes
 $(document).ready(function(){

	if( !$('#formSelClient').length== 0){
		
        get_MyClient();
		
	}

});

function asignarCodigo(){

$('#txtcodigo').val()


};

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
				loadComponent("");
			}

	})

}

//<option value="1">1</option>


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
