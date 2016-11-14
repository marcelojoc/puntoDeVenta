	$( document ).ready(function() {
		
	get_Products();

	//si hay algo en el total de venta lo asigno al campo txttotal
	$('#txttotal').val($('#campototal').val())
	$('#hiddentxttotal').val($('#campototal').val())
	
	// setear fechas de calendario
	var hoy= $('#date_now').val();							 //Valor de fecha del servidor 
    var inicio = moment(hoy, "DD/MM/YYYY"); 				// parseo la fecha como fecha en moment
	var fin    = moment(hoy, "DD/MM/YYYY").add(7, 'd');    // sumo 7 dias para el calendario
    setCalendar(inicio.format('DD/MM/YYYY'), fin.format('DD/MM/YYYY'))

														// documentacion
													   // https://bootstrap-datepicker.readthedocs.io/en/latest/index.html
    
		
	});



$( "a[name*='link_']" ).click(function( event ) {
  event.preventDefault();
  var url = this.href;
  var confirmacion="";

	if( this.text == 'Nueva Venta' ){

			confirmacion= 'La venta actual sera cancelada';
	}else{

			confirmacion= 'Deseas salir de la aplicacion?';
	}
	if(confirm(confirmacion)){
			location.href = url;
	}else{
			return false;
	}

});


function setCalendar(hoy, semana){

		$('.input-group.date').datepicker({
		format: "dd/mm/yyyy",
		startDate: hoy,
		endDate: semana,
		maxViewMode: 1,
		clearBtn: true,
		language: "es",
		daysOfWeekDisabled: "0",
		autoclose: true
		}); 
}


function verifClic(aChoix) {

	document.getElementById('frmDifference').hdnChoix.value = aChoix;
}

