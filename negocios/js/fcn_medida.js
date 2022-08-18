//----------------------- FUNCION FOCUS  ----------------------
focus_medidas = function getFocus() 
{           
    $('#addMedida').on('shown.bs.modal', function() 
    {
      $('#txtMedidaNombre').trigger('focus')
    })
}

//-----------------------INSERTAR MEDIDA ----------------------
function insertar_medida()
{  
  var nom_medida = document.getElementById("txtMedidaNombre").value.toUpperCase();  
  var parametros = 
  {
    "Actividad": "insertar_medida",
    "nom_medida": nom_medida   
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_medidas.php',
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
            $('#addMedida').modal('hide');
            $("#pages").load('views/view_medidas.php');
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

//-----------------------CARGAR MODAL MEDIDAS ----------------------
function showmodal_medida(idmedida)
{
    var idmedida1 = idmedida;
    var parametros = {"Actividad": "modal_medida", "idmedida": idmedida1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_medidas.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#txtidmedida').val(response[0]);                 
                $('#txtMedidaNombre1').val(response[1]);
            },
            complete: function(){}
        });
        $('#updMedida').on('shown.bs.modal', function() 
        {
          $('#txtMedidaNombre1').trigger('focus')
        });
}

function update_medida()
{
  var id_medida = document.getElementById("txtidmedida").value;  
  var nom_medida = document.getElementById("txtMedidaNombre1").value.toUpperCase();  

  var parametros = 
  {
    "Actividad": "update_medida",    
    "nom_medida": nom_medida ,
    "id_medida": id_medida
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_medidas.php',
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
            $('#updMedida').modal('hide');
            $("#pages").load('views/view_medidas.php');
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

