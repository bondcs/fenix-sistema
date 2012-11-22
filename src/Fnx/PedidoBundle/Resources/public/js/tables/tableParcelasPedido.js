$(document).ready(function() {
    onTableAjaxParcelaPedido();
    confirmDialogParcelaPedido();
})

function onTableAjaxParcelaPedido(){

        var pedidoId = $('.tableParcelasPedido').attr('pedido')
        oTableParcelaPedido = $('.tableParcelasPedido').dataTable({
            "bJQueryUI": true,
            "bSortClasses": false,
            "sPaginationType": "full_numbers",
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": Routing.generate("ajaxParcelaPedido", {'id' : pedidoId}),
            "aoColumns": [
                { "mDataProp": "numero",
                    "sClass": "control center"},
                { "mDataProp": "movimentacao.data",
                    "sClass": "control center"},
                { "mDataProp": "movimentacao.formaPagamento.nome" },
                { "mDataProp": "movimentacao.valor",
                    "sClass": "control center"},
                { "mDataProp": "dt_vencimento",
                    "sClass": "control center"},
                { "mDataProp": "movimentacao.valor_pago",
                    "sClass": "control center"},
                { "mDataProp": "movimentacao.data_pagamento",
                    "sClass": "control center"},
                { "mDataProp": "movimentacao.id" },
                    
             ],
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
            "aoColumnDefs": [{"bVisible": false, "aTargets": [7]}],
            "bAutoWidth": false,
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                var totalValor = 0;
                var totalPago = 0;
                for (var i=0; i < aaData.length; i++){
                    totalValor += parseFloat(aaData[i]['movimentacao']["valorNumber"]);
                    totalPago += parseFloat(aaData[i]['movimentacao']["valor_pagoNumber"]);
                }
                
                var nCells = nRow.getElementsByTagName('th');
		nCells[1].innerHTML = formataDinheiro(totalValor+"");
                nCells[3].innerHTML = formataDinheiro(totalPago+"");
            },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                
                if (aData['finalizado'])
                {
                  $(nRow).addClass('verde');
                }else{
                    if (aData['situacao'] == 'Em atraso'){
                        $(nRow).addClass('vermelho');
                    }
                }
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
                                    ajaxLoadDialog(Routing.generate("parcelaPedidoNew", {'id' : pedidoId}));
                                 }
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Finalizar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 if (clickTableTerminate()){
                                    var aaData = this.fnGetSelectedData()
                                    id = aaData[0]['movimentacao']["id"];
                                    $( "#dialog-confirm-parcela" ).dialog("open");
                                 }
                                 return false;
                                 
                                 
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Editar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 if (clickTableTerminate()){
                                    var aaData = this.fnGetSelectedData()
                                    id = aaData[0]['movimentacao']["id"];
                                    ajaxLoadDialogParcela(Routing.generate("parcelaPedidoEdit", {'id' : id}));
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
                                    id = aaData[0]['movimentacao']["id"];
                                    $( "#dialog-confirm" ).dialog("open");
                                    $( "#dialog-confirm" ).dialog("option", "buttons", {
                                        "Deletar": function() {
                                               ajaxDeleteParcela(Routing.generate("removeParcelaPedido", {"id" : id})); 
                                               $(this).dialog("close");
                                        },
                                        "Cancelar": function(){
                                               $(this).dialog("close");
                                        }
                                    } );
                                 }
                                 return false;
                                 
                            }
                        }
                        
                    ]
                }
        });
        
   
}

function confirmDialogParcelaPedido(){

	$( "#dialog-confirm-parcela" ).dialog({
                autoOpen: false,
		resizable: false,
		height:"auto",
                modal: true,
		buttons: {
                         "Finalizar": function() {
                               ajaxFinalizarParcela(Routing.generate("finalizarParcelaPedido", {"id" : id})); 
                               $(this).dialog("close");
                         },
                         "Cancelar": function(){
                                $(this).dialog("close");
                         }
		}
	});
        
     return false;
}




