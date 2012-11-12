$(document).ready(function() {
    onTableAjaxTransacaoGeral();
    filtrarGeral();
    
    
})

function onTableAjaxTransacaoGeral(){

        oTableTransacaoGeral = $('.tableTransacoesGeral').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bSortClasses": false,
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "iDisplayLength": 30,
            "aaSorting": [[0,'desc']],
            "sAjaxSource": Routing.generate("ajaxTransacaoGeral", {'inicio' : $(".inicio").val(),
                                                'fim' : $(".fim").val(),
                                                'tipo' : $(".tipo").val(),
                                                'data' : $(".tipoData input:checked").val() ? $(".tipoData input:checked").val() : 'a',
                                                'conta' : $(".conta").val(),
                                                'categoria' : $(".categoria").val() ? $(".categoria").val() : 0,
                                                'doc' : $(".doc").val() ? $(".doc").val() : 0
            }),
            "aoColumns": [
                { "mDataProp": "parcela.registro.id" },
                { "mDataProp": "descricao" },
                { "mDataProp": "formaPagamento.nome"},
                { "mDataProp": "tipo",
                    "sClass": "center"},
                { "mDataProp": "valor",
                    "sClass": "center"},
                { "mDataProp": "valor_pago",
                    "sClass": "center"},
                { "mDataProp": "dt_vencimento",
                    "sClass": "center"},
                { "mDataProp": "data_pagamento",
                    "sClass": "center"},
                { "mDataProp": "situacao" },
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
            "aoColumnDefs": [{"bVisible": false, "aTargets": [9]},
                             {"bVisible": false, "aTargets": [8]}],
            "bAutoWidth": false,
//            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
//                var valor = 0;
//                for (var i=0; i < aaData.length; i++){
//                    valor += parseFloat(aaData[i]["valorNumber"]);
//                }
//                
//                var nCells = nRow.getElementsByTagName('th');
//                nCells[1].innerHTML = formataDinheiro(valor+"");
//            },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
               
                if (aData['parcela']['finalizado'])
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
                    "sSwfPath": "/"+url_dominio+"/web/bundles/fnxadmin/table/tools/swf/copy_csv_xls_pdf.swf",
                    "aButtons": [
                        "copy",
                        "print",
                        {
                            "sExtends": "pdf",
                            "mColumns": "visible",
                            "sPdfOrientation": "landscape",
                            "sPdfMessage": "Movimentações de Conta"
                        },
                        {
                            "sExtends": "text",
                            "sButtonText": "Adicionar",
                            "fnClick" : function(){
                                 ajaxLoadDialog(Routing.generate("movimentacaoNew"));
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Finalizar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]["id"];
                                 $( "#dialog-confirm-parcela" ).dialog("open");
                                 return false;
                                 
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": "Editar",
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]["id"];
                                 ajaxLoadDialogParcela(Routing.generate("parcelaGeralEdit", {'id' : id}));
                                 
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
                                            ajaxDeleteParcela(Routing.generate("removeParcelaGeral", {"id" : id})); 
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


function filtrarGeral(){
    
    $('#filtrarGeral').click(function(){

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
        
        oTableTransacaoGeral.fnNewAjax(Routing.generate("ajaxTransacaoGeral",
                            {'inicio' : $(".inicio").val(),
                             'fim' : $(".fim").val(),
                             'tipo' : $(".tipo").val(),
                             'data' : $(".tipoData input:checked").val() ? $(".tipoData input:checked").val() : 'a',    
                             'conta' : $(".conta").val(),
                             'categoria' : $(".categoria").val() ? $(".categoria").val() : 0,
                             'doc' : $(".doc").val() ? $(".doc").val() : 0
                            }));
                            
        oTableTransacaoGeral.dataTable().fnReloadAjax();
        
        $(".doc").val("");
        return false;
    })
    
    
} 





