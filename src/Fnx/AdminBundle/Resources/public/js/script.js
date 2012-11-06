$(document).ready(function() {

        onTable();
        onTabs();
        onFnAction();
        actionDialog();
        simpleDialog();
        initDatepicker()
        populaCidade();
        populaCidadeDialog()
        ajaxDialog();
        confirmDialog();
        cpfCnpj();
        onChangecpfCnpj();
        efeitoErro();
        populaComplete();
        ajaxSubmit();
        onSubmitForm();
        onLoadingAjax();
        doubleclick();
        ajaxUpload();
        moeda();
        initTimePicker();
        tooltip();
        masks();
        onSubmitFormPagamento();
        translateHtml5Validation();
        sempreZero();
        button();
        
        
        
} );

function button(){
    $( "input[type=submit],button, input[type=button], .button " ).button({
    });
    return false;
}

$(document).ajaxStart(function(){
     $(".ajaxLoader").show();
}).ajaxStop(function(){
     $(".ajaxLoader").hide();

});

function onReadyAjax(){

        simpleDialog();
        confirmDialog();
        cpfCnpj();
        onChangecpfCnpj();
        populaCidade();
        initDatepicker();
        ajaxSubmitTable();
        ajaxSubmit();
        addFuncionario();
        removeFuncionario();
        onMultiSelect();
        onSubmitForm();
        onLoadingAjax();
        ajaxUpload();
        moeda();
        tooltip();
        masks();
        onSubmitFormPagamento();
        translateHtml5Validation();
        sempreZero();
        button();


}

