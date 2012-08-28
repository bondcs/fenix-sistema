

$(document).ready(function() {

        onTable();
        onFnAction();
        actionDialog();
        simpleDialog();
        populaCidade();
        ajaxDialog();
        confirmDialog();
        cpfCnpj(); 
        onChangecpfCnpj();
        efeitoErro();
        
} );

function onTable(){
    
        oTable = $('#table').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bRetrieve": true
        });
        
        $("#table tbody tr").click( function() {
            
            // alterar a cor do fundo da linha
            if ( $(this).hasClass('row_selected') ) {
                $(this).removeClass('row_selected');
            }
            else {
                oTable.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
            }   
        });
    
}


function ajaxDialog(){
    
        $(".ajax-link").click(function(event){
             event.preventDefault();
             var url = $(this).attr("href");
             if (url != '#'){                
                $.ajax({
                       type: 'GET',
                       url: url,
                       success: function(result){
                               $(".simpleDialog").html(result);
                               $('.simpleDialog').dialog('open');
                               $("#menuContent a[route], .menuDialog a[route]").attr('href', "#");
                               
                               /* hack para recarregar funcões previamentes carregadas no onReady()*/
                               simpleDialog();
                               confirmDialog();
                       }
                   })
            }
            
        })
             

}

function ajaxSubmit(){

                var url = $('.ajaxForm').attr("action");
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $('.ajaxForm').serialize(),
                    success: function(result){
                        
                        if (result['success']){
                            window.location.href = result['success'];
                            return false;
                        }
                        
                        $(".simpleDialog").html(result);
                        $('.simpleDialog').dialog('close');
                        $('.simpleDialog').dialog('open');
                    }
                })
}           


function onFnAction(){
        
        $("#menuContent a[route], .menuDialog a[route]").click(function(e){
                if (key = $(".row_selected").find(".id").html()){
                    var url = Routing.generate($(this).attr('route') , {"id": key});
                    $(this).attr('href', url);
                    
                    if ($(this).hasClass("confirm-link")){
                        $("#dialog-confirm").dialog('open');
                        return false;
                    }

                }else{
                    $('#dialogAction').dialog('open');
                    e.preventdefault()
                    return false;
                }
                
        })
    
}

function confirmDialog(){
    
        var url;
        
	$( "#dialog-confirm" ).dialog({
                autoOpen: false,
		resizable: false,
		height:"auto",
                modal: false,
                close: function(){
                     $("#menuContent a[route], .menuDialog a[route]").attr('href', "#");
                },
		buttons: {
			"Deletar": function() { 
                                if (url == null){
                                    window.location.href = $(".confirm-link").attr("href"); 
                                }else{
                                    window.location.href = url;
                                }
				$( this ).dialog( "close" );
                                
			},
			"Cancel": function() {
				$( this ).dialog( "close" ); 
                                return false;
			}
		}
	});
        
        $( ".dialog-confirm-link" ).on("click",function(){
            url = $(this).attr('href');
            $("#dialog-confirm").dialog('open');
            return false;

        });

}

function actionDialog(){
        $( "#dialogAction" ).dialog({
                autoOpen: false,
		resizable: false,
                height:"auto",
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
                                return false;
			}
		}
	});        
       
    
}

function simpleDialog(){
        $( ".simpleDialog" ).dialog({
                autoOpen: false,
		resizable: false,
                height:"auto",
                width:"auto",
		modal: true,
                open: function(){
                    
                }
	});        
       
}

function dialogClose(){
        $( ".simpleDialog" ).dialog('close');
}

function populaCidade(){
    
    $("#estado").change(function(){
        
        $.ajax({
            type: 'POST',
            url: Routing.generate('ajaxCidade', { estadoId: $('#estado').val() }),
            success: function(valores){
                $('#cidade').empty();
                var options = "";
                
                $.each(valores,function(key,valor){
                       options += '<option value="'+ valor['id']+ '">'+ valor['nome']+'</option>';
                })
                $("#cidade").html(options);  
            }
        
        })
})
}

 function onChangecpfCnpj(){
     
     
     $("#pessoa input").bind('change',function(){
        cpfCnpj();
     })
     
     return false;
     
 }

function cpfCnpj(){
    
        var valor = $("#form input[type='radio']:checked").val();
        
        if (valor == 'j'){

             $("#fisico").addClass("hide");
            
             if ($("#juridico").hasClass("hide")){
                  $("#juridico").removeClass("hide");
                  $("#responsavel").removeClass("hide");
             }
            
        }else{
             $("#juridico").addClass("hide");
             $("#responsavel").addClass("hide");
             if ($("#fisico").hasClass("hide")){
                  $("#fisico").removeClass("hide");
             }
            
        }
        
        return false;
}

function efeitoErro(){
    $(".flash-success, .flash-error").delay(4000).slideUp("slow");
    return false;
    
}