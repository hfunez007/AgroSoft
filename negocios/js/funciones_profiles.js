
$(document).ready(function()
{
  showprofiles();
  showoption();
  showoption1();

  $("#checkTodos").change(function () 
  {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
  });
  
  $("#checkTodos1").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
  });

});

focus_nuevoperfil = function getFocus() 
{           
  $('#addperfil').on('shown.bs.modal', function () { $('#txtnuevoperfil').focus(); })
}

////////////////////////////////////Mostrar todos los roles o perfiles////////////////////////////////////////
function showprofiles()
{  
  var parametros = 
  {
    "Actividad": "showprofiles",    
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_profiles.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () { fncmostrarimg1() },    
          success: function(response)
          {
            var filas= $("#t_profiles tr").length;
            var i=1;
            while(i<filas)
            {   
              document.getElementById("t_profiles").deleteRow(1);
              i=i+1;
            }
            $('#t_profiles').append(response);
            var countFilas = $("#t_profiles tbody tr").length;
          },
          complete: function(){ fncocultarimg1() }
        });
}

////////////////////////////////////Mostrar todas las opciones del Menú////////////////////////////////////////
function showoption()
{
  var parametros = 
  {
    "Actividad": "showoption",  
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_profiles.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () { fncmostrarimg1() },    
          success: function(response)
          {
            var filas= $("#add_profiles tr").length;
            var i=1;
            while(i<filas)
            {
              document.getElementById("add_profiles").deleteRow(1);
              i=i+1;
            }
            $('#add_profiles').append(response);
            var countFilas = $("#add_profiles tbody tr").length;
          },
          complete: function(){ fncocultarimg1() }
        });
}

///////////////////////////////////Insert Nombre de Nuevo Perfil////////////////////////////////

function insernprofile()
{
  var nuevoperfil = document.getElementById("txtnuevoperfil").value;
  var parametros = { "Actividad": "newrol","nuevoperfil": nuevoperfil };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_profiles.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () { fncmostrarimg1() },    
          success: function(response)
          {
            if (response=='exito')
            {
              swal.fire({ 
               //title:response,
               html:'REGISTRO INGRESADO CORRECTAMENTE',
               type: 'success',
               showCloseButton: true,
               confirmButtonText: 'Aceptar'
             }).then(function(){ $('body').removeClass('modal-open');$('.modal-backdrop').remove();
             $("#pages").load('views/view_perfiles.php');});
            }
            else if (response=='existe')
            {
              swal.fire({ 
               //title:response,
               html:'EL REGISTRO YA EXISTE',
               type: 'info',
               showCloseButton: true,
               confirmButtonText: 'Aceptar'
              });
            }
            else
            {
              swal.fire({ 
               //title:response,
               html:'ERROR AL INGRESAR EL REGISTRO',
               type: 'info',
               showCloseButton: true,
               confirmButtonText: 'Aceptar'
             });
            }

              
          },
          complete: function(){ fncocultarimg1() }
        });
}



////////////////////////////////////Insertar nuevo perfil////////////////////////////////////////
function inserprofile()
{  
  var estado =document.getElementsByName("options[]");
  var desc =document.getElementsByName("options1[]");
  var url =document.getElementsByName("options2[]");
  var opt =document.getElementsByName("options3[]");
  
  for (i=0;i<estado.length && desc.length && url.length && opt.length; i++)
  {
    var estado1 = estado[i].checked ? 1 : 0;
    var desc1=desc[i].value;
    var url1=url[i].value;
    var opt1=opt[i].value;
    
    var parametros = 
    {
      "Actividad": "inserprofile",
      "estado1": estado1,
      "desc1": desc1,
      "url1": url1,
      "opt1": opt1
    };
    $.ajax({
            data:   parametros,
            url:   'negocios/php/tree_profiles.php',
            type:  'post',
            dataType: 'json',
            cache:false,
            beforeSend: function () { fncmostrarimg1() },    
            success: function(response){},
          });
  }
}


////////////////////////////////////Gif de Carga////////////////////////////////////////
function fncmostrarimg1()
{
  $('#circle').css('display','');
  $("#circle").animate({width:30},2000);  
}

function fncocultarimg1()
{
  $("#circle").animate({width:30},1000, function(){$('#circle').css('display','none');}); 
}

////////////////////////////////////Mostrar todas las opciones del Menú para editar roles////////////////////////////////////////
function showoption1(idrm)
{  
  var idrm2=idrm;
  var parametros = 
  {
    "Actividad": "showoption1",
    "idrm2": idrm2
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_profiles.php',
          type:  'post',
          dataType: 'json',
          cache:false,
            beforeSend: function () { fncmostrarimg1() },    
            success: function(response)
            {
              var filas= $("#edit_profiles tr").length;
              var i=1;
              while(i<filas)
              {
                document.getElementById("edit_profiles").deleteRow(1);
                i=i+1;
              }
              $('#edit_profiles').append(response);
              var countFilas = $("#edit_profiles tbody tr").length;
            },
            complete: function(){  fncocultarimg1() }
        });
}

/////////////////////////Mostrar información del perfil///////////////////////

function infop(idrm)
{
  var idrm1=idrm;
  var parametros = 
  {
    "Actividad": "infop",
    "idrm1": idrm1
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_profiles.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () {},
          success: function(response){ $('#txteditperfil').val(response[0]); },
          complete: function(){}
         });
}

////////////////////////////////////Insertar nuevo perfil////////////////////////////////////////
function updateprofile()
{  
  var est =document.getElementsByName("options4[]");
  var desc =document.getElementsByName("options5[]");
  var url =document.getElementsByName("options6[]");
  var opt =document.getElementsByName("options7[]");
  
  for (i=0;i<est.length && url.length && opt.length && desc.length; i++)
  {
     var idperfil = document.getElementById("txteditperfil").value;
     var est1 = est[i].checked ? 1 : 0;
     var desc1=desc[i].value;
     var url1=url[i].value;
     var opt1=opt[i].value;
     
     var parametros = 
     {
       "Actividad": "updateprofile",
        "idperfil": idperfil,
        "est": est1,
        "desc1": desc1,
        "url1": url1,
        "opt1": opt1
    };
    $.ajax({
            data:   parametros,
            url:   'negocios/php/tree_profiles.php',
            type:  'post',
            dataType: 'json',
            cache:false,
            beforeSend: function () { fncmostrarimg1() },    
            success: function(response){},
            complete: function(){}
          })}

      swal.fire({ 
              //title:response,
               html:'REGISTRO INGRESADO CORRECTAMENTE',
               type: 'success',
               showCloseButton: true,
               confirmButtonText: 'Aceptar'
             }).then(function(){ $('body').removeClass('modal-open');$('.modal-backdrop').remove();
             $("#pages").load('views/view_perfiles.php');});
}

