function listado_ingresos()
{
  $("#pages").load('views/view_ingresos.php');
}

function agregar_ingresos()
{
  $("#pages").load('views/view_ingresosadd.php');
}

focus_pclientes1 = function getFocus() 
{           
    $('#addCliente2').on('shown.bs.modal', function() 
    {
      $('#txtnombre').trigger('focus');
    });
}

function insertar_cliente2()
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
                $('#addCliente2').modal('hide');                 
                $('#addCliente2').on('hidden.bs.modal', function(e) {
                  const $formulario = $('#addCliente2').find('form');
                  $formulario[0].reset();
                });
                
              });
              f_cargarclientes();
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

function f_cargarclientes()
{
  $.post("negocios/php/tree_clientes.php", { Actividad: "cargarProveedores" }, function(data)
  {
      $("#lstcliente").html(data);
  });
}

function ObtenerPrecio()
{
  var producto = $("#lstproducto [value='"+$('#cboproducto').val()+"']").data("value");
  var parametros = {"Actividad": "ObtenerPrecio", "producto": producto};
  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_ingresos.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
            $('#txtPrecio').val(response[0]); 
          },
          complete: function(){}
      });
}

function insertar_ingreso()
{
    Swal.fire({
        // title: 'Aprobar',
        text: "Desea Generar Detalle de Ingreso?",
        icon: 'question',
        
        showDenyButton: true,
        denyButtonColor: '#d33',
        denyButtonText: `No`,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Generar!'
      }).then((result) => {
        if (result.isConfirmed) 
        {
            var proveedor = $("#lstcliente [value='"+$('#cbocliente').val()+"']").data("value");
            var ingresofecha = document.getElementById("dtfechaingreso").value;
            var codigo = document.getElementById("txtcodigo").value;
            var tipo = document.getElementById("cbotipo").value;

            var parametros = 
            {
                "Actividad": "insertar_ingreso",
                "proveedor": proveedor,
                "ingresofecha": ingresofecha,
                "codigo": codigo,
                "tipo": tipo
            };

            $.ajax(
            {
                data:   parametros,
                url:   'negocios/php/tree_ingresos.php',
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
                    });
                    actualizar_ingreso(response.cot_id);                                                                                         
                    }
                    else if (response.validacion!='')
                    {
                    Swal.fire({
                        icon: 'error',
                        title: 'Informacion',
                        html: ' ' + response.validacion
                    });
                    }
                    else
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
    }); 
}

function actualizar_ingreso(idrm)
{
  $("#pages").load("views/view_ingresosupd.php?id="+idrm);
}

function instertar_detingreso()
{
    var producto = $("#lstproducto [value='"+$('#cboproducto').val()+"']").data("value");
    var precio = document.getElementById("txtPrecio").value;
    var cantidad = document.getElementById("txtcantidad").value;    
    var ingresoid = document.getElementById("txtingresoid").value;
    
    var parametros = 
    {
        "Actividad": "instertar_detingreso",
        "producto" : producto,
        "precio": precio,
        "cantidad": cantidad,
        "ingresoid": ingresoid        
    };

    $.ajax(
    {
        data:   parametros,
        url:   'negocios/php/tree_ingresos.php',
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
            });

                $('#cboproducto').val('');
                $('#txtcantidad').val('');   
                $('#txtPrecio').val(''); 
                $("#tbl_detalleingreso tfoot").remove();
                $("#tbl_detalleingreso tr>td").remove();
                showmodal_ingresodetalle(ingresoid);      
                $('#cboproducto').trigger('focus')          
            }
            else if (response.validacion!='')
            {
            Swal.fire({
                icon: 'error',
                title: 'Informacion',
                html: ' ' + response.validacion
            });
            }
            else
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

function eliminar_lineadetalleingreso(ingred_id, producto, cantidad)
{
  var ingresoid = document.getElementById("txtingresoid").value;
  Swal.fire({
    // title: 'Aprobar',
    text: "Desea Eliminar Linea de Ingreso?",
    icon: 'question',
    
    showDenyButton: true,
    denyButtonColor: '#d33',
    denyButtonText: `No`,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Eliminar!'
  }).then((result) => {
    if (result.isConfirmed) 
    {
      var ingred_id1 = ingred_id;
      var producto1 = producto;
      var cantidad1 = cantidad;

      var parametros = 
      {
          "Actividad": "eliminar_lineadetalleingreso",
          "ingresodet" : ingred_id1,
          "producto": producto1,
          "cantidad": cantidad1,
          "ingresoid": ingresoid
      };

      $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_ingresos.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function(){},
          success: function(response)
          {
          if (response=='exito')
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
              title: 'Eliminado Correctamente'
            })
            $("#tbl_detalleingreso tfoot").remove();
            $("#tbl_detalleingreso tr>td").remove();
            showmodal_ingresodetalle(ingresoid); 
          }
          else
          {
            Swal.fire({
              icon: 'error',
              title: 'Informacion',
              html: 'Error al Eliminar'
            });
          }
          },
      complete: function(){}
      }); 
    }
  });  
}

function showmodal_ingreso (ingreso)
{
  var ingreso1 = ingreso;
  var parametros = {"Actividad": "showmodal_ingreso", "ingreso": ingreso1};
  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_ingresos.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
              $('#txtingresoid').val(response[0]);
              $('#txtcodigo1').val(response[1]);
              $('#dtfechaingreso1').val(response[2]);
              $('#cbocliente1').val(response[3]);
              $('#cbotipo1').val(response[4]);
              $("#tbl_detalleingreso tfoot").remove();
              $("#tbl_detalleingreso tr>td").remove();
              showmodal_ingresodetalle(ingreso1);
                  
          },
          complete: function(){}
      });
}

