$(document).ready(function() {
    onTableAjaxParcela();
    confirmDialogParcela();
    notifityParcela();
})

function onTableAjaxParcela(){

        var atividadeId = $('.tableParcelas').attr('atividade')
        oTableParcela = $('.tableParcelas').dataTable({
            "bJQueryUI": true,
            "bSortClasses": false,
            "sPaginationType": "full_numbers",
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": Routing.generate("ajaxParcela", {'id' : atividadeId}),
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
                                    ajaxLoadDialog(Routing.generate("parcelaNew", {'id' : atividadeId}));
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
                                    ajaxLoadDialogParcela(Routing.generate("parcelaEdit", {'id' : id}));
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
                                               ajaxDeleteParcela(Routing.generate("removeParcela", {"id" : id})); 
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

function confirmDialogParcela(){

	$( "#dialog-confirm-parcela" ).dialog({
                autoOpen: false,
		resizable: false,
		height:"auto",
                modal: true,
		buttons: {
                         "Finalizar": function() {
                               ajaxFinalizarParcela(Routing.generate("finalizarParcela", {"id" : id})); 
                               $(this).dialog("close");
                         },
                         "Cancelar": function(){
                                $(this).dialog("close");
                         }
		}
	});
        
     return false;
}

function ajaxFinalizarParcela(url){
    
    $.ajax({
            type: 'POST',
            url: url,
            success: function(result){
                $('.redraw').each(function(){
                         $(this).dataTable().fnReloadAjax();
                });
                $(".hidden").addClass("DTTT_disabled");
                $.each(result,function(){
                    notifityParcela(this)
                });
                
                return false;
            }
   })
    
}

function ajaxLoadDialogParcela(url){
    
    $.ajax({
            type: 'GET',
            url: url,
            success: function(result){
                
                if (result['erro']){
                    notifityParcela('erro02')
                    return false;
                }
                
                $(".simpleDialog").html(result);
                $(".simpleDialog").dialog('open');
                onReadyAjax();
                return false;
            }
   })
    
}

function notifityParcela(tipo){
    $.pnotify.defaults.styling = "jqueryui";
    
    if (tipo == 'erro01'){
        $.pnotify({
            title: 'Atenção!',
            text: 'Valor pago inválido.',
            type: 'info'
        }); 
    }
    
    if (tipo == 'erro02'){
        $.pnotify({
            title: 'Atenção!',
            text: 'Já está finalizada.',
            type: 'info'
        }); 
    }
    
    if (tipo == 'erro03'){
        $.pnotify({
            title: 'Atenção!',
            text: 'Insira uma data de pagamento.',
            type: 'info'
        }); 
    }
    
    if (tipo == 'erro04'){
        $.pnotify({
            title: 'Erro!',
            text: 'Saldo insuficiente na conta.',
            type: 'error'
        });
    }
    
    if (tipo == 'success'){
        $.pnotify({
            title: 'Sucesso!',
            text: 'Registro finalizado.',
            type: 'info'
        }); 
    }
    
}

function ajaxDeleteParcela(url){

    $.ajax({
            type: 'POST',
            url: url,
            success: function(result){
                
                if (result['erro']){
                    notifityParcela('erro02')
                    return false;
                }
                
                $('.redraw').each(function(){
                         $(this).dataTable().fnReloadAjax();
                });
                $(".hidden").addClass("DTTT_disabled");
                notifity("delete");
                return false;
            }
   })
    
}



