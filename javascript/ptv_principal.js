$( document ).ready(function() {
    
    
get_Product();




    
});










function get_Product(param= null){
    
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



function loadComponent(data ){



	
	var select =$('#selectProduct');
	//select.html('');
		$.each(data, function(id,value){
				select.append('<option value="'+value.id_product+'">'+value.nom_product + ' - '+value.stock_product+'</option>');
				});
	

    
}
