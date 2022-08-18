function listado_facturas()
{
  $("#pages").load('views/view_ingresos.php');
}

function agregar_facturas()
{
  $("#pages").load('views/view_facturasadd.php');  
}

focus_pclientes2 = function getFocus() 
{           
    $('#addCliente3').on('shown.bs.modal', function() 
    {
      $('#txtnombre').trigger('focus');
    });
}

function insertar_cliente3()
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
                $('#addCliente3').modal('hide');                 
                $('#addCliente3').on('hidden.bs.modal', function(e) {
                  const $formulario = $('#addCliente3').find('form');
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
  $.post("negocios/php/tree_clientes.php", { Actividad: "cargarClientes" }, function(data)
  {
      $("#lstcliente").html(data);
  });
}

function insertar_factura()
{
    Swal.fire({
        // title: 'Aprobar',
        text: "Desea Generar Detalle de Factura?",
        icon: 'question',
        
        showDenyButton: true,
        denyButtonColor: '#d33',
        denyButtonText: `No`,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Generar!'
      }).then((result) => {
        if (result.isConfirmed) 
        {        
            var fecha = document.getElementById("dtFechaFactura").value;            
            var cliente = $("#lstcliente [value='"+$('#cbocliente').val()+"']").data("value");
            var tipoFactura = document.getElementById("cboTipoFactura").value;
            var tipoCodigo = document.getElementById("cboTipoRecibo").value;
            var empresa = document.getElementById("cboEmpresa").value;

            var parametros = 
            {
                "Actividad": "insertar_factura",
                "fecha": fecha,
                "cliente": cliente,
                "tipoFactura": tipoFactura,
                "tipoCodigo": tipoCodigo,
                "empresa": empresa
            };

            $.ajax(
            {
                data:   parametros,
                url:   'negocios/php/tree_facturas.php',
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
                    actualizar_factura(response.fact_id);                                                                                         
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

function actualizar_factura(idrm)
{
  $("#pages").load("views/view_facturasupd.php?id="+idrm);
}

function showmodal_factura (factura)
{
  var factura1 = factura;
  var parametros = {"Actividad": "showmodal_factura", "factura": factura1};
  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_facturas.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
              $('#txtFacturaId').val(response[0]);
              $('#txtCodigo1').val(response[1]);
              $('#dtFechaFactura1').val(response[2]);
              $('#cbocliente1').val(response[3]);
              $('#cboTipoFactura1').val(response[4]);
              $('#cboTipoRecibo1').val(response[5]);
              $('#cboEmpresa1').val(response[6]);
              $("#tbl_detalleFactura tfoot").remove();
              $("#tbl_detalleFactura tr>td").remove();
              showmodal_facturadetalle(factura1);
          },
          complete: function(){}
      });
}

function showmodal_facturadetalle(factura)
{
  var factura1 = factura;
  var parametros = 
  { 
      "Actividad": "lst_facturadetalle", 
      "factura": factura1
  };

    $.ajax(
        {
        data:     parametros,
        url:      'negocios/php/tree_facturas.php',
        type:     'post',
        dataType: 'json',
        cache:    false,
        beforeSend: function(){},
        success: function(response)
        {
          var filas= $("tbl_detalleFactura tr").length;
          var i=1;
          while(i<filas)
              {
                  document.getElementById("tbl_detalleFactura").deleteRow(1);
                  i=i+1;
              }
          $('#tbl_detalleFactura').append(response);
          var countFilas = $("#tbl_detalleFactura tbody tr").length;
          $("#wrapperTableConf").fadeIn(1500); 
        }, 
        complete: function(){}
        });
}

function ObtenerPrecio()
{
  var producto = $("#lstproducto [value='"+$('#cboproducto').val()+"']").data("value");
  var parametros = {"Actividad": "ObtenerPrecio", "producto": producto};
  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_facturas.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
            $('#txtPrecio').val(response[0]); 
            $('#txtDisponible').val(response[1]); 
          },
          complete: function(){}
      });
}

function instertar_detfactura()
{
    var producto = $("#lstproducto [value='"+$('#cboproducto').val()+"']").data("value");
    var precio = document.getElementById("txtPrecio").value;
    var cantidad = document.getElementById("txtCantidad").value;    
    var factura = document.getElementById("txtFacturaId").value;
    var disponible = document.getElementById("txtDisponible").value;
    
    var parametros = 
    {
        "Actividad": "instertar_detfactura",
        "producto" : producto,
        "precio": precio,
        "cantidad": cantidad,
        "factura": factura,
        "disponible": disponible       
    };

    $.ajax(
    {
        data:   parametros,
        url:   'negocios/php/tree_facturas.php',
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
                $('#txtCantidad').val('');   
                $('#txtPrecio').val(''); 
                $('#txtDisponible').val(''); 
                $("#tbl_detalleFactura tfoot").remove();
                $("#tbl_detalleFactura tr>td").remove();
                showmodal_facturadetalle(factura);      
                $('#cboproducto').trigger('focus');          
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

function eliminar_lineadetallefactura(factd_id, producto, cantidad)
{
  var factura = document.getElementById("txtFacturaId").value;
  Swal.fire({
    // title: 'Aprobar',
    text: "Desea Eliminar Linea de Factura?",
    icon: 'question',
    
    showDenyButton: true,
    denyButtonColor: '#d33',
    denyButtonText: `No`,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Eliminar!'
  }).then((result) => {
    if (result.isConfirmed) 
    {
      var factd_id1 = factd_id;
      var producto1 = producto;
      var cantidad1 = cantidad;

      var parametros = 
      {
          "Actividad": "eliminar_lineadetallefactura",
          "facturad" : factd_id1,
          "producto": producto1,
          "cantidad": cantidad1,
          "factura": factura
      };

      $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_facturas.php',
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
            $("#tbl_detalleFactura tfoot").remove();
            $("#tbl_detalleFactura tr>td").remove();
            showmodal_facturadetalle(factura); 
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

function InsertarPagosFactura()
{
  var factura = document.getElementById("txtFacturaId").value;
  var parametros = {"Actividad": "InsertarPagosFactura", "factura": factura};
  
  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_facturas.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
         
            var conteo = response[0];            
            // Si no hay productos vendidos regresamos
            if ( parseFloat(conteo) > 0 )
            {
              // Si hay productos vendidos vamos a registrar Pagos
            $("#pages").load("views/view_abonosFactura.php?id="+factura);
                  
            }
            else {
              Swal.fire('No existe detalle de venta.')
              return;
            }
          },
          complete: function(){}
      });   
}

function fcn_datosgenerales(facturaID)
{
  var parametros = {"Actividad": "fcn_datosgenerales", "facturaID": facturaID};

  //Limpiar labels
  var labelObj = document.getElementById("lblFecha");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblCodigo");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblCliente");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblTipoFactura");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblTipoCodigo");
  labelObj.innerHTML = "";
  var labelObj = document.getElementById("lblEmpresa");
  labelObj.innerHTML = "";

  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_facturas.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
            
            $('#txtFactId').val(response[0]); 
            $('#lblFecha').append(response[1]); 
            $('#lblCodigo').append(response[2]); 
            $('#lblCliente').append(response[3]); 
            $('#lblTipoFactura').append(response[4]); 
            $('#lblTipoCodigo').append(response[5]); 
            $('#lblEmpresa').append(response[6]); 
            $('#txtTotal').val(response[7]); 
            $('#txtPagado').val(response[8]); 
            $('#txtPendiente').val(response[9]); 
            $('#txtPendiente1').val(response[10]); 

            if (parseFloat(response[9]) == 0)
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

function fcn_listaPagosFacturas(facturaID)
{
 var parametros = 
 {
     "Actividad": "buscar_pagosFactura",
     "facturaID": facturaID
 };

 $.ajax(
     {
         data:   parametros,
         url:   'negocios/php/tree_facturas.php',
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
      var facturaID = document.getElementById("txtFactId").value;
     
      var parametros = 
      {
      "Actividad": "insertar_pago",
      "fecha": fecha,
      "tipo": tipo,
      "valor": valor,
      "facturaID": facturaID,
      "recibo": recibo,
      "estado": estado
      };
  
      $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_facturas.php',
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
                  fcn_datosgenerales(facturaID);
                  $('#txtRecibo').val('');
                  $('#txtValorPago').val('');
                  $("#t_pagos tfoot").remove();
                  $("#t_pagos tr>td").remove();
                  fcn_listaPagosFacturas(facturaID);
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