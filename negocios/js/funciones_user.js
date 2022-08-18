
/////////////////////////Fnc Select barrio/colonia admin////////////////
function fncLlenarselectgenero1()
{
  $.post("negocios/php/tree_user.php", { Actividad: "CargarGenero1" }, function(data) { $("#selectgenero1").html(data);});
}

////////////////////////////////////Fnc Select rol de usuario//////////////////////////////////////////
function fncLlenarselectrol1()
{
  $.post("negocios/php/tree_user.php", { Actividad: "CargarRol1" }, function(data){ $("#selectrol1").html(data); });
}


////////////////////////////////////////isertar user nuevos administradores de barrios//////////////////////////
function insertuser()
{
  var identidad = document.getElementById("idper").value;
  var nombres = document.getElementById("nomper").value;   
  var apellidos = document.getElementById("apeper").value;
  var genero = document.getElementById("selectgenero1").value;
  var fechan = document.getElementById("fechaper").value;
  var telefono = document.getElementById("telper").value;
  var email = document.getElementById("emailper").value;   
  var rol = document.getElementById("selectrol1").value;

  if (identidad=='')
  {
    swal.fire({ 
            //title:response,
            html:'<b>Ingrese un número de Identidad </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'

          }).then(function(){ var posicion = $("#idper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#idper").css('border-color','red'); });
  }
  else if (identidad!='' && identidad.length<13)
  {
    swal.fire({ 
           //title:response,
          html:'<b>Ingrese un número de Identidad Correcto </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#idper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#idper").css('border-color','red'); });
   }
   else if (nombres=="")
   {
     swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar Nombres </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#nomper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#nomper").css('border-color','red'); });
   }
   else if (apellidos=="")
   {
      swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar Apellidos </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#apeper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#apeper").css('border-color','red'); });
   }
   else if (genero==0)
   {
     swal.fire({ 
               //title:response,
               html:'<b>Debe Seleccionar un Genero </b>',
               type: 'info',
               showCloseButton: true,
               confirmButtonText: 'Aceptar'

             }).then(function(){ var posicion = $("#selectgenero1").focus().top;
            $("html, body").animate({scrollTop: posicion}, 700);$("#selectgenero1").css('border-color','red'); });
   }
   else if (fechan=="")
   {
     swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar fecha de nacimiento </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#fechaper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#fechaper").css('border-color','red'); });
    }
    else if (telefono=="")
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar Teléfono </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#telper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#telper").css('border-color','red'); });
    }
    else if (telefono!='' && telefono.length<8)
    {
      swal.fire({ 
            //title:response,
            html:'<b>Ingrese un número de Teléfono Correcto </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#telper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#telper").css('border-color','red'); });
    }
    else if (email=="")
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar E-Mail </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#emailper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#emailper").css('border-color','red'); });
    }
    else if ($("#emailper").val().indexOf('@', 0) == -1 || $("#emailper").val().indexOf('.', 0) == -1) 
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar un E-Mail Correcto </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#emailper").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#emailper").css('border-color','red'); });
    }
    else if (rol==0)
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe Seleccionar el perfil del usuario</b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#selectrol1").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#selectrol1").css('border-color','red'); });
    }
    else
    {
      var parametros = 
      {
        "Actividad": "insertuser",
        "identidad": identidad,
        "nombres": nombres,
        "apellidos": apellidos,
        "genero": genero,
        "fechan": fechan,
        "telefono": telefono,
        "email": email,
        "rol": rol
      };

    $.ajax({

        data:   parametros,
        url:   'negocios/php/tree_user.php',
        type:  'post',
        dataType: 'json',
        cache:false,
        beforeSend: function () {

          fncmostrarimg1()
        },success: function(response)
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
                $("#pages").load('views/view_user.php');});
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
}

////////////////////////////////////////mostrar usuarios administradores de barrios////////////////////////

function showduser()
{
  var cargar = 0;
  var parametros = 
  {
    "Actividad": "showduser",
    "cargar": cargar
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_user.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () {},
          success: function(response)
          {
            var filas= $("#datosuserb tr").length;
            var i=1;
            while(i<filas)
            {
              document.getElementById("datosuserb").deleteRow(1);
              i=i+1;
            }
            $('#datosuserb').append(response);
            var countFilas = $("#datosuserb tbody tr").length;
          },
          complete: function(){}
        });
}

////////////////////////////////////buscar personas///////////////////////////////////////////////////////
//////////////////////////////////////VERIFICAR USUARIOS admin///////////////////////////////////////////////
function Get_IdPer()
{
  //DATOS DEL PARTICIPANTE
  var Identidad = document.getElementById("idper").value;
  if(Identidad!='' && Identidad.length==13)
  {
    var parametros={"actividad":"Get_IdPer","Identidad":Identidad};
    $.ajax({
            data:parametros,
             url:'negocios/php/tree_user.php',
             type:'post',
             dataType:'json',
             beforeSend:function(){ fncmostrarimg(); },
             success:function(response)
             {
               if(response==null)
               {
                 swal.fire({ 
                        //title:response,
                        html:'Número de Identidad no existe ',
                        type: 'info',
                        showCloseButton: true,
                        confirmButtonText: 'Aceptar'
                      });
                }
                else 
                {
                  $('#codper').val(response[0]);              
                  $('#nomper').val(response[2]+' '+response[3]);
                  $('#apeper').val(response[4]+' '+response[5]);
                  $('#selectgenero1').val(response[6]);
                  $('#fechaper').val(response[7]);
                }
             },
             complete: function() { fncocultarimg(); }            
          });
  }
  else
  {
    swal.fire({ 
          //title:response,
          html:'Existe un error en el número de identidad del participante, por favor asegúrese de ingresarla correctamente',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'

          });
  } 
}

