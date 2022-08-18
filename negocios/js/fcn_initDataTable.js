

function _initDataTable(tbl,model,param){

var table = $("#"+tbl).DataTable({

        lengthChange:false, pageLength:8, autoWidth:false, responsive:true, retrieve: true,
        
        buttons: [ 
        {extend: 'copy',text:'<i class="fas fa-copy"></i> Copiar',titleAttr: 'Copiar'},
        {extend: 'print',text:'<i class="fas fa-print"></i> Imprimir',titleAttr: 'Imprimir'},
        {extend: 'excel',text:'<i class="far fa-file-excel"></i> Excel',titleAttr: 'Exportar a Excel',title: null, sheetName: 'DatosEducacion'},
        {extend: 'pdf',text:'<i class="far fa-file-pdf"></i> PDF',titleAttr: 'Exportar a PDF'}
        ],

        language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
    });
 
    $("#"+tbl).dataTable().fnClearTable();
    table.rows.add($(_setDataList(param,model))).draw();
    table.buttons().container().appendTo("#"+tbl+"_wrapper .col-md-6:eq(0)");
}

function _setDataList(param,model){
var listData;
$.ajax({
url: "negocios/php/"+model+".php",
data:param,
type: "POST", async: false, cache: false,
}).done(function(response, textStatus, jqXHR){
listData=response;
}).fail(function(jqXHR, textStatus, errorThrown){
console.error("The following error occurred: "+ textStatus, errorThrown);
}).always(function(){});
return listData;
}


