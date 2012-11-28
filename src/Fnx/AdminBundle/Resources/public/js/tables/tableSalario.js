$(document).ready(function() {
    onTableAjaxSalario();
    filtrarSalarios();
  
})

function onTableAjaxSalario(){
        
        oTableSalario = $('.tableSalario').dataTable({
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
            "sAjaxSource": Routing.generate("escalaSalario", {'mes' : $(".mes").val(),'ano' : $(".ano").val()}),
            "aoColumns": [
                { "mDataProp": null},
                { "mDataProp": "nome" },
                { "mDataProp": "salario.salario"},
                { "mDataProp": "salario.pagamento.bonus"},
                { "mDataProp": "dependentes",
                    "sClass" : "center"},
                { "mDataProp": "salario.ultimoPagamento",
                    "sClass" : "center"},
                { "mDataProp": null},
                { "mDataProp": "id" },
                { "mDataProp": "salario.pagamento.id"}
                    
             ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                 if (aData['salario']['pagamento']['pago']){
                      $('td:eq(0)', nRow).html('<input disabled="disabled" type="checkbox" name="pagamentos[]" value="'+aData['salario']['pagamento']['id']+'">');
                      $(nRow).addClass('riscado');
                 }else{
                      $('td:eq(0)', nRow).html('<input type="checkbox" name="pagamentos[]" value="'+aData['salario']['pagamento']['id']+'">');
                 }
                 $('td:eq(2)', nRow).html(formataDinheiro(aData['salario']['salario']+""));
                 $('td:eq(3)', nRow).html(formataDinheiro(aData['salario']['pagamento']['bonus']+""));
                 $('td:eq(4)', nRow).html(aData['dependentes']+" x "+formataDinheiro(aData['valorDependente']+""));
                 $('td:eq(6)', nRow).html(formataDinheiro(aData['dependentes']*aData['valorDependente']+aData['salario']['salario']+aData['salario']['pagamento']['bonus']+""));
                 
            },
            "aoColumnDefs": [{"bVisible": false, "aTargets": [7]},
                             {"bVisible": false, "aTargets": [8]},
                             {"bSortable": false, "aTargets": [0]}],
            "bAutoWidth": false,
            "sDom": '<"H"Tfr<"toolbar02">>t<"F"ip>',
            "oTableTools": {
                    "sRowSelect": "single",
                    "sSwfPath": url_dominio+"/fnxadmin/table/tools/swf/copy_csv_xls_pdf.swf",
                    "sSelectedClass": "row_selected",
                    "aButtons": [
                        {
                            "sExtends": "print",
                            "sButtonText": '<img src="'+imageUrl+'print-icone.png">Print'
                        },
                        {
                            "sExtends": "pdf",
                            "mColumns": "visible",
                            "sPdfOrientation": "landscape",
                            "sPdfMessage": "Escalas",
                            "sButtonText": '<img src="'+imageUrl+'pdf-icone.png">PDF'
                        },
                        {
                            "sExtends": "text",
                            "sButtonText": '<img src="'+imageUrl+'money-icone.png">Pagar',
                            "fnClick" : function(){
                                  var form = $("#formSalario")
                                  $.ajax({
                                        type: 'POST',
                                        url: Routing.generate("salarioPagamento", {'mes' : $(".mes").val()}),
                                        data: form.serialize(),
                                        success: function(result){
                                            notifity(result['notifity']);
                                            if (result['notifity'] == 'noSelected'){
                                                 return false;
                                            }

                                            onReadyAjax();
                                            $('.tableSalario').dataTable().fnReloadAjax();
                                        }
                                  }) 
                            }
                        },
                        {
                            "sExtends": "text",
                            "sButtonText": '<img src="'+imageUrl+'eng-icone.png">Gerar',
                            "fnClick" : function(){
                                  var form = $("#formSalario")
                                  $.ajax({
                                        type: 'POST',
                                        url: Routing.generate("salarioGerarPagamento", {'mes' : $(".mes").val(),'ano' : $(".ano").val()}),
                                        data: form.serialize(),
                                        success: function(result){
                                            notifity(result['notifity']);
                                            if (result['notifity'] == 'noSelected'){
                                                 return false;
                                            }

                                            onReadyAjax();
                                            $('.tableSalario').dataTable().fnReloadAjax();
                                        }
                                  }) 
                            }
                        },
                        {
                            "sExtends": "select_single",
                            "sButtonText": '<img src="'+imageUrl+'money-add-icone.png">Bônus',
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 var id = aaData[0]["id"];
                                 var pagamentoId = aaData[0]['salario']['pagamento']['id'];
                                 ajaxLoadDialog(Routing.generate("funcionarioSalarioEdit", {"pagamentoId" : pagamentoId}));
                                 
                            }
                        }   
                        
                    ]
                }
        });
        
         $("div.toolbar02").html('<b>Salários</b>');
        
}

function filtrarSalarios(){
    
    $('.mes, .ano').change(function(){
        oTableSalario.fnNewAjax(Routing.generate("escalaSalario", {'mes' : $(".mes").val(),'ano' : $(".ano").val()}));                   
        oTableSalario.dataTable().fnReloadAjax();
        return false;
    })
      
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