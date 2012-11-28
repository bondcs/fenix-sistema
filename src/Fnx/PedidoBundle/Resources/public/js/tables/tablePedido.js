$(document).ready(function() {
    onTableAjaxPedido();
    filtrarPedidos()
})

function onTableAjaxPedido(){

        var pedidoId = $('.tablePedido').attr('pedido')
        oTablePedido = $('.tablePedido').dataTable({
            "bJQueryUI": true,
            "bSortClasses": false,
            "sPaginationType": "full_numbers",
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": Routing.generate("ajaxPedido", {'status' : $(".status").val() }),
            "aoColumns": [
                { "mDataProp": "cliente.nome"},
                { "mDataProp": "data",
                    "sClass": "control center"},
                { "mDataProp": "previsao",
                    "sClass": "control center"},
                { "mDataProp": "data_fechamento",
                    "sClass": "control center"},
                { "mDataProp": "valor",
                    "sClass": "control center"},
                { "mDataProp": "id",
                    "sClass": "id" },
                    
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
            //"aoColumnDefs": [{"bVisible": false, "aTargets": [5]}],
            "bAutoWidth": false,
            "sDom": '<"H"Tfr>t<"F"ip>',
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
                    ]
            }
                
        });
        
   
}

function filtrarPedidos(){
    
    $('.status').change(function(){
        oTablePedido.fnNewAjax(Routing.generate("ajaxPedido", {'status' : $(".status").val() }));                   
        oTablePedido.dataTable().fnReloadAjax();
        return false;
    })
      
}
