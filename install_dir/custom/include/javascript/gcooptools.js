
// Este archivo contiene todas las funciones globales que se usan en los formularios
//

function require(field, formname_)
{   
    var name = field.attr('id');
    var formname = (typeof(formname_) != 'undefined') ? formname_ : 'EditView';
    //colocamos el span
    $('#' +name +'_label').append('<span class="required">*</span>');

   	for(i = 0; i < validate[formname].length; i++){
		if(validate[formname][i][0] == name){
            validate[formname][i][2] = true;
        }
    }

};

function unrequire(field, formname_)
{
    var formname = (typeof(formname_) != 'undefined') ? formname_ : 'EditView';
    var name = field.attr('id');
    //quitamos el span
    $('#' +name +'_label .required').remove();
   	for(i = 0; i < validate[formname].length; i++){
		if(validate[formname][i][0] == name){
			validate[formname][i][2] = false;
        }
    }

};

function hide_childs(childs, formname_)
{
    var formname = (typeof(formname_) != 'undefined') ? formname_ : 'EditView';
    for (var i in childs)
    {
        var child = $(childs[i]);
//        if ( carga === false ) {
//            child.val('');
//        }
        child.change();
        child.hide();
        $(childs[i] + '_label').hide();
        unrequire(child, formname);
    }
   
};

function show_childs(childs, formname_)
{
    var formname = (typeof(formname_) != 'undefined') ? formname_ : 'EditView';
    for (var i in childs)
    {
        var child = $(childs[i]);
        child.show();
        child.change();
        $(childs[i] + '_label').show();
    //agregar logica para agregar y quitar requireds
        require(child, formname);
    }

};
function makeReadOnly( str_element )
{
   // NO USAR PROPIEDAD DISABLED, SINO LUEGO GRABA MAL EL DATO AL HACER SUBMIT. 
   // USAR SOLO PROPIEDAD READONLY.
   $(str_element).attr("readonly", true);
   $(str_element).css({"color":"#555"});

   $(str_element).css({"background-color":"#ECF1F4"});
    //Nos fijamos si es un select, y en ese caso, le frizamos el valor 
    //Joac
    var is_select = $(str_element).is("select");
 
    
    if (is_select == true) {
        $(str_element).removeAttr("onchange");
        $(str_element).unbind();
        var value = $(str_element).val();
        $(str_element).change(function() {
            $(this).attr("value", value);
            });
        };

}

function makeReadWrite( str_element )
{
   $(str_element).attr("readonly", false);
   $(str_element).css({"color":"black"});

   $(str_element).css({"background-color":"white"});

}


/* reemplaza el caracter punto por caracter coma.  */
function punto2coma( aString ) 
{
        return aString.toString().replace(/\./gi,',');
}


/* reemplaza el caracter coma por punto */
function coma2punto( aString ) 
{
        return aString.toString().replace(/,/gi,'.');
}


/**
 * Redondea num a dec decimales.  Por ejemplo num = 157,1234   dec = 2  => resultado = 157,12
 */
function roundNumber(num, dec) {

         num = parseFloat( num );
         dec = parseInt( dec );  

         result = Math.round(num*Math.pow(10,dec)) / Math.pow(10,dec);

         return result;
}

// Esta función estrae los valores pasados por GET
// Ejemplo:

//    http://www.foo.com/index.html?bob=123&frank=321&tom=213#top

//    var frank_param = gup( 'frank' );

//    Devuelve el valor 321


function gup( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}
