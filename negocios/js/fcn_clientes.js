//----------------------- FUNCION FOCUS  ----------------------
focus_pclientes = function getFocus() 
{           
    $('#addCliente').on('shown.bs.modal', function() 
    {
      $('#txtnombre').trigger('focus')
    });
}

//-----------------------INSERTAR CLIENTES/PROVEEDORES ----------------------
function insertar_cliente()
{
 
  var nombre = document.getElementById("txtnombre").value.toUpperCase();
  var rtn = document.getElementById("txtrtn").value;
  var telefono = document.getElementById("txttelefono").value;
  var email = document.getElementById("txtemail").value.toUpperCase();
  var contacto = document.getElementById("txtcontacto").value.toUpperCase();
  var tipo = document.getElementById("cbotipo").value;
  var direccion = document.getElementById("txtdireccion").value.toUpperCase();
  

  var parametros = 
  {
    "Actividad": "insertar_cliente",
    "nombre": nombre,
    "rtn": rtn,
    "telefono":telefono,
    "email": email,
    "contacto": contacto,
    "tipo": tipo,
    "direccion": direccion
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_clientes.php',
      type:  'post',
      dataType: 'json',
      cache:false,
      beforeSend: function(){},
      success: function(response)
      {
        if (response.mensaje=='exito')
        {
            const Toast = Swal.mixin({
                toast: true,
                // position: 'bottom-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })
              
              Toast.fire({
                icon: 'success',
                title: 'Agregado Correctamente'
              }).then(function(){ 
                $('#addCliente').modal('hide');
                $("#pages").load('views/view_clientes.php');
            });
        }
        else if (response.validacion!='')
        {
            Swal.fire({
                icon: 'error',
                title: 'Informacion',
                html: ' ' + response.validacion
                });
        }
        else  if (response.mensaje=='error')
        {
            Swal.fire({
                icon: 'error',
                title: 'Informacion',
                html: 'Error al Agregar'
                });
        }
      },
  complete: function(){}
  });   
}

//-----------------------CARGAR MODAL PACIENTES ----------------------
function showmodal_cliente(idcliente)
{
    var idcliente1 = idcliente;
    var parametros = {"Actividad": "showmodal_cliente", "idcliente": idcliente1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_clientes.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#txtcliid').val(response[0]); 
                $('#txtnombre1').val(response[1]); 
                $('#txtrtn1').val(response[2]); 
                $('#txttelefono1').val(response[3]);
                $('#txtemail1').val(response[4]);
                $('#txtcontacto1').val(response[5]);   
                $('#cbotipo1').val(response[6]); 
                $('#txtdireccion1').val(response[7]);
            },
            complete: function(){}
        });
    $('#updCliente').on('shown.bs.modal', function() 
    {
      $('#txtnombre1').trigger('focus')
    });
}

//-----------------------INSERTAR CLIENTES/PROVEEDORES ----------------------
function update_cliente()
{

    var id = document.getElementById("txtcliid").value;
    var nombre = document.getElementById("txtnombre1").value.toUpperCase();
    var rtn = document.getElementById("txtrtn1").value;
    var telefono = document.getElementById("txttelefono1").value;
    var email = document.getElementById("txtemail1").value.toUpperCase();
    var contacto = document.getElementById("txtcontacto1").value.toUpperCase();
    var tipo = document.getElementById("cbotipo1").value;
    var direccion = document.getElementById("txtdireccion1").value.toUpperCase();
  

    var parametros = 
    {
        "Actividad": "update_cliente",
        "nombre": nombre,
        "rtn": rtn,
        "telefono":telefono,
        "email": email,
        "contacto": contacto,
        "tipo": tipo,
        "direccion": direccion,
        "id":id
    };

    $.ajax(
    {
        data:   parametros,
        url:   'negocios/php/tree_clientes.php',
        type:  'post',
        dataType: 'json',
        cache:false,
        beforeSend: function(){},
        success: function(response)
        {
            if (response.mensaje=='exito')
            {
                const Toast = Swal.mixin({
                    toast: true,
                    // position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizado Correctamente'
                }).then(function(){                    
                    $('#updCliente').modal('hide');
                    $("#pages").load('views/view_clientes.php');
                });
            }
            else if (response.validacion!='')
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Informacion',
                    html: ' ' + response.validacion
                    });
            }
            else  if (response.mensaje=='error')
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Informacion',
                    html: 'Error al Agregar'
                    });
            }
        },
    complete: function(){}
    });   
}