function showmodal_ingresodetalle(ingreso_id)
{
  var ingreso_id1 = ingreso_id;
  var parametros = 
  { 
      "Actividad": "lst_ingresodetalle", 
      "ingreso_id": ingreso_id1
  };

    $.ajax(
        {
        data:     parametros,
        url:      'negocios/php/tree_ingresos.php',
        type:     'post',
        dataType: 'json',
        cache:    false,
        beforeSend: function(){},
        success: function(response)
        {
          var filas= $("tbl_detalleingreso tr").length;
          var i=1;
          while(i<filas)
              {
                  document.getElementById("tbl_detalleingreso").deleteRow(1);
                  i=i+1;
              }
          $('#tbl_detalleingreso').append(response);
          var countFilas = $("#tbl_detalleingreso tbody tr").length;
          $("#wrapperTableConf").fadeIn(1500); 
        }, 
        complete: function(){}
        });
}

function abonosIngresos(idrm)
{
  $("#pages").load("views/view_abonosIngreso.php?id="+idrm);
}

function fcn_datosgenerales(ingresoID)
{
  var parametros = {"Actividad": "fcn_datosgenerales", "ingresoID": ingresoID};

  //Limpiar labels
  var labelObj = document.getElementById("lblFecha");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblCodigo");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblProveedor");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblTipo");
  labelObj.innerHTML = "";

  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_ingresos.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
            
            $('#txtIngreID').val(response[0]); 
            $('#lblFecha').append(response[1]); 
            $('#lblCodigo').append(response[2]); 
            $('#lblProveedor').append(response[3]); 
            $('#lblTipo').append(response[4]); 
            $('#txtTotal').val(response[5]); 
            $('#txtPagado').val(response[6]); 
            $('#txtPendiente').val(response[7]); 
            $('#txtPendiente1').val(response[8]); 

            if (parseFloat(response[7]) == 0)
            {
              document.getElementById("dtFechapago").disabled = true;
              document.getElementById("cboTipoTago").disabled = true;
              document.getElementById("txtRecibo").disabled = true;
              document.getElementById("txtValorPago").disabled = true;
              document.getElementById("btnInsertarPago").disabled = true;
            }


          },
          complete: function(){}
      });
}

function insertar_pago()
{
    var total = document.getElementById("txtTotal").value;
    var pagado = document.getElementById("txtPagado").value;
    var pendiente = document.getElementById("txtPendiente1").value;
    var valor = document.getElementById("txtValorPago").value;
    

    if (total == pagado || parseFloat(pendiente) ==0)
    {
        Swal.fire('No Hay Saldo Pendiente');
        return;
    }
    else if (valor > parseFloat(pendiente))
    {
      Swal.fire('El Pago es Mayor Pendiente');
      return;
    }
    else
    {

      debugger;
      // Saber si queda cancelado
      var pen = parseFloat(pendiente)-parseFloat(valor);
      var estado = 0;
      if (pen ==0 )
      {
         estado = 2;
      }
      else { estado = 1}

      var fecha = document.getElementById("dtFechapago").value;
      var tipo = document.getElementById("cboTipoTago").value;
      var recibo = document.getElementById("txtRecibo").value;      
      var ingresoID = document.getElementById("txtIngreID").value;
     
      var parametros = 
      {
      "Actividad": "insertar_pago",
      "fecha": fecha,
      "tipo": tipo,
      "valor": valor,
      "ingresoID": ingresoID,
      "recibo": recibo,
      "estado": estado
      };
  
      $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_ingresos.php',
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
                  })
                  fcn_datosgenerales(ingresoID);
                  $('#txtRecibo').val('');
                  $('#txtValorPago').val('');
                  $("#t_pagos tfoot").remove();
                  $("#t_pagos tr>td").remove();
                  fcn_listaPagosIngreso(ingresoID);
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
}

function fcn_listaPagosIngreso(ingresoID)
{
 var parametros = 
 {
     "Actividad": "buscar_pagosIngreso",
     "ingresoID": ingresoID
 };

 $.ajax(
     {
         data:   parametros,
         url:   'negocios/php/tree_ingresos.php',
         type:  'post',
         dataType: 'json',
         cache:false,
         beforeSend: function(){},
         success: function(response)
         {
             // console.log(response.mensaje);
             if (response.mensaje=='exito')
             {
                 var filas= $("t_pagos tr").length;
                 var i=1;
                 while(i<filas)
                     {
                     document.getElementById("t_pagos").deleteRow(1);
                     i=i+1;
                     }
                 $("#t_pagos tr>td").remove();
                 $('#t_pagos').append(response.tablatalonario);
                 var countFilas = $("#t_pagos tbody tr").length;

                //  if (parseInt(response.total) > 0) {
                //     document.getElementById("btnrecibo").disabled = false;            
                //   } else {
                //     document.getElementById("btnrecibo").disabled = true;
                //   }

                 $("#wrapperTableConf").fadeIn(1500); 
             }             
             else  if (response.mensaje=='error')
             {
                 Swal.fire({
                     icon: 'error',
                     title: 'Informacion',
                     html: 'Error al Buscar Informacion'
                     });
             }
         },
     complete: function(){}
     });   
}

//----------------------- FUNCION FOCUS  ----------------------
focus_producto1 = function getFocus() 
{           
    $('#addProducto1').on('shown.bs.modal', function() 
    {
      $('#txtCodigo1').trigger('focus')
    })
}

//-----------------------INSERTAR PRODUCTO ----------------------
function insertar_producto1()
{ 
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
            cargarProductos();
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

function cargarProductos()
{
  $.post("negocios/php/tree_productos.php", { Actividad: "cargarProductos" }, function(data)
  {
      $("#lstproducto").html(data);
  });
}