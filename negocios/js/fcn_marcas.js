//----------------------- FUNCION FOCUS  ----------------------
focus_marca = function getFocus() 
{           
    $('#addMarca').on('shown.bs.modal', function() 
    {
      $('#txtnombreMarca').trigger('focus')
    });
}

//-----------------------INSERTAR ----------------------
function insertar_marca()
{
 
  var nombre = document.getElementById("txtnombreMarca").value.toUpperCase();

  var parametros = 
  {
    "Actividad": "insertar_marca",
    "nombre": nombre
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_marcas.php',
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
                $('#addMarca').modal('hide');
                $("#pages").load('views/view_marcas.php');
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

//-----------------------CARGAR MODAL ----------------------
function showmodal_marca(idMarca)
{
    var idMarca1 = idMarca;
    var parametros = {"Actividad": "showmodal_marca", "idMarca": idMarca1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_marcas.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#txtmarcaId').val(response[0]); 
                $('#txtnombreMarca1').val(response[1]); 
            },
            complete: function(){}
        });
    // Focus en update
    $('#updMarca').on('shown.bs.modal', function() 
    {
      $('#txtnombreMarca1').trigger('focus')
    });
}

//-----------------------INSERTAR ----------------------
function update_marca()
{
 
  var nombre = document.getElementById("txtnombreMarca1").value.toUpperCase();
  var idMarca = document.getElementById("txtmarcaId").value;

  var parametros = 
  {
    "Actividad": "update_marca",
    "nombre": nombre,
    "idMarca": idMarca
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_marcas.php',
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
                $('#updMarca').modal('hide');
                $("#pages").load('views/view_marcas.php');
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

// Modelos

function showModelos(idMarca)
{
    $("#pages").load("views/view_modelos.php?idMarca="+idMarca);   
}

function titulo(idMarca)
{
    var idMarca1 = idMarca;
    var parametros = {"Actividad": "obtenerMarca", "idMarca": idMarca1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_marcas.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#titulo').html('Listado de Modelos de ' + response[0]); 
                $('#txtnombreMarca100').val(response[0]); 
                $('#txtmarcaId100').val(idMarca); 
            },
            complete: function(){}
        });
}

//----------------------- FUNCION FOCUS  ----------------------
focus_modelo = function getFocus() 
{           
    $('#addModelo').on('shown.bs.modal', function() 
    {
      $('#txtnombreModelo').trigger('focus')
    });
}

//-----------------------INSERTAR ----------------------
function insertar_modelo()
{
  var modelo = document.getElementById("txtnombreModelo").value.toUpperCase();
  var marca = document.getElementById("txtmarcaId100").value;

  var parametros = 
  {
    "Actividad": "insertar_modelo",
    "modelo": modelo,
    "marca": marca
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_modelos.php',
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
                $('#addModelo').modal('hide');
                $("#pages").load("views/view_modelos.php?idMarca="+marca);  
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

//-----------------------CARGAR MODAL ----------------------
function showmodal_modelo(idModelo)
{
    var idModelo1 = idModelo;
    var parametros = {"Actividad": "showmodal_modelo", "idModelo": idModelo1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_modelos.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#txtnombreMarca101').val(response[2]); 
                $('#txtmarcaId101').val(response[1]); 

                $('#txtmodeloId').val(response[0]); 
                $('#txtnombreModelo1').val(response[3]); 
            },
            complete: function(){}
        });
    // Focus en update
    $('#updModelo').on('shown.bs.modal', function() 
    {
      $('#txtnombreModelo1').trigger('focus')
    });
}

//----------------------- UPDATE ----------------------
function update_modelo()
{
  var modelo = document.getElementById("txtnombreModelo1").value.toUpperCase();
  var marca = document.getElementById("txtmarcaId101").value;
  var modeloId = document.getElementById("txtmodeloId").value;

  var parametros = 
  {
    "Actividad": "update_modelo",
    "modelo": modelo,
    "marca": marca,
    "modeloId": modeloId
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_modelos.php',
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
                $('#updModelo').modal('hide');
                $("#pages").load("views/view_modelos.php?idMarca="+marca);  
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