//----------------------- LLAMAR FORMULARIO PARA AGREGAR  ----------------------
function agregar_cotizacion()
{
  $("#pages").load('views/view_cotizacionadd.php');
}

function listado_cotizacion()
{
  $("#pages").load('views/view_cotizacion.php');
}

focus_pclientes1 = function getFocus() 
{           
    $('#addCliente2').on('shown.bs.modal', function() 
    {
      $('#txtnombre').focus();
    });    
}

function f_cargarclientes()
{
  $.post("negocios/php/tree_cotizacion.php", { Actividad: "cargarclientes" }, function(data)
  {
      $("#lstcliente").html(data);
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

function insertar_cotizacion()
{
    Swal.fire({
        // title: 'Aprobar',
        text: "Desea Generar Detalle de Cotizacion?",
        icon: 'question',
        
        showDenyButton: true,
        denyButtonColor: '#d33',
        denyButtonText: `No`,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Generar!'
      }).then((result) => {
        if (result.isConfirmed) 
        {
            var cliente = $("#lstcliente [value='"+$('#cbocliente').val()+"']").data("value");
            var cotfecha = document.getElementById("dtfechacot").value;

            var proceso = document.getElementById("txtProceso").value;
            var fechaapertura = document.getElementById("dtFechaApertura").value;
            var validez = document.getElementById("txtValidez").value;
            var entrega = document.getElementById("txtEntrega").value;

            var parametros = 
            {
                "Actividad": "insertar_cotizacion",
                "cliente" : cliente,
                "cotfecha": cotfecha,
                "proceso": proceso,
                "fechaapertura": fechaapertura,
                "validez": validez,
                "entrega": entrega
            };

            $.ajax(
            {
                data:   parametros,
                url:   'negocios/php/tree_cotizacion.php',
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
                      actualizar_cotizacion(response.cot_id);                                               
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

function actualizar_cotizacion(idrm)
{
  $("#pages").load("views/view_cotizacionupd.php?id="+idrm);
}

function showmodal_cotizacion (cot_id)
{
  var cot_id1 = cot_id;
  var parametros = {"Actividad": "showmodal_cotizacion", "cot_id": cot_id1};
  $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_cotizacion.php',
          type:  'post',
          dataType: 'json',
          cache: false,
          beforeSend: function () {},
          success: function(response)
          {
              $('#txtcotid1').val(response[0]);
              $('#txtcodigo1').val(response[1]);
              $('#dtfechacot1').val(response[2]);
              $('#cbocliente1').val(response[3]);

              $('#txtProceso1').val(response[4]);

              var d = moment(response[5]).format('YYYY-MM-DDTHH:mm');
              $('#dtFechaApertura1').val(d);

              $('#txtValidez1').val(response[6]);
              $('#txtEntrega1').val(response[7]);

              $("#tbl_detallecotizacion1 tfoot").remove();
              $("#tbl_detallecotizacion1 tr>td").remove();
              showmodal_cotdetalle1(cot_id);                  
          },
          complete: function(){}
      });
}

function showmodal_cotdetalle1(cot_id)
{
  var cot_id1 = cot_id;
  var parametros = 
  { 
      "Actividad": "lst_cotizaciondetalle1", 
      "cot_id": cot_id1
  };

    $.ajax(
        {
        data:     parametros,
        url:      'negocios/php/tree_cotizacion.php',
        type:     'post',
        dataType: 'json',
        cache:    false,
        beforeSend: function(){},
        success: function(response)
        {
          var filas= $("tbl_detallecotizacion1 tr").length;
          var i=1;
          while(i<filas)
              {
                  document.getElementById("tbl_detallecotizacion1").deleteRow(1);
                  i=i+1;
              }
          $('#tbl_detallecotizacion1').append(response);
          var countFilas = $("#tbl_detallecotizacion1 tbody tr").length;
          $("#wrapperTableConf").fadeIn(1500); 
        }, 
        complete: function(){}
        });
}

function fcn_buscarPrecio1()
{
  var producto = $("#lstproducto1 [value='"+$('#cboproducto1').val()+"']").data("value");
  var parametros = {"Actividad": "buscarPrecio", "producto": producto};
  $.ajax(
    {
        data:   parametros,
        url:   'negocios/php/tree_cotizacion.php',
        type:  'post',
        dataType: 'json',
        cache: false,
        beforeSend: function () {},
        success: function(response)
        {
            $('#txtPrecio1').val(response[0]);            
        },
        complete: function(){}
    });
}

function instertar_detcotizacion1()
{
    var producto = $("#lstproducto1 [value='"+$('#cboproducto1').val()+"']").data("value");
    var cantidad = document.getElementById("txtcantidad1").value;
    var cotid = document.getElementById("txtcotid1").value;
    var precio = document.getElementById("txtPrecio1").value;

    var parametros = 
    {
        "Actividad": "instertar_detcotizacion",
        "producto" : producto,
        "cantidad": cantidad,
        "cotid": cotid,
        "precio": precio
    };

    $.ajax(
    {
        data:   parametros,
        url:   'negocios/php/tree_cotizacion.php',
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

                $('#cboproducto1').val('');
                $('#txtPrecio1').val('');  
                $('#txtcantidad1').val(''); 

                $("#tbl_detallecotizacion1 tfoot").remove();
                $("#tbl_detallecotizacion1 tr>td").remove();
                showmodal_cotdetalle1(cotid);                
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

function eliminar_lineadetallecot1(cot_id, cotd_id)
{
  Swal.fire({
    // title: 'Aprobar',
    text: "Desea Eliminar Linea de Cotizacion?",
    icon: 'question',
    
    showDenyButton: true,
    denyButtonColor: '#d33',
    denyButtonText: `No`,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Eliminar!'
  }).then((result) => {
    if (result.isConfirmed) 
    {
      var cot_id1 = cot_id;
      var cotd_id1 = cotd_id;

      var parametros = 
      {
          "Actividad": "eliminar_lineadetallecot",
          "cot_id" : cot_id1,
          "cotd_id": cotd_id1
      };

      $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_cotizacion.php',
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
            $("#tbl_detallecotizacion1 tfoot").remove();
            $("#tbl_detallecotizacion1 tr>td").remove();
            showmodal_cotdetalle1(cot_id); 
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

function reporte_cotizacion(cot_id)
{
    var cot_id1 = cot_id;
    url = "negocios/php/reportes/rpt_cotizacion.php?id="+cot_id1;
    window.open(url);
}

function estado_cotizacion(cot_id, cot_estado, tipocot)
{
  if (cot_estado == 0 )
  {
    var pregunta = "Desea Rechazar La Cotizacion?";
  }
  if (cot_estado == 1 || cot_estado == 2 )
  {
    var pregunta = "Desea Aprobar La Cotizacion?";
  }

  Swal.fire({
    // title: 'Aprobar',
    text: pregunta,
    icon: 'question',
    
    showDenyButton: true,
    denyButtonColor: '#d33',
    denyButtonText: `No`,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Actualizar!'
  }).then((result) => {
    if (result.isConfirmed) 
    {
      var cot_id1 = cot_id;
      var cot_estado1 = cot_estado;

      var parametros = 
      {
          "Actividad": "actualizarEstadoCot",
          "cot_id" : cot_id1,
          "cot_estado": cot_estado1
      };

      $.ajax(
      {
          data:   parametros,
          url:   'negocios/php/tree_cotizacion.php',
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
              title: 'Actualizado Correctamente'
            })
            $("#pages").load('views/view_cotizacion.php?tipo='+tipocot);            
          }
          else
          {
            Swal.fire({
              icon: 'error',
              title: 'Informacion',
              html: 'Error al Actualizar'
            });
          }
          },
      complete: function(){}
      }); 
    }
  });  
}

// Actualiza cantidad detalle
function update_cantidadCotizacionDetalle(dato1)
{
  debugger;
    dato = dato1;
    var parametros = 
    {
        "Actividad": "update_cantidadCotizacionDetalle",
        "dato" : dato
    }

    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_cotizacion.php',
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
                  });    

                  var parts = dato.split(":");
                 
                  $("#tbl_detallecotizacion1 tfoot").remove();
                  $("#tbl_detallecotizacion1 tr>td").remove();
                  showmodal_cotdetalle1(parts[1]);                         
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

// Actualiza precio detalle
function update_precioCotizacionDetalle(dato1)
{
  debugger;
    dato = dato1;
    var parametros = 
    {
        "Actividad": "update_precioCotizacionDetalle",
        "dato" : dato
    }

    $.ajax(
        {
            data:   parametros,
            url:   'negocios/php/tree_cotizacion.php',
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
                  });    

                  var parts = dato.split(":");
                 
                  $("#tbl_detallecotizacion1 tfoot").remove();
                  $("#tbl_detallecotizacion1 tr>td").remove();
                  showmodal_cotdetalle1(parts[1]);                         
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