/////////////////////////////////////////////cargar gif//////////////////////////////////////////////////////////
function fncmostrarimg()
{
  $('#ImgCarga').css('display','');
  $("#ImgCarga").animate({width:30},2000);  
}

function fncocultarimg()
{
  $("#ImgCarga").animate({width:30},1000, function(){$('#ImgCarga').css('display','none');}); 
}

//////////////////////////////////////360 insertar nuevo usuarios////////////////////
function fncmostrarimg1()
{
  $('#ImgCarga1').css('display','');
  $("#ImgCarga1").animate({width:30},2000);  
}

function fncocultarimg1()
{
  $("#ImgCarga1").animate({width:30},1000, function(){$('#ImgCarga').css('display','none');}); 
}

/////////////////////////update a informacion general del los usuarios administradores//////////////////////

function updateusuarios()
{
  var codper = document.getElementById("codper2").value;
  var identidad = document.getElementById("idper1").value;
  var nombres = document.getElementById("nomper1").value;   
  var apellidos = document.getElementById("apeper1").value;
  var genero = document.getElementById("selectgenero2").value;
  var fechan = document.getElementById("fechaper1").value;
  var telefono = document.getElementById("telper1").value;
  var email = document.getElementById("emailper1").value;
  var rol = document.getElementById("selectrol2").value;
  var estado = document.getElementById("selectestados2").value;
  
  if (identidad=='')
  {
    swal.fire({ 
          //title:response,
          html:'<b>Ingrese un número de Identidad </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
        }).then(function(){ var posicion = $("#idper1").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#idper1").css('border-color','red'); });
  }
  else if (identidad!='' && identidad.length<13)
  {
    swal.fire({ 
          //title:response,
          html:'<b>Ingrese un número de Identidad Correcto </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'

        }).then(function(){ var posicion = $("#idper1").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#idper1").css('border-color','red'); });
  }
  else if (nombres=="")
  {
     swal.fire({ 
          //title:response,
          html:'<b>Debe ingresar Nombres </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
        }).then(function(){ var posicion = $("#nomper1").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#nomper1").css('border-color','red'); });
  }
  else if (apellidos=="")
  {
    swal.fire({ 
         //title:response,
          html:'<b>Debe ingresar Apellidos </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
        }).then(function(){ var posicion = $("#apeper1").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#apeper1").css('border-color','red'); });
  }
  else if (genero==0)
  {
    swal.fire({ 
          //title:response,
          html:'<b>Debe Seleccionar un Genero </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
        }).then(function(){ var posicion = $("#selectgenero2").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#selectgenero2").css('border-color','red'); });
  }
  else if (fechan=="")
  {
    swal.fire({ 
          //title:response,
          html:'<b>Debe ingresar fecha de nacimiento </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
        }).then(function(){ var posicion = $("#fechaper1").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#fechaper1").css('border-color','red'); });
  }
  else if (telefono=="")
  {
    swal.fire({ 
          //title:response,
          html:'<b>Debe ingresar Teléfono </b>',
          type: 'info',
          showCloseButton: true,
          confirmButtonText: 'Aceptar'
        }).then(function(){ var posicion = $("#telper1").focus().top;
        $("html, body").animate({scrollTop: posicion}, 700);$("#telper1").css('border-color','red'); });
  }
  else if (telefono!='' && telefono.length<8)
  {

      swal.fire({ 
            //title:response,
            html:'<b>Ingrese un número de Teléfono Correcto </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#telper1").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#telper1").css('border-color','red'); });
   }
   else if (email=="")
   {
     swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar E-Mail </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#emailper1").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#emailper1").css('border-color','red'); });
    }
    else if ($("#emailper1").val().indexOf('@', 0) == -1 || $("#emailper1").val().indexOf('.', 0) == -1) 
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe ingresar un E-Mail Correcto </b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#emailper1").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#emailper1").css('border-color','red'); });
    }
    else if (rol==0)
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe Seleccionar el perfil del usuario</b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#selectrol2").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#selectrol2").css('border-color','red'); });
    }
    else if (estado==0)
    {
      swal.fire({ 
            //title:response,
            html:'<b>Debe Seleccionar un estado del usuario</b>',
            type: 'info',
            showCloseButton: true,
            confirmButtonText: 'Aceptar'
          }).then(function(){ var posicion = $("#selectestados2").focus().top;
          $("html, body").animate({scrollTop: posicion}, 700);$("#selectestados2").css('border-color','red'); });
    }
    else 
    {
      var parametros = {
                        "Actividad": "updateusuarios",
                        "codper": codper,
                        "identidad": identidad,
                        "nombres": nombres,
                        "apellidos": apellidos,
                        "genero": genero,
                        "fechan": fechan,
                        "telefono": telefono,
                        "email": email,
                        "rol": rol,
                        "estado": estado
                      };


      $.ajax({
                data:   parametros,
                url:   'negocios/php/tree_user.php',
                type:  'post',
                dataType: 'json',
                cache:false,
                beforeSend: function () {},
                success: function(response)
                {
                  if (response=='exito')
                  {
                    swal.fire({ 
                          //title:response,
                          html:'LOS REGISTRO SE MODIFICARON CORRECTAMENTE',
                          type: 'success',
                          showCloseButton: true,
                          confirmButtonText: 'Aceptar'
                        }).then(function(){ $('body').removeClass('modal-open');$('.modal-backdrop').remove();
                        $("#pages").load('views/view_user.php');});
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
                },complete: function(){}
      });
    }
}

//////////////////////////////////////cargar datos generales de los usuarios//////////////////////////////////
function showdactuser(idrm)
{
  var cod_user = idrm;
  var parametros = 
  {
    "Actividad": "showdactuser",
    "cod_user": cod_user
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_user.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () {},
          success: function(response)
          {
            var filas= $("#actuser tr").length;
            var i=1;
            while(i<filas)
            {
              document.getElementById("actuser").deleteRow(1);
              i=i+1;
            }
            $('#actuser').append(response);
            var countFilas = $("#actuser tbody tr").length;
          },
          complete: function(){}
        });
}

/////////////////////////informacion general de los usuarios///////////////////////

function infouser(idrm)
{
  document.getElementById("idper1").disabled = true;
  // document.getElementById("nomper1").disabled = true;
  // document.getElementById("apeper1").disabled = true;
  // document.getElementById("selectgenero2").disabled = true;
  // document.getElementById("fechaper1").disabled = true;
  // document.getElementById("telper1").disabled = true;
  // document.getElementById("emailper1").disabled = true;
  // document.getElementById("selectrol2").disabled = true;
  // document.getElementById("selectestados2").disabled = true;
  // document.getElementById("insert1").disabled = true;
  
  var idrm1=idrm;
  var parametros = 
  {
    "Actividad": "infouser",
    "idrm1": idrm1
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_user.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () {},
          success: function(response)
          {
            $('#codper2').val(response[0]); 
            $('#idper1').val(response[1]); 
            $('#nomper1').val(response[2]); 
            $('#apeper1').val(response[3]);
            $('#selectgenero2').val(response[4]); 
            $('#fechaper1').val(response[5]); 
            $('#telper1').val(response[6]); 
            $('#emailper1').val(response[7]);           
            $('#selectrol2').val(response[8]); 
            $('#selectestados2').val(response[9]); 
          },
          complete: function(){}
        });
}

//////////////////////////UPDATE//////////////////////////////////////
function fncLlenarselectgenero2()
{
  $.post("negocios/php/tree_user.php", { Actividad: "CargarGenero2" }, 
  function(data){ $("#selectgenero2").html(data); });
}

////////////////////////////////////Fnc Select rol de usuario//////////////////////////////////////////
function fncLlenarselectrol2()
{
  $.post("negocios/php/tree_user.php", { Actividad: "CargarRol2" }, 
  function(data){ $("#selectrol2").html(data); });
}

////////////////////////////////////Fnc Select Estados registrados//////////////////////////////////////////
function fncLlenarselectestados2()
{
  $.post("negocios/php/tree_user.php", { Actividad: "CargarEstados2" }, 
  function(data){ $("#selectestados2").html(data);});
}

////////habiliara campos de usuarios////////////////////////
function enabled1()
{
  document.getElementById("idper1").disabled = false;
  document.getElementById("nomper1").disabled = false;
  document.getElementById("apeper1").disabled = false;
  document.getElementById("selectgenero2").disabled = false;
  document.getElementById("fechaper1").disabled = false;
  document.getElementById("telper1").disabled = false;
  document.getElementById("emailper1").disabled = false;
  document.getElementById("selectrol2").disabled = false;
  document.getElementById("selectestados2").disabled = false;
  document.getElementById("insert1").disabled = false;
}

function showdactuser(idrm)
{
  var cod_barrio = idrm;
  var parametros = 
  {
    "Actividad": "showdactuser",
    "cod_barrio": cod_barrio
  };
  $.ajax({
          data:   parametros,
          url:   'negocios/php/tree_user.php',
          type:  'post',
          dataType: 'json',
          cache:false,
          beforeSend: function () {},
          success: function(response)
          {
            var filas= $("#actbarrios tr").length;
            var i=1;
            while(i<filas)
            {
              document.getElementById("actbarrios").deleteRow(1);
              i=i+1;
            }
            $('#actbarrios').append(response);
            var countFilas = $("#actbarrios tbody tr").length;
            $("#totalCursos").html(countFilas);
            $("#wrapperTableConf").fadeIn(1500);
        },
        complete: function(){}
      });
}