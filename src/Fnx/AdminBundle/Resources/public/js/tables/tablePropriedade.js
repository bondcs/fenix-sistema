$(document).ready(function() {
    onTableAjaxPropriedade()
})

function onTableAjaxPropriedade(){
        
        var imageUrl = '/tcc-fenix/web/bundles/fnxadmin/images/';
        oTableProp = $('.tablePropriedade').dataTable({
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
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": urlSource,
            "aoColumns": [
                { "mDataProp": "nome" },
                { "mDataProp": "quantidade",
                        "sClass": "center"},
                { "mDataProp": "descricao" },
                { "mDataProp": "id" },
                { "mDataProp": "checado",
                    "sClass": "center check"}
                    
            ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                if (aData['checado'] == true){
                    $('td:eq(3)', nRow).html('<img src="'+imageUrl+'check2.png">');
                }else{
                    $('td:eq(3)', nRow).html('<img src="'+imageUrl+'uncheck2.png">');
                }
            },
            "aoColumnDefs": [{"bVisible": false, "aTargets": [3]},
                             {"bSortable": false, "aTargets": [4]}],
            "bAutoWidth": false,
            "sDom": '<"H"Tfr>t<"F"ip>',
                "oTableTools": {
                    "sRowSelect": "single",
                    "sSelectedClass": "row_selected",
                    "aButtons": [
                        {
                            "sExtends": "text",
                            "sButtonText": "Adicionar",
                            "fnClick" : function(){
                                 ajaxLoadDialog(urlAdd);
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Editar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]["id"];
                                 ajaxLoadDialog(Routing.generate(routeEdit, {"id" : id}));
                                 
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
                                            ajaxDelete(Routing.generate(routeDelete, {"id" : id})); 
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
        
        
        $(".tablePropriedade tbody td.check").live("click", function(){
            var data = oTableProp.fnGetData(this.parentNode);
            url = Routing.generate("propriedadeCheck", {"id" : data['id']})
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

        
}