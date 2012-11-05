$(document).ready(function() {
    onTableAjaxEscalaPatru();
    filtrarEscalaPatru();
    habilitarEscalaPatru();
  
})

function onTableAjaxEscalaPatru(){
        
        oTableEscalaPatru = $('.tableEscalaPatru ').dataTable({
            "bJQueryUI": true,
            "oLanguage": {
                "sProcessing":   "Processando...",
                "sLengthMenu":   "Mostrar _MENU_ registros",
                "sZeroRecords":  "Não foram encontrados resultados",
                "sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty":    "Mostrando de 0 até 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
                "sInfoPostFix":  "",
                "sSearch":       "Buscar:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Primeiro",
                    "sPrevious": "Anterior",
                    "sNext":     "Seguinte",
                    "sLast":     "Último"
                }
            },
            "sPaginationType": "full_numbers",
            "bPaginate": false,
            "bLengthChange": false,
            
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": Routing.generate("escalaPatruAjax", {
                                                'data' : $(".data").val()
            }),
            "aoColumns": [
                { "mDataProp": "funcionariosString" },
                { "mDataProp": "descricao"},
                { "mDataProp": "escalaEx",
                    "sClass": "center"},
                { "mDataProp": "ativo",
                    "sClass": "center check"},
                { "mDataProp": "id" },
                    
             ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                if (aData['ativo'] == true){
                    $('td:eq(3)', nRow).html('<img src="'+imageUrl+'check2.png">');
                }else{
                    $('td:eq(3)', nRow).html('<img src="'+imageUrl+'uncheck2.png">');
                }
            },
            "aoColumnDefs": [{"bVisible": false, "aTargets": [4]},
                              {"bSortable": false, "aTargets": [3]}],
            "bAutoWidth": false,
            "sDom": '<"H"Tfr>t<"F"ip>',
            "oTableTools": {
                    "sRowSelect": "single",
                    "sSwfPath": "/"+url_dominio+"/web/bundles/fnxadmin/table/tools/swf/copy_csv_xls_pdf.swf",
                    "sSelectedClass": "row_selected",
                    "aButtons": [
                        "copy",
                        "print",
                        {
                            "sExtends": "pdf",
                            "mColumns": "visible",
                            "sPdfOrientation": "landscape",
                            "sPdfMessage": "Escalas"
                        },
                        {
                            "sExtends": "text",
                            "sButtonText": "Escalas",
                            "fnClick" : function(){
                                 loadUltimas();
                            }
                        }, 
                        {
                            "sExtends": "text",
                            "sButtonText": "Adicionar",
                            "fnClick" : function(){
                                 ajaxLoadDialog(Routing.generate("escalaPatruAdd",{'data' : $(".data").val()}), "Escala para Patrulhamento");
                            }
                        }, 
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Editar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]["id"];
                                 ajaxLoadDialog(Routing.generate("escalaPatruEdit", {"id" : id, 'data' : $(".data").val()}), "Escala para Patrulhamento");
                                 
                            }
                        },
                         {
                            "sExtends": "select_single",
                            "sButtonText": "Deletar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]["id"];
                                 $( "#dialog-confirm" ).dialog("open");
                                 $( "#dialog-confirm" ).dialog("option", "buttons", {
                                     "Deletar": function() {
                                            ajaxDelete(Routing.generate("escalaPatruRemove", {"id" : id})); 
                                            $(this).dialog("close");
                                     },
                                     "Cancelar": function(){
                                            $(this).dialog("close");
                                     }
                                 } );
                                 return false;
                                 
                            }
                        }
                    ]
                }
        });
        
        
}

function filtrarEscalaPatru(){
    $('.data').change(function(){
        oTableEscalaPatru .fnNewAjax(Routing.generate("escalaPatruAjax", {'data' : $(".data").val()}));                     
        oTableEscalaPatru .dataTable().fnReloadAjax();
        return false;
    }) 
}

function habilitarEscalaPatru(){
    
    $("#habilitarEscala").change(function(){
        if ($('#habilitarEscala').is(':checked')){
           $(".inicio").removeAttr("disabled");
           $(".fim").removeAttr("disabled");
        }else{
           $(".inicio").attr("disabled", "disabled");
           $(".fim").attr("disabled", "disabled") 
        }
    });
}

$(".tableEscalaPatru tbody td.check").live("click", function(){
    
            var data = oTableEscalaPatru.fnGetData(this.parentNode);
            var url = Routing.generate("escalaPatruCheck", {"id" : data['id']})
            $.ajax({
                type: 'POST',
                url: url,
                success: function(){
                    $('.redraw').dataTable().fnReloadAjax();
                    onReadyAjax();  
                    return false;
                }
            })
})

function loadUltimas(){
    
    $.ajax({
            type: 'POST',
            url: Routing.generate("escalaPatruUltima",{'data' : $(".data").val()}),
            success: function(result){
                if (result['notifity'] == 'erroEscala01'){
                     notifity("erroEscala01")
                     return false;
                }

                onReadyAjax();
                $('.tableEscalaPatru').dataTable().fnReloadAjax();
            }
      }) 
}