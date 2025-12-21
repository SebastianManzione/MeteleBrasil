
function preguntar(pregunta) {
 Swal.fire({  
  title: 'Pagamento em mãos',  
  text: pregunta,
  icon: 'question',
  showDenyButton: true,  showCancelButton: true,  
  confirmButtonText: `Sim, continuar!`,  
  denyButtonText: `Não`,
}).then((result) => {  
    /* Read more about isConfirmed, isDenied below */  
    if (result.isConfirmed) {    
        alert()
      
        //$("#tTarifas" + index).remove();
    } else if (result.isDenied) {    
       return false
    }
});


 // 
}