//----------------------- FUNCION FOCUS  ----------------------
focus_marca1 = function getFocus() 
{           
    $('#addMarca1').on('shown.bs.modal', function() 
    {
      $('#txtnombreMarca300').trigger('focus')
    });
}

//-----------------------INSERTAR ----------------------
function insertar_marca1()
{
 
  var nombre = document.getElementById("txtnombreMarca300").value.toUpperCase();

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
                $('#addMarca1').modal('hide');                
            });
            cargarMarcas();
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

function cargarMarcas()
{
  $.post("negocios/php/tree_marcas.php", { Actividad: "cargarMarcas"}, function(data)
  {
      $("#cboMarca").html(data);
  });
}

//----------------------- FUNCION FOCUS  ----------------------
focus_producto = function getFocus() 
{           
    $('#addProducto').on('shown.bs.modal', function() 
    {
      $('#txtCodigo').trigger('focus')
    })
}

//-----------------------INSERTAR PRODUCTO ----------------------
function insertar_producto()
{ 
  var codigo = document.getElementById("txtCodigo").value;
  var producto = document.getElementById("txtProducto").value.toUpperCase();
  var marca = document.getElementById("cboMarca").value;
  var medida = document.getElementById("cboMedida").value;
  var precioCompra = document.getElementById("txtPrecioCompra").value;
  var cantMinima = document.getElementById("txtCantMinima").value;
  var precioVenta = document.getElementById("txtPrecioVenta").value;
  var tipoImpuesto = document.getElementById("cboImpuesto").value;
  var comentario = document.getElementById("txtComentario").value.toUpperCase();
 
  var parametros = 
  {
    "Actividad": "insertar_producto",
    "codigo": codigo,
    "producto": producto,
    "marca": marca,
    "medida": medida,
    "precioCompra": precioCompra,
    "cantMinima": cantMinima,
    "precioVenta": precioVenta,
    "tipoImpuesto": tipoImpuesto,
    "comentario": comentario
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_productos.php',
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
            $('#addProducto').modal('hide');
            $("#pages").load('views/view_productos.php');});
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

//-----------------------CARGAR MODAL PRODUCTOS ----------------------
function showmodal_producto(idprod)
{
    var idprod1 = idprod;
    var parametros = {"Actividad": "modal_producto", "idprod": idprod1};
    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_productos.php',
            type:  'post',
            dataType: 'json',
            cache: false,
            beforeSend: function () {},
            success: function(response)
            {
                $('#idprod').val(response[0]); 
                $('#txtCodigo1').val(response[1]); 
                $('#txtProducto1').val(response[2]);
                $('#cboMarca1').val(response[3]);
                $('#cboMedida1').val(response[4]);
                $('#txtPrecioCompra1').val(response[5]);
                $('#txtCantMinima1').val(response[6]);
                $('#txtPrecioVenta1').val(response[7]);
                $('#cboImpuesto1').val(response[8]);
                $('#txtComentario1').val(response[9]);
                
            },
            complete: function(){}
        });
    $('#updProducto').on('shown.bs.modal', function() 
    {
      $('#txtCodigo1').trigger('focus')
    });
}

function update_producto()
{
  var idprod = document.getElementById("idprod").value;
  var codigo = document.getElementById("txtCodigo1").value;
  var producto = document.getElementById("txtProducto1").value.toUpperCase();
  var marca = document.getElementById("cboMarca1").value;
  var medida = document.getElementById("cboMedida1").value;
  var precioCompra = document.getElementById("txtPrecioCompra1").value;
  var cantMinima = document.getElementById("txtCantMinima1").value;
  var precioVenta = document.getElementById("txtPrecioVenta1").value;
  var tipoImpuesto = document.getElementById("cboImpuesto1").value;
  var comentario = document.getElementById("txtComentario1").value.toUpperCase();

  var parametros = 
  {
    "Actividad": "update_producto",
    "idprod":idprod,
    "codigo": codigo,
    "producto": producto,
    "marca": marca,
    "medida": medida,
    "precioCompra": precioCompra,
    "cantMinima": cantMinima,
    "precioVenta": precioVenta,
    "tipoImpuesto": tipoImpuesto,
    "comentario": comentario
  };

  $.ajax(
  {
      data:   parametros,
      url:   'negocios/php/tree_productos.php',
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
            $('#updProducto').modal('hide');
            $("#pages").load('views/view_productos.php');});
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

//----------------------- FUNCION FOCUS  ----------------------
focus_medida1 = function getFocus() 
{           
    $('#addMedida1').on('shown.bs.modal', function() 
    {
      $('#txtMedida').trigger('focus')
    });
}

//-----------------------INSERTAR ----------------------
function insertar_medida1()
{
 
  var nom_medida = document.getElementById("txtMedida").value.toUpperCase();

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
                $('#addMedida1').modal('hide');                
            });
            cargarMedidas();
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

function cargarMedidas()
{
  $.post("negocios/php/tree_medidas.php", { Actividad: "cargarMedidas"}, function(data)
  {
      $("#cboMedida").html(data);
  });
}