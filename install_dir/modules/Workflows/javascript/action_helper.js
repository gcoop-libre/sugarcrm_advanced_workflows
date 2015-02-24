function update_parameters()
{
    $("#parametros_label").append("<img src='themes/Sugar5/images/working.gif' height='11px'/>");
    $.get("index.php?module=Workflows&action=Parametros&function="+$(this).val(), 
        function(data)
        {
            $("#parametros").text(data);
            $("#parametros_label img").remove();
        });
}

$(document).ready(function()
    {   
        $("#accion").change(update_parameters);
    }
);

