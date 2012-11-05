$(document).ready(function() {
    onTableAjaxLocal()
})

function onTableAjaxLocal(){
        
        oTableLocal = $('.tableLocais').dataTable({
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
                { "mDataProp": "descricao" },
                { "mDataProp": "cidade.nome" },
                { "mDataProp": "bairro" },
                { "mDataProp": "rua" },
                { "mDataProp": "numero" },
                { "mDataProp": "complemento" },
                { "mDataProp": "custo",
                    "sClass": "center"},
                { "mDataProp": "id" },
                    
             ],
            "aoColumnDefs": [{"bVisible": false, "aTargets": [7]}],
            "bAutoWidth": false,
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                var total = 0;
                for (var i=0; i < aaData.length; i++){
                    total += aaData[i]["custoNumber"]*1;
                }
                
                var nCells = nRow.getElementsByTagName('th');
		nCells[1].innerHTML = formataDinheiro(total+"");
            },
            "sDom": '<"H"Tfr>t<"F"ip>',
                "oTableTools": {
                    "sRowSelect": "single",
                    "sSelectedClass": "row_selected",
                    "aButtons": [
                        {
                            "sExtends": "text",
                            "sButtonText": "Adicionar",
                            "fnClick" : function(){
                                if (clickTableTerminate()){
                                   ajaxLoadDialog(urlAdd, "Local");
                                }
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Editar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                               if (clickTableTerminate()){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]["id"];
                                 ajaxLoadDialog(Routing.generate(routeEdit, {"id" : id}), "Local");
                               }
                                 
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Deletar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                if (clickTableTerminate()){
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
                        }
                        
                    ]
                }
        });
        
}