function verifSubmit() {

	/*
		esto es asi, pueden venir dos strings  'DIF'que es pago postergado y 'ESP' que es de contado efectivo
		si viene DIF el recibido debe estar en cero, por que no necesito la plata
		y la fecha del componente datapicker debe ser una fecha valida.
		
	==============

	Si viene ESP la fecha debe estar vacia
	la cantidad debe ser superior al total
	===================

	Al final dee re direccionar al action del formulario,
	
	*/
	//document.getElementById('frmFacturation').submit();

	var aChoix = document.getElementById('frmDifference').hdnChoix.value;

	if(aChoix == 'DIF'){

		$('#txtRecibido').val('');                // recibido en blanco
		$('#txtVuelto').val('');				 // vuelto en blanco
		$('#hiddentxtVuelto').val('');			// vuelto en blanco		
		var fecha= $('#date_comp').val();      // traigo lo que hay en el componente DP 
		var hoy  = $('#date_now').val();	  // Valor de fecha del servidor 
		if(fecha != ''){					 // compruebo que tenha algo, si hay algo valido la fecha, si no lanzo el picker
											// si esta aca es por que hay algo en el campo

			try {
				var validaFecha = moment(fecha, "DD/MM/YYYY");  // formateo la fecha y 

				if(validaFecha.isValid()){                    // si esta bien la fecha envio el form  falta ver si no esta entre el rango

						return true;
				}else{

						return dispararCalendario();   // no es valida la fecha

				}

			}
				catch(err) {					  // si salta algun error dejo el datapicker vacio y disparo el calendario

				return dispararCalendario();   // algun error en la validacion
			}

		}else{								// si el DP no tiene nada disparo el calendario y el foco

				return dispararCalendario(); 
		}

	}else{

											// Si el pago es efectivo solo verifico el monto si es numerico 

			if( !isNaN($('#txtRecibido').val() ) ){

				return true;
			}else{

				alert('El valor ingresado no es Valido');
				$('#txtRecibido').val('');
				return false;
			}

	}


function dispararCalendario(){
				$('#date_comp').val('');					  // pongo el recibido en 0 disparo el calendario y bloqueo el boton efectivo
				$('#txtRecibido').val('');					 // pongo en 0 el recibido y el cambio
				$('#txtVuelto').val('');				
				$('#hiddentxtVuelto').val('');			   // vuelto en blanco	
				$('.input-group.date').datepicker('show');
				$('#btnEfectivo').attr('disabled', true);
				return false;
}



}

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



	function loadComponent(data ){   // completa el desplegable con los productos

	// cargo el select de productos siempre que tenga Stock
		
		var select =$('#selectProduct');
		var opcion= '';
			$.each(data, function(id,value){

					if(value.stock_product <= 0){
					opcion= 'disabled'
					}else{

						opcion= ''
					}
					select.append('<option value="'+value.id_product+'" '+ opcion + '>'+value.nom_product + ' | '+value.stock_product+'</option>');
				
			});
		get_valProduct();
	}


	function get_valProduct(){      // carga Local de la tabla de precios y carga valores de lo seleccionado

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
						
						//console.log(json);
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


	function cargaValores(datos){     // carga valores de los productos seleccionados

		var stock= $('#txtstock');
		var pUnit= $('#txtPunit');
		var hidden= $('#hiddenpUnit');

		stock.val(datos.stock_product);

		var precio = parseFloat(datos.prod_precio)
		pUnit.val(precio.toFixed(2));
		hidden.val(precio.toFixed(2));

	}

	function cargatablaLocal(tabla){             // lee y re escribe tabla en local Storage para tabla 

		//console.log(tabla);
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

		var recibido	= parseFloat($('#txtRecibido').val()); // seteo los componentes del form 
        var total 		= parseFloat($('#txttotal').val());
		var btnEfectivo = $('#btnEfectivo');
		var estadoBtn   = true;    							  // seteado el valor deshabilitado verdadero en el boton efectivo
		var vuelto		= $('#txtVuelto');
        var vueltohidden=   $('#hiddentxtVuelto');

		if (recibido > total) {                              // compruebo el monto de la compra y lo que me paga

		resultat = ((recibido - total) * 100 ) / 100;

		vuelto.val(resultat.toFixed(2));
		vueltohidden.val(resultat.toFixed(2));
		estadoBtn= false;
		} else if (recibido == total) {

			vuelto.val(0);
			estadoBtn= false;

		} else {

			vuelto.val('-');
			estadoBtn= true;
		}

		btnEfectivo.attr('disabled', estadoBtn);
	}


	function setValorTabla(valor){  // actualiza con el nuevo precio el objeto con los datos

		$.post( "ajax_query.php", { consulta: "set_valorTabla", dato: valor }, function(data){

		} );
	}


function valNums(comp){   // valido que los campos introducidos sean numeros enteros..


var dato = parseInt(comp.val());

	if(!isNaN(dato)){

		return true;
	}else{

		comp.val('');
		comp.focus();
		return false;

	}





}





// solo de prueba

	function pruebaRemito(param= null){
		
		$.ajax(
				{
				url : 'http://localhost/dolibar_local/htdocs/commande/card.php?id=6',
				type: "POST",
				data : {
						action: 'builddoc',
						token: '083ab704e6d787d6ad9867c05b9700e3',
						},
				

				success : function(json) {
						console.log(json);
				//loadComponent(json);
				},

				error : function(xhr, status) {
					alert('Disculpe, existió un problema '+ status + xhr );
				},


		})

	}


	function envioRemito(param= null){
		
		$.ajax(
				{
				url : 'http://localhost/dolibar_local/htdocs/compta/facture.php?facid=42&action=presend&mode=init',
				type: "POST",
				data : {
						action: 'builddoc',
						token: '083ab704e6d787d6ad9867c05b9700e3',
						},
				

				success : function(json) {
						console.log(json);
				//loadComponent(json);
				},

				error : function(xhr, status) {
					alert('Disculpe, existió un problema '+ status + xhr );
				},


		})

	}

	//  fin prueba de remitos 