function onTable(){

        oTable = $('table.tablePlugin').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bRetrieve": true,
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
                    "sFirst": "Inicio",
                    "sLast": "Ultima",
                    "sNext": "Próxima",
                    "sPrevious": "Anterior"
                  },
                "iDisplayLength": 25
            }
        });

        $("table.tablePlugin tbody tr").click( function() {

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

function onTabs(){
        $( ".tabs" ).tabs();
}

function onMultiSelect(){
   $(".multiselect").multiselect();
}

function ajaxDialog(){
        $(".ajax-link").live("click",function(event){
             event.preventDefault();
             var url = $(this).attr("href");
             if (url != '#'){
                $.ajax({
                       type: 'GET',
                       url: url,
                       success: function(result){
                               $(".simpleDialog").html(result);
                               $(".simpleDialog").dialog( "option", "title", $(".ajax-link").attr('title') );
                               $(".simpleDialog").dialog('open');
                               $("#menuContent a[route], .menuDialog a[route]").attr('href', "#");

                               /* hack para recarregar funcões previamente carregadas no onReady()*/
                               onReadyAjax();

                       }
                   })
            }

        })


}

function ajaxSubmit(){

                $(".ajaxForm").submit(function(){
                    var url = $('.ajaxForm').attr("action");
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $('.ajaxForm').serialize(),
                        success: function(result){
                            if (result['dialogName']){
                               notifity(result['message']);
                               $(result['dialogName']).dialog('close');
                               return false;
                            }

                            $(".simpleDialog").html(result);
                            $('.simpleDialog').dialog('close');
                            $('.simpleDialog').dialog('open');

                            /* hack para recarregar funcões previamentes carregadas no onReady()*/
                            onReadyAjax();
                        }
                    })

                    return false;
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

function doubleclick(){
    $(".doubleclick tbody tr").dblclick(function(){
        var key = $(this).find(".id").html();
        var url = Routing.generate($('.doubleclick').attr('route') , {"id": key});
        window.location.href = url;

    })

    return false;
}

function confirmDialog(){

        var url;

	$( "#dialog-confirm" ).dialog({
                autoOpen: false,
		resizable: false,
		height:"auto",
                modal: true,
                close: function(){
                     $("#menuContent a[route], .menuDialog a[route]").attr('href', "#");
                },
		buttons: {
			"Confirmar": function() {
                                if (url == null){
                                    window.location.href = $(".confirm-link").attr("href");
                                }else{
                                    window.location.href = url;
                                }
				$( this ).dialog( "close" );

			},
			"Cancelar": function() {
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
		modal: true
	});

}

function dialogClose(){
        $( ".simpleDialog" ).dialog('close');
}

function populaCidade(){

    $("#estado").change(function(){
        $.ajax({
            type: 'POST',
            url: Routing.generate('ajaxCidade', { estadoId: $(this).val() }),
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

    return false;
}

function populaCidadeDialog(){

    $("#estado").live("change",function(){
        $.ajax({
            type: 'POST',
            url: Routing.generate('ajaxCidade', { estadoId: $(this).val() }),
            success: function(valores){
                $('#cidadeDialog').empty();
                var options = "";

                $.each(valores,function(key,valor){
                       options += '<option value="'+ valor['id']+ '">'+ valor['nome']+'</option>';
                })
                $("#cidadeDialog").html(options);
            }

        })
})
    return false;
}

function populaComplete(){

    $(".complete input").focus(function(){

        $.ajax({
            type: 'POST',
            url: Routing.generate("ajaxCliente"),
            success: function(valores){
                var nomes = new Array();
                $.each(valores,function(key,valor){
                       nomes[key] = valor['nome'];
                })
                $(".complete input").autocomplete({
                    source: nomes
                })
            }

        })
    })

    return false;
}

 function onChangecpfCnpj(){
     $("#pessoa input").on('change',function(){
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

function initDatepicker() {

    $.datepicker.regional['pt-BR'] = {
		closeText: 'Fechar',
		prevText: '&#x3c;Anterior',
		nextText: 'Pr&oacute;ximo&#x3e;',
		currentText: 'Hoje',
		monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
		'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
		'Jul','Ago','Set','Out','Nov','Dez'],
		dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
		dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
                defaultDate: '00/00/00',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
                
            
      };
                
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);

//    $('#datepicker')
//        .attr('readonly', 'readonly')
//        .datepicker({
//            dateFormat: 'dd/mm/yy h:i:s',
//            changeMonth: true,
//            yearRange: "1920:2000",
//	    changeYear: true
//        });

    $('.datepickerImage input')
        .datetimepicker({
            showOn: "button",
	    buttonImage: imageUrl+"calendar.png",
	    buttonImageOnly: true
    });
    
    $('.picker input').attr("readonly", 'readonly');


    $.timepicker.regional['pt-BR'] = {
        timeFormat: 'hh:mm:ss',
	timeOnlyTitle: 'Escolha um tempo',
	timeText: 'Tempo',
	hourText: 'Hora',
	minuteText: 'Minuto',
	secondText: 'Segundo',
	millisecText: 'Milésimo',
	currentText: 'Agora',
	closeText: 'Add',
	ampm: false
    };

    $.timepicker.setDefaults($.timepicker.regional['pt-BR']);
    
        
    $('.datepicker input').datetimepicker();
    $('.datepicker input').blur();
    $('.picker input').datepicker();
    $('.picker input').blur();
    $( "#datepicker" ).datepicker();
    
    return false;

}

function initTimePicker(){
    $('.timepicker input').timepicker();
    return false;
}

function ajaxLoadDialog(url, title){

    $.ajax({
            type: 'GET',
            url: url,
            success: function(result){
                $(".simpleDialog").html(result);
                $(".simpleDialog").dialog( "option", "title", title || "" );
                $(".simpleDialog").dialog('open');
                onReadyAjax();
                return false;
            }
   })

}

function ajaxDelete(url){
    
        $.ajax({
                type: 'POST',
                url: url,
                success: function(){
                    $('.redraw').each(function(){
                             $(this).dataTable().fnReloadAjax();
                    });
                    $(".hidden").addClass("DTTT_disabled");
                    notifity("delete");
                    return false;
                }
       })

}

function clickTableTerminate(){
    
    if ($("#status").html() == 'Arquivado'){
        notifity('arquivado');
        return false;
    }
    
    return true;
}

function formataDinheiroTabela(valor){
    return valor.toString().replace(".",",");
}

function ajaxSubmitTable(){

    $(".ajaxFormTable").submit(function(){
                $.ajax({
                    type: 'POST',
                    url: $('.ajaxFormTable').attr("action"),
                    data: $('.ajaxFormTable').serialize(),
                    success: function(result){

                        if (result['dialogName']){
                           if (result['message'] == 'erroSaldo'){
                               notifity(result['message']);
                               return false;
                           } 
                            
                           $('.redraw').each(function(){
                               $(this).dataTable().fnReloadAjax();
                           });
                           $('.redraw').dataTable().fnReloadAjax();
                           notifity(result['message']);
                           $(result['dialogName']).dialog('close');
                           $(".hidden").addClass("DTTT_disabled");
                           return false;
                        }
                        $(".simpleDialog").html(result);
                        $('.simpleDialog').dialog('close');
                        $('.simpleDialog').dialog('open');

                        /* hack para recarregar funcões previamentes carregadas no onReady()*/
                        onReadyAjax();
                    }
                })

        return false;
    })

}


function notifity(tipo){
    $.pnotify.defaults.styling = "jqueryui";

    if (tipo == 'add'){
        $.pnotify({
            title: 'Sucesso!',
            text: 'O registro foi efetuado.',
            type: 'success'
        });
    }else if (tipo == 'edit'){
        $.pnotify({
            title: 'Sucesso!',
            text: 'O registro  foi alterado.',
            type: 'success'
        });
    }else if (tipo == 'delete'){
        $.pnotify({
            title: 'Sucesso!',
            text: 'O registro  foi excluído.',
            type: 'success'
        });
    }else if (tipo == 'erroSaldo'){
        $.pnotify({
            title: 'Erro!',
            text: 'Saldo insuficiente na conta.',
            type: 'error'
        });
    }else if (tipo == 'arquivado'){
        $.pnotify({
            title: 'Erro!',
            text: 'Atividade arquivada.',
            type: 'error'
        });
    }else if (tipo == 'noSelected'){
        $.pnotify({
            title: 'Erro!',
            text: 'Nenhum registro selecionado.',
            type: 'error'
        });
    }else if (tipo == 'gerado'){
        $.pnotify({
            title: 'Erro!',
            text: 'Pagamentos já foram gerados.',
            type: 'error'
        });
    }else if (tipo == 'gerar'){
        $.pnotify({
            title: 'Sucesso!',
            text: 'Pagamentos gerados.',
            type: 'notice'
        });
    }else if (tipo == "erroEscala01"){
        $.pnotify({
            title: 'Erro!',
            text: 'Já possui uma(s) escala(s) cadastrada(s).',
            type: 'notice'
        });
    }

}

function addFuncionario(){

    $("#funcionarioAdd").on("click", function(){
        var widget = $("#funcionario-container").attr("data-prototype");
        widget = widget.replace(/\$\$name\$\$/g, funcionarioCount);
        funcionarioCount++;
        var newLi = $("<li></li>").html(widget);
        newLi.append('<a class="funcionarioClose" href="#">Close</a>');
        newLi.appendTo($("#funcionario-container"));
        removeFuncionario();
        return false;
    });

    return false;
}

function removeFuncionario(){

    $(".funcionarioClose").on("click",function(){
        $(this).parent().remove();
        return false;
    })

    return false;
}

function onSubmitForm(){

        $(".ajax-form").submit(function(){
                    var url = $('.ajax-form').attr("action");
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $('.ajax-form').serialize(),
                        success: function(result){
                            if (result['url']){
                                 window.location.href = result['url'];
                                 notifity(result['notifity']);
                                 return false;
                            }

                            $(".box-form").html(result);
                            onReadyAjax();
                        }
                    })

                    return false;
        })

        return false;
    }
    
function onSubmitFormPagamento(){
            $(".ajax-form-pagamento").submit(function(){
                        var url = $('.ajax-form-pagamento').attr("action");
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: $('.ajax-form-pagamento').serialize(),
                            success: function(result){
                                if (result['url']){
                                     if (result['url'] == "arquivado"){
                                         notifity("arquivado");
                                         return false;
                                     }
                                     
                                     window.location.href = result['url'];
                                     notifity(result['notifity']);
                                     return false;
                                }

                                $(".box-form-pagamento").html(result);
                                onReadyAjax();
                            }
                        })

                        return false;
            })
        
        return false;
    }
    
function onLoadingAjax(){

        $(".ajaxLoader").hide();
        return false;
    }

function ajaxUpload(){
        var options = {
            success: showResponse
        };

        $(".ajax-form-upload").ajaxForm(options);
        return false;
    }

function showResponse(responseText, statusText, xhr, $form){

        if (responseText['url']){
            window.location.href = responseText['url'];
            return false;
        }

        $(".simpleDialog").html(responseText);
        $('.simpleDialog').dialog('close');
        $('.simpleDialog').dialog('open');

        /* hack para recarregar funcões previamentes carregadas no onReady()*/
        onReadyAjax();
    }

function gallery(){

    }

function moeda(){

    var moeda = $('.moeda');

    $(moeda).focus(function(){
	if($(this).val() != '')
	    $(this).val(desformataDinheiro($(this).val()));
    }).blur(function(){
	if($(this).val() != '')
	    $(this).val(formataDinheiro($(this).val()));
    }).each(function(){
	 if($(this).val()){
	     $(this).val(formataDinheiro($(this).val()));
	 }
    });
    
    var moedaTable = $('.moedaTable');
        moedaTable.each(function(){
            if($(this).html()){
                $(this).html(formataDinheiro($(this).html()));
            }
        })
}

function number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function formataDinheiro(value){
    return "R$ " + number_format(value.replace(',','.'), 2, ',','');
}

function desformataDinheiro(value){
    return value.replace(",", ".").slice(3) * 1;
}

function formataDinheiroTabela(valor){
    return valor.toString().replace(".",",");
}

$(function() {
     $( ".accordion" ).accordion();
});

function tooltip(){

    //$(".myform :input").tooltip();

      return false;
}

function masks(){

    $(".telefone").mask("(99) 9999-9999");
    $(".cpf").mask("999.999.999-99");
    $(".cnpj").mask("99.999.999/9999-99");
    $(".cep").mask("99999-999")
    $(".data").mask("99/99/9999")
    return false;
}

function translateHtml5Validation(){
    
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function(e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                e.target.setCustomValidity("Este valor não deve ser vazio");
            }
        };
        elements[i].oninput = function(e) {
            e.target.setCustomValidity("");
        };
    }

}

function sempreZero(){
    
    $('.zero').focusout(function(){
        if ($(this).val() == ""){
        $(this).val(0)
        }
    })
    
    $('.um').focusout(function(){
        if (validarInteiro($(this).val()) == "" || $(this).val() == 0){
        $(this).val(1)
        }
    })
    
}

function validarInteiro(valor){
    valor = parseInt(valor)
    if (isNaN(valor)) {
          return ""
    }else{
          return valor
    }
} 

