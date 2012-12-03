// Cuando se termina de cargar el DOM se ejecuta la siguiente funcion
$(function() {
	
    // 1. Agregar al boton de mostrar el formulario la opcion de ocultar y mostrar
    $("#mostrarFormUsuario").bind("click", function() {
        if ($("#formularioCancion").css("display") == "none") {
            $("#formularioCancion").slideDown();
        } else {
            $("#formularioCancion").slideUp();
        }
    });
	/// Autocompletar Album
	$("#AlbumAutocompletar").autocomplete("index.php?ac=album&autoCompleteTerm=nombre",{
		minChars: 3,
		max: 1000,
		delay: 0,
		parse: function(data) {
			data = jQuery.parseJSON(data);
			return $.map(data, function(row) {
				return {
					data: [
						row.nombre, 
						row.idAlbum
					],
					value: row.nombre,
					result: row.nombre
				}
			});
		}
	}).addClass("autocomplete").bind("result", function(e, row, nombre ){
		$('input:hidden').filter('[name=Album_idAlbum]').val(row[1]);
		//$("<input type='hidden' name='id_area_conocimiento' value='"+row[1]+"' />").insertBefore($("#areaAutoCompletar"));
	});
	/// Autocompletar Genero
	$("#GeneroAutocompletar").autocomplete("index.php?ac=genero&autoCompleteTerm=nombre",{
		minChars: 3,
		max: 1000,
		delay: 0,
		parse: function(data) {
			data = jQuery.parseJSON(data);
			return $.map(data, function(row) {
				return {
					data: [
						row.nombre, 
						row.idGenero
					],
					value: row.nombre,
					result: row.nombre
				}
			});
		}
	}).addClass("autocomplete").bind("result", function(e, row, nombre ){
		$('input:hidden').filter('[name=Genero_idGenero]').val(row[1]);
		//$("<input type='hidden' name='id_area_conocimiento' value='"+row[1]+"' />").insertBefore($("#areaAutoCompletar"));
	});
    
    // 2. Validar los campos del formulario, enviarlo por ajax y actulizar la tabla
    var v = $("#formularioCancion").validate(
    {
        rules : {
            nombre : {
                required : true
            },
            Album_idAlbum : {
                required : true
            },
			Genero_idGenero : {
                required : true
            }
        },
        messages : {
        // varName: {required: "Este campo es requerido"},
        // paramName: {required: "Este campo es requerido"},
        // value: {required: "Este campo es requerido"}
        },
        submitHandler : function(form) {
            $(form)
            .ajaxSubmit(
            {
                dataType : "json",
                success : function(obj,statusText, xhr, $form) {
                    tUsuario.fnClearTable(true);
                    $("#result").html(obj.msg);
                    $('[name]', form).val('');
                },
                beforeSubmit : function(arr,$form, options) {
                    $("#result")
                    .html("Loading");
                },
                error : function(context, xhr,
                    status, errMsg) {
                    $("#result")
                    .html(
                        status
                        + "<br />"
                        + context["responseText"]);
                }
            });
        }
    });
	
    $(".editarCancion").live("click", function(e) {// edit
        e.preventDefault();
        // var arr = {};
        // parse_str($(this).attr("href").substr(1),arr);
        $("#result").html("Loading");
        $("#formularioCancion").hide();
        $.get($(this).attr("href"), function(obj) {
            for (i in obj) {
                $("#formularioCancion *[name=" + i + "]").val(obj[i]);
            }
            $("#result").html("");
            $("#formularioCancion").slideDown();
        // $("#result").html(obj.msg);
        // tLaeOfficeExpenses.fnClearTable(true);//uncomment
        }, "json");
        return false;
    });

    //Eliminar por AJAX con confirmacion
    $(".eliminarCancion").live("click", function(e) {// delete
        e.preventDefault();
        if (confirm("Eliminar?")) {
            $.get($(this).attr("href"), function(obj) {
                $("#result").html(obj.msg);
               tUsuario .fnClearTable(true);// uncomment
            }, "json");
        }

        return false;
    });
	
	/////// LEER TAGS
	$(".leerTags").live("click", function(e) {// edit
        e.preventDefault();
        // var arr = {};
        // parse_str($(this).attr("href").substr(1),arr);
        $("#resultTags").html("Loading");
		var input = document.createElement("input");
		with(input) {
			setAttribute("name", "leerTags");
			setAttribute("value", "1");
			setAttribute("type", "hidden");
			setAttribute("class", "leerTags2");
		}
		$("#formularioCancion").append(input); 
        $("#formularioCancion")
            .ajaxSubmit(
            {
                dataType : "json",
                success : function(obj,statusText, xhr, $form) {
                    $("#resultTags").html(obj);
                },
                beforeSubmit : function(arr,$form, options) {
                    $(".leerTags2").remove();
                },
                error : function(context, xhr,
                    status, errMsg) {
                    $("#result")
                    .html(
                        status
                        + "<br />"
                        + context["responseText"]);
                }
            });
		
        return false;
    });
	
    // Utilizando el plugin Jquery DataTable para hacer el consultar AJAX
    var tUsuario = $('#tCancion').dataTable(
    {
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : "index.php?ac=cancion",
        "bSearchable" : true,
        "sScrollY" : $(window).height() * 0.99 - 377,
        "sDom" : "frtiSHF",
        "bDeferRender" : true,
        "bJQueryUI" : true,
        "sPaginationType" : "full_numbers",
        "sServerMethod" : "POST",
        "aoColumns" : [
        /* null, */{
            "bVisible" : false
        },
        {   "bSortable" : false,
            "mDataProp" : null,
            "fnRender" : function(o) {
                return '<audio src="index.php?ac=loadCancion&id='+o.aData[0]+'" controls></audio>';
            }
		},
        null,
        null,
		null,
		null,
		null,
		null,
		null,
        {
            "bSortable" : false,
            "mDataProp" : null,
            "fnRender" : function(o) {
                return '<div style="display:block; width:120px;"><a class="editarCancion" href="index.php?ac=cancion&accion=editar&id='
                + o.aData[0]
                + '">Editar</a> '
                + '<a class="eliminarCancion" href="index.php?ac=cancion&accion=eliminar&id='
                + o.aData[0]
                + '">Eliminar</a></div>';
            }
        }]
    }).columnFilter({
        sPlaceHolder : "foot",
        sRangeSeparator : '~',
        aoColumns : [
			null, 
			{ type : "text"},
			{ type : "text"},
			{ type : "text"},
			{ type : "text"},
			{ type : "text"},
			{ type : "text"},
			{ type : "text"},
			{ type : "text"},
         	null
		]
    });
});