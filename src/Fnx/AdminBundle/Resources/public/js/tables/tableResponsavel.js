$(document).ready(function(){
    onTableResponsavel()
})


function onTableResponsavel(){
        
        oTable = $('.tableResponsavel').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": urlSource,
            "aoColumns": [
                { "mDataProp": "nome" },
                { "mDataProp": "telefone",
                    "sClass": "center"},
                { "mDataProp": "cpf",
                    "sClass": "center"},
                { "mDataProp": "usuario",
                    "sClass": "center"},
                { "mDataProp": "id" },
                    
             ],
            "aoColumnDefs": [{"bVisible": false, "aTargets": [4]}],
            "bAutoWidth": false,
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
            "sDom": '<"H"Tfr>t<"F"ip>',
                "oTableTools": {
                    "sRowSelect": "single",
                    "sSelectedClass": "row_selected",
                    "aButtons": [
                        {
                            "sExtends": "text",
                            "sButtonText": '<img src="'+imageUrl+'add-icone.png">Adicionar',
                            "fnClick" : function(){
                                 ajaxLoadDialog(urlAdd, "Adicionar responsável");
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": '<img src="'+imageUrl+'edit-icone.png">Editar',
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]['id'];
                                 ajaxLoadDialog(Routing.generate(routeEdit, {"id" : id}), "Editar responsável");
                                 
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": '<img src="'+imageUrl+'user-icone.png">Usuário',
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]['id'];
                                 ajaxLoadDialog(Routing.generate(routeUsuario, {"id" : id}),"Usuário");
                                 
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": '<img src="'+imageUrl+'delete-icone.png">Deletar',
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 var aaData = this.fnGetSelectedData()
                                 id = aaData[0]['id'];
                                 $( "#dialog-confirm" ).dialog("open");
                                 $( "#dialog-confirm" ).dialog("option", "title", "Deletar responsável");
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
}


