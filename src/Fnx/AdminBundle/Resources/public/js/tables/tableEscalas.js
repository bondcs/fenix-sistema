$(document).ready(function() {
    onTableAjaxEscala()
})

function onTableAjaxEscala(){

        oTableEscala = $('.tableEscalas').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bPaginate": true,
            "bInfo": false,
            "bRetrieve": true,
            "bProcessing": true,
            "sAjaxSource": urlSource,
            "aoColumns": [
                {"mDataProp": null,
                    "sClass": "control center",
                    "sDefaultContent": '<img src="'+imageUrl+'details_open.png">'},
                { "mDataProp": "dtInicio" },
                { "mDataProp": "dtFim" },
                { "mDataProp": "local" },
                { "mDataProp": "custoUnitario" },
                { "mDataProp": "custoTotal" },
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
            "aoColumnDefs": [{"bVisible": false, "aTargets": [6]},
                             {"bSortable": false, "aTargets": [0]}],
            "bAutoWidth": false,
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                var total = 0;
                for (var i=0; i < aaData.length; i++){
                    total += aaData[i]["custoTotalNumber"]*1;
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
                            "sButtonText": '<img src="'+imageUrl+'add-icone.png">Adicionar',
                            "fnClick" : function(){
                                 if (clickTableTerminate()){
                                    ajaxLoadDialog(urlAdd, "Adicionar escala");
                                 }
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": '<img src="'+imageUrl+'edit-icone.png">Editar',
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 if (clickTableTerminate()){
                                    var aaData = this.fnGetSelectedData()
                                    id = aaData[0]["id"];
                                    ajaxLoadDialog(Routing.generate(routeEdit, {"id" : id}),"Editar escala");
                                 }
                                 
                            }
                        },
                        
                        {
                            "sExtends": "select_single",
                            "sButtonText": '<img src="'+imageUrl+'delete-icone.png">Deletar',
                            "sButtonClass": "hidden",
                            "fnClick" : function(){
                                 if (clickTableTerminate()){
                                    var aaData = this.fnGetSelectedData()
                                    id = aaData[0]["id"];
                                    $( "#dialog-confirm" ).dialog("open");
                                    $( "#dialog-confirm" ).dialog("option", "title", "Deletar escala");
                                    $( "#dialog-confirm" ).dialog("option", "buttons", {
                                        "Deletar": function() {
                                               ajaxDelete(Routing.generate(routeDelete, {"id" : id}));
                                               $( "#dialog-confirm" ).dialog("option", "title", "");
                                               $(this).dialog("close");
                                        },
                                        "Cancelar": function(){
                                               $( "#dialog-confirm" ).dialog("option", "title", "");
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
        
//         $('.DTTT_button').on("click", function(){
//             alert("ola");
//         })
        
         $('.tableEscalas tbody td.control').live('click', function () {
    
            nTr = this.parentNode;
            if ( oTableEscala.fnIsOpen(nTr) ) {
              $('img', this).attr('src', imageUrl+'details_open.png');
              $('div.innerRow', $(nTr).next()[0]).slideUp( function () {
                    oTableEscala.fnClose( nTr );
              } );
            } else {
              $('img', this).attr('src', imageUrl+'details_close.png');
              var nDetailsRow = oTableEscala.fnOpen( nTr, getContent(nTr), "info_row" );
              $('div.innerRow', nDetailsRow).slideDown();
            }
        });
        
}  
   
   function getContent(tr){
       
       var data = oTableEscala.fnGetData(tr);
       var dataFun = data['funcionarios'];
       var sOut = '<div class="innerRow"><table cellpadding="5" cellspacing="0" border="0" >';
       
       sOut += '<tr>';
       var cont = 7;
       for (var i = 0; i < dataFun.length; i++){
             sOut+='<td><a style="color:blue" href="'+Routing.generate('funcionarioShow' , {"id": dataFun[i]['id']})+'">'+dataFun[i]['nome']+'<a></td>';
             cont++
             if (cont % 7 == 0){
                 sOut+='</tr><tr>'
             }
       }
       sOut += '</tr>';
       sOut += '</table></div>';
       
       return sOut;
   }
 



