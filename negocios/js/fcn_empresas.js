//----------------------- FUNCION FOCUS  ----------------------
focus_empresas = function getFocus() 
{           
    $('#addEmpresa').on('shown.bs.modal', function() 
    {
      $('#txtNombre').trigger('focus')
    });
}

//-----------------------INSERTAR CLIENTES/PROVEEDORES ----------------------
function insertar_empresa()
{
 
  var nombre = document.getElementById("txtNombre").value.toUpperCase();
  var rtn = document.getElementById("txtRTN").value;
  var telefono = document.getElementById("txtTelefono").value;
  var email = document.getElementById("txtEmail").value.toUpperCase();
  var web = document.getElementById("txtWEB").value.toUpperCase();  
  var direccion = document.getElementById("txtDireccion").value.toUpperCase();
  

  var parametros = 
  {
    "Actividad": "insertar_empresa",
    "nombre": nombre,
    "rtn": rtn,
    "telefono":telefono,
    "email": email,
    "web": web,
    "direccion": direccion
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_empresas.php',
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
                $('#addEmpresa').modal('hide');
                $("#pages").load('views/view_empresas.php');
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
function showmodal_empresa(idEmpresa)
{
    var idEmpresa1 = idEmpresa;
    var parametros = {"Actividad": "showmodal_empresa", "idEmpresa": idEmpresa1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_empresas.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#txtEmpId').val(response[0]); 
                $('#txtNombre1').val(response[1]); 
                $('#txtRTN1').val(response[2]); 
                $('#txtTelefono1').val(response[3]);
                $('#txtEmail1').val(response[4]);
                $('#txtWEB1').val(response[5]);                   
                $('#txtDireccion1').val(response[6]);
            },
            complete: function(){}
        });
    $('#updEmpresa').on('shown.bs.modal', function() 
    {
      $('#txtNombre1').trigger('focus')
    });
}

//-----------------------INSERTAR CLIENTES/PROVEEDORES ----------------------
function update_empresa()
{
 
  var nombre = document.getElementById("txtNombre1").value.toUpperCase();
  var rtn = document.getElementById("txtRTN1").value;
  var telefono = document.getElementById("txtTelefono1").value;
  var email = document.getElementById("txtEmail1").value.toUpperCase();
  var web = document.getElementById("txtWEB1").value.toUpperCase();  
  var direccion = document.getElementById("txtDireccion1").value.toUpperCase();
  var idEmpresa = document.getElementById("txtEmpId").value;;
  

  var parametros = 
  {
    "Actividad": "update_empresa",
    "nombre": nombre,
    "rtn": rtn,
    "telefono":telefono,
    "email": email,
    "web": web,
    "direccion": direccion,
    "idEmpresa": idEmpresa
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_empresas.php',
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
                $('#updEmpresa').modal('hide');
                $("#pages").load('views/view_empresas.php');
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