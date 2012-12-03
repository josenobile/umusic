// Cuando se termina de cargar el DOM se ejecuta la siguiente funcion
$(function() {
	
    // 1. Agregar al boton de mostrar el formulario la opcion de ocultar y mostrar
    $("#mostrarFormUsuario").bind("click", function() {
        if ($("#formularioUsuario").css("display") == "none") {
            $("#formularioUsuario").slideDown();
        } else {
            $("#formularioUsuario").slideUp();
        }
    });

    
    // 2. Validar los campos del formulario, enviarlo por ajax y actulizar la tabla
    var v = $("#formularioUsuario").validate(
    {
        rules : {
            email : {
                required : true
            },
            contraseña : {
                required : true
            },
            descripcion : {
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

    $(".editarUsuario").live("click", function(e) {// edit
        e.preventDefault();
        // var arr = {};
        // parse_str($(this).attr("href").substr(1),arr);
        $("#result").html("Loading");
        $("#formularioUsuario").hide();
        $.get($(this).attr("href"), function(obj) {
            for (i in obj) {
                $("#formularioUsuario *[name=" + i + "]").val(obj[i]);
            }
            $("#result").html("");
            $("#formularioUsuario").slideDown();
        // $("#result").html(obj.msg);
        // tLaeOfficeExpenses.fnClearTable(true);//uncomment
        }, "json");
        return false;
    });

    //Eliminar por AJAX con confirmacion
    $(".eliminarUsuario").live("click", function(e) {// delete
        e.preventDefault();
        if (confirm("Eliminar?")) {
            $.get($(this).attr("href"), function(obj) {
                $("#result").html(obj.msg);
               tUsuario .fnClearTable(true);// uncomment
            }, "json");
        }

        return false;
    });

    // Utilizando el plugin Jquery DataTable para hacer el consultar AJAX
    var tUsuario = $('#tUsuario').dataTable(
    {
        "bProcessing" : true,
        "bServerSide" : true,
        "sAjaxSource" : "index.php?ac=usuario",
        "bSearchable" : true,
        "sScrollY" : $(window).height() * 0.99 - 377,
        "sDom" : "frtiSHF",
        "bDeferRender" : true,
        "bJQueryUI" : true,
        "sPaginationType" : "full_numbers",
        "sServerMethod" : "POST",
        "aoColumns" : [
        { "bVisible" : false},
        null,
        null,
        null,
		null,
        {
            "bSortable" : false,
            "mDataProp" : null,
            "fnRender" : function(o) {
                return '<div style="display:block; width:120px;"><a class="editarUsuario" href="index.php?ac=usuario&accion=editar&id='
                + o.aData[0]
                + '">Editar</a> '
                + '<a class="eliminarUsuario" href="index.php?ac=usuario&accion=eliminar&id='
                + o.aData[0]
                + '">Eliminar</a></div>';
            }
        }]
    }).columnFilter({
        sPlaceHolder : "foot",
        sRangeSeparator : '~',
        aoColumns : [null, {
            type : "text"
        }, {
            type : "text"
        }, {
            type : "text"
        }, {
            type : "text"
        }, null]
    });

});