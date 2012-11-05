$(document).ready(function() {
    onTableAjaxServicoAdmin();
    filtrarServicos();
  
})

function onTableAjaxServicoAdmin(){
        
        oTableServicoAdmin = $('.tableServicoAdmin').dataTable({
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
            "sAjaxSource": Routing.generate("ajaxServicoAdmin", {"id" : $(".tableServicoAdmin").attr("atividade")}),
            "aoColumns": [
                { "mDataProp": "descricao" },
                { "mDataProp": "valor"},
                { "mDataProp": "fornecedor.nome"},
                { "mDataProp": "id" },
                    
             ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                 $('td:eq(1)', nRow).html(formataDinheiro(aData['valor']+""));
            },
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                var valor = 0;
                for (var i=0; i < aaData.length; i++){
                    valor += parseFloat(aaData[i]["valorNumber"]);
                }
                
                var nCells = nRow.getElementsByTagName('th');
                nCells[1].innerHTML = formataDinheiro(valor+"");
            },
            "aoColumnDefs": [{"bVisible": false, "aTargets": [3]}],
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
                                if (clickTableTerminate()){
                                    ajaxLoadDialog(Routing.generate("funcionario_servico_admin_new",{"id" : $(".tableServicoAdmin").attr("atividade")}));
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
                                 ajaxLoadDialog(Routing.generate("funcionario_servico_admin_edit", {"id" : id}));
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
                                            ajaxDelete(Routing.generate("funcionario_servico_admin_delete", {"id" : id})); 
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

function filtrarServicos(){
    
//    $('.mes, .ano').change(function(){
//        
//       // oTableServicoAdmin.fnNewAjax(Routing.generate("escalaSalario", {'mes' : $(".mes").val(),'ano' : $(".ano").val()}));                   
//        oTableServicoAdmin.dataTable().fnReloadAjax();
//        return false;
//    })
    
    
}
//
//$(".tableSalario tbody td.check").live("click", function(){
//    
//            var data = oTableSalario.fnGetData(this.parentNode);
//            var url = Routing.generate("escalaFunCheck", {"id" : data['id']})
//            $.ajax({
//                type: 'POST',
//                url: url,
//                success: function(){
//                    $('.redraw').dataTable().fnReloadAjax();
//                    onReadyAjax();  
//                    return false;
//                }
//            })
//})


