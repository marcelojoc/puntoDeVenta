	$( document ).ready(function() {
		
	get_Products();

	//si hay algo en el total de venta lo asigno al campo txttotal
	$('#txttotal').val($('#campototal').val())


		
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
			get_valProduct();
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
						cargatablaLocal(json.tabla_desc);
						cargaValores(json.datos_prod);
						$('#txtcantidad').val('');

				//loadComponent(json);
				},

				error : function(xhr, status) {
					alert('Disculpe, existió un problema '+ status + xhr );
				},

		})

	}


	function cargaValores(datos){

		var stock= $('#txtstock');
		var pUnit= $('#txtPunit');
		var hidden= $('#hiddenpUnit');

		stock.val(datos.stock_product);

		var precio = parseFloat(datos.prod_precio)
		pUnit.val(precio.toFixed(2));
		hidden.val(precio.toFixed(2));

	}

	function cargatablaLocal(tabla){

		console.log(tabla);
		localStorage.removeItem('tabla');
		localStorage.setItem('tabla', JSON.stringify(tabla));
	}


	// recibe el dato de cantidad  y hace el calculo
	function calculos(el){

		//consulto localStorage 
		var tabla = JSON.parse(localStorage.tabla);
		var e = parseInt(el);

			if(el == '0' || el=='' ){   //  si cantidad es cero, pongo el valor unitario basico
				$('#txtPunit').val($('#hiddenpUnit').val());
			}

		$.each( tabla, function( key, value ) {
		
		//  verificar si el valor es el maximo, utiliza un -1 para simbolizar el maximo de Stock

		if(value.max == '-1'){
			value.max= 10000000000000;

		}
			if(e >= parseInt(value.min) && e <= parseInt(value.max)){

					if(value.descuento !=0){

						$('#txtPunit').val(value.descuento);
						setValorTabla(value.descuento);
					}

			}
		
		});

		calcularTotal();

	}



	function calcularTotal(){

		// compruebo que los campos tengan datos
		// todos deben ser numeros

		var cantidad =  !isNaN(parseInt($('#txtcantidad').val()))? parseInt($('#txtcantidad').val()) : 0 ;
		var stock = !isNaN(parseInt($('#txtstock').val()))? parseInt($('#txtstock').val()) : 0 ;
		var pUnit = !isNaN(parseFloat($('#txtPunit').val()))? parseFloat($('#txtPunit').val()) : 0 ;
		var descuento = !isNaN(parseInt($('#txtdesc').val()))? parseInt($('#txtdesc').val()) : 0 ;
		var baseImp = parseInt ( $('#txtbase').val());
		var valor_desc=null;



		//  total parcial de la venta	
		var total_ht = ( (pUnit * cantidad)*100 ) / 100 ;



		if ( descuento <= 0 ) {

			valor_desc = 0;

		} else {

			valor_desc = total_ht * descuento / 100;

		}

	// Recalcul du montant total, avec la remise
		var total = ( (total_ht - valor_desc) *100 ) / 100;

		$('#txtbase').val('$' + total.toFixed(2))

	}



	function calculoCuenta(){


		var recibido= parseFloat($('#txtRecibido').val());
        var total =   parseFloat($('#txttotal').val());
		var vuelto=   $('#txtVuelto');



		if (recibido > total) {    // compruebo el monto de la compra y lo que me paga

		resultat = ((recibido - total) * 100 ) / 100;

		vuelto.val(resultat.toFixed(2));

		} else if (recibido == total) {

		vuelto.val(0);

		} else {

		vuelto.val('-');

		}

parseInt(value.max)


	}

	function setValorTabla(valor){


		$.post( "ajax_query.php", { consulta: "set_valorTabla", dato: valor }, function(data){

			console.log(data);
		} );
	}