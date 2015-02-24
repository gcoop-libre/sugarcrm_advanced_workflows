<?php
/**
* setTemplateCache 
*
* Funcion que habilita o deshabilita la cache de templates, 
* segun el parametro pasado.
*
* Se devuelve el valor anterior del estado del cache. 
*
* @input: bool  -  habilitar o deshabilitar
* @output: bool -  estado anterior de la cache
*
* Es util para deshabiliar la cache temporalmente en ciertas paginas
*
* Por ejemplo en la vista detalle de productos del socio 
* (tarjetas, cuentas corrientes,etc ) , donde se
* imprimen los valores directamente en el template, como esos valores
* estan por fuera de las variables normales del template, el sugar
* toma estos valores como html estatico y cachea los valores 
* del producto del usuario anterior.
*
* Deshabilitando la cache, se resuleve este problema.
*
* Los sintomas son que en modo developer la pagina en cuestion
* se ve correctamente, pero deshabilitando el modo developer, quedan cacheadas
* paginas anteriores.
*/

require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('include/database/DBManagerFactory.php');
require_once('include/database/DBManager.php');

function localSqlQuery($sql )
{

    $db = DBManagerFactory::getInstance();
    $queryresult = $db->query( $sql, $dieOnError = true );

    $rows = array();
    while ( $row = $db->fetchByAssoc($queryresult) ) {
      $rows [] = $row;
    }
    return $rows;
}


/*
* create_url
* recibe parametros, modulo, action, id. 
* args es un array que debe contener los siguientes items
* - module (obligatorio)
* - action (no obligatorio)
* - record (extras)
* - parametros extras
* base_path por defecto usa index.php
* si deseamos usar otro path, le pasamos se le pasa a la funcion
*/
function create_url($args, $base_path = "index.php")
{
    if (isset($args['module']) and !empty($args['module']))
    {
        return "$base_path?".http_build_query($args);
    }
    else
    {
        // TODO: tirar una exception. Usar GcoopExceptions
    }
}

/*
 * Set a message which reflects the status of the performed operation.
 * 
 * If the function is called with no arguments,
 * this function returns all set messages without clearing them.
 * 
 * Parametros
 * 
 * $message The message to be displayed to the user.i
 * For consistency with other messages, it should begin with a capital letter and end with a period.
 * 
 * $type The type of the message. One of the following values are possible:
 * - 'info'
 * - 'exito'
 * - 'alerta'
 * - 'error'
 * - 'working'
 * 
 * $repeat If this is FALSE and the message is already set, then the message won't be repeated.
 * 
*/
function sugar_set_message($message = NULL, $type = 'info', $repeat = TRUE)
{
    if ($message) 
    {
        if (!isset($_SESSION['messages'][$type])) 
        {
            $_SESSION['messages'][$type] = array();
        }

    if ($repeat || !in_array($message, $_SESSION['messages'][$type])) 
    {
        $_SESSION['messages'][$type][] = $message;
    }

    // Mark this page as being uncacheable.
    // drupal_page_is_cacheable(FALSE);
    }

    // Messages not set when DB connection fails.
    return isset($_SESSION['messages']) ? $_SESSION['messages'] : NULL;
}

/*
 * Return all messages that have been set.
 * Parameters
 * 
 * $type (optional) Only return messages of this type.
 * 
 * $clear_queue (optional) Set to FALSE if you do not want to clear the messages queue
 * 
 * Return value
 * 
 * An associative array, the key is the message type,
 * the value an array of messages. If the $type parameter is passed,
 * you get only that type, or an empty array if there are no such messages. 
 * If $type is not passed, all message types are returned,
 * or an empty array if none exist.
*/
function sugar_get_messages($type = NULL, $clear_queue = TRUE) 
{
    if ($messages = sugar_set_message()) 
    {
        if ($type) 
        {
            if ($clear_queue) 
            {
                unset($_SESSION['messages'][$type]);
            }
            if (isset($messages[$type])) 
            {
                return array($type => $messages[$type]);
            }
        }
        else 
        {
            if ($clear_queue) 
            {
                unset($_SESSION['messages']);
            }
            return $messages;
        }
    }
    return array();
}

function sugar_get_notify_list ()
{
    $msg = sugar_get_messages();
    $return_string = "";
    foreach ($msg as $tipo => $ss)
    {
        //$return_string .= "<div class='gcoop_$tipo mensajes'>";
        foreach ($ss as $mensaje)
        {
            $return_string .= "<div class='gcoop_$tipo mensajes'>";
            $return_string .= "$mensaje <br />";
            $return_string .= "</div>";
        }
        unset($mensaje);
        //$return_string .= "</div>";
    }
    unset($ss);

    return $return_string;
}


/**
 * A wrapper for sugar's mail class
 * Sends emails to everyon in $tos array.
 * $tos['user name'] = 'username@domain.com';
 *
 * @example :   $tos['Some User'] = 'SomeUser@somewhere.com';
 *              $tos['Some User 2'] = 'SomeUser2@somewhere.com';
 *              sendSugarPHPMail($tos, 'hi', 'hello fellas');
 *
 * @param associative array
 * @param string $subject
 * @param string $body
 * @return boolean
 */
function sendSugarPHPMail($tos, $subject, $body, $type="text/html")
{
    require_once('include/SugarPHPMailer.php');
    require_once('modules/Administration/Administration.php');

    $mail = new SugarPHPMailer();
    $admin = new Administration();
    $admin->retrieveSettings();

    if ($admin->settings['mail_sendtype'] == "SMTP")
    {
        $mail->Host = $admin->settings['mail_smtpserver'];
        $mail->Port = $admin->settings['mail_smtpport'];

        if ($admin->settings['mail_smtpauth_req'])
        {
            $mail->SMTPAuth = TRUE;
            $mail->Username = $admin->settings['mail_smtpuser'];
            $mail->Password = $admin->settings['mail_smtppass'];
        }

        $mail->Mailer   = "smtp";
        $mail->SMTPKeepAlive = true;
    }
    else
    {
        $mail->mailer = 'sendmail';
    }

    $mail->From     = $admin->settings['notify_fromaddress'];
    $mail->FromName = $admin->settings['notify_fromname'];
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->ContentType = $type; //"text/plain" # Envia en texto plano

    foreach ($tos as $name => $address)
    {
        $mail->AddAddress("{$address}", "{$name}");
    }

    if (!$mail->send())
    {
        $mensaje_de_error = "sendSugarPHPMail - error: '{$mail->ErrorInfo}'";
        $GLOBALS['log']->info($mensaje_de_error);
        throw new Exception ($mensaje_de_error);
    }
    else
    {
        return true;
    }
}
?>
