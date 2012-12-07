$(document).ready(function() {
    onTableAjaxTurno()
})

function onTableAjaxTurno(){
        
        oTableTurno = $('.tableTurno').dataTable({
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
            "aoColumnDefs": [{"bVisible": false, "aTargets": [3]}],
            "bAutoWidth": false,
            "sDom": '<"H"Tfr<"toolbar01">>t<"F"ip>',
            "oTableTools": {
                    "sRowSelect": "single",
                    "sSwfPath": url_dominio+"/fnxadmin/table/tools/swf/copy_csv_xls_pdf.swf",
                    "sSelectedClass": "row_selected",
                    "aButtons": [
                        "copy",
                        "print",
                        {
                            "sExtends": "pdf",
                            "mColumns": "visible",
                            "sPdfOrientation": "landscape"
                        },
//                        "xls",
                    ]
                }
        });
        
        $("div.toolbar01").html('<b>Escalas Normais</b>');
        
}