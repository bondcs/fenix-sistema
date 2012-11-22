$(document).ready(function() {
    onTableAjaxTransacaoSistema();
    confirmDialogParcela();
    notifityParcelaTransacoes();
    filtrar();
    
    
})

function onTableAjaxTransacaoSistema(inicio, fim, tipo){

        oTableTransacao = $('.tableTransacoes').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": Routing.generate("ajaxTransacao", {'inicio' : $(".inicio").val(), 'fim' : $(".fim").val(),'tipo' : $(".tipo").val(), 'conta' : $('.tableTransacoes').attr("conta")
            }),
            "aoColumns": [
                { "mDataProp": "data_pagamento",
                    "sClass": "center"},
                { "mDataProp": "descricao" },
                { "mDataProp": "tipo"},
                { "mDataProp": "formaPagamento.nome"},
                { "mDataProp": "valor_pago",
                    "sClass": "center"},
                { "mDataProp": "id" },
                    
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
            "aoColumnDefs": [{"bVisible": false, "aTargets": [5]}],
            "bAutoWidth": false,
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                var valor = 0;
                for (var i=0; i < aaData.length; i++){
                    valor += parseFloat(aaData[i]["valorNumber"]);
                }
                
                var nCells = nRow.getElementsByTagName('th');
                nCells[1].innerHTML = formataDinheiro(valor+"");
            },
//            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
//                
////                if ( aData['movimentacao']['valor'] <= aData['movimentacao']['valor_pago'] )
////                {
////                  $(nRow).addClass('verde');
////                }
//                if ( aData['finalizado'])
//                {
//                  $(nRow).addClass('riscado');
//                }
//            },
            "sDom": '<"H"Tfr>t<"F"ip>',
                "oTableTools": {
                    "sRowSelect": "single",
                    "sSelectedClass": "row_selected",
                    "sRowSelect": "single",
                    "sSwfPath": "/"+url_dominio+"/web/bundles/fnxadmin/table/tools/swf/copy_csv_xls_pdf.swf",
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
                        }
                       
                    ]
                }
        });
        
   
}


function filtrar(){
    
    $('#filtrar').click(function(){
        var flag = false;
        if ($(".inicio").val() == ""){
            notifityParcela('erro01');
            flag = true;
        }
        
        if ($(".fim").val() == ""){
            notifityParcela('erro02')
            flag = true
        }
        
        if (flag){
            return false;
        }
        
        $('.tableTransacoes').dataTable().fnNewAjax(Routing.generate("ajaxTransacao", {'inicio' : $(".inicio").val(), 'fim' : $(".fim").val(),'tipo' : $(".tipo").val(), 'conta' : $('.tableTransacoes').attr("conta")}));
        $('.tableTransacoes').dataTable().fnReloadAjax();
    })
    
} 

function notifityParcelaTransacoes(tipo){
    $.pnotify.defaults.styling = "jqueryui";
    
    if (tipo == 'erro01'){
        $.pnotify({
            title: 'Atenção!',
            text: 'Data inicial inválida',
            type: 'info'
        }); 
    }
    
    if (tipo == 'erro02'){
        $.pnotify({
            title: 'Atenção!',
            text: 'Data final inválida',
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
    
    if (tipo == 'success'){
        $.pnotify({
            title: 'Sucesso!',
            text: 'Parcela finalizada.',
            type: 'info'
        }); 
    }
    
}




