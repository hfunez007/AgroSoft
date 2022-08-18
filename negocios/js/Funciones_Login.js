function fncLogIn()
{
    var userName=document.getElementById("txtCuenta").value.trim();
    var pass = document.getElementById("txtPass").value.trim();

    var parametros=
    {
	    "Actividad":"Login",
	    "pass":pass,
	    "userName":userName
    };
    $.ajax({
            data:  parametros,
            url:   'negocios/php/Consultas_Login.php',
            type:  'post',
            dataType: 'json',
            beforeSend: function () {  },
            success:  function (response)
            {   	
                if(response.log==1)
                {
                    if(response.codigoRol==2) 
                    {
                          window.location="site.php?logged="+response.rol
                    }
                    else
                    {
                       window.location="site.php?logged="+response.rol
                    }   
                }
                else if(response.log==2)
                {
                    window.location="changepass.php?logged="+response.rol		
                }                  
                else if(response.log==0) 
                {
                     swal.fire({ 
                            html:'<b>Usuario/Contraseña son Incorrectos</b>',
                            type: 'info',
                            showCloseButton: true,
                            confirmButtonText: 'Aceptar'
                        });
                }
            },
            error: function(xhr) 
            { 
                alert("Error occured.please try again");
                alert(xhr.statusText + xhr.responseText);

            }
        });
}


function fcn_cambiar_pass() 
{
        
    var pass1 = document.getElementById("pass1").value;
    var passconfi = document.getElementById("passconfi").value;
        
    if ( (pass1 == "") || (passconfi == "")  )
    {
    
        $("#ErrorPasswordSegura").html("<div class='alert alert-danger'><strong>Los campos estan vacios!</strong></div>");
        $("#ErrorPasswordSegura").fadeIn(300).delay(800).fadeOut(1000);
    }
    else if ( pass1 != passconfi ) 
    {
    
        $("#ErrorPasswordSegura").html("<div class='alert alert-danger'><strong>Las contraseñas no son iguales!</strong></div>");
        $("#ErrorPasswordSegura").fadeIn(300).delay(800).fadeOut(1000);
    }
    else if  (pass1.length >= 8 && passconfi.length >= 8 )  
    {
        var parametros = 
        {
    
            "pass1" : pass1,
            "Actividad" : "CambiarPass"
    
        };
        $.ajax({
    
                    data:  parametros,
                    url:   'negocios/php/Consultas_Login.php',
                    type:  'post',
                            dataType: 'json',
                    beforeSend: function () {
                    // alert("Su contraseña ha sido cambiada con exito!"); window.location="index.php";
                     },
                    success:  function (response) 
                    {
    
                        if (response=='exito')
                        {
                            swal.fire({ 
                            //title:response,
                            html:'Su contraseña se cambió con éxito!',
                            type: 'success',
                            showCloseButton: true,
                            confirmButtonText: 'Aceptar'
                
                            }).then(function(){window.location="index.php";});
                        }
                    }    
        });
    }
    else 
    {
        $("#ErrorPasswordSegura").html("<div class='alert alert-danger'><strong>Su Contraseña no cumple las politicas de seguridad del sistema!</strong></div>");
        $("#ErrorPasswordSegura").fadeIn(300).delay(800).fadeOut(1000);
    } 
    
}