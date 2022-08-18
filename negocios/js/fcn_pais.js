//----------------------- FUNCION FOCUS  ----------------------
focus_pais = function getFocus() 
{           
    $('#addpais').on('shown.bs.modal', function() 
    {
      $('#idpais').trigger('focus')
    })
}

//-----------------------INSERTAR MEDIDA ----------------------
function insertar_pais()
{
  var idpais = document.getElementById("idpais").value;
  var nom_pais = document.getElementById("nom_pais").value.toUpperCase();  

  var parametros = 
  {
    "Actividad": "insertar_medida",
    "idpais": idpais,
    "nom_pais": nom_pais   
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_pais.php',
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
            $('#addpais').modal('hide');
            $("#pages").load('views/view_pais.php');
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
function showmodal_pais(idpais)
{
    var idpais1 = idpais;
    var parametros = {"Actividad": "modal_pais", "idpais": idpais1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_pais.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#txtidpais').val(response[0]); 
                $('#idpais1').val(response[1]); 
                $('#nom_pais1').val(response[2]);
            },
            complete: function(){}
        });
    $('#updpais').on('shown.bs.modal', function() 
    {
      $('#idpais1').trigger('focus')
    });
}

function update_pais()
{
  var iddpais = document.getElementById("txtidpais").value;
  var idpais = document.getElementById("idpais1").value;
  var nom_pais = document.getElementById("nom_pais1").value.toUpperCase();  

  var parametros = 
  {
    "Actividad": "update_pais",
    "idpais": idpais,
    "nom_pais": nom_pais  ,
    "iddpais":iddpais
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_pais.php',
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
            $('#updpais').modal('hide');
            $("#pages").load('views/view_pais.php');
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

