$( document ).ready(function() {
    
    
get_Products();




    
});



function get_Products(param= null){
    
	$.ajax(
			{
			url : 'ajax_query.php',
			type: "POST",
			data : {
					consulta: 'get_products'
					},
			dataType: 'JSON',

			success : function(json) {
				    loadComponent(json);
			//loadComponent(json);
			},

			error : function(xhr, status) {
				alert('Disculpe, existió un problema '+ status + xhr );
			},

			// código a ejecutar sin importar si la petición falló o no
			complete : function(xhr, status) {
				//loadComponent("");
			}

	})

}



function loadComponent(data ){

// cargo el select de productos siempre que tenga Stock
	
	var select =$('#selectProduct');
	var opcion= '';
		$.each(data, function(id,value){

				if(value.stock_product <= 0){
                   opcion= 'disabled'
				}else{

					opcion= ''
				}
                   select.append('<option value="'+value.id_product+'" '+ opcion + '>'+value.nom_product + ' - '+value.stock_product+'</option>');
			
		});
}


function get_valProduct(){

	var param= $('#selectProduct').val();  // seteo el valor seleccionado del Select
    
	$.ajax(
			{
			url : 'ajax_query.php',
			type: "POST",
			data : {
					consulta: 'get_valProduct',
					dato    : param
					},
			dataType: 'JSON',

			success : function(json) {
				    
					console.log(json);
			//loadComponent(json);
			},

			error : function(xhr, status) {
				alert('Disculpe, existió un problema '+ status + xhr );
			},

			// código a ejecutar sin importar si la petición falló o no
			//complete : function(xhr, status) {
				//loadComponent("");
			//}

	})

}