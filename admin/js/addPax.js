function addPax(){
                                    			
                                  		
                                    			$("#tipos_pax_table").focus();
                                    			var txtDireccion=$("#txtDireccion").val();
                                                var txtLatitud=$("#txtLatitud").val();
                                                var txtLongitud=$("#txtLongitud").val();
                                    			var nombre_tarifa=$("#nombre_tarifa_txt").val();
                                    			var from_edades=$('#from_edades_txt').val();
                                    			var from_edades_text=$('#from_edades_txt :selected').text();
                                                var to_edades=$('#to_edades_txt').val();
                                                var to_edades_text=$('#to_edades_txt :selected').text();
                                    			var tipo_tarifa=$('#tipo_tarifa').val();
                                    			var tipo_tarifa_text=$('#tipo_tarifa :selected').text();
                                    			var valor_tarifa=$('#vTarifa').val();
                                    			var pago_minimo=$('#pago_minimo').val();
                                    			var selCancelaciones=$('#selCancelaciones').val();
                                    			var selCancelaciones_text=$('#selCancelaciones :selected').text();
                                                var comisiona=$('#comisiona').is(":checked");
                                              
                                                var comisionaFunny;
                                                if (comisiona==true) {
                                                    comisiona=1;
                                                    comisionaFunny="si";
                                                      
                                                }
                                                else{
                                                    comisiona=0;
                                                   comisionaFunny="no"; 
                                                }

                                              
                                    			 
if (txtDireccion.length>0) {
	if (nombre_tarifa.length>0) {

		if (true) {//valor_tarifa>0 && tipo_tarifa!=4
			
		
	       $('#tipos_pax_table').append('<tr><td>'+txtDireccion+'</td>'+
	       	 							 '<td>'+nombre_tarifa+'</td>'+
        								 '<td>'+from_edades_text+'</td>'+
        								 '<td>'+to_edades_text+'</td>'+
        								 '<td>'+tipo_tarifa_text+'</td>'+
        								 '<td>'+valor_tarifa+'</td>'+
        								 '<td>'+valor_tarifa+'</td>'+
        								 '<td>'+selCancelaciones_text+'</td>'+
                                         '<td>'+comisionaFunny+'</td> '+
                                         '<td></td>'+
        								 ' </tr>');


      
         $('#tipos_pax_div').append('<input type="hidden" name="txtDireccion[]" value="'+txtDireccion+'"></input>'+
                                        '<input type="hidden" name="txtLatitud[]" value="'+txtLatitud+'"></input>'+
                                        '<input type="hidden" name="txtLongitud[]" value="'+txtLongitud+'"></input>'+
                                         '<input type="hidden" name="nombre_tarifa[]" value="'+nombre_tarifa+'"></input>'+
                                         '<input type="hidden" name="from_edades[]" value="'+from_edades+'"></input>'+
                                         '<input type="hidden" name="to_edades[]" value="'+to_edades+'"></input>'+
                                         '<input type="hidden" name="tipo_tarifa[]" value="'+tipo_tarifa+'"></input>'+
                                         '<input type="hidden" name="valor_tarifa[]" value="'+valor_tarifa+'"></input>'+
                                         '<input type="hidden" name="pago_minimo[]" value="'+valor_tarifa+'"></input>'+
                                         '<input type="hidden" name="selCancelaciones[]" value="'+selCancelaciones+'"></input>'+
                                          '<input type="hidden" name="comisiona[]" value="'+comisiona+'"></input>'+
                                         ' ');




	        $('html, body').animate({
 scrollTop: $("#tipos_pax_table").offset().top
 }, 1000);
swal.fire("Metelebrasil", "Taxa adicionada com sucesso", "success");
}//if (tipo_tarifa!=4) {
	else{
swal.fire("error", "As taxas não gratuitas devem ter um preço", "warning");
		 
		  $('html, body').animate({
 scrollTop: $("#nombre_tarifa_txt").offset().top
 }, 1000);
}
} else{
swal.fire("error", "As taxas devem ter um nome", "warning");
		 
		  $('html, body').animate({
 scrollTop: $("#nombre_tarifa_txt").offset().top
 }, 1000);

}                                   		
}//if (txtDireccion.length>0) {
	else{//if (txtDireccion.length>0) {
		swal.fire("error", "Eu não seleciono um ponto de partida no mapa", "warning");
		 
		  $('html, body').animate({
 scrollTop: $("#searchBoxDIV").offset().top
 }, 1000);
	}

 
}
                                    		    $('#tipo_tarifa').on('change', function() {
                                    		    	if (this.value==4) {
                                    		    		
																      var a = $("#tipo_tarifa :selected" ).text();
																     $('#vTarifa').val("0");
																      $('#vTarifa').attr('disabled', 'disabled');
                                                                       $('#pago_minimo').val("0");
                                                                      $('#pago_minimo').attr('disabled', 'disabled');
                                                                      
                                    		    	}
                                    		    	else{
                                    		    		   $('#pago_minimo').removeAttr('disabled');
                                                           $('#vTarifa').removeAttr('disabled');
                                    		    	}
																      
																